<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    /**
     * Berapa menit OTP berlaku.
     */
    private const EXPIRES_MINUTES = 10;

    /**
     * Minimum jarak (menit) antara 2 request OTP dari user yang sama.
     * Mencegah spam kirim ulang.
     */
    private const RESEND_COOLDOWN_MINUTES = 2;

    /**
     * Generate & kirim OTP ke email user.
     * Melempar exception jika masih dalam cooldown.
     *
     * @throws \RuntimeException
     */
    public function send(User $user, string $type = 'email_verification'): OtpCode
    {
        // Cek rate-limit: apakah ada OTP yang baru dikirim dalam cooldown period?
        $recent = OtpCode::where('user_id', $user->id)
            ->where('type', $type)
            ->whereNull('used_at')
            ->where('last_sent_at', '>=', now()->subMinutes(self::RESEND_COOLDOWN_MINUTES))
            ->latest()
            ->first();

        if ($recent) {
            $waitSeconds = max(1, (int) ceil(now()->diffInSeconds($recent->last_sent_at->addMinutes(self::RESEND_COOLDOWN_MINUTES), false)));
            throw new \RuntimeException("Tunggu {$waitSeconds} detik sebelum meminta OTP baru.");
        }

        // Invalidate semua OTP lama yang belum dipakai untuk user+type ini
        OtpCode::where('user_id', $user->id)
            ->where('type', $type)
            ->whereNull('used_at')
            ->delete();

        // Generate 6-digit OTP
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $otp = OtpCode::create([
            'user_id'      => $user->id,
            'code'         => $code,
            'type'         => $type,
            'expires_at'   => now()->addMinutes(self::EXPIRES_MINUTES),
            'last_sent_at' => now(),
        ]);

        Mail::to($user->email)->send(new OtpMail($code, $user->name, self::EXPIRES_MINUTES));

        return $otp;
    }

    /**
     * Verifikasi OTP yang diinput user.
     * Return true jika valid, false jika salah/expired/sudah dipakai.
     */
    public function verify(User $user, string $code, string $type = 'email_verification'): bool
    {
        $otp = OtpCode::where('user_id', $user->id)
            ->where('type', $type)
            ->where('code', $code)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $otp) {
            return false;
        }

        // Tandai OTP sudah dipakai
        $otp->update(['used_at' => now()]);

        return true;
    }

    /**
     * Cek apakah user masih dalam cooldown (untuk info di UI).
     */
    public function getRemainingCooldown(User $user, string $type = 'email_verification'): int
    {
        $recent = OtpCode::where('user_id', $user->id)
            ->where('type', $type)
            ->whereNull('used_at')
            ->where('last_sent_at', '>=', now()->subMinutes(self::RESEND_COOLDOWN_MINUTES))
            ->latest()
            ->first();

        if (! $recent) {
            return 0;
        }

        return max(0, (int) now()->diffInSeconds(
            $recent->last_sent_at->addMinutes(self::RESEND_COOLDOWN_MINUTES),
            false
        ));
    }
}
