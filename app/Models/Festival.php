<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Festival extends Model
{
    use HasFactory;

    protected $table = 'festival';

    protected $fillable = [
        'nama',
        'slug',
        'edisi',
        'tahun',
        'tema',
        'tagline',
        'deskripsi',
        'penyelenggara',
        'format',
        'lokasi_utama',
        'tanggal_mulai',
        'tanggal_selesai',
        'zona_waktu',
        'logo',
        'banner',
        'website_url',
        'email_kontak',
        'aktif',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'aktif'           => 'boolean',
        'tahun'           => 'integer',
    ];

    /**
     * Scope query untuk festival yang sedang aktif.
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }

    /**
     * Relasi ke periode/tahapan festival.
     */
    public function periodes(): HasMany
    {
        return $this->hasMany(FestivalPeriode::class, 'festival_id')->orderBy('mulai');
    }

    /**
     * Relasi ke kategori lomba/karya festival.
     */
    public function kategoris(): HasMany
    {
        return $this->hasMany(FestivalKategori::class, 'festival_id')->orderBy('urutan');
    }
}
