<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\User;
use App\Rules\ValidParticipantIdentity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ParticipantController extends Controller
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

    private function isAdminOrPanitia(User $user): bool
    {
        return $user->hasAnyRole(User::ADMIN_ROLES) || $user->hasRole('panitia');
    }

    /**
     * GET /api/v1/participants/{id}
     * Menampilkan profil peserta/PIC untuk pemilik akun atau pengelola.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $peserta = Peserta::with([
            'user:id,name,email,account_type,account_status',
            'karyasAsLeader:id,festival_id,peserta_id,judul,kategori,status',
        ])->find($id);

        if (! $peserta) {
            return $this->jsonResponse(false, 'Profil peserta tidak ditemukan.', null, null, 404);
        }

        $user = $request->user();
        $isOwner = $peserta->user_id === $user->id;

        if (! $isOwner && ! $this->isAdminOrPanitia($user)) {
            return $this->jsonResponse(false, 'Profil peserta tidak ditemukan.', null, null, 404);
        }

        return $this->jsonResponse(true, 'Profil peserta berhasil diambil.', $peserta);
    }

    /**
     * PUT/PATCH /api/v1/participants/{id}
     * Memperbarui data profil peserta dan menyelaraskannya dengan profil akun.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $peserta = Peserta::with('user')->find($id);
        if (! $peserta) {
            return $this->jsonResponse(false, 'Profil peserta tidak ditemukan.', null, null, 404);
        }

        $user = $request->user();
        $isOwner = $peserta->user_id === $user->id;

        if (! $isOwner && ! $user->hasAnyRole(User::ADMIN_ROLES)) {
            return $this->jsonResponse(false, 'Anda tidak berwenang mengubah profil peserta ini.', null, null, 403);
        }

        $effectiveJenis = $request->input('jenis_peserta', $peserta->jenis_peserta);
        $effectiveInstitusi = $request->input('institusi', $peserta->institusi);

        $validated = $request->validate([
            'nama_lengkap'    => ['sometimes', 'required', 'string', 'max:100'],
            'no_telepon'      => ['nullable', 'string', 'max:20'],
            'institusi'       => ['sometimes', 'required', 'string', 'max:150'],
            'jenis_peserta'   => ['sometimes', 'required', 'string', Rule::in(['mahasiswa', 'siswa', 'siswa_smk', 'umum', 'pic'])],
            'nomor_identitas' => [
                'nullable',
                'string',
                'max:30',
                new ValidParticipantIdentity($effectiveJenis, $effectiveInstitusi, $peserta->id),
            ],
        ]);

        if (! array_key_exists('nomor_identitas', $validated) && $peserta->nomor_identitas !== null) {
            (new ValidParticipantIdentity($effectiveJenis, $effectiveInstitusi, $peserta->id))
                ->validate('nomor_identitas', $peserta->nomor_identitas, static function (string $message): void {
                    throw ValidationException::withMessages(['nomor_identitas' => [$message]]);
                });
        }

        $updated = DB::transaction(function () use ($peserta, $validated) {
            $peserta->update($validated);

            if ($peserta->user) {
                $userUpdates = [];
                if (isset($validated['nama_lengkap'])) {
                    $userUpdates['name'] = $validated['nama_lengkap'];
                }
                if (! empty($userUpdates)) {
                    $peserta->user->update($userUpdates);
                }

                $personData = [
                    'full_name' => $peserta->nama_lengkap,
                    'email'     => $peserta->email,
                ];
                if (array_key_exists('no_telepon', $validated)) {
                    $personData['phone'] = $validated['no_telepon'];
                }

                $peserta->user->person()->updateOrCreate(
                    ['user_id' => $peserta->user_id],
                    $personData
                );
            }

            return $peserta->fresh(['user:id,name,email,account_type,account_status']);
        });

        return $this->jsonResponse(true, 'Profil peserta berhasil diperbarui.', $updated);
    }
}
