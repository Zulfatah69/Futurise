<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'peserta';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'email',
        'no_telepon',
        'institusi',
        'jenis_peserta',
        'nomor_identitas',
        'identity_unique_key',
        'foto',
    ];

    protected $hidden = [
        'identity_unique_key',
    ];

    protected static function booted(): void
    {
        static::saving(function (Peserta $peserta): void {
            $peserta->nomor_identitas = self::normalizeIdentityNumber($peserta->nomor_identitas);
            $peserta->identity_unique_key = self::buildIdentityUniqueKey(
                $peserta->jenis_peserta,
                $peserta->institusi,
                $peserta->nomor_identitas
            );
        });
    }

    public static function normalizeIdentityNumber(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $cleaned = preg_replace('/[\s\-]+/', '', trim($value)) ?? '';

        return $cleaned === '' ? null : mb_strtoupper($cleaned);
    }

    public static function buildIdentityUniqueKey(?string $jenisPeserta, ?string $institusi, ?string $nomorIdentitas): ?string
    {
        $normalizedNumber = self::normalizeIdentityNumber($nomorIdentitas);
        if ($normalizedNumber === null) {
            return null;
        }

        $jenis = mb_strtolower(trim((string) $jenisPeserta));

        return match ($jenis) {
            'siswa', 'siswa_smk' => "nisn:{$normalizedNumber}",
            'umum'               => "nik:{$normalizedNumber}",
            default              => 'nim:' . mb_strtolower(preg_replace('/\s+/', ' ', trim((string) $institusi)) ?? '') . ":{$normalizedNumber}",
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Karya yang disubmit oleh peserta ini sebagai ketua tim / penanggung jawab.
     */
    public function karyasAsLeader(): HasMany
    {
        return $this->hasMany(Karya::class, 'peserta_id');
    }

    /**
     * Seluruh karya di mana peserta terlibat (baik ketua maupun anggota berakun).
     */
    public function karyas(): BelongsToMany
    {
        return $this->belongsToMany(Karya::class, 'karya_peserta', 'peserta_id', 'karya_id')
            ->withPivot(['peran', 'is_ketua', 'urutan', 'created_at']);
    }
}
