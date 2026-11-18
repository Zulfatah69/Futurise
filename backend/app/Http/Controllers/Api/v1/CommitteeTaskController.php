<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\PanitiaFestival;
use App\Models\PanitiaTugas;
use App\Models\User;
use App\Services\PanitiaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommitteeTaskController extends Controller
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

    private function authorizeTaskAction(Request $request, PanitiaTugas $task): bool
    {
        $user = $request->user();
        if (!$user) {
            return false;
        }

        if ($user->hasAnyRole(User::ADMIN_ROLES)) {
            return true;
        }

        $task->loadMissing('panitiaFestival.panitia');
        $panitiaEmail = $task->panitiaFestival?->panitia?->email;

        return ($panitiaEmail && $user->email === $panitiaEmail) || $user->hasRole('panitia');
    }

    /**
     * GET /api/v1/committee-tasks
     * Daftar tugas/shift panitia dengan filter & paginasi.
     */
    public function index(Request $request): JsonResponse
    {
        if (!$this->isAdminOrPanitia($request)) {
            return $this->jsonResponse(false, 'Akses ditolak.', null, null, 403);
        }

        $filters = $request->only(['panitia_festival_id', 'festival_id', 'tanggal', 'status']);
        $perPage = min((int) $request->query('per_page', 20), 100);
        $paginator = $this->panitiaService->listTasks($filters, $perPage);

        return $this->jsonResponse(true, 'Daftar tugas panitia berhasil dimuat.', $paginator->items(), [
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'last_page'    => $paginator->lastPage(),
        ]);
    }

    /**
     * POST /api/v1/committee-tasks
     * Buat penugasan jadwal/shift baru untuk panitia.
     */
    public function store(Request $request): JsonResponse
    {
        if (!$this->isAdminOrPanitia($request)) {
            return $this->jsonResponse(false, 'Akses ditolak.', null, null, 403);
        }

        $validated = $request->validate([
            'panitia_festival_id' => ['required', 'exists:panitia_festival,id'],
            'judul'               => ['required', 'string', 'max:150'],
            'lokasi_id'           => ['nullable', 'exists:lokasi,id'],
            'jadwal_id'           => ['nullable', 'exists:jadwal,id'],
            'tanggal'             => ['required', 'date'],
            'jam_mulai'           => ['required', 'date_format:H:i'],
            'jam_selesai'         => ['required', 'date_format:H:i'],
            'status'              => ['nullable', Rule::in(['terjadwal', 'bertugas', 'selesai', 'absen', 'diganti'])],
            'catatan'             => ['nullable', 'string', 'max:255'],
        ]);

        $assignment = PanitiaFestival::findOrFail($validated['panitia_festival_id']);
        $task = $this->panitiaService->createTask($assignment, $validated);

        return $this->jsonResponse(true, 'Tugas panitia berhasil dijadwalkan.', $task->load(['panitiaFestival.panitia']), null, 201);
    }

    /**
     * GET /api/v1/committee-tasks/{task}
     * Detail penugasan shift panitia.
     */
    public function show(Request $request, PanitiaTugas $task): JsonResponse
    {
        if (!$this->isAdminOrPanitia($request)) {
            return $this->jsonResponse(false, 'Akses ditolak.', null, null, 403);
        }

        $task->load(['panitiaFestival.panitia', 'panitiaFestival.divisi', 'panitiaFestival.festival']);

        return $this->jsonResponse(true, 'Detail tugas berhasil diambil.', $task);
    }

    /**
     * PUT /api/v1/committee-tasks/{task}
     * Perbarui jadwal atau catatan tugas.
     */
    public function update(Request $request, PanitiaTugas $task): JsonResponse
    {
        if (!$this->isAdminOrPanitia($request)) {
            return $this->jsonResponse(false, 'Akses ditolak.', null, null, 403);
        }

        $validated = $request->validate([
            'judul'       => ['sometimes', 'required', 'string', 'max:150'],
            'lokasi_id'   => ['nullable', 'exists:lokasi,id'],
            'jadwal_id'   => ['nullable', 'exists:jadwal,id'],
            'tanggal'     => ['sometimes', 'required', 'date'],
            'jam_mulai'   => ['sometimes', 'required', 'date_format:H:i'],
            'jam_selesai' => ['sometimes', 'required', 'date_format:H:i'],
            'status'      => ['nullable', Rule::in(['terjadwal', 'bertugas', 'selesai', 'absen', 'diganti'])],
            'catatan'     => ['nullable', 'string', 'max:255'],
        ]);

        $task->update($validated);

        return $this->jsonResponse(true, 'Tugas panitia berhasil diperbarui.', $task->fresh(['panitiaFestival.panitia']));
    }

    /**
     * DELETE /api/v1/committee-tasks/{task}
     * Hapus jadwal tugas.
     */
    public function destroy(Request $request, PanitiaTugas $task): JsonResponse
    {
        if (!$this->isAdminOrPanitia($request)) {
            return $this->jsonResponse(false, 'Akses ditolak.', null, null, 403);
        }

        $task->delete();

        return $this->jsonResponse(true, 'Tugas panitia berhasil dihapus.');
    }

    /**
     * POST /api/v1/committee-tasks/{task}/check-in
     * Presensi kehadiran shift tugas panitia.
     */
    public function checkIn(Request $request, PanitiaTugas $task): JsonResponse
    {
        if (! $this->authorizeTaskAction($request, $task)) {
            return $this->jsonResponse(false, 'Akses ditolak. Anda tidak berwenang melakukan presensi untuk tugas ini.', null, null, 403);
        }

        $validated = $request->validate([
            'catatan' => ['nullable', 'string', 'max:255'],
        ]);

        $updated = $this->panitiaService->checkInTask($task, $validated['catatan'] ?? null);

        return $this->jsonResponse(true, 'Presensi kehadiran shift tugas berhasil dicatat.', $updated);
    }

    /**
     * POST /api/v1/committee-tasks/{task}/complete
     * Selesaikan shift tugas panitia.
     */
    public function complete(Request $request, PanitiaTugas $task): JsonResponse
    {
        if (! $this->authorizeTaskAction($request, $task)) {
            return $this->jsonResponse(false, 'Akses ditolak. Anda tidak berwenang memperbarui status tugas ini.', null, null, 403);
        }

        $validated = $request->validate([
            'catatan' => ['nullable', 'string', 'max:255'],
        ]);

        $updated = $this->panitiaService->completeTask($task, $validated['catatan'] ?? null);

        return $this->jsonResponse(true, 'Shift tugas panitia berhasil diselesaikan.', $updated);
    }
}
