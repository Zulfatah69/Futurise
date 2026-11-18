<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggotaTim extends Model
{
    use HasFactory;

    protected $table = 'anggota_tim';

    protected $fillable = [
        'karya_id',
        'nama_lengkap',
        'peran',
        'is_ketua',
        'email',
        'no_telepon',
        'institusi',
        'jenis_peserta',
        'nomor_identitas',
        'foto',
        'urutan',
    ];

    protected $casts = [
        'is_ketua' => 'boolean',
        'urutan'   => 'integer',
    ];

    public function karya(): BelongsTo
    {
        return $this->belongsTo(Karya::class, 'karya_id');
    }
}
