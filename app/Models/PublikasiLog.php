<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublikasiLog extends Model
{
    use HasFactory;

    protected $table = 'publikasi_log';

    public $timestamps = false;

    protected $fillable = [
        'publikasi_id',
        'status_lama',
        'status_baru',
        'diubah_oleh',
        'catatan',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (PublikasiLog $log) {
            if (empty($log->created_at)) {
                $log->created_at = now();
            }
        });
    }

    public function publikasi(): BelongsTo
    {
        return $this->belongsTo(PublikasiKarya::class, 'publikasi_id');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diubah_oleh');
    }
}
