<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FestivalKategori extends Model
{
    use HasFactory;

    protected $table = 'festival_kategori';

    protected $fillable = [
        'festival_id',
        'nama',
        'deskripsi',
        'kuota',
        'urutan',
    ];

    protected $casts = [
        'kuota'  => 'integer',
        'urutan' => 'integer',
    ];

    /**
     * Relasi ke festival induk.
     */
    public function festival(): BelongsTo
    {
        return $this->belongsTo(Festival::class, 'festival_id');
    }
}
