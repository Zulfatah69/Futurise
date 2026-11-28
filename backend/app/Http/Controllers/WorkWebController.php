<?php

namespace App\Http\Controllers;

use App\Models\Festival;
use App\Models\Karya;
use App\Models\KaryaFile;
use App\Models\User;
use App\Services\KaryaService;
use App\Services\PublikasiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WorkWebController extends Controller
{
    public function __construct(
        protected KaryaService $karyaService,
        protected PublikasiService $publikasiService
    ) {}

    /**
     * Redirect kembali ke /dashboard (opsional dengan parameter tab bila diminta oleh form portal).
     */
    protected function redirectToDashboard(Request $request): RedirectResponse
    {
        $tab = $request->input('redirect_tab');
        $allowedTabs = ['ringkasan', 'profil', 'karya', 'dokumen', 'kurasi'];

        if (is_string($tab) && in_array($tab, $allowedTabs, true)) {
            return redirect()->route('dashboard', ['tab' => $tab]);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Cari karya yang berhak dikelola oleh user (ketua/anggota tim, atau PIC kampus untuk kampusnya).
     */
    protected function findAccessibleWork(User $user, int $id): Karya
    {
        $peserta = $user->peserta()->first();
        abort_if(! $peserta, 403, 'Profil kepesertaan belum dilengkapi.');

        $isPicKampus = $user->hasRole('pic-kampus');
        $normalizedInst = mb_strtolower(trim((string) $peserta->institusi));

        return Karya::query()
            ->where('id', $id)
            ->where(function ($query) use ($peserta, $isPicKampus, $normalizedInst) {
                $query->where('peserta_id', $peserta->id)
                    ->orWhereHas('pesertas', function ($pq) use ($peserta) {
                        $pq->where('peserta.id', $peserta->id);
                    });

                if ($isPicKampus && $normalizedInst !== '') {
                    $query->orWhereHas('submitter', function ($sq) use ($normalizedInst) {
                        $sq->whereRaw('LOWER(TRIM(institusi)) = ?', [$normalizedInst]);
                    });
                }
            })
            ->with(['submitter'])
            ->firstOrFail();
    }

    /**
     * Mendaftarkan karya baru dari Web Dashboard Peserta.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $peserta = $user->peserta()->first();

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
            'masalah'               => ['nullable', 'string'],
            'pengguna_sasaran'      => ['nullable', 'string', 'max:255'],
            'solusi'                => ['nullable', 'string'],
            'fitur_utama'           => ['nullable', 'string'],
            'tahap_kesiapan'        => ['nullable', 'string', 'max:100'],
            'hasil_uji'             => ['nullable', 'string'],
            'keterbatasan'          => ['nullable', 'string'],
            'kebutuhan_kolaborasi'  => ['nullable', 'string'],
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

        DB::transaction(function () use ($peserta, $festival, $validated, $status, $request, $user) {
            $this->karyaService->assertInstitutionQuotaAvailable($festival->id, $peserta);

            $karya = Karya::create([
                'festival_id'           => $festival->id,
                'peserta_id'            => $peserta->id,
                'judul'                 => $validated['judul'],
                'deskripsi'             => $validated['deskripsi'],
                'masalah'               => $validated['masalah'] ?? null,
                'pengguna_sasaran'      => $validated['pengguna_sasaran'] ?? null,
                'solusi'                => $validated['solusi'] ?? null,
                'fitur_utama'           => $validated['fitur_utama'] ?? null,
                'kategori'              => $validated['kategori'],
                'teknologi'             => $validated['teknologi'],
                'tahap_kesiapan'        => $validated['tahap_kesiapan'] ?? null,
                'hasil_uji'             => $validated['hasil_uji'] ?? null,
                'keterbatasan'          => $validated['keterbatasan'] ?? null,
                'kebutuhan_kolaborasi'  => $validated['kebutuhan_kolaborasi'] ?? null,
                'url_demo'              => $validated['url_demo'] ?? null,
                'persetujuan_publikasi' => $request->boolean('persetujuan_publikasi', true),
                'status'                => $status,
            ]);

            $this->karyaService->recordRevision(
                $karya,
                $user->id,
                null,
                $status,
                $status === 'diajukan' ? 'Karya didaftarkan dan langsung diajukan ke kurasi.' : 'Draft karya awal dibuat.'
            );

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

        return $this->redirectToDashboard($request)->with('status', $message);
    }

    /**
     * Perbarui detail karya pada status draft atau perlu_revisi.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $karya = $this->findAccessibleWork($request->user(), $id);

        if (! in_array($karya->status, ['draft', 'perlu_revisi'], true)) {
            return $this->redirectToDashboard($request)
                ->with('error', 'Karya hanya dapat diubah saat berstatus Draft atau Perlu Revisi.');
        }

        $validated = $request->validate([
            'judul'                 => ['required', 'string', 'max:200'],
            'kategori'              => ['required', 'string', 'max:100'],
            'teknologi'             => ['required', 'string', 'max:255'],
            'deskripsi'             => ['required', 'string'],
            'masalah'               => ['nullable', 'string'],
            'pengguna_sasaran'      => ['nullable', 'string', 'max:255'],
            'solusi'                => ['nullable', 'string'],
            'fitur_utama'           => ['nullable', 'string'],
            'tahap_kesiapan'        => ['nullable', 'string', 'max:100'],
            'hasil_uji'             => ['nullable', 'string'],
            'keterbatasan'          => ['nullable', 'string'],
            'kebutuhan_kolaborasi'  => ['nullable', 'string'],
            'url_demo'              => ['nullable', 'url', 'max:255'],
            'persetujuan_publikasi' => ['nullable', 'boolean'],
            'catatan_perbaikan'     => ['nullable', 'string', 'max:500'],
        ], [
            'judul.required'     => 'Judul karya wajib diisi.',
            'kategori.required'  => 'Kategori lomba wajib dipilih.',
            'teknologi.required' => 'Stack teknologi wajib diisi.',
            'deskripsi.required' => 'Deskripsi ringkasan karya wajib diisi.',
        ]);

        DB::transaction(function () use ($karya, $validated, $request): void {
            $status = $karya->status;

            $karya->update([
                'judul'                 => $validated['judul'],
                'deskripsi'             => $validated['deskripsi'],
                'masalah'               => $validated['masalah'] ?? null,
                'pengguna_sasaran'      => $validated['pengguna_sasaran'] ?? null,
                'solusi'                => $validated['solusi'] ?? null,
                'fitur_utama'           => $validated['fitur_utama'] ?? null,
                'kategori'              => $validated['kategori'],
                'teknologi'             => $validated['teknologi'],
                'tahap_kesiapan'        => $validated['tahap_kesiapan'] ?? null,
                'hasil_uji'             => $validated['hasil_uji'] ?? null,
                'keterbatasan'          => $validated['keterbatasan'] ?? null,
                'kebutuhan_kolaborasi'  => $validated['kebutuhan_kolaborasi'] ?? null,
                'url_demo'              => $validated['url_demo'] ?? null,
                'persetujuan_publikasi' => $request->boolean('persetujuan_publikasi', true),
            ]);

            $this->karyaService->recordRevision(
                $karya->fresh(),
                $request->user()->id,
                $status,
                $status,
                $validated['catatan_perbaikan'] ?? 'Pembaruan data karya melalui portal peserta/kampus.'
            );
        });

        return $this->redirectToDashboard($request)
            ->with('status', 'Perubahan detail karya berhasil disimpan ke riwayat versi.');
    }

    /**
     * Finalisasi submit karya dari draft ke diajukan.
     */
    public function submit(Request $request, int $id): RedirectResponse
    {
        $karya = $this->findAccessibleWork($request->user(), $id);

        if ($karya->status !== 'draft' && $karya->status !== 'perlu_revisi') {
            return $this->redirectToDashboard($request)->with('info', 'Karya ini sudah diajukan sebelumnya.');
        }

        $statusLama = $karya->status;

        DB::transaction(function () use ($karya, $statusLama, $request): void {
            $karya->update(['status' => 'diajukan']);
            $this->karyaService->recordRevision(
                $karya,
                $request->user()->id,
                $statusLama,
                'diajukan',
                $statusLama === 'perlu_revisi' ? 'Karya diajukan ulang setelah perbaikan.' : 'Karya diajukan final untuk kurasi.'
            );
        });

        return $this->redirectToDashboard($request)->with('status', 'Karya berhasil diajukan untuk proses kurasi!');
    }

    /**
     * Unggah berkas tambahan (lampiran gambar/video/revisi proposal).
     */
    public function uploadFile(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();
        $peserta = $user->peserta()->first();
        $karya = $this->findAccessibleWork($user, $id);

        $request->validate([
            'file'       => [
                'required',
                'file',
                'max:25600', // max 25MB
                'mimes:pdf,jpg,jpeg,png,gif,mp4,mov,zip,rar,doc,docx,ppt,pptx',
            ],
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

        return $this->redirectToDashboard($request)->with('status', 'Berkas lampiran berhasil diunggah.');
    }

    /**
     * Tambah anggota tim ke karya yang masih dalam tahap draft atau perlu_revisi.
     */
    public function addMember(Request $request, int $id): RedirectResponse
    {
        $karya = $this->findAccessibleWork($request->user(), $id);

        if (! in_array($karya->status, ['draft', 'perlu_revisi'], true)) {
            return $this->redirectToDashboard($request)
                ->with('error', 'Anggota tim hanya dapat ditambah saat status karya Draft atau Perlu Revisi.');
        }

        $validated = $request->validate([
            'nama_lengkap'    => ['required', 'string', 'max:100'],
            'peran'           => ['required', 'string', 'max:50'],
            'email'           => ['nullable', 'email', 'max:100'],
            'no_telepon'      => ['nullable', 'string', 'max:20'],
            'nomor_identitas' => ['nullable', 'string', 'max:30'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap anggota tim wajib diisi.',
            'peran.required'        => 'Peran anggota tim wajib diisi.',
        ]);

        $nextOrder = ((int) $karya->anggotaTims()->max('urutan')) + 1;
        $submitter = $karya->submitter ?? $request->user()->peserta;

        $karya->anggotaTims()->create([
            'nama_lengkap'    => trim($validated['nama_lengkap']),
            'peran'           => trim($validated['peran']),
            'is_ketua'        => false,
            'email'           => $validated['email'] ?? null,
            'no_telepon'      => $validated['no_telepon'] ?? null,
            'nomor_identitas' => $validated['nomor_identitas'] ?? null,
            'institusi'       => $submitter?->institusi,
            'jenis_peserta'   => $submitter?->jenis_peserta ?? 'mahasiswa',
            'urutan'          => max(2, $nextOrder),
        ]);

        return $this->redirectToDashboard($request)
            ->with('status', 'Anggota tim berhasil ditambahkan ke karya.');
    }

    /**
     * Hapus anggota non-ketua dari tim karya.
     */
    public function removeMember(Request $request, int $id, int $memberId): RedirectResponse
    {
        $karya = $this->findAccessibleWork($request->user(), $id);

        if (! in_array($karya->status, ['draft', 'perlu_revisi'], true)) {
            return $this->redirectToDashboard($request)
                ->with('error', 'Anggota tim hanya dapat dihapus saat status karya Draft atau Perlu Revisi.');
        }

        $member = $karya->anggotaTims()->findOrFail($memberId);
        if ($member->is_ketua) {
            return $this->redirectToDashboard($request)
                ->with('error', 'Ketua tim tidak dapat dihapus dari daftar tim.');
        }

        $member->delete();

        return $this->redirectToDashboard($request)
            ->with('status', 'Anggota tim berhasil dihapus.');
    }

    /**
     * Ajukan persetujuan publikasi showcase untuk karya yang sudah lolos kurasi.
     */
    public function requestPublication(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();
        $karya = $this->findAccessibleWork($user, $id);

        if ($karya->status !== 'lolos') {
            return $this->redirectToDashboard($request)
                ->with('error', 'Hanya karya yang berstatus Lolos Kurasi yang dapat diajukan ke Showcase Publik.');
        }

        $validated = $request->validate([
            'judul_publik'     => ['nullable', 'string', 'max:180'],
            'ringkasan_publik' => ['nullable', 'string', 'max:500'],
            'izin_peserta'     => ['accepted'],
        ], [
            'izin_peserta.accepted' => 'Persetujuan izin publikasi peserta wajib dicentang.',
        ]);

        DB::transaction(function () use ($karya, $validated, $user): void {
            $karya->update(['persetujuan_publikasi' => true]);

            $publikasi = $this->publikasiService->createOrUpdateDraft($karya, [
                'kanal'            => 'website',
                'judul_publik'     => ! empty($validated['judul_publik']) ? trim($validated['judul_publik']) : $karya->judul,
                'ringkasan_publik' => ! empty($validated['ringkasan_publik']) ? trim($validated['ringkasan_publik']) : Str::limit($karya->deskripsi, 450),
                'izin_peserta'     => true,
            ], $user);

            if (! in_array($publikasi->status, ['menunggu_persetujuan', 'terjadwal', 'terbit'], true)) {
                $this->publikasiService->submitForApproval(
                    $publikasi,
                    $user,
                    'Diajukan dari Portal Peserta / PIC Kampus untuk persetujuan Divisi Publikasi.'
                );
            }
        });

        return $this->redirectToDashboard($request)
            ->with('status', 'Pengajuan publikasi Showcase berhasil dikirim ke Divisi Publikasi.');
    }

    /**
     * Hapus draft karya agar kuota pengajuan kampus dapat digunakan kembali.
     */
    public function destroy(Request $request, int $id): RedirectResponse
    {
        $karya = $this->findAccessibleWork($request->user(), $id);

        if ($karya->status !== 'draft') {
            return $this->redirectToDashboard($request)
                ->with('error', 'Hanya karya berstatus Draft yang dapat dihapus.');
        }

        $karya->load('files');
        $paths = $karya->files->pluck('path')->filter()->values()->all();

        DB::transaction(function () use ($karya): void {
            $karya->pesertas()->detach();
            $karya->delete();
        });

        foreach ($paths as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        return $this->redirectToDashboard($request)
            ->with('status', 'Draft karya berhasil dihapus dan kuota pengajuan kembali tersedia.');
    }

    /**
     * Hapus berkas lampiran karya saat status masih draft atau perlu_revisi.
     */
    public function deleteFile(Request $request, int $id, int $fileId): RedirectResponse
    {
        $karya = $this->findAccessibleWork($request->user(), $id);

        if (! in_array($karya->status, ['draft', 'perlu_revisi'], true)) {
            return $this->redirectToDashboard($request)
                ->with('error', 'Berkas lampiran hanya dapat dihapus saat karya berstatus Draft atau Perlu Revisi.');
        }

        $file = $karya->files()->findOrFail($fileId);
        $path = $file->path;

        DB::transaction(function () use ($karya, $file, $path): void {
            if ($karya->file_utama === $path) {
                $karya->update(['file_utama' => null]);
            }
            if ($karya->thumbnail === $path) {
                $karya->update(['thumbnail' => null]);
            }

            $file->delete();
        });

        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        return $this->redirectToDashboard($request)
            ->with('status', 'Berkas lampiran berhasil dihapus.');
    }
}
