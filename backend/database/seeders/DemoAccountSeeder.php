<?php

namespace Database\Seeders;

use App\Models\DivisiPanitia;
use App\Models\Festival;
use App\Models\Panitia;
use App\Models\PanitiaFestival;
use App\Models\PeranAkses;
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

        $this->call([
            RoleSeeder::class,
            FestivalSeeder::class,
            CommitteeSeeder::class,
        ]);

        $passwordHash = Hash::make('password123');
        $now = now();
        $festival = Festival::aktif()->first() ?? Festival::first();
        $divisionsByName = $festival
            ? DivisiPanitia::where('festival_id', $festival->id)->get()->keyBy('nama')
            : collect();

        $accounts = [
            // 9 Role Admin/Panitia lainnya (super-admin sudah dibuat di RoleSeeder: admin@futurisee.com / admin123)
            ['role' => 'ketua-pelaksana',    'name' => 'Demo Ketua Pelaksana',    'email' => 'ketua@futurise.test',      'account_type' => 'internal', 'pic_category' => null, 'divisi' => null,                              'jabatan' => 'ketua',       'kaos' => 'L',  'peran_kode' => 'ketua_pelaksana'],
            ['role' => 'sekretariat',        'name' => 'Demo Sekretariat',        'email' => 'sekretariat@futurise.test','account_type' => 'internal', 'pic_category' => null, 'divisi' => 'Kesekretariatan & Administrasi',  'jabatan' => 'sekretaris',  'kaos' => 'M',  'peran_kode' => 'sekretariat'],
            ['role' => 'keuangan',           'name' => 'Demo Keuangan',           'email' => 'keuangan@futurise.test',   'account_type' => 'internal', 'pic_category' => null, 'divisi' => 'Keuangan & Logistik',             'jabatan' => 'bendahara',   'kaos' => 'M',  'peran_kode' => null],
            ['role' => 'program-kurasi',     'name' => 'Demo Program & Kurasi',   'email' => 'kurasi@futurise.test',     'account_type' => 'internal', 'pic_category' => null, 'divisi' => 'Program & Kurasi Karya',          'jabatan' => 'koordinator', 'kaos' => 'L',  'peran_kode' => 'kurator_karya'],
            ['role' => 'kampus-sekolah',     'name' => 'Demo Relasi Kampus',      'email' => 'kampus@futurise.test',     'account_type' => 'internal', 'pic_category' => null, 'divisi' => 'Relasi Kampus & Sekolah',         'jabatan' => 'koordinator', 'kaos' => 'M',  'peran_kode' => null],
            ['role' => 'industri-sponsor',   'name' => 'Demo Industri & Sponsor', 'email' => 'industri@futurise.test',   'account_type' => 'internal', 'pic_category' => null, 'divisi' => 'Sponsor & Kemitraan Industri',    'jabatan' => 'koordinator', 'kaos' => 'L',  'peran_kode' => null],
            ['role' => 'venue-teknis',       'name' => 'Demo Venue & Teknis',     'email' => 'venue@futurise.test',      'account_type' => 'internal', 'pic_category' => null, 'divisi' => 'Venue, Booth & Teknis',           'jabatan' => 'koordinator', 'kaos' => 'XL', 'peran_kode' => 'koordinator_lapangan'],
            ['role' => 'publikasi',          'name' => 'Demo Publikasi',          'email' => 'publikasi@futurise.test',  'account_type' => 'internal', 'pic_category' => null, 'divisi' => 'Publikasi, Humas & Media',        'jabatan' => 'koordinator', 'kaos' => 'M',  'peran_kode' => 'pengelola_publikasi'],
            ['role' => 'registrasi-layanan', 'name' => 'Demo Registrasi Layanan', 'email' => 'registrasi@futurise.test', 'account_type' => 'internal', 'pic_category' => null, 'divisi' => 'Registrasi & Layanan Peserta',    'jabatan' => 'koordinator', 'kaos' => 'S',  'peran_kode' => 'registrasi_layanan'],

            // 7 Role Non-Admin / Eksternal
            ['role' => 'pengunjung',    'name' => 'Demo Pengunjung',    'email' => 'pengunjung@futurise.test',   'account_type' => 'public',   'pic_category' => null],
            ['role' => 'tim-mahasiswa', 'name' => 'Demo Tim Mahasiswa', 'email' => 'mahasiswa@futurise.test',    'account_type' => 'public',   'pic_category' => null],
            ['role' => 'pic-kampus',    'name' => 'Demo PIC Kampus',    'email' => 'pic.kampus@futurise.test',   'account_type' => 'pic',      'pic_category' => 'kampus-sekolah'],
            ['role' => 'pic-sekolah',   'name' => 'Demo PIC Sekolah',   'email' => 'pic.sekolah@futurise.test',  'account_type' => 'pic',      'pic_category' => 'kampus-sekolah'],
            ['role' => 'pic-industri',  'name' => 'Demo PIC Industri',  'email' => 'pic.industri@futurise.test', 'account_type' => 'pic',      'pic_category' => 'industri-sponsor'],
            ['role' => 'pic-sponsor',   'name' => 'Demo PIC Sponsor',   'email' => 'pic.sponsor@futurise.test',  'account_type' => 'pic',      'pic_category' => 'industri-sponsor'],
            ['role' => 'juri',          'name' => 'Demo Juri',          'email' => 'juri@futurise.test',         'account_type' => 'internal', 'pic_category' => null],
        ];

        $ketuaAssignment = null;
        $ketuaPanitiaId = null;
        $accessRolesByCode = PeranAkses::all()->keyBy('kode');

        foreach ($accounts as $idx => $item) {
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

            if (array_key_exists('jabatan', $item)) {
                $panitia = Panitia::updateOrCreate(
                    ['email' => $item['email']],
                    [
                        'nama_lengkap' => $item['name'],
                        'no_telepon'   => sprintf('08121000%04d', $idx + 1),
                        'institusi'    => 'APTIKOM Jawa Barat',
                        'jenis'        => 'internal',
                        'ukuran_kaos'  => $item['kaos'],
                        'aktif'        => true,
                    ]
                );

                if ($item['role'] === 'ketua-pelaksana') {
                    $ketuaPanitiaId = $panitia->id;
                }

                if ($festival) {
                    $divisiId = $item['divisi'] ? ($divisionsByName->get($item['divisi'])?->id) : null;
                    $assignment = PanitiaFestival::updateOrCreate(
                        [
                            'festival_id' => $festival->id,
                            'panitia_id'  => $panitia->id,
                        ],
                        [
                            'divisi_id'         => $divisiId,
                            'jabatan'           => $item['jabatan'],
                            'atasan_id'         => $item['role'] === 'ketua-pelaksana' ? null : $ketuaAssignment?->id,
                            'status'            => 'aktif',
                            'tanggal_bergabung' => '2026-11-01',
                        ]
                    );

                    if ($item['role'] === 'ketua-pelaksana') {
                        $ketuaAssignment = $assignment;
                    }
                }

                if (! empty($item['peran_kode']) && ($peran = $accessRolesByCode->get($item['peran_kode']))) {
                    $pivotData = [
                        'festival_id'    => $festival?->id,
                        'diberikan_oleh' => $ketuaPanitiaId,
                    ];

                    if ($panitia->peranAkses()->where('peran_id', $peran->id)->exists()) {
                        $panitia->peranAkses()->updateExistingPivot($peran->id, $pivotData);
                    } else {
                        $panitia->peranAkses()->attach($peran->id, $pivotData);
                    }
                }
            }

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
