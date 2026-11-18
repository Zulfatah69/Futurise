<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request): JsonResponse|RedirectResponse
    {
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil. Kode OTP telah dikirim ke email Anda.',
            ], 201);
        }

        // Redirect ke halaman input OTP setelah registrasi berhasil
        return redirect()->route('otp.show')->with(
            'status',
            'Registrasi berhasil! Masukkan kode OTP yang telah dikirim ke email Anda.'
        );
    }
}
