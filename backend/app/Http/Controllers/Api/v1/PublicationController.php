<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublikasiKaryaResource;
use App\Http\Resources\PublikasiLogResource;
use App\Models\Karya;
use App\Models\Peserta;
use App\Models\PublikasiKarya;
use App\Services\PublikasiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    public function __construct(
        protected PublikasiService $publikasiService
    ) {}

    /**
     * Helper standard JSON response.
     */
    private function jsonResponse(bool $success, string $message, mixed $data = null, mixed $meta = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $data,
            'meta'    => $meta,
        ], $code);
    }

    /**
     * Helper cek apakah user adalah admin atau panitia.
     */
    private function isAdminOrPanitia(Request $request): bool
    {
        $user = $request->user();
        return $user->hasAnyRole(\App\Models\User::ADMIN_ROLES) || $user->hasRole('panitia');
    }

    /**
     * Helper cek hak akses kepemilikan karya atau role pengelola (admin/panitia).
     */
    private function authorizeManageKarya(Request $request, Karya $karya): bool
    {
        if ($this->isAdminOrPanitia($request)) {
            return true;
        }

        $user = $request->user();
        $peserta = Peserta::where('user_id', $user->id)->first();
        if (! $peserta) {
            return false;
        }

        return $karya->peserta_id === $peserta->id
            || $karya->pesertas()->where('peserta.id', $peserta->id)->exists();
    }

    /**
     * Daftar semua publikasi (Internal Panitia & Peserta).
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = PublikasiKarya::with(['karya.submitter', 'thumbnailFile']);

        // Jika bukan admin/panitia, hanya tampilkan karya milik peserta yang login
        if (! $this->isAdminOrPanitia($request)) {
            $peserta = Peserta::where('user_id', $user->id)->first();
            if (! $peserta) {
                return $this->jsonResponse(true, 'Daftar publikasi karya kosong.', [], null);
            }
            $query->whereHas('karya', function ($q) use ($peserta) {
                $q->where('peserta_id', $peserta->id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('kanal')) {
            $query->where('kanal', $request->query('kanal'));
        }

        $perPage = min((int) $request->query('per_page', 15), 50);
        $publications = $query->latest()->paginate($perPage);

        return $this->jsonResponse(
            true,
            'Daftar publikasi karya berhasil diambil.',
            PublikasiKaryaResource::collection($publications->items()),
            [
                'current_page' => $publications->currentPage(),
                'last_page'    => $publications->lastPage(),
                'per_page'     => $publications->perPage(),
                'total'        => $publications->total(),
            ]
        );
    }

    /**
     * Detail satu publikasi karya.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $pub = PublikasiKarya::with(['karya.submitter', 'karya.files', 'thumbnailFile', 'logs.updater'])->findOrFail($id);

        if (! $this->authorizeManageKarya($request, $pub->karya)) {
            return $this->jsonResponse(false, 'Anda tidak berhak mengakses data publikasi karya ini.', null, null, 403);
        }

        return $this->jsonResponse(
            true,
            'Detail publikasi karya berhasil diambil.',
            new PublikasiKaryaResource($pub)
        );
    }

    /**
     * Simpan / buat draft publikasi karya.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'karya_id'          => ['required', 'integer', 'exists:karya,id'],
            'kanal'             => ['nullable', 'string', 'in:website,galeri_online,media_sosial,katalog_cetak,layar_venue'],
            'slug'              => ['nullable', 'string', 'max:200', 'unique:publikasi_karya,slug'],
            'judul_publik'      => ['nullable', 'string', 'max:200'],
            'ringkasan_publik'  => ['nullable', 'string', 'max:500'],
            'thumbnail_file_id' => ['nullable', 'integer', 'exists:karya_file,id'],
            'is_unggulan'       => ['nullable', 'boolean'],
            'urutan_tampil'     => ['nullable', 'integer'],
            'izin_peserta'      => ['nullable', 'boolean'],
            'meta_title'        => ['nullable', 'string', 'max:70'],
            'meta_description'  => ['nullable', 'string', 'max:160'],
        ]);

        $karya = Karya::findOrFail($validated['karya_id']);

        if (! $this->authorizeManageKarya($request, $karya)) {
            return $this->jsonResponse(false, 'Anda tidak berhak membuat publikasi untuk karya ini.', null, null, 403);
        }

        $publikasi = $this->publikasiService->createOrUpdateDraft($karya, $validated, $request->user());

        return $this->jsonResponse(
            true,
            'Draft publikasi karya berhasil disimpan.',
            new PublikasiKaryaResource($publikasi->load(['karya.submitter', 'thumbnailFile'])),
            null,
            201
        );
    }

    /**
     * Perbarui draft publikasi karya.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $pub = PublikasiKarya::findOrFail($id);

        if (! $this->authorizeManageKarya($request, $pub->karya)) {
            return $this->jsonResponse(false, 'Anda tidak berhak mengubah publikasi karya ini.', null, null, 403);
        }

        $validated = $request->validate([
            'judul_publik'      => ['nullable', 'string', 'max:200'],
            'ringkasan_publik'  => ['nullable', 'string', 'max:500'],
            'slug'              => ['nullable', 'string', 'max:200', 'unique:publikasi_karya,slug,' . $id],
            'thumbnail_file_id' => ['nullable', 'integer', 'exists:karya_file,id'],
            'is_unggulan'       => ['nullable', 'boolean'],
            'urutan_tampil'     => ['nullable', 'integer'],
            'izin_peserta'      => ['nullable', 'boolean'],
            'meta_title'        => ['nullable', 'string', 'max:70'],
            'meta_description'  => ['nullable', 'string', 'max:160'],
        ]);

        $updated = $this->publikasiService->createOrUpdateDraft($pub->karya, $validated, $request->user());

        return $this->jsonResponse(
            true,
            'Publikasi karya berhasil diperbarui.',
            new PublikasiKaryaResource($updated->load(['karya.submitter', 'thumbnailFile']))
        );
    }

    /**
     * Ajukan publikasi untuk kurasi (oleh peserta/ketua tim).
     */
    public function submitForApproval(Request $request, int $id): JsonResponse
    {
        $pub = PublikasiKarya::findOrFail($id);

        if (! $this->authorizeManageKarya($request, $pub->karya)) {
            return $this->jsonResponse(false, 'Anda tidak berhak mengajukan publikasi karya ini.', null, null, 403);
        }

        $validated = $request->validate([
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);
        $notes = $validated['catatan'] ?? null;
        $updated = $this->publikasiService->submitForApproval($pub, $request->user(), $notes);

        return $this->jsonResponse(
            true,
            'Publikasi karya berhasil diajukan untuk proses kurasi panitia.',
            new PublikasiKaryaResource($updated)
        );
    }

    /**
     * Setujui publikasi karya (kurasi lulus, siap tampil).
     */
    public function approve(Request $request, int $id): JsonResponse
    {
        if (! $this->isAdminOrPanitia($request)) {
            return $this->jsonResponse(false, 'Hanya panitia atau admin yang dapat menyetujui publikasi karya.', null, null, 403);
        }

        $pub = PublikasiKarya::findOrFail($id);
        $validated = $request->validate([
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);
        $notes = $validated['catatan'] ?? null;

        $approved = $this->publikasiService->approve($pub, $request->user(), $notes);

        return $this->jsonResponse(
            true,
            'Publikasi karya berhasil disetujui.',
            new PublikasiKaryaResource($approved)
        );
    }

    /**
     * Rilis/Terbitkan langsung ke etalase publik.
     */
    public function publish(Request $request, int $id): JsonResponse
    {
        if (! $this->isAdminOrPanitia($request)) {
            return $this->jsonResponse(false, 'Hanya panitia atau admin yang dapat menerbitkan publikasi karya.', null, null, 403);
        }

        $pub = PublikasiKarya::findOrFail($id);
        $validated = $request->validate([
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);
        $notes = $validated['catatan'] ?? null;

        $published = $this->publikasiService->publish($pub, $request->user(), $notes);

        return $this->jsonResponse(
            true,
            'Karya berhasil diterbitkan ke etalase showcase publik.',
            new PublikasiKaryaResource($published)
        );
    }

    /**
     * Tarik karya dari peredaran showcase publik.
     */
    public function withdraw(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $pub = PublikasiKarya::findOrFail($id);

        if (! $this->authorizeManageKarya($request, $pub->karya)) {
            return $this->jsonResponse(false, 'Anda tidak berhak menarik publikasi karya ini.', null, null, 403);
        }

        $validated = $request->validate([
            'alasan' => ['required', 'string', 'max:255'],
        ]);

        $withdrawn = $this->publikasiService->withdraw($pub, $user, $validated['alasan']);

        return $this->jsonResponse(
            true,
            'Publikasi karya berhasil ditarik.',
            new PublikasiKaryaResource($withdrawn)
        );
    }

    /**
     * Riwayat log audit publikasi.
     */
    public function logs(Request $request, int $id): JsonResponse
    {
        $pub = PublikasiKarya::findOrFail($id);

        if (! $this->authorizeManageKarya($request, $pub->karya)) {
            return $this->jsonResponse(false, 'Anda tidak berhak melihat riwayat log karya ini.', null, null, 403);
        }

        $logs = $pub->logs()->with('updater')->get();

        return $this->jsonResponse(
            true,
            'Riwayat log publikasi berhasil diambil.',
            PublikasiLogResource::collection($logs)
        );
    }

    // =========================================================================
    // ENDPOINT PUBLIK (SHOWCASE & EXPO) — Tanpa Otentikasi
    // =========================================================================

    /**
     * Katalog Galeri Showcase Publik (Bebas diakses pengunjung).
     */
    public function publicShowcase(Request $request): JsonResponse
    {
        $filters = [
            'kategori'    => $request->query('kategori'),
            'festival_id' => $request->query('festival_id'),
            'search'      => $request->query('search'),
            'unggulan'    => $request->boolean('unggulan'),
            'sort'        => $request->query('sort', 'terbaru'),
        ];

        $perPage = min((int) $request->query('per_page', 12), 48);
        $showcase = $this->publikasiService->getPublicShowcase($filters, $perPage);

        return $this->jsonResponse(
            true,
            'Galeri showcase karya berhasil diambil.',
            PublikasiKaryaResource::collection($showcase->items()),
            [
                'current_page' => $showcase->currentPage(),
                'last_page'    => $showcase->lastPage(),
                'per_page'     => $showcase->perPage(),
                'total'        => $showcase->total(),
            ]
        );
    }

    /**
     * Detail satu karya di etalase publik (via slug ramah SEO).
     */
    public function publicShowcaseDetail(string $slug): JsonResponse
    {
        $pub = PublikasiKarya::published()
            ->with([
                'karya.submitter:id,nama_lengkap,institusi',
                'karya.festival',
                'karya.pesertas:id,nama_lengkap,institusi',
                'karya.anggotaTims:id,karya_id,nama_lengkap,peran,is_ketua,urutan',
                'karya.files' => fn ($q) => $q->where('is_publik', true),
                'thumbnailFile',
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment tayangan
        $this->publikasiService->incrementView($pub);

        return $this->jsonResponse(
            true,
            'Detail karya showcase berhasil diambil.',
            new PublikasiKaryaResource($pub)
        );
    }

    /**
     * Kirim apresiasi/suka untuk karya publik.
     */
    public function like(string $slug): JsonResponse
    {
        $pub = PublikasiKarya::published()->where('slug', $slug)->firstOrFail();
        $totalLikes = $this->publikasiService->incrementLike($pub);

        return $this->jsonResponse(
            true,
            'Apresiasi Anda berhasil dikirimkan.',
            ['jumlah_suka' => $totalLikes]
        );
    }
}
