<?php

namespace Database\Seeders;

use App\Models\ReferensiInstitusi;
use Illuminate\Database\Seeder;

class ReferensiInstitusiSeeder extends Seeder
{
    /**
     * Seed fallback institusi populer jika dataset lengkap (import_institusi.py) belum dijalankan.
     */
    public function run(): void
    {
        if (ReferensiInstitusi::query()->exists()) {
            return;
        }

        $now = now();

        $institutions = [
            ['nama' => 'Institut Teknologi Nasional Bandung', 'kategori' => 'perguruan_tinggi', 'provinsi' => 'Prov. Jawa Barat', 'kab_kota' => 'Kota Bandung'],
            ['nama' => 'Institut Teknologi Bandung', 'kategori' => 'perguruan_tinggi', 'provinsi' => 'Prov. Jawa Barat', 'kab_kota' => 'Kota Bandung'],
            ['nama' => 'Universitas Padjadjaran', 'kategori' => 'perguruan_tinggi', 'provinsi' => 'Prov. Jawa Barat', 'kab_kota' => 'Kab. Sumedang'],
            ['nama' => 'Universitas Pendidikan Indonesia', 'kategori' => 'perguruan_tinggi', 'provinsi' => 'Prov. Jawa Barat', 'kab_kota' => 'Kota Bandung'],
            ['nama' => 'Universitas Telkom', 'kategori' => 'perguruan_tinggi', 'provinsi' => 'Prov. Jawa Barat', 'kab_kota' => 'Kab. Bandung'],
            ['nama' => 'Politeknik Negeri Bandung', 'kategori' => 'perguruan_tinggi', 'provinsi' => 'Prov. Jawa Barat', 'kab_kota' => 'Kab. Bandung Barat'],
            ['nama' => 'Institut Pertanian Bogor', 'kategori' => 'perguruan_tinggi', 'provinsi' => 'Prov. Jawa Barat', 'kab_kota' => 'Kab. Bogor'],
            ['nama' => 'Universitas Indonesia', 'kategori' => 'perguruan_tinggi', 'provinsi' => 'Prov. Jawa Barat', 'kab_kota' => 'Kota Depok'],
            ['nama' => 'Universitas Gadjah Mada', 'kategori' => 'perguruan_tinggi', 'provinsi' => 'Prov. D.I. Yogyakarta', 'kab_kota' => 'Kab. Sleman'],
            ['nama' => 'Institut Teknologi Sepuluh Nopember', 'kategori' => 'perguruan_tinggi', 'provinsi' => 'Prov. Jawa Timur', 'kab_kota' => 'Kota Surabaya'],
            ['nama' => 'Universitas Diponegoro', 'kategori' => 'perguruan_tinggi', 'provinsi' => 'Prov. Jawa Tengah', 'kab_kota' => 'Kota Semarang'],
            ['nama' => 'Universitas Airlangga', 'kategori' => 'perguruan_tinggi', 'provinsi' => 'Prov. Jawa Timur', 'kab_kota' => 'Kota Surabaya'],
            ['nama' => 'Universitas Brawijaya', 'kategori' => 'perguruan_tinggi', 'provinsi' => 'Prov. Jawa Timur', 'kab_kota' => 'Kota Malang'],
            ['nama' => 'Universitas Bina Nusantara', 'kategori' => 'perguruan_tinggi', 'provinsi' => 'Prov. D.K.I. Jakarta', 'kab_kota' => 'Kota Jakarta Barat'],
            ['nama' => 'Politeknik Elektronika Negeri Surabaya', 'kategori' => 'perguruan_tinggi', 'provinsi' => 'Prov. Jawa Timur', 'kab_kota' => 'Kota Surabaya'],
            ['nama' => 'SMKN 1 BANDUNG', 'kategori' => 'smk', 'provinsi' => 'Prov. Jawa Barat', 'kab_kota' => 'Kota Bandung'],
            ['nama' => 'SMKN 2 BANDUNG', 'kategori' => 'smk', 'provinsi' => 'Prov. Jawa Barat', 'kab_kota' => 'Kota Bandung'],
            ['nama' => 'SMKN 4 BANDUNG', 'kategori' => 'smk', 'provinsi' => 'Prov. Jawa Barat', 'kab_kota' => 'Kota Bandung'],
            ['nama' => 'SMKN 1 CIMAHI', 'kategori' => 'smk', 'provinsi' => 'Prov. Jawa Barat', 'kab_kota' => 'Kota Cimahi'],
            ['nama' => 'SMK TELKOM BANDUNG', 'kategori' => 'smk', 'provinsi' => 'Prov. Jawa Barat', 'kab_kota' => 'Kab. Bandung'],
        ];

        $rows = array_map(static fn (array $item): array => array_merge($item, [
            'npsn'       => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]), $institutions);

        ReferensiInstitusi::query()->insert($rows);
    }
}
