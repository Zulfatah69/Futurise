<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DivisiPanitia extends Model
{
    use HasFactory;

    protected $table = 'divisi_panitia';

    public $timestamps = false;

    protected $fillable = [
        'festival_id',
        'nama',
        'deskripsi',
        'kuota_anggota',
        'urutan',
    ];

    protected $casts = [
        'festival_id' => 'integer',
        'kuota_anggota' => 'integer',
        'urutan' => 'integer',
    ];

    public function festival(): BelongsTo
    {
        return $this->belongsTo(Festival::class, 'festival_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(PanitiaFestival::class, 'divisi_id');
    }
}
