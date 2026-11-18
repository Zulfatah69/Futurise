<?php

namespace App\Http\Responses;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        // Akun dinonaktifkan oleh Super Admin.
        if ($user->account_status === 'inactive') {
            auth()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Akun kamu sedang dinonaktifkan. Silakan hubungi admin.',
                ], 403);
            }

            return redirect()->route('login')->withErrors([
                'email' => 'Akun kamu sedang dinonaktifkan. Silakan hubungi admin.',
            ]);
        }

        // Email belum diverifikasi.
        if (!$user->hasVerifiedEmail()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Silakan verifikasi email kamu terlebih dahulu.',
                ], 403);
            }

            return redirect()->route('otp.show');
        }

        // PIC sudah verifikasi, tetapi belum disetujui admin.
        if (
            $user->account_type === 'pic' &&
            $user->account_status !== 'active'
        ) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Akun PIC kamu masih menunggu persetujuan admin.',
                ], 403);
            }

            return redirect()->route('account.pending');
        }

        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        $target = $user->hasAnyRole(User::ADMIN_ROLES)
            ? route('filament.admin.pages.dashboard')
            : route('dashboard');

        return redirect()->intended($target);
    }
}
