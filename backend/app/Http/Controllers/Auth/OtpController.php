<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OtpController extends Controller
{
    public function __construct(private readonly OtpService $otpService) {}

    /**
     * Tampilkan form input OTP.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // Jika sudah verified, langsung redirect
        if ($user && $user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $cooldown = $user ? $this->otpService->getRemainingCooldown($user) : 0;

        return view('auth.otp-verify', compact('cooldown'));
    }

    /**
     * Verifikasi kode OTP yang diinput.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits'   => 'Kode OTP harus 6 digit angka.',
        ]);

        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $valid = $this->otpService->verify($user, $request->input('otp'));

        if (! $valid) {
            return back()->withErrors(['otp' => 'Kode OTP salah, sudah dipakai, atau telah kedaluwarsa.']);
        }

        // Tandai email sebagai verified
        $user->markEmailAsVerified();

        // Aktifkan akun (dari pending_approval ke active jika bukan PIC)
        if ($user->account_type !== 'pic') {
            $user->update(['account_status' => 'active']);
        }

        return redirect()->intended(route('dashboard', absolute: false))
            ->with('status', 'Akun berhasil diverifikasi! Selamat datang di FUTURiSE.');
    }

    /**
     * Kirim ulang OTP (dengan rate-limit).
     */
    public function resend(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        try {
            $this->otpService->send($user);
            return back()->with('status', 'Kode OTP baru telah dikirim ke email Anda.');
        } catch (\RuntimeException $e) {
            return back()->withErrors(['otp' => $e->getMessage()]);
        }
    }
}
