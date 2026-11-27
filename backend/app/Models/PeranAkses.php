<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PeranAkses extends Model
{
    use HasFactory;

    protected $table = 'peran_akses';

    public $timestamps = false;

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'izin',
    ];

    protected $casts = [
        'izin' => 'array',
    ];

    public function panitias(): BelongsToMany
    {
        return $this->belongsToMany(Panitia::class, 'panitia_peran', 'peran_id', 'panitia_id')
            ->withPivot(['festival_id', 'diberikan_oleh', 'created_at']);
    }
}
