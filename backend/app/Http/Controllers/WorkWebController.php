<?php

namespace App\Http\Controllers;

use App\Models\Festival;
use App\Models\Karya;
use App\Models\KaryaFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WorkWebController extends Controller
{
    /**
     * Mendaftarkan karya baru dari Web Dashboard Peserta.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $peserta = $user->peserta;

        if (! $peserta) {
            return redirect()->route('dashboard')->with('error', 'Silakan lengkapi profil kepesertaan terlebih dahulu sebelum mendaftarkan karya.');
        }

        $festival = Festival::aktif()->first();
        if (! $festival) {
            return redirect()->route('dashboard')->with('error', 'Maaf, belum ada festival yang aktif untuk submisi karya.');
        }

        $validated = $request->validate([
            'judul'                 => ['required', 'string', 'max:200'],
            'kategori'              => ['required', 'string', 'max:100'],
            'teknologi'             => ['required', 'string', 'max:255'],
            'deskripsi'             => ['required', 'string'],
            'url_demo'              => ['nullable', 'url', 'max:255'],
            'proposal'              => ['nullable', 'file', 'mimes:pdf', 'max:20480'], // max 20MB
            'persetujuan_publikasi' => ['nullable', 'boolean'],
            'action_type'           => ['required', 'in:draft,submit'],
            'anggota_nama'          => ['nullable', 'array'],
            'anggota_nama.*'        => ['nullable', 'string', 'max:100'],
            'anggota_peran'         => ['nullable', 'array'],
            'anggota_peran.*'       => ['nullable', 'string', 'max:50'],
            'anggota_email'         => ['nullable', 'array'],
            'anggota_email.*'       => ['nullable', 'email', 'max:100'],
        ], [
            'judul.required'     => 'Judul karya wajib diisi.',
            'kategori.required'  => 'Kategori lomba wajib dipilih.',
            'teknologi.required' => 'Stack teknologi wajib diisi.',
            'deskripsi.required' => 'Deskripsi ringkasan karya wajib diisi.',
            'proposal.mimes'     => 'Berkas proposal wajib berformat PDF.',
            'proposal.max'       => 'Ukuran berkas proposal maksimal 20 MB.',
        ]);

        $status = ($validated['action_type'] === 'submit') ? 'diajukan' : 'draft';

        DB::transaction(function () use ($peserta, $festival, $validated, $status, $request) {
            $karya = Karya::create([
                'festival_id'           => $festival->id,
                'peserta_id'            => $peserta->id,
                'judul'                 => $validated['judul'],
                'deskripsi'             => $validated['deskripsi'],
                'kategori'              => $validated['kategori'],
                'teknologi'             => $validated['teknologi'],
                'url_demo'              => $validated['url_demo'] ?? null,
                'persetujuan_publikasi' => $request->boolean('persetujuan_publikasi', true),
                'status'                => $status,
            ]);

            // Daftarkan ketua tim di karya_peserta
            $karya->pesertas()->attach($peserta->id, [
                'peran'      => 'Ketua Tim',
                'is_ketua'   => true,
                'urutan'     => 1,
                'created_at' => now(),
            ]);

            // Daftarkan anggota tim tambahan
            if (! empty($validated['anggota_nama'])) {
                $urutan = 2;
                foreach ($validated['anggota_nama'] as $index => $nama) {
                    if (! empty(trim($nama))) {
                        $karya->anggotaTims()->create([
                            'nama_lengkap'  => trim($nama),
                            'peran'         => $validated['anggota_peran'][$index] ?? 'Anggota Tim',
                            'is_ketua'      => false,
                            'email'         => $validated['anggota_email'][$index] ?? null,
                            'institusi'     => $peserta->institusi,
                            'jenis_peserta' => $peserta->jenis_peserta,
                            'urutan'        => $urutan++,
                        ]);
                    }
                }
            }

            // Simpan berkas proposal jika diunggah
            if ($request->hasFile('proposal')) {
                $file = $request->file('proposal');
                $filename = $file->getClientOriginalName();
                $path = $file->store("karya_files/{$karya->id}", 'public');

                $karyaFile = KaryaFile::create([
                    'karya_id'      => $karya->id,
                    'jenis'         => 'dokumen',
                    'nama_asli'     => $filename,
                    'path'          => $path,
                    'mime_type'     => $file->getClientMimeType(),
                    'ukuran_byte'   => $file->getSize(),
                    'keterangan'    => 'Proposal Proyek / Karya Lomba',
                    'is_publik'     => true,
                    'checksum'      => hash_file('sha256', $file->getRealPath()),
                    'diunggah_oleh' => $peserta->id,
                ]);

                $karya->update(['file_utama' => $path]);
            }
        });

        $message = ($status === 'diajukan')
            ? 'Karya inovasi Anda berhasil didaftarkan dan diajukan untuk proses kurasi!'
            : 'Draft karya berhasil disimpan. Anda dapat melengkapinya kapan saja sebelum batas waktu.';

        return redirect()->route('dashboard')->with('status', $message);
    }

    /**
     * Finalisasi submit karya dari draft ke diajukan.
     */
    public function submit(Request $request, int $id): RedirectResponse
    {
        $peserta = $request->user()->peserta;
        $karya = Karya::where('peserta_id', $peserta?->id)->findOrFail($id);

        if ($karya->status !== 'draft' && $karya->status !== 'perlu_revisi') {
            return redirect()->route('dashboard')->with('info', 'Karya ini sudah diajukan sebelumnya.');
        }

        $karya->update(['status' => 'diajukan']);

        return redirect()->route('dashboard')->with('status', 'Karya berhasil diajukan untuk proses kurasi juri!');
    }

    /**
     * Unggah berkas tambahan (lampiran gambar/video/revisi proposal).
     */
    public function uploadFile(Request $request, int $id): RedirectResponse
    {
        $peserta = $request->user()->peserta;
        $karya = Karya::where('peserta_id', $peserta?->id)->findOrFail($id);

        $request->validate([
            'file'       => ['required', 'file', 'max:25600'], // max 25MB
            'jenis'      => ['required', 'in:utama,thumbnail,galeri,dokumen,video,lainnya'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $path = $file->store("karya_files/{$karya->id}", 'public');

        KaryaFile::create([
            'karya_id'      => $karya->id,
            'jenis'         => $request->input('jenis'),
            'nama_asli'     => $filename,
            'path'          => $path,
            'mime_type'     => $file->getClientMimeType(),
            'ukuran_byte'   => $file->getSize(),
            'keterangan'    => $request->input('keterangan'),
            'is_publik'     => true,
            'checksum'      => hash_file('sha256', $file->getRealPath()),
            'diunggah_oleh' => $peserta->id,
        ]);

        if ($request->input('jenis') === 'utama') {
            $karya->update(['file_utama' => $path]);
        } elseif ($request->input('jenis') === 'thumbnail') {
            $karya->update(['thumbnail' => $path]);
        }

        return redirect()->route('dashboard')->with('status', 'Berkas lampiran berhasil diunggah.');
    }
}
