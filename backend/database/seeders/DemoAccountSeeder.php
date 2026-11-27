<?php

namespace Database\Seeders;

use App\Models\Peserta;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoAccountSeeder extends Seeder
{
    /**
     * Buat akun uji coba untuk setiap role (hanya untuk environment non-production).
     */
    public function run(): void
    {
        if (app()->isProduction()) {
            return;
        }

        $this->call(RoleSeeder::class);

        $passwordHash = Hash::make('password123');
        $now = now();

        $accounts = [
            // 9 Role Admin/Panitia lainnya (super-admin sudah dibuat di RoleSeeder: admin@futurisee.com / admin123)
            ['role' => 'ketua-pelaksana',    'name' => 'Demo Ketua Pelaksana',    'email' => 'ketua@futurise.test',      'account_type' => 'internal', 'pic_category' => null],
            ['role' => 'sekretariat',        'name' => 'Demo Sekretariat',        'email' => 'sekretariat@futurise.test','account_type' => 'internal', 'pic_category' => null],
            ['role' => 'keuangan',           'name' => 'Demo Keuangan',           'email' => 'keuangan@futurise.test',   'account_type' => 'internal', 'pic_category' => null],
            ['role' => 'program-kurasi',     'name' => 'Demo Program & Kurasi',   'email' => 'kurasi@futurise.test',     'account_type' => 'internal', 'pic_category' => null],
            ['role' => 'kampus-sekolah',     'name' => 'Demo Relasi Kampus',      'email' => 'kampus@futurise.test',     'account_type' => 'internal', 'pic_category' => null],
            ['role' => 'industri-sponsor',   'name' => 'Demo Industri & Sponsor', 'email' => 'industri@futurise.test',   'account_type' => 'internal', 'pic_category' => null],
            ['role' => 'venue-teknis',       'name' => 'Demo Venue & Teknis',     'email' => 'venue@futurise.test',      'account_type' => 'internal', 'pic_category' => null],
            ['role' => 'publikasi',          'name' => 'Demo Publikasi',          'email' => 'publikasi@futurise.test',  'account_type' => 'internal', 'pic_category' => null],
            ['role' => 'registrasi-layanan', 'name' => 'Demo Registrasi Layanan', 'email' => 'registrasi@futurise.test', 'account_type' => 'internal', 'pic_category' => null],

            // 7 Role Non-Admin / Eksternal
            ['role' => 'pengunjung',    'name' => 'Demo Pengunjung',    'email' => 'pengunjung@futurise.test',   'account_type' => 'public',   'pic_category' => null],
            ['role' => 'tim-mahasiswa', 'name' => 'Demo Tim Mahasiswa', 'email' => 'mahasiswa@futurise.test',    'account_type' => 'public',   'pic_category' => null],
            ['role' => 'pic-kampus',    'name' => 'Demo PIC Kampus',    'email' => 'pic.kampus@futurise.test',   'account_type' => 'pic',      'pic_category' => 'kampus-sekolah'],
            ['role' => 'pic-sekolah',   'name' => 'Demo PIC Sekolah',   'email' => 'pic.sekolah@futurise.test',  'account_type' => 'pic',      'pic_category' => 'kampus-sekolah'],
            ['role' => 'pic-industri',  'name' => 'Demo PIC Industri',  'email' => 'pic.industri@futurise.test', 'account_type' => 'pic',      'pic_category' => 'industri-sponsor'],
            ['role' => 'pic-sponsor',   'name' => 'Demo PIC Sponsor',   'email' => 'pic.sponsor@futurise.test',  'account_type' => 'pic',      'pic_category' => 'industri-sponsor'],
            ['role' => 'juri',          'name' => 'Demo Juri',          'email' => 'juri@futurise.test',         'account_type' => 'internal', 'pic_category' => null],
        ];

        foreach ($accounts as $item) {
            $user = User::updateOrCreate(
                ['email' => $item['email']],
                [
                    'name'              => $item['name'],
                    'password'          => $passwordHash,
                    'account_type'      => $item['account_type'],
                    'account_status'    => 'active',
                    'pic_category'      => $item['pic_category'],
                    'email_verified_at' => $now,
                ]
            );

            $user->syncRoles([$item['role']]);

            if ($item['role'] === 'tim-mahasiswa') {
                Peserta::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nama_lengkap'    => $user->name,
                        'email'           => $user->email,
                        'no_telepon'      => '081200000001',
                        'institusi'       => 'Institut Teknologi Nasional Bandung',
                        'jenis_peserta'   => 'mahasiswa',
                        'nomor_identitas' => '152026999',
                    ]
                );
            }
        }
    }
}
