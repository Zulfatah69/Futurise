<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PublikasiKarya extends Model
{
    use HasFactory;

    protected $table = 'publikasi_karya';

    protected $fillable = [
        'karya_id',
        'kanal',
        'slug',
        'judul_publik',
        'ringkasan_publik',
        'thumbnail_file_id',
        'status',
        'tanggal_terbit',
        'tanggal_berakhir',
        'is_unggulan',
        'urutan_tampil',
        'izin_peserta',
        'izin_tanggal',
        'disetujui_oleh',
        'alasan_penarikan',
        'meta_title',
        'meta_description',
        'jumlah_dilihat',
        'jumlah_suka',
    ];

    protected $casts = [
        'is_unggulan'      => 'boolean',
        'izin_peserta'     => 'boolean',
        'urutan_tampil'    => 'integer',
        'jumlah_dilihat'   => 'integer',
        'jumlah_suka'      => 'integer',
        'tanggal_terbit'   => 'datetime',
        'tanggal_berakhir' => 'datetime',
        'izin_tanggal'     => 'datetime',
    ];

    /**
     * Otomatis buat slug jika belum diisi.
     */
    protected static function booted(): void
    {
        static::creating(function (PublikasiKarya $pub) {
            if (empty($pub->slug)) {
                $baseSlug = Str::slug($pub->judul_publik);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }
                $pub->slug = $slug;
            }
        });
    }

    public function karya(): BelongsTo
    {
        return $this->belongsTo(Karya::class, 'karya_id');
    }

    public function thumbnailFile(): BelongsTo
    {
        return $this->belongsTo(KaryaFile::class, 'thumbnail_file_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PublikasiLog::class, 'publikasi_id')->orderBy('created_at', 'desc');
    }

    /**
     * Scope untuk karya yang aktif tayang di etalase/showcase publik.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'terbit')
            ->where('izin_peserta', true)
            ->where(function ($q) {
                $q->whereNull('tanggal_berakhir')
                  ->orWhere('tanggal_berakhir', '>=', now());
            });
    }
}
