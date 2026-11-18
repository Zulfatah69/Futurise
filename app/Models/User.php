<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'name',
    'email',
    'password',
    'account_type',
    'account_status',
    'pic_category',
    'email_verified_at',
])]
#[Hidden(['password', 'remember_token'])]
// MustVerifyEmail diimplementasikan agar markEmailAsVerified() tersedia,
// namun flow verifikasi ditangani custom via OTP (bukan link Fortify bawaan).
class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * Peran yang boleh masuk ke panel panitia.
     */
    public const ADMIN_ROLES = [
        'super-admin',
        'ketua-pelaksana',
        'sekretariat',
        'keuangan',
        'program-kurasi',
        'kampus-sekolah',
        'industri-sponsor',
        'venue-teknis',
        'publikasi',
        'registrasi-layanan',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        // Satu identitas orang per akun (BR-10), dibuat otomatis
        static::created(function (User $user) {
            $user->person()->firstOrCreate([], [
                'full_name' => $user->name,
                'email' => $user->email,
            ]);
        });
    }

    /**
     * Override notifikasi verifikasi email default Laravel.
     * FUTURiSE menggunakan kode OTP 6-digit (OtpService) daripada email link.
     */
    public function sendEmailVerificationNotification(): void
    {
        // Sengaja dikosongkan agar Laravel tidak mencoba mengirim link verification.verify bawaan.
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Admin panel hanya untuk role yang terdaftar & akun aktif.
        // Email verification sudah dilakukan via OTP saat registrasi.
        return $this->account_status === 'active'
            && $this->hasAnyRole(self::ADMIN_ROLES);
    }

    public function person(): HasOne
    {
        return $this->hasOne(Person::class);
    }

    public function peserta(): HasOne
    {
        return $this->hasOne(Peserta::class);
    }
}