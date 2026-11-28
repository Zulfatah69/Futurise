<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KaryaVersi extends Model
{
    use HasFactory;

    protected $table = 'karya_versi';

    public $timestamps = false;

    protected $fillable = [
        'karya_id',
        'versi',
        'diubah_oleh',
        'status_lama',
        'status_baru',
        'catatan',
        'snapshot',
        'created_at',
    ];

    protected $casts = [
        'versi'      => 'integer',
        'snapshot'   => 'array',
        'created_at' => 'datetime',
    ];

    public function karya(): BelongsTo
    {
        return $this->belongsTo(Karya::class, 'karya_id');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diubah_oleh');
    }
}
