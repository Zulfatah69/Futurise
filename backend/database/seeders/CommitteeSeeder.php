<?php

namespace Database\Seeders;

use App\Models\DivisiPanitia;
use App\Models\Festival;
use App\Models\PeranAkses;
use Illuminate\Database\Seeder;

class CommitteeSeeder extends Seeder
{
    /**
     * Seed master divisi kepanitiaan festival aktif dan definisi peran akses.
     */
    public function run(): void
    {
        $festival = Festival::aktif()->first() ?? Festival::first();

        if ($festival) {
            $divisions = [
                [
                    'nama'          => 'Kesekretariatan & Administrasi',
                    'deskripsi'     => 'Pengelolaan administrasi kepanitiaan, surat-menyurat, dan verifikasi akun PIC.',
                    'kuota_anggota' => 10,
                    'urutan'        => 1,
                ],
                [
                    'nama'          => 'Program & Kurasi Karya',
                    'deskripsi'     => 'Kurasi administrasi karya peserta, penyusunan jadwal lomba, dan penjurian.',
                    'kuota_anggota' => 12,
                    'urutan'        => 2,
                ],
                [
                    'nama'          => 'Publikasi, Humas & Media',
                    'deskripsi'     => 'Manajemen showcase karya publik, materi publikasi, dan dokumentasi acara.',
                    'kuota_anggota' => 10,
                    'urutan'        => 3,
                ],
                [
                    'nama'          => 'Relasi Kampus & Sekolah',
                    'deskripsi'     => 'Koordinasi PIC perguruan tinggi dan SMK peserta di Jawa Barat.',
                    'kuota_anggota' => 8,
                    'urutan'        => 4,
                ],
                [
                    'nama'          => 'Sponsor & Kemitraan Industri',
                    'deskripsi'     => 'Kerjasama sponsor, mitra industri, dan inkubasi karya peserta.',
                    'kuota_anggota' => 8,
                    'urutan'        => 5,
                ],
                [
                    'nama'          => 'Venue, Booth & Teknis',
                    'deskripsi'     => 'Penataan zona pameran, alokasi booth, dan dukungan teknis lapangan.',
                    'kuota_anggota' => 15,
                    'urutan'        => 6,
                ],
                [
                    'nama'          => 'Registrasi & Layanan Peserta',
                    'deskripsi'     => 'Helpdesk peserta, presensi pengunjung, dan layanan informasi venue.',
                    'kuota_anggota' => 12,
                    'urutan'        => 7,
                ],
                [
                    'nama'          => 'Keuangan & Logistik',
                    'deskripsi'     => 'Pengelolaan anggaran operasional, konsumsi, dan perlengkapan panitia.',
                    'kuota_anggota' => 6,
                    'urutan'        => 8,
                ],
            ];

            foreach ($divisions as $division) {
                DivisiPanitia::updateOrCreate(
                    [
                        'festival_id' => $festival->id,
                        'nama'        => $division['nama'],
                    ],
                    [
                        'deskripsi'     => $division['deskripsi'],
                        'kuota_anggota' => $division['kuota_anggota'],
                        'urutan'        => $division['urutan'],
                    ]
                );
            }
        }

        $accessRoles = [
            [
                'kode'      => 'ketua_pelaksana',
                'nama'      => 'Ketua Pelaksana',
                'deskripsi' => 'Akses penuh koordinasi divisi, jadwal, dan keputusan festival.',
                'izin'      => ['festival.manage', 'committee.manage', 'works.review', 'publications.publish'],
            ],
            [
                'kode'      => 'sekretariat',
                'nama'      => 'Sekretariat Panitia',
                'deskripsi' => 'Mengelola data panitia, pembagian divisi, dan verifikasi akun PIC.',
                'izin'      => ['users.verify_pic', 'committee.manage', 'divisions.manage'],
            ],
            [
                'kode'      => 'kurator_karya',
                'nama'      => 'Kurator Karya Lomba',
                'deskripsi' => 'Meninjau kelayakan karya peserta serta meminta revisi atau meloloskan karya.',
                'izin'      => ['works.view', 'works.review'],
            ],
            [
                'kode'      => 'pengelola_publikasi',
                'nama'      => 'Pengelola Publikasi & Showcase',
                'deskripsi' => 'Menyetujui, menerbitkan, dan menarik karya dari etalase showcase publik.',
                'izin'      => ['publications.view', 'publications.approve', 'publications.publish', 'publications.withdraw'],
            ],
            [
                'kode'      => 'koordinator_lapangan',
                'nama'      => 'Koordinator Shift & Venue',
                'deskripsi' => 'Mengatur jadwal tugas panitia serta pencatatan presensi shift lapangan.',
                'izin'      => ['tasks.manage', 'tasks.check_in'],
            ],
            [
                'kode'      => 'registrasi_layanan',
                'nama'      => 'Registrasi & Layanan Peserta',
                'deskripsi' => 'Membantu aktivasi akun peserta dan layanan meja registrasi.',
                'izin'      => ['users.view', 'users.activate', 'tasks.check_in'],
            ],
        ];

        foreach ($accessRoles as $role) {
            PeranAkses::updateOrCreate(
                ['kode' => $role['kode']],
                [
                    'nama'      => $role['nama'],
                    'deskripsi' => $role['deskripsi'],
                    'izin'      => $role['izin'],
                ]
            );
        }
    }
}
