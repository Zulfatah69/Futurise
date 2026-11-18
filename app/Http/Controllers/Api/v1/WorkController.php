<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\AnggotaTim;
use App\Models\Festival;
use App\Models\Karya;
use App\Models\KaryaFile;
use App\Models\Peserta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WorkController extends Controller
{
    private function jsonResponse(bool $success, string $message, mixed $data = null, mixed $meta = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $data,
            'meta'    => $meta,
        ], $statusCode);
    }

    /**
     * Helper untuk mengambil data Peserta dari User yang sedang login.
     */
    private function getCurrentPeserta(Request $request): ?Peserta
    {
        return Peserta::where('user_id', $request->user()->id)->first();
    }

    /**
     * GET /api/v1/works
     * Menampilkan daftar karya peserta yang sedang login (atau semua karya jika admin).
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Karya::query()->with(['festival:id,nama,edisi,tahun', 'submitter:id,nama_lengkap,institusi', 'anggotaTims', 'files']);

        $peserta = $this->getCurrentPeserta($request);

        // Jika bukan admin, hanya tampilkan karya di mana user adalah ketua/anggota
        if (! $user->hasRole('super-admin') && ! $user->hasRole('admin')) {
            if (! $peserta) {
                return $this->jsonResponse(true, 'Belum memiliki karya yang didaftarkan.', []);
            }
            $query->where(function ($q) use ($peserta) {
                $q->where('peserta_id', $peserta->id)
                  ->orWhereHas('pesertas', function ($p) use ($peserta) {
                      $p->where('peserta.id', $peserta->id);
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->query('kategori'));
        }

        if ($request->filled('festival_id')) {
            $query->where('festival_id', $request->query('festival_id'));
        }

        if ($request->filled('q')) {
            $search = $request->query('q');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('teknologi', 'like', "%{$search}%");
            });
        }

        $perPage = min((int) $request->query('per_page', 15), 50);
        $works = $query->latest()->paginate($perPage);

        return $this->jsonResponse(true, 'Daftar karya berhasil dimuat.', $works->items(), [
            'current_page' => $works->currentPage(),
            'per_page'     => $works->perPage(),
            'total'        => $works->total(),
            'last_page'    => $works->lastPage(),
        ]);
    }

    /**
     * GET /api/v1/works/{id}
     * Menampilkan detail satu karya.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $karya = Karya::with([
            'festival',
            'submitter',
            'pesertas',
            'anggotaTims',
            'files',
        ])->find($id);

        if (! $karya) {
            return $this->jsonResponse(false, 'Karya tidak ditemukan.', null, null, 404);
        }

        return $this->jsonResponse(true, 'Detail karya berhasil dimuat.', $karya);
    }

    /**
     * POST /api/v1/works
     * Mendaftarkan draft karya baru oleh peserta.
     */
    public function store(Request $request): JsonResponse
    {
        $peserta = $this->getCurrentPeserta($request);
        if (! $peserta) {
            return $this->jsonResponse(false, 'Anda harus melengkapi profil peserta sebelum mendaftarkan karya lomba.', null, null, 403);
        }

        $validated = $request->validate([
            'festival_id'           => 'nullable|exists:festival,id',
            'judul'                 => 'required|string|max:200',
            'deskripsi'             => 'required|string',
            'kategori'              => 'required|string|max:50',
            'teknologi'             => 'required|string|max:255',
            'url_demo'              => 'nullable|url|max:255',
            'lisensi'               => 'nullable|string|max:50',
            'persetujuan_publikasi' => 'nullable|boolean',
            'anggota_tim'           => 'nullable|array',
            'anggota_tim.*.nama_lengkap' => 'required|string|max:100',
            'anggota_tim.*.peran'        => 'nullable|string|max:50',
            'anggota_tim.*.email'        => 'nullable|email|max:100',
            'anggota_tim.*.no_telepon'   => 'nullable|string|max:20',
            'anggota_tim.*.institusi'    => 'nullable|string|max:150',
        ]);

        // Default ke festival yang aktif jika tidak disertakan
        $festivalId = $validated['festival_id'] ?? Festival::aktif()->value('id');
        if (! $festivalId) {
            return $this->jsonResponse(false, 'Tidak ada festival yang sedang aktif untuk pendaftaran karya.', null, null, 422);
        }

        $karya = DB::transaction(function () use ($validated, $festivalId, $peserta) {
            $karya = Karya::create([
                'festival_id'           => $festivalId,
                'peserta_id'            => $peserta->id,
                'judul'                 => $validated['judul'],
                'deskripsi'             => $validated['deskripsi'],
                'kategori'              => $validated['kategori'],
                'teknologi'             => $validated['teknologi'],
                'url_demo'              => $validated['url_demo'] ?? null,
                'lisensi'               => $validated['lisensi'] ?? null,
                'persetujuan_publikasi' => $validated['persetujuan_publikasi'] ?? false,
                'status'                => 'draft',
            ]);

            // Daftarkan ketua tim di karya_peserta
            $karya->pesertas()->attach($peserta->id, [
                'peran'      => 'Ketua Tim',
                'is_ketua'   => true,
                'urutan'     => 1,
                'created_at' => now(),
            ]);

            // Jika ada anggota tim tambahan
            if (! empty($validated['anggota_tim'])) {
                $urutan = 2;
                foreach ($validated['anggota_tim'] as $anggota) {
                    $karya->anggotaTims()->create([
                        'nama_lengkap'    => $anggota['nama_lengkap'],
                        'peran'           => $anggota['peran'] ?? 'Anggota',
                        'is_ketua'        => false,
                        'email'           => $anggota['email'] ?? null,
                        'no_telepon'      => $anggota['no_telepon'] ?? null,
                        'institusi'       => $anggota['institusi'] ?? $peserta->institusi,
                        'jenis_peserta'   => $peserta->jenis_peserta,
                        'urutan'          => $urutan++,
                    ]);
                }
            }

            return $karya;
        });

        $karya->load(['festival', 'submitter', 'pesertas', 'anggotaTims']);

        return $this->jsonResponse(true, 'Draft karya berhasil dibuat.', $karya, null, 201);
    }

    /**
     * PUT/PATCH /api/v1/works/{id}
     * Memperbarui data karya (hanya bisa jika masih draft atau perlu_revisi).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $karya = Karya::find($id);
        if (! $karya) {
            return $this->jsonResponse(false, 'Karya tidak ditemukan.', null, null, 404);
        }

        $peserta = $this->getCurrentPeserta($request);
        $user = $request->user();

        // Cek izin (hanya ketua tim atau admin)
        if ($karya->peserta_id !== ($peserta?->id) && ! $user->hasRole('super-admin') && ! $user->hasRole('admin')) {
            return $this->jsonResponse(false, 'Anda tidak memiliki hak untuk mengubah karya ini.', null, null, 403);
        }

        if (! in_array($karya->status, ['draft', 'perlu_revisi']) && ! $user->hasRole('super-admin')) {
            return $this->jsonResponse(false, 'Karya yang sudah diajukan tidak dapat diubah.', null, null, 422);
        }

        $validated = $request->validate([
            'judul'                 => 'sometimes|required|string|max:200',
            'deskripsi'             => 'sometimes|required|string',
            'kategori'              => 'sometimes|required|string|max:50',
            'teknologi'             => 'sometimes|required|string|max:255',
            'url_demo'              => 'nullable|url|max:255',
            'lisensi'               => 'nullable|string|max:50',
            'persetujuan_publikasi' => 'nullable|boolean',
        ]);

        $karya->update($validated);

        return $this->jsonResponse(true, 'Data karya berhasil diperbarui.', $karya);
    }

    /**
     * POST /api/v1/works/{id}/submit
     * Finalisasi pengajuan karya dari status draft menjadi diajukan.
     */
    public function submit(Request $request, int $id): JsonResponse
    {
        $karya = Karya::with(['files'])->find($id);
        if (! $karya) {
            return $this->jsonResponse(false, 'Karya tidak ditemukan.', null, null, 404);
        }

        $peserta = $this->getCurrentPeserta($request);
        if ($karya->peserta_id !== ($peserta?->id)) {
            return $this->jsonResponse(false, 'Hanya ketua tim yang dapat mengajukan karya secara final.', null, null, 403);
        }

        if ($karya->status !== 'draft' && $karya->status !== 'perlu_revisi') {
            return $this->jsonResponse(false, 'Karya ini sudah diajukan sebelumnya.', null, null, 422);
        }

        $karya->update([
            'status' => 'diajukan',
        ]);

        return $this->jsonResponse(true, 'Karya berhasil diajukan untuk proses kurasi.', $karya);
    }

    /**
     * POST /api/v1/works/{id}/files
     * Mengunggah berkas lampiran karya (proposal PDF, gambar thumbnail, demo video).
     */
    public function uploadFile(Request $request, int $id): JsonResponse
    {
        $karya = Karya::find($id);
        if (! $karya) {
            return $this->jsonResponse(false, 'Karya tidak ditemukan.', null, null, 404);
        }

        $peserta = $this->getCurrentPeserta($request);
        if ($karya->peserta_id !== ($peserta?->id)) {
            return $this->jsonResponse(false, 'Anda tidak berwenang mengunggah berkas untuk karya ini.', null, null, 403);
        }

        $request->validate([
            'file'        => 'required|file|max:25600', // maks 25 MB
            'jenis'       => 'required|in:utama,thumbnail,galeri,dokumen,video,lainnya',
            'keterangan'  => 'nullable|string|max:255',
            'is_publik'   => 'nullable|boolean',
        ]);

        $uploadedFile = $request->file('file');
        $originalName = $uploadedFile->getClientOriginalName();
        $mimeType = $uploadedFile->getClientMimeType();
        $size = $uploadedFile->getSize();
        $checksum = hash_file('sha256', $uploadedFile->getRealPath());

        // Simpan file ke storage publik
        $path = $uploadedFile->store("karya_files/{$id}", 'public');

        $karyaFile = KaryaFile::create([
            'karya_id'      => $id,
            'jenis'         => $request->input('jenis'),
            'nama_asli'     => $originalName,
            'path'          => $path,
            'mime_type'     => $mimeType,
            'ukuran_byte'   => $size,
            'keterangan'    => $request->input('keterangan'),
            'is_publik'     => $request->boolean('is_publik', true),
            'checksum'      => $checksum,
            'diunggah_oleh' => $peserta->id,
        ]);

        // Jika jenisnya 'utama', perbarui juga di kolom file_utama karya
        if ($request->input('jenis') === 'utama') {
            $karya->update(['file_utama' => $path]);
        } elseif ($request->input('jenis') === 'thumbnail') {
            $karya->update(['thumbnail' => $path]);
        }

        return $this->jsonResponse(true, 'Berkas karya berhasil diunggah.', $karyaFile, null, 201);
    }

    /**
     * GET /api/v1/works/{id}/files
     * Mengambil seluruh berkas yang dilampirkan pada karya.
     */
    public function getFiles(int $id): JsonResponse
    {
        $karya = Karya::find($id);
        if (! $karya) {
            return $this->jsonResponse(false, 'Karya tidak ditemukan.', null, null, 404);
        }

        return $this->jsonResponse(true, 'Daftar berkas karya berhasil dimuat.', $karya->files);
    }

    /**
     * POST /api/v1/works/{id}/team-members
     * Menambahkan anggota tim pada karya.
     */
    public function addTeamMember(Request $request, int $id): JsonResponse
    {
        $karya = Karya::find($id);
        if (! $karya) {
            return $this->jsonResponse(false, 'Karya tidak ditemukan.', null, null, 404);
        }

        $peserta = $this->getCurrentPeserta($request);
        if ($karya->peserta_id !== ($peserta?->id)) {
            return $this->jsonResponse(false, 'Hanya ketua tim yang dapat menambahkan anggota.', null, null, 403);
        }

        $validated = $request->validate([
            'nama_lengkap'    => 'required|string|max:100',
            'peran'           => 'nullable|string|max:50',
            'email'           => 'nullable|email|max:100',
            'no_telepon'      => 'nullable|string|max:20',
            'institusi'       => 'nullable|string|max:150',
            'jenis_peserta'   => 'nullable|string|max:30',
            'nomor_identitas' => 'nullable|string|max:30',
        ]);

        $nextUrutan = ($karya->anggotaTims()->max('urutan') ?? 1) + 1;

        $anggota = $karya->anggotaTims()->create(array_merge($validated, [
            'is_ketua' => false,
            'urutan'   => $nextUrutan,
        ]));

        return $this->jsonResponse(true, 'Anggota tim berhasil ditambahkan.', $anggota, null, 201);
    }

    /**
     * DELETE /api/v1/works/{id}/team-members/{memberId}
     * Menghapus anggota tim.
     */
    public function removeTeamMember(Request $request, int $id, int $memberId): JsonResponse
    {
        $karya = Karya::find($id);
        if (! $karya) {
            return $this->jsonResponse(false, 'Karya tidak ditemukan.', null, null, 404);
        }

        $peserta = $this->getCurrentPeserta($request);
        if ($karya->peserta_id !== ($peserta?->id)) {
            return $this->jsonResponse(false, 'Hanya ketua tim yang dapat menghapus anggota.', null, null, 403);
        }

        $member = AnggotaTim::where('karya_id', $id)->find($memberId);
        if (! $member) {
            return $this->jsonResponse(false, 'Anggota tim tidak ditemukan.', null, null, 404);
        }

        $member->delete();

        return $this->jsonResponse(true, 'Anggota tim berhasil dihapus.', null);
    }
}
