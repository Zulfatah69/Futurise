<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\UpdateAccessRoleRequest;
use App\Models\Festival;
use App\Models\Panitia;
use App\Models\PanitiaFestival;
use App\Models\PeranAkses;
use App\Models\User;
use App\Services\PanitiaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommitteeController extends Controller
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
     * GET /api/v1/committee
     * Daftar panitia dengan filter & paginasi.
     */
    public function index(Request $request): JsonResponse
    {
        if (!$this->isAdminOrPanitia($request)) {
            return $this->jsonResponse(false, 'Akses ditolak. Hanya pengelola yang dapat melihat daftar panitia.', null, null, 403);
        }

        $filters = $request->only(['search', 'jenis', 'aktif']);
        $perPage = min((int) $request->query('per_page', 15), 100);
        $paginator = $this->panitiaService->listPanitia($filters, $perPage);

        return $this->jsonResponse(true, 'Daftar panitia berhasil dimuat.', $paginator->items(), [
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'last_page'    => $paginator->lastPage(),
        ]);
    }

    /**
     * POST /api/v1/committee
     * Tambah data profil panitia baru.
     */
    public function store(Request $request): JsonResponse
    {
        if (!$this->isAdminOrPanitia($request)) {
            return $this->jsonResponse(false, 'Akses ditolak.', null, null, 403);
        }

        $validated = $request->validate([
            'nama_lengkap'            => ['required', 'string', 'max:100'],
            'email'                   => ['required', 'email', 'max:100', 'unique:panitia,email'],
            'no_telepon'              => ['nullable', 'string', 'max:20'],
            'institusi'               => ['nullable', 'string', 'max:150'],
            'jenis'                   => ['required', Rule::in(['internal', 'relawan', 'profesional', 'mahasiswa_magang'])],
            'ukuran_kaos'             => ['nullable', Rule::in(['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'])],
            'kontak_darurat_nama'     => ['nullable', 'string', 'max:100'],
            'kontak_darurat_telepon'  => ['nullable', 'string', 'max:20'],
            'foto'                    => ['nullable', 'string', 'max:255'],
            'password'                => ['nullable', 'string', 'min:8'],
            'aktif'                   => ['nullable', 'boolean'],
        ]);

        $panitia = $this->panitiaService->createPanitia($validated);

        return $this->jsonResponse(true, 'Data panitia berhasil ditambahkan.', $panitia, null, 201);
    }

    /**
     * GET /api/v1/committee/{committee}
     * Detail panitia beserta penugasan festival.
     */
    public function show(Request $request, Panitia $committee): JsonResponse
    {
        if (!$this->isAdminOrPanitia($request)) {
            return $this->jsonResponse(false, 'Akses ditolak.', null, null, 403);
        }

        $committee->load([
            'user',
            'festivalAssignments.festival',
            'festivalAssignments.divisi',
            'festivalAssignments.tasks',
        ]);

        return $this->jsonResponse(true, 'Detail panitia berhasil diambil.', $committee);
    }

    /**
     * PUT /api/v1/committee/{committee}
     * Perbarui data panitia.
     */
    public function update(Request $request, Panitia $committee): JsonResponse
    {
        if (!$this->isAdminOrPanitia($request)) {
            return $this->jsonResponse(false, 'Akses ditolak.', null, null, 403);
        }

        $validated = $request->validate([
            'nama_lengkap'            => ['sometimes', 'required', 'string', 'max:100'],
            'email'                   => ['sometimes', 'required', 'email', 'max:100', Rule::unique('panitia', 'email')->ignore($committee->id)],
            'no_telepon'              => ['nullable', 'string', 'max:20'],
            'institusi'               => ['nullable', 'string', 'max:150'],
            'jenis'                   => ['sometimes', 'required', Rule::in(['internal', 'relawan', 'profesional', 'mahasiswa_magang'])],
            'ukuran_kaos'             => ['nullable', Rule::in(['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'])],
            'kontak_darurat_nama'     => ['nullable', 'string', 'max:100'],
            'kontak_darurat_telepon'  => ['nullable', 'string', 'max:20'],
            'foto'                    => ['nullable', 'string', 'max:255'],
            'password'                => ['nullable', 'string', 'min:8'],
            'aktif'                   => ['nullable', 'boolean'],
        ]);

        $updated = $this->panitiaService->updatePanitia($committee, $validated);

        return $this->jsonResponse(true, 'Data panitia berhasil diperbarui.', $updated);
    }

    /**
     * DELETE /api/v1/committee/{committee}
     * Hapus data panitia.
     */
    public function destroy(Request $request, Panitia $committee): JsonResponse
    {
        if (!$this->isAdminOrPanitia($request)) {
            return $this->jsonResponse(false, 'Akses ditolak.', null, null, 403);
        }

        $this->panitiaService->deletePanitia($committee);

        return $this->jsonResponse(true, 'Data panitia berhasil dihapus.');
    }

    /**
     * POST /api/v1/festivals/{festival}/committee/{committee}/assign
     * Penugasan panitia ke edisi festival tertentu.
     */
    public function assignToFestival(Request $request, Festival $festival, Panitia $committee): JsonResponse
    {
        if (!$this->isAdminOrPanitia($request)) {
            return $this->jsonResponse(false, 'Akses ditolak.', null, null, 403);
        }

        $validated = $request->validate([
            'divisi_id'         => ['nullable', 'exists:divisi_panitia,id'],
            'jabatan'           => ['required', Rule::in(['ketua', 'wakil_ketua', 'sekretaris', 'bendahara', 'koordinator', 'anggota', 'relawan'])],
            'atasan_id'         => ['nullable', 'exists:panitia_festival,id'],
            'status'            => ['nullable', Rule::in(['mendaftar', 'diterima', 'aktif', 'nonaktif', 'ditolak', 'mundur'])],
            'tanggal_bergabung' => ['nullable', 'date'],
            'tanggal_selesai'   => ['nullable', 'date', 'after_or_equal:tanggal_bergabung'],
            'catatan'           => ['nullable', 'string', 'max:255'],
        ]);

        $assignment = $this->panitiaService->assignToFestival($festival, $committee, $validated);

        return $this->jsonResponse(true, 'Panitia berhasil ditugaskan ke festival.', $assignment->load(['festival', 'panitia', 'divisi']), null, 201);
    }

    /**
     * PUT /api/v1/committee-assignments/{assignment}
     * Perbarui penugasan panitia pada festival.
     */
    public function updateAssignment(Request $request, PanitiaFestival $assignment): JsonResponse
    {
        if (!$this->isAdminOrPanitia($request)) {
            return $this->jsonResponse(false, 'Akses ditolak.', null, null, 403);
        }

        $validated = $request->validate([
            'divisi_id'         => ['nullable', 'exists:divisi_panitia,id'],
            'jabatan'           => ['sometimes', Rule::in(['ketua', 'wakil_ketua', 'sekretaris', 'bendahara', 'koordinator', 'anggota', 'relawan'])],
            'atasan_id'         => ['nullable', 'exists:panitia_festival,id'],
            'status'            => ['sometimes', Rule::in(['mendaftar', 'diterima', 'aktif', 'nonaktif', 'ditolak', 'mundur'])],
            'tanggal_selesai'   => ['nullable', 'date'],
            'sertifikat_file'   => ['nullable', 'string', 'max:255'],
            'catatan'           => ['nullable', 'string', 'max:255'],
        ]);

        $updated = $this->panitiaService->updateAssignment($assignment, $validated);

        return $this->jsonResponse(true, 'Penugasan panitia berhasil diperbarui.', $updated);
    }

    /**
     * GET /api/v1/festivals/{festival}/committee
     * Daftar penugasan panitia pada edisi festival tertentu.
     */
    public function festivalCommittee(Request $request, Festival $festival): JsonResponse
    {
        if (! $this->isAdminOrPanitia($request)) {
            return $this->jsonResponse(false, 'Akses ditolak.', null, null, 403);
        }

        $filters = $request->only(['divisi_id', 'status', 'jabatan']);
        $perPage = min((int) $request->query('per_page', 20), 100);
        $paginator = $this->panitiaService->listFestivalAssignments($festival, $filters, $perPage);

        return $this->jsonResponse(true, 'Daftar penugasan panitia festival berhasil dimuat.', $paginator->items(), [
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'last_page'    => $paginator->lastPage(),
        ]);
    }

    /**
     * GET /api/v1/access-roles
     * Daftar peran dan hak akses kepanitiaan (peran_akses).
     */
    public function accessRoles(Request $request): JsonResponse
    {
        if (! $this->isAdminOrPanitia($request)) {
            return $this->jsonResponse(false, 'Akses ditolak.', null, null, 403);
        }

        return $this->jsonResponse(true, 'Daftar peran akses berhasil dimuat.', $this->panitiaService->listAccessRoles());
    }

    /**
     * POST /api/v1/access-roles
     * Tambah definisi peran akses kepanitiaan baru.
     */
    public function storeAccessRole(Request $request): JsonResponse
    {
        if (! $request->user()?->hasAnyRole(User::ADMIN_ROLES)) {
            return $this->jsonResponse(false, 'Akses ditolak. Hanya administrator yang dapat menambah peran akses.', null, null, 403);
        }

        $validated = $request->validate([
            'kode'      => ['required', 'string', 'max:50', 'unique:peran_akses,kode'],
            'nama'      => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:255'],
            'izin'      => ['nullable', 'array'],
        ]);

        $role = $this->panitiaService->createAccessRole($validated);

        return $this->jsonResponse(true, 'Peran akses berhasil ditambahkan.', $role, null, 201);
    }

    /**
     * POST /api/v1/committee/{committee}/roles
     * Memberikan peran akses kepada anggota panitia.
     */
    public function assignRole(Request $request, Panitia $committee): JsonResponse
    {
        if (! $request->user()?->hasAnyRole(User::ADMIN_ROLES)) {
            return $this->jsonResponse(false, 'Akses ditolak. Hanya administrator yang dapat memberikan peran akses.', null, null, 403);
        }

        $validated = $request->validate([
            'peran_id'    => ['required', 'integer', 'exists:peran_akses,id'],
            'festival_id' => ['nullable', 'integer', 'exists:festival,id'],
        ]);

        $role = PeranAkses::findOrFail($validated['peran_id']);
        $grantedById = Panitia::where('email', $request->user()->email)->value('id');

        $updated = $this->panitiaService->assignAccessRole(
            $committee,
            $role,
            $validated['festival_id'] ?? null,
            $grantedById
        );

        return $this->jsonResponse(true, 'Peran akses berhasil diberikan kepada panitia.', $updated);
    }

    /**
     * PUT/PATCH /api/v1/access-roles/{role}
     * Perbarui definisi peran akses kepanitiaan.
     */
    public function updateAccessRole(UpdateAccessRoleRequest $request, PeranAkses $role): JsonResponse
    {
        if (! $request->user()?->hasAnyRole(User::ADMIN_ROLES)) {
            return $this->jsonResponse(false, 'Akses ditolak. Hanya administrator yang dapat memperbarui peran akses.', null, null, 403);
        }

        $updated = $this->panitiaService->updateAccessRole($role, $request->validated());

        return $this->jsonResponse(true, 'Peran akses berhasil diperbarui.', $updated);
    }

    /**
     * DELETE /api/v1/committee/{committee}/roles/{role}
     * Cabut peran akses dari anggota panitia.
     */
    public function revokeRole(Request $request, Panitia $committee, PeranAkses $role): JsonResponse
    {
        if (! $request->user()?->hasAnyRole(User::ADMIN_ROLES)) {
            return $this->jsonResponse(false, 'Akses ditolak. Hanya administrator yang dapat mencabut peran akses.', null, null, 403);
        }

        $updated = $this->panitiaService->revokeAccessRole($committee, $role);

        return $this->jsonResponse(true, 'Peran akses berhasil dicabut dari panitia.', $updated);
    }
}
