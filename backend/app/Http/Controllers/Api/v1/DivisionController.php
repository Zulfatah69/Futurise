<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\DivisiPanitia;
use App\Models\Festival;
use App\Models\User;
use App\Services\PanitiaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DivisionController extends Controller
{
    public function __construct(
        protected PanitiaService $panitiaService
    ) {}

    private function jsonResponse(bool $success, string $message, mixed $data = null, mixed $meta = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $data,
            'meta'    => $meta,
        ], $code);
    }

    private function isAdminOrPanitia(Request $request): bool
    {
        $user = $request->user();
        return $user && ($user->hasAnyRole(User::ADMIN_ROLES) || $user->hasRole('panitia'));
    }

    /**
     * GET /api/v1/festivals/{festival}/divisions
     * Daftar divisi panitia pada festival.
     */
    public function index(Festival $festival): JsonResponse
    {
        $divisions = $this->panitiaService->listDivisions($festival);
        return $this->jsonResponse(true, 'Daftar divisi festival berhasil dimuat.', $divisions);
    }

    /**
     * POST /api/v1/festivals/{festival}/divisions
     * Tambah divisi baru pada festival.
     */
    public function store(Request $request, Festival $festival): JsonResponse
    {
        if (!$this->isAdminOrPanitia($request)) {
            return $this->jsonResponse(false, 'Akses ditolak.', null, null, 403);
        }

        $validated = $request->validate([
            'nama'          => [
                'required',
                'string',
                'max:100',
                Rule::unique('divisi_panitia', 'nama')->where('festival_id', $festival->id),
            ],
            'deskripsi'     => ['nullable', 'string', 'max:255'],
            'kuota_anggota' => ['nullable', 'integer', 'min:1'],
            'urutan'        => ['nullable', 'integer', 'min:0'],
        ]);

        $division = $this->panitiaService->createDivision($festival, $validated);

        return $this->jsonResponse(true, 'Divisi panitia berhasil ditambahkan.', $division, null, 201);
    }

    /**
     * GET /api/v1/festivals/{festival}/divisions/{division}
     * Detail divisi beserta anggota panitia yang ditugaskan.
     */
    public function show(Festival $festival, DivisiPanitia $division): JsonResponse
    {
        if ($division->festival_id !== $festival->id) {
            return $this->jsonResponse(false, 'Divisi tidak ditemukan pada festival ini.', null, null, 404);
        }

        $division->load(['assignments.panitia']);

        return $this->jsonResponse(true, 'Detail divisi berhasil diambil.', $division);
    }

    /**
     * PUT /api/v1/festivals/{festival}/divisions/{division}
     * Perbarui divisi panitia.
     */
    public function update(Request $request, Festival $festival, DivisiPanitia $division): JsonResponse
    {
        if (!$this->isAdminOrPanitia($request)) {
            return $this->jsonResponse(false, 'Akses ditolak.', null, null, 403);
        }

        if ($division->festival_id !== $festival->id) {
            return $this->jsonResponse(false, 'Divisi tidak ditemukan pada festival ini.', null, null, 404);
        }

        $validated = $request->validate([
            'nama'          => [
                'sometimes',
                'required',
                'string',
                'max:100',
                Rule::unique('divisi_panitia', 'nama')
                    ->where('festival_id', $festival->id)
                    ->ignore($division->id),
            ],
            'deskripsi'     => ['nullable', 'string', 'max:255'],
            'kuota_anggota' => ['nullable', 'integer', 'min:1'],
            'urutan'        => ['nullable', 'integer', 'min:0'],
        ]);

        $updated = $this->panitiaService->updateDivision($division, $validated);

        return $this->jsonResponse(true, 'Divisi panitia berhasil diperbarui.', $updated);
    }

    /**
     * DELETE /api/v1/festivals/{festival}/divisions/{division}
     * Hapus divisi panitia.
     */
    public function destroy(Request $request, Festival $festival, DivisiPanitia $division): JsonResponse
    {
        if (!$this->isAdminOrPanitia($request)) {
            return $this->jsonResponse(false, 'Akses ditolak.', null, null, 403);
        }

        if ($division->festival_id !== $festival->id) {
            return $this->jsonResponse(false, 'Divisi tidak ditemukan pada festival ini.', null, null, 404);
        }

        $this->panitiaService->deleteDivision($division);

        return $this->jsonResponse(true, 'Divisi panitia berhasil dihapus.');
    }
}
