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
        'foto',
    ];

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
