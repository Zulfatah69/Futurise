<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Panitia extends Model
{
    use HasFactory;

    protected $table = 'panitia';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'no_telepon',
        'institusi',
        'jenis',
        'ukuran_kaos',
        'kontak_darurat_nama',
        'kontak_darurat_telepon',
        'foto',
        'password_hash',
        'terakhir_login',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'terakhir_login' => 'datetime',
    ];

    protected $hidden = [
        'password_hash',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'email', 'email');
    }

    public function festivalAssignments(): HasMany
    {
        return $this->hasMany(PanitiaFestival::class, 'panitia_id');
    }

    public function festivals(): BelongsToMany
    {
        return $this->belongsToMany(Festival::class, 'panitia_festival', 'panitia_id', 'festival_id')
            ->withPivot(['id', 'divisi_id', 'jabatan', 'atasan_id', 'status', 'tanggal_bergabung', 'tanggal_selesai', 'catatan'])
            ->withTimestamps();
    }
}
