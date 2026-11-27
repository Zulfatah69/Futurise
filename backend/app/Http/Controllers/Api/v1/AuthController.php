<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\User;
use App\Rules\ValidParticipantIdentity;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    /**
     * Tahap 1: Registrasi Akun Baru via REST API.
     * Cukup nama, email, phone, dan password.
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone'    => ['nullable', 'string', 'max:20'],
        ]);

        return DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'           => $validated['name'],
                'email'          => $validated['email'],
                'password'       => Hash::make($validated['password']),
                'account_type'   => 'public',
                'account_status' => 'active',
                'pic_category'   => null,
            ]);

            if (! empty($validated['phone'])) {
                $user->person()->update([
                    'phone' => $validated['phone'],
                ]);
            }

            // Assign role pengunjung
            if (Role::where('name', 'pengunjung')->where('guard_name', 'web')->exists()) {
                $user->assignRole('pengunjung');
            }

            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran akun berhasil. Silakan lanjutkan pendaftaran peserta lomba jika ingin berkompetisi.',
                'data' => [
                    'user' => [
                        'id'             => $user->id,
                        'name'           => $user->name,
                        'email'          => $user->email,
                        'account_type'   => $user->account_type,
                        'account_status' => $user->account_status,
                        'roles'          => $user->getRoleNames(),
                    ],
                    'peserta' => null,
                    'token'   => $token,
                ],
                'meta' => null,
            ], 201);
        });
    }

    /**
     * Tahap 2: Pendaftaran Peserta Lomba via REST API (auth:sanctum).
     */
    public function registerParticipant(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->peserta) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda sudah terdaftar sebagai peserta lomba.',
                'data'    => $user->peserta,
                'meta'    => null,
            ], 422);
        }

        $validated = $request->validate([
            'institusi'       => ['required', 'string', 'max:150'],
            'jenis_peserta'   => ['required', 'string', 'in:mahasiswa,siswa,siswa_smk,umum,pic'],
            'nomor_identitas' => [
                'required',
                'string',
                'max:30',
                new ValidParticipantIdentity(
                    $request->input('jenis_peserta'),
                    $request->input('institusi')
                ),
            ],
            'no_telepon'      => ['nullable', 'string', 'max:20'],
        ]);

        $peserta = DB::transaction(function () use ($user, $validated) {
            $phone = $validated['no_telepon'] ?? $user->person?->phone;

            $peserta = Peserta::create([
                'user_id'         => $user->id,
                'nama_lengkap'    => $user->name,
                'email'           => $user->email,
                'no_telepon'      => $phone,
                'institusi'       => $validated['institusi'],
                'jenis_peserta'   => $validated['jenis_peserta'],
                'nomor_identitas' => $validated['nomor_identitas'],
            ]);

            // Selaraskan nomor telepon ke data profil akun (people)
            if (! empty($phone)) {
                $user->person()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'full_name' => $user->name,
                        'email'     => $user->email,
                        'phone'     => $phone,
                    ]
                );
            }

            if ($user->hasRole('pengunjung') && Role::where('name', 'tim-mahasiswa')->where('guard_name', 'web')->exists()) {
                $user->removeRole('pengunjung');
                $user->assignRole('tim-mahasiswa');
            }

            return $peserta;
        });

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran peserta lomba berhasil disimpan.',
            'data'    => [
                'user'    => $user->load('peserta'),
                'peserta' => $peserta,
            ],
            'meta' => null,
        ], 201);
    }

    /**
     * Login via REST API.
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::with('peserta')->where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau kata sandi tidak valid.',
                'data'    => null,
                'meta'    => null,
            ], 401);
        }

        if ($user->account_status === 'pending_approval') {
            return response()->json([
                'success' => false,
                'message' => 'Akun PIC Anda masih menunggu persetujuan Admin panitia.',
                'data'    => [
                    'account_status' => 'pending_approval',
                ],
                'meta' => null,
            ], 403);
        }

        if ($user->account_status === 'inactive') {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda dinonaktifkan oleh administrator.',
                'data'    => null,
                'meta'    => null,
            ], 403);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data'    => [
                'user' => [
                    'id'             => $user->id,
                    'name'           => $user->name,
                    'email'          => $user->email,
                    'account_type'   => $user->account_type,
                    'account_status' => $user->account_status,
                    'pic_category'   => $user->pic_category,
                    'roles'          => $user->getRoleNames(),
                ],
                'peserta' => $user->peserta,
                'token'   => $token,
            ],
            'meta' => null,
        ]);
    }

    /**
     * Get Current Authenticated User Profile.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('peserta');

        return response()->json([
            'success' => true,
            'message' => 'Data profil berhasil diambil.',
            'data'    => [
                'user' => [
                    'id'             => $user->id,
                    'name'           => $user->name,
                    'email'          => $user->email,
                    'account_type'   => $user->account_type,
                    'account_status' => $user->account_status,
                    'pic_category'   => $user->pic_category,
                    'roles'          => $user->getRoleNames(),
                ],
                'peserta' => $user->peserta,
            ],
            'meta' => null,
        ]);
    }

    /**
     * Logout & Revoke Token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
            'data'    => null,
            'meta'    => null,
        ]);
    }

    /**
     * Request token reset kata sandi melalui email.
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(['email' => $validated['email']]);

        if ($status === Password::RESET_THROTTLED) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak permintaan reset kata sandi. Silakan tunggu beberapa saat.',
                'data'    => null,
                'meta'    => null,
            ], 429);
        }

        return response()->json([
            'success' => true,
            'message' => 'Jika email terdaftar, tautan reset kata sandi telah dikirimkan.',
            'data'    => null,
            'meta'    => null,
        ]);
    }

    /**
     * Reset kata sandi menggunakan token yang valid.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'token'    => ['required', 'string'],
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = Password::reset(
            $validated,
            function (User $user, string $password): void {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return response()->json([
                'success' => false,
                'message' => 'Token reset kata sandi tidak valid atau telah kedaluwarsa.',
                'data'    => null,
                'meta'    => null,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diperbarui. Silakan login kembali.',
            'data'    => null,
            'meta'    => null,
        ]);
    }
}
