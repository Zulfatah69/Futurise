<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

class Karya extends Model
{
    use HasFactory;

    protected $table = 'karya';

    protected $fillable = [
        'festival_id',
        'peserta_id',
        'judul',
        'deskripsi',
        'masalah',
        'pengguna_sasaran',
        'solusi',
        'fitur_utama',
        'kategori',
        'teknologi',
        'tahap_kesiapan',
        'hasil_uji',
        'keterbatasan',
        'kebutuhan_kolaborasi',
        'file_utama',
        'thumbnail',
        'url_demo',
        'lisensi',
        'persetujuan_publikasi',
        'status',
        'catatan_kurasi',
        'skor_akhir',
    ];

    protected $casts = [
        'persetujuan_publikasi' => 'boolean',
        'skor_akhir'            => 'decimal:2',
    ];

    public function scopeLolos(Builder $query): Builder
    {
        return $query->where('status', 'lolos');
    }

    public function scopePublik(Builder $query): Builder
    {
        return $query->where('persetujuan_publikasi', true);
    }

    public function festival(): BelongsTo
    {
        return $this->belongsTo(Festival::class, 'festival_id');
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public function pesertas(): BelongsToMany
    {
        return $this->belongsToMany(Peserta::class, 'karya_peserta', 'karya_id', 'peserta_id')
            ->withPivot(['peran', 'is_ketua', 'urutan', 'created_at']);
    }

    public function anggotaTims(): HasMany
    {
        return $this->hasMany(AnggotaTim::class, 'karya_id')->orderBy('urutan');
    }

    public function files(): HasMany
    {
        return $this->hasMany(KaryaFile::class, 'karya_id')->orderBy('urutan');
    }

    public function versis(): HasMany
    {
        return $this->hasMany(KaryaVersi::class, 'karya_id')->orderByDesc('versi');
    }

    public function publikasis(): HasMany
    {
        return $this->hasMany(PublikasiKarya::class, 'karya_id');
    }

    public function publikasiWeb(): HasOne
    {
        return $this->hasOne(PublikasiKarya::class, 'karya_id')->where('kanal', 'website');
    }
}
