<?php

namespace Database\Seeders;

use App\Models\Festival;
use App\Models\FestivalPeriode;
use App\Models\FestivalKategori;
use Illuminate\Database\Seeder;

class FestivalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $festival = Festival::updateOrCreate(
            ['slug' => 'futurise-2026'],
            [
                'nama'            => 'FUTURiSE 2026',
                'edisi'           => 'Edisi 1 (2026)',
                'tahun'           => 2026,
                'tema'            => 'Inovasi Teknologi Berkelanjutan Menuju Indonesia Emas',
                'tagline'         => 'Empowering Future Innovations and Academic Collaboration',
                'deskripsi'       => 'Festival Inovasi Teknologi dan Rekayasa Indonesia (FUTURiSE) diselenggarakan oleh APTIKOM Wilayah IV Jawa Barat sebagai ajang pameran dan kompetisi karya teknologi tingkat nasional bagi siswa SMK dan mahasiswa perguruan tinggi.',
                'penyelenggara'   => 'APTIKOM Jawa Barat',
                'format'          => 'hibrida',
                'lokasi_utama'    => 'Bandung, Jawa Barat',
                'tanggal_mulai'   => '2026-11-01',
                'tanggal_selesai' => '2026-12-15',
                'zona_waktu'      => 'Asia/Jakarta',
                'website_url'     => 'https://aptikom.org',
                'email_kontak'    => 'futurise@aptikom.org',
                'aktif'           => true,
            ]
        );

        // Periodes
        $periodes = [
            [
                'tahap'      => 'pendaftaran',
                'mulai'      => '2026-11-01 00:00:00',
                'selesai'    => '2026-11-25 23:59:59',
                'keterangan' => 'Pendaftaran akun peserta dan verifikasi profil',
            ],
            [
                'tahap'      => 'pengumpulan_karya',
                'mulai'      => '2026-11-10 00:00:00',
                'selesai'    => '2026-11-30 23:59:59',
                'keterangan' => 'Pengunggahan berkas karya, proposal, dan video demo inovasi',
            ],
            [
                'tahap'      => 'kurasi_administrasi',
                'mulai'      => '2026-12-01 00:00:00',
                'selesai'    => '2026-12-05 23:59:59',
                'keterangan' => 'Verifikasi keaslian karya dan kelayakan administrasi oleh tim kurasi',
            ],
            [
                'tahap'      => 'penjurian',
                'mulai'      => '2026-12-06 00:00:00',
                'selesai'    => '2026-12-12 23:59:59',
                'keterangan' => 'Penilaian karya oleh dewan juri ahli APTIKOM Jawa Barat',
            ],
            [
                'tahap'      => 'awarding',
                'mulai'      => '2026-12-15 09:00:00',
                'selesai'    => '2026-12-15 17:00:00',
                'keterangan' => 'Pameran (expo karya), pengumuman pemenang, dan awarding ceremony',
            ],
        ];

        foreach ($periodes as $p) {
            FestivalPeriode::updateOrCreate(
                [
                    'festival_id' => $festival->id,
                    'tahap'       => $p['tahap'],
                ],
                [
                    'mulai'      => $p['mulai'],
                    'selesai'    => $p['selesai'],
                    'keterangan' => $p['keterangan'],
                ]
            );
        }

        // Kategori Lomba
        $kategoris = [
            [
                'nama'      => 'Web & Mobile App Innovation',
                'deskripsi' => 'Pengembangan aplikasi web atau mobile responsif dengan pemecahan masalah riil masyarakat/industri.',
                'kuota'     => 100,
                'urutan'    => 1,
            ],
            [
                'nama'      => 'Artificial Intelligence & Big Data',
                'deskripsi' => 'Implementasi Machine Learning, Deep Learning, computer vision, atau analitik data terapan.',
                'kuota'     => 80,
                'urutan'    => 2,
            ],
            [
                'nama'      => 'IoT, Robotics & Embedded Systems',
                'deskripsi' => 'Sistem fisik berbasis mikrokontroler/sensor terhubung IoT atau perangkat robotika otomasi cerdas.',
                'kuota'     => 60,
                'urutan'    => 3,
            ],
            [
                'nama'      => 'Game Development & Interactive Media',
                'deskripsi' => 'Pengembangan game edukasi, rekreasi, simulasi, atau karya grafis dan media interaktif berkualitas.',
                'kuota'     => 50,
                'urutan'    => 4,
            ],
            [
                'nama'      => 'Cybersecurity & Infrastructure',
                'deskripsi' => 'Pengembangan alat deteksi celah keamanan, arsitektur jaringan aman, atau sistem kriptografi terapan.',
                'kuota'     => 40,
                'urutan'    => 5,
            ],
        ];

        foreach ($kategoris as $k) {
            FestivalKategori::updateOrCreate(
                [
                    'festival_id' => $festival->id,
                    'nama'        => $k['nama'],
                ],
                [
                    'deskripsi' => $k['deskripsi'],
                    'kuota'     => $k['kuota'],
                    'urutan'    => $k['urutan'],
                ]
            );
        }
    }
}
