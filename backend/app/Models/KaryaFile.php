<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KaryaFile extends Model
{
    use HasFactory;

    protected $table = 'karya_file';

    protected $fillable = [
        'karya_id',
        'jenis',
        'nama_asli',
        'path',
        'url_eksternal',
        'mime_type',
        'ukuran_byte',
        'durasi_detik',
        'lebar',
        'tinggi',
        'keterangan',
        'urutan',
        'is_publik',
        'checksum',
        'diunggah_oleh',
    ];

    protected $casts = [
        'ukuran_byte'  => 'integer',
        'durasi_detik' => 'integer',
        'lebar'        => 'integer',
        'tinggi'       => 'integer',
        'urutan'       => 'integer',
        'is_publik'    => 'boolean',
    ];

    public function karya(): BelongsTo
    {
        return $this->belongsTo(Karya::class, 'karya_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(Peserta::class, 'diunggah_oleh');
    }
}
