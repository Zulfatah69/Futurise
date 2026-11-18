<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Festival;
use App\Models\FestivalKategori;
use App\Models\FestivalPeriode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FestivalController extends Controller
{
    /**
     * Format response JSON standar FUTURiSE API.
     */
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
     * GET /api/v1/festivals
     * Daftar festival (dapat difilter berdasarkan status aktif atau pencarian).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Festival::query()->with(['periodes', 'kategoris']);

        if ($request->has('aktif')) {
            $query->where('aktif', filter_var($request->query('aktif'), FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->filled('q')) {
            $search = $request->query('q');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('tema', 'like', "%{$search}%")
                  ->orWhere('tahun', 'like', "%{$search}%");
            });
        }

        $perPage = min((int) $request->query('per_page', 15), 50);
        $festivals = $query->orderByDesc('tahun')->paginate($perPage);

        return $this->jsonResponse(true, 'Daftar festival berhasil diambil.', $festivals->items(), [
            'current_page' => $festivals->currentPage(),
            'per_page'     => $festivals->perPage(),
            'total'        => $festivals->total(),
            'last_page'    => $festivals->lastPage(),
        ]);
    }

    /**
     * GET /api/v1/festivals/active
     * Mengambil festival yang sedang aktif lengkap dengan tahapan periode dan kategori lomba.
     */
    public function active(): JsonResponse
    {
        $festival = Festival::aktif()
            ->with(['periodes', 'kategoris'])
            ->first();

        if (! $festival) {
            return $this->jsonResponse(false, 'Tidak ada festival yang sedang aktif saat ini.', null, null, 404);
        }

        return $this->jsonResponse(true, 'Festival aktif berhasil diambil.', $festival);
    }

    /**
     * GET /api/v1/festivals/{idOrSlug}
     * Detail satu festival berdasarkan ID atau slug.
     */
    public function show(string $idOrSlug): JsonResponse
    {
        $festival = is_numeric($idOrSlug)
            ? Festival::with(['periodes', 'kategoris'])->find($idOrSlug)
            : Festival::with(['periodes', 'kategoris'])->where('slug', $idOrSlug)->first();

        if (! $festival) {
            return $this->jsonResponse(false, 'Festival tidak ditemukan.', null, null, 404);
        }

        return $this->jsonResponse(true, 'Detail festival berhasil diambil.', $festival);
    }

    /**
     * POST /api/v1/festivals
     * Membuat festival baru.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama'            => 'required|string|max:150',
            'slug'            => 'nullable|string|max:150|unique:festival,slug',
            'edisi'           => 'required|string|max:50',
            'tahun'           => 'required|integer|digits:4',
            'tema'            => 'nullable|string|max:255',
            'tagline'         => 'nullable|string|max:255',
            'deskripsi'       => 'nullable|string',
            'penyelenggara'   => 'nullable|string|max:150',
            'format'          => 'nullable|in:luring,daring,hibrida',
            'lokasi_utama'    => 'nullable|string|max:255',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'zona_waktu'      => 'nullable|string|max:50',
            'logo'            => 'nullable|string|max:255',
            'banner'          => 'nullable|string|max:255',
            'website_url'     => 'nullable|url|max:255',
            'email_kontak'    => 'nullable|email|max:100',
            'aktif'           => 'nullable|boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['nama']) . '-' . $validated['tahun'];
        }

        $festival = Festival::create($validated);

        return $this->jsonResponse(true, 'Festival berhasil dibuat.', $festival, null, 201);
    }

    /**
     * PUT/PATCH /api/v1/festivals/{id}
     * Memperbarui informasi festival.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $festival = Festival::find($id);
        if (! $festival) {
            return $this->jsonResponse(false, 'Festival tidak ditemukan.', null, null, 404);
        }

        $validated = $request->validate([
            'nama'            => 'sometimes|required|string|max:150',
            'slug'            => "sometimes|required|string|max:150|unique:festival,slug,{$id}",
            'edisi'           => 'sometimes|required|string|max:50',
            'tahun'           => 'sometimes|required|integer|digits:4',
            'tema'            => 'nullable|string|max:255',
            'tagline'         => 'nullable|string|max:255',
            'deskripsi'       => 'nullable|string',
            'penyelenggara'   => 'nullable|string|max:150',
            'format'          => 'nullable|in:luring,daring,hibrida',
            'lokasi_utama'    => 'nullable|string|max:255',
            'tanggal_mulai'   => 'sometimes|required|date',
            'tanggal_selesai' => 'sometimes|required|date|after_or_equal:tanggal_mulai',
            'zona_waktu'      => 'nullable|string|max:50',
            'logo'            => 'nullable|string|max:255',
            'banner'          => 'nullable|string|max:255',
            'website_url'     => 'nullable|url|max:255',
            'email_kontak'    => 'nullable|email|max:100',
            'aktif'           => 'nullable|boolean',
        ]);

        $festival->update($validated);

        return $this->jsonResponse(true, 'Festival berhasil diperbarui.', $festival);
    }

    /**
     * DELETE /api/v1/festivals/{id}
     * Menghapus festival.
     */
    public function destroy(int $id): JsonResponse
    {
        $festival = Festival::find($id);
        if (! $festival) {
            return $this->jsonResponse(false, 'Festival tidak ditemukan.', null, null, 404);
        }

        $festival->delete();

        return $this->jsonResponse(true, 'Festival berhasil dihapus.', null);
    }

    // ==========================================
    // PERIODE (CRUD /festivals/{id}/periods)
    // ==========================================

    public function getPeriods(int $id): JsonResponse
    {
        $festival = Festival::find($id);
        if (! $festival) {
            return $this->jsonResponse(false, 'Festival tidak ditemukan.', null, null, 404);
        }

        return $this->jsonResponse(true, 'Daftar periode berhasil diambil.', $festival->periodes);
    }

    public function storePeriod(Request $request, int $id): JsonResponse
    {
        $festival = Festival::find($id);
        if (! $festival) {
            return $this->jsonResponse(false, 'Festival tidak ditemukan.', null, null, 404);
        }

        $validated = $request->validate([
            'tahap'      => 'required|string|max:100',
            'mulai'      => 'required|date',
            'selesai'    => 'required|date|after:mulai',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $periode = $festival->periodes()->create($validated);

        return $this->jsonResponse(true, 'Periode tahapan berhasil ditambahkan.', $periode, null, 201);
    }

    public function updatePeriod(Request $request, int $festivalId, int $periodId): JsonResponse
    {
        $periode = FestivalPeriode::where('festival_id', $festivalId)->find($periodId);
        if (! $periode) {
            return $this->jsonResponse(false, 'Periode tahapan tidak ditemukan.', null, null, 404);
        }

        $validated = $request->validate([
            'tahap'      => 'sometimes|required|string|max:100',
            'mulai'      => 'sometimes|required|date',
            'selesai'    => 'sometimes|required|date|after:mulai',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $periode->update($validated);

        return $this->jsonResponse(true, 'Periode tahapan berhasil diperbarui.', $periode);
    }

    public function destroyPeriod(int $festivalId, int $periodId): JsonResponse
    {
        $periode = FestivalPeriode::where('festival_id', $festivalId)->find($periodId);
        if (! $periode) {
            return $this->jsonResponse(false, 'Periode tahapan tidak ditemukan.', null, null, 404);
        }

        $periode->delete();

        return $this->jsonResponse(true, 'Periode tahapan berhasil dihapus.', null);
    }

    // ==========================================
    // KATEGORI (CRUD /festivals/{id}/categories)
    // ==========================================

    public function getCategories(int $id): JsonResponse
    {
        $festival = Festival::find($id);
        if (! $festival) {
            return $this->jsonResponse(false, 'Festival tidak ditemukan.', null, null, 404);
        }

        return $this->jsonResponse(true, 'Daftar kategori kompetisi berhasil diambil.', $festival->kategoris);
    }

    public function storeCategory(Request $request, int $id): JsonResponse
    {
        $festival = Festival::find($id);
        if (! $festival) {
            return $this->jsonResponse(false, 'Festival tidak ditemukan.', null, null, 404);
        }

        $validated = $request->validate([
            'nama'      => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:255',
            'kuota'     => 'nullable|integer|min:1',
            'urutan'    => 'nullable|integer',
        ]);

        $kategori = $festival->kategoris()->create($validated);

        return $this->jsonResponse(true, 'Kategori kompetisi berhasil ditambahkan.', $kategori, null, 201);
    }

    public function updateCategory(Request $request, int $festivalId, int $categoryId): JsonResponse
    {
        $kategori = FestivalKategori::where('festival_id', $festivalId)->find($categoryId);
        if (! $kategori) {
            return $this->jsonResponse(false, 'Kategori kompetisi tidak ditemukan.', null, null, 404);
        }

        $validated = $request->validate([
            'nama'      => 'sometimes|required|string|max:100',
            'deskripsi' => 'nullable|string|max:255',
            'kuota'     => 'nullable|integer|min:1',
            'urutan'    => 'nullable|integer',
        ]);

        $kategori->update($validated);

        return $this->jsonResponse(true, 'Kategori kompetisi berhasil diperbarui.', $kategori);
    }

    public function destroyCategory(int $festivalId, int $categoryId): JsonResponse
    {
        $kategori = FestivalKategori::where('festival_id', $festivalId)->find($categoryId);
        if (! $kategori) {
            return $this->jsonResponse(false, 'Kategori kompetisi tidak ditemukan.', null, null, 404);
        }

        $kategori->delete();

        return $this->jsonResponse(true, 'Kategori kompetisi berhasil dihapus.', null);
    }
}
