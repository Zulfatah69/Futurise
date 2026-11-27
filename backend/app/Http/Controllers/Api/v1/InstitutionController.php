<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\ReferensiInstitusi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class InstitutionController extends Controller
{
    /**
     * Peta singkatan & padanan bahasa populer kampus/sekolah di Indonesia.
     */
    private const ALIASES = [
        'itenas'            => 'Institut Teknologi Nasional',
        'itb'               => 'Institut Teknologi Bandung',
        'ui'                => 'Universitas Indonesia',
        'ugm'               => 'Universitas Gadjah Mada',
        'unpad'             => 'Universitas Padjadjaran',
        'its'               => 'Institut Teknologi Sepuluh Nopember',
        'undip'             => 'Universitas Diponegoro',
        'unair'             => 'Universitas Airlangga',
        'ub'                => 'Universitas Brawijaya',
        'upi'               => 'Universitas Pendidikan Indonesia',
        'ipb'               => 'Institut Pertanian Bogor',
        'uny'               => 'Universitas Negeri Yogyakarta',
        'unesa'             => 'Universitas Negeri Surabaya',
        'uns'               => 'Universitas Sebelas Maret',
        'unhas'             => 'Universitas Hasanuddin',
        'usu'               => 'Universitas Sumatera Utara',
        'unand'             => 'Universitas Andalas',
        'unsri'             => 'Universitas Sriwijaya',
        'binus'             => 'Universitas Bina Nusantara',
        'telkom'            => 'Universitas Telkom',
        'telkom university' => 'Universitas Telkom',
        'polban'            => 'Politeknik Negeri Bandung',
        'pens'              => 'Politeknik Elektronika Negeri Surabaya',
        'unikom'            => 'Universitas Komputer Indonesia',
        'unpar'             => 'Universitas Katolik Parahyangan',
        'maranatha'         => 'Universitas Kristen Maranatha',
        'unisba'            => 'Universitas Islam Bandung',
        'widyatama'         => 'Universitas Widyatama',
        'unjani'            => 'Universitas Jenderal Achmad Yani',
        'uin bandung'       => 'Universitas Islam Negeri Sunan Gunung Djati',
    ];

    /**
     * Ekspansi singkatan kata umum di awal/tengah nama institusi.
     */
    private const WORD_EXPANSIONS = [
        'univ'   => 'universitas',
        'poltek' => 'politeknik',
        'inst'   => 'institut',
    ];

    private const RESULT_COLUMNS = ['id', 'nama', 'kategori', 'provinsi', 'kab_kota'];

    private function escapeLike(string $value): string
    {
        return addcslashes($value, '\\%_');
    }

    private function applyCategoryFilter(Builder $query, ?string $kategori): Builder
    {
        if (! empty($kategori) && in_array($kategori, ['perguruan_tinggi', 'smk'], true)) {
            $query->where('kategori', $kategori);
        }

        return $query;
    }

    /**
     * Urutkan berdasarkan relevansi: exact -> prefix -> phrase -> prioritas Jawa Barat -> nama terpendek.
     */
    private function applyRelevanceOrdering(Builder $query, string $phraseLower): Builder
    {
        $escapedPhrase = $this->escapeLike($phraseLower);

        return $query->orderByRaw(
            'CASE
                WHEN LOWER(nama) = ? THEN 0
                WHEN nama LIKE ? THEN 1
                WHEN nama LIKE ? THEN 2
                ELSE 3
            END ASC',
            [$phraseLower, "{$escapedPhrase}%", "%{$escapedPhrase}%"]
        )
            ->orderByRaw("CASE WHEN provinsi LIKE '%Jawa Barat%' THEN 0 ELSE 1 END ASC")
            ->orderByRaw('LENGTH(nama) ASC')
            ->orderBy('nama', 'asc');
    }

    /**
     * Fallback ringan untuk typo jika query ketat menghasilkan 0 baris.
     */
    private function fuzzyFallback(array $words, string $phraseLower, ?string $kategori): Collection
    {
        $prefixes = array_values(array_filter(array_map(
            fn (string $word): string => mb_substr($word, 0, min(3, mb_strlen($word))),
            $words
        )));

        if (empty($prefixes)) {
            return collect();
        }

        $candidatesQuery = $this->applyCategoryFilter(ReferensiInstitusi::query(), $kategori);
        $candidatesQuery->where(function (Builder $q) use ($prefixes): void {
            foreach ($prefixes as $prefix) {
                $q->where('nama', 'LIKE', '%' . $this->escapeLike($prefix) . '%');
            }
        });

        $candidates = $candidatesQuery
            ->orderByRaw("CASE WHEN provinsi LIKE '%Jawa Barat%' THEN 0 ELSE 1 END ASC")
            ->orderByRaw('LENGTH(nama) ASC')
            ->limit(100)
            ->get(self::RESULT_COLUMNS);

        return $candidates
            ->map(function (ReferensiInstitusi $item) use ($phraseLower): array {
                $nameLower = mb_strtolower($item->nama);
                similar_text($phraseLower, $nameLower, $percent);

                // Boost untuk prefiks awal yang sama atau institusi di Jawa Barat
                $score = $percent;
                if (str_starts_with($nameLower, mb_substr($phraseLower, 0, 4))) {
                    $score += 15;
                }
                if (str_contains((string) $item->provinsi, 'Jawa Barat')) {
                    $score += 5;
                }

                return ['item' => $item, 'score' => $score, 'percent' => $percent];
            })
            ->filter(fn (array $entry): bool => $entry['percent'] >= 45)
            ->sortByDesc('score')
            ->take(15)
            ->pluck('item')
            ->values();
    }

    /**
     * Autocomplete pencarian nama Perguruan Tinggi & SMK dengan ranking relevansi.
     */
    public function search(Request $request): JsonResponse
    {
        $kategori = $request->input('kategori'); // perguruan_tinggi | smk

        // Mode preload ringkas untuk cache client-side agar ketikan terasa instan (< 5 ms)
        if ($request->boolean('preload') && in_array($kategori, ['perguruan_tinggi', 'smk'], true)) {
            $rows = ReferensiInstitusi::query()
                ->where('kategori', $kategori)
                ->orderByRaw("CASE WHEN provinsi LIKE '%Jawa Barat%' THEN 0 ELSE 1 END ASC")
                ->orderByRaw('LENGTH(nama) ASC')
                ->toBase()
                ->get(['nama', 'provinsi', 'kab_kota'])
                ->map(static fn ($row): array => [$row->nama, $row->provinsi, $row->kab_kota])
                ->all();

            return response()->json([
                'success' => true,
                'data'    => $rows,
            ]);
        }

        $normalized = preg_replace('/\s+/', ' ', trim((string) $request->input('q', ''))) ?? '';

        if (mb_strlen($normalized) < 2) {
            return response()->json([
                'success' => true,
                'data'    => [],
            ]);
        }

        $lower = mb_strtolower($normalized);
        $builder = $this->applyCategoryFilter(ReferensiInstitusi::query(), $kategori);

        if (isset(self::ALIASES[$lower])) {
            $alias = self::ALIASES[$lower];
            $aliasLower = mb_strtolower($alias);
            $escapedAlias = $this->escapeLike($aliasLower);
            $escapedRaw = $this->escapeLike($lower);

            $builder->where(function (Builder $q) use ($escapedAlias, $escapedRaw): void {
                $q->where('nama', 'LIKE', "%{$escapedAlias}%")
                  ->orWhere('nama', 'LIKE', "%{$escapedRaw}%");
            });

            $results = $this->applyRelevanceOrdering($builder, $aliasLower)
                ->limit(15)
                ->get(self::RESULT_COLUMNS);
        } else {
            $rawWords = preg_split('/\s+/', $lower, -1, PREG_SPLIT_NO_EMPTY) ?: [];
            $expandedWords = array_map(
                fn (string $word): string => self::WORD_EXPANSIONS[$word] ?? $word,
                $rawWords
            );
            $searchPhrase = implode(' ', $expandedWords);

            $builder->where(function (Builder $q) use ($expandedWords): void {
                foreach ($expandedWords as $word) {
                    $q->where('nama', 'LIKE', '%' . $this->escapeLike($word) . '%');
                }
            });

            $results = $this->applyRelevanceOrdering($builder, $searchPhrase)
                ->limit(15)
                ->get(self::RESULT_COLUMNS);

            if ($results->isEmpty() && mb_strlen($searchPhrase) >= 4) {
                $results = $this->fuzzyFallback($expandedWords, $searchPhrase, $kategori);
            }
        }

        return response()->json([
            'success' => true,
            'data'    => $results,
        ]);
    }
}
