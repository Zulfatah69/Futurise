<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\ReferensiInstitusi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    /**
     * Peta singkatan populer universitas/kampus di Indonesia.
     */
    private const ALIASES = [
        'itenas' => 'Institut Teknologi Nasional',
        'itb'    => 'Institut Teknologi Bandung',
        'ui'     => 'Universitas Indonesia',
        'ugm'    => 'Universitas Gadjah Mada',
        'unpad'  => 'Universitas Padjadjaran',
        'its'    => 'Institut Teknologi Sepuluh Nopember',
        'undip'  => 'Universitas Diponegoro',
        'unair'  => 'Universitas Airlangga',
        'ub'     => 'Universitas Brawijaya',
        'upi'    => 'Universitas Pendidikan Indonesia',
        'ipb'    => 'Institut Pertanian Bogor',
        'uny'    => 'Universitas Negeri Yogyakarta',
        'unesa'  => 'Universitas Negeri Surabaya',
        'uns'    => 'Universitas Sebelas Maret',
        'unhas'  => 'Universitas Hasanuddin',
        'usu'    => 'Universitas Sumatera Utara',
        'unand'  => 'Universitas Andalas',
        'unsri'  => 'Universitas Sriwijaya',
        'binus'  => 'Universitas Bina Nusantara',
        'telkom' => 'Universitas Telkom',
        'polban' => 'Politeknik Negeri Bandung',
        'pens'   => 'Politeknik Elektronika Negeri Surabaya',
    ];

    /**
     * Autocomplete pencarian nama Perguruan Tinggi & SMK.
     */
    public function search(Request $request): JsonResponse
    {
        $rawQuery = trim($request->input('q', ''));
        $kategori = $request->input('kategori'); // perguruan_tinggi | smk

        if (mb_strlen($rawQuery) < 2) {
            return response()->json([
                'success' => true,
                'data'    => [],
            ]);
        }

        $lower = mb_strtolower($rawQuery);
        $builder = ReferensiInstitusi::query();

        if (! empty($kategori) && in_array($kategori, ['perguruan_tinggi', 'smk'], true)) {
            $builder->where('kategori', $kategori);
        }

        // Jika cocok dengan singkatan populer (misal: "itenas", "itb")
        if (isset(self::ALIASES[$lower])) {
            $alias = self::ALIASES[$lower];
            $builder->where(function ($q) use ($rawQuery, $alias) {
                $q->where('nama', 'LIKE', "%{$alias}%")
                  ->orWhere('nama', 'LIKE', "%{$rawQuery}%");
            });
        } else {
            // Pecah berdasarkan spasi agar "smk 1 bandung" cocok dengan "SMKN 1 BANDUNG"
            $words = preg_split('/\s+/', $rawQuery, -1, PREG_SPLIT_NO_EMPTY);
            $builder->where(function ($q) use ($words) {
                foreach ($words as $word) {
                    $q->where('nama', 'LIKE', "%{$word}%");
                }
            });
        }

        $results = $builder
            ->orderBy('nama', 'asc')
            ->limit(15)
            ->get(['id', 'nama', 'kategori', 'provinsi', 'kab_kota']);

        return response()->json([
            'success' => true,
            'data'    => $results,
        ]);
    }
}
