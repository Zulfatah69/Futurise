<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class CreateNewUser implements \Laravel\Fortify\Contracts\CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Tahap 1: Registrasi Akun Pengguna Baru.
     * Hanya membutuhkan data akun dasar (nama, email, phone, password).
     * Data kepesertaan lomba diisi di Tahap 2 setelah login.
     */
    public function create(array $input): User
    {
        $validated = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],

            'phone' => ['nullable', 'string', 'max:20'],

            'password' => $this->passwordRules(),
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ])->validate();

        $user = User::create([
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'password'       => Hash::make($validated['password']),
            'account_type'   => 'public',
            'account_status' => 'active',
            'pic_category'   => null,
        ]);

        // Simpan nomor HP jika diisi
        if (! empty($validated['phone'])) {
            $user->person()->update([
                'phone' => $validated['phone'],
            ]);
        }

        // Berikan role awal sebagai 'pengunjung'
        if (Role::where('name', 'pengunjung')->where('guard_name', 'web')->exists()) {
            $user->assignRole('pengunjung');
        }

        // Kirim OTP verifikasi email via Gmail SMTP
        app(OtpService::class)->send($user);

        return $user;
    }
}
