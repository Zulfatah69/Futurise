<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FestivalPeriode extends Model
{
    use HasFactory;

    protected $table = 'festival_periode';

    protected $fillable = [
        'festival_id',
        'tahap',
        'mulai',
        'selesai',
        'keterangan',
    ];

    protected $casts = [
        'mulai'   => 'datetime',
        'selesai' => 'datetime',
    ];

    /**
     * Relasi ke festival induk.
     */
    public function festival(): BelongsTo
    {
        return $this->belongsTo(Festival::class, 'festival_id');
    }

    /**
     * Cek apakah periode sedang berlangsung sekarang.
     */
    public function isSedangBerlangsung(): bool
    {
        $now = now();
        return $this->mulai <= $now && $this->selesai >= $now;
    }
}
