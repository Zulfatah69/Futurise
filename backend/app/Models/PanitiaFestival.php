<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PanitiaFestival extends Model
{
    use HasFactory;

    protected $table = 'panitia_festival';

    public const UPDATED_AT = null;

    protected $fillable = [
        'festival_id',
        'panitia_id',
        'divisi_id',
        'jabatan',
        'atasan_id',
        'status',
        'tanggal_bergabung',
        'tanggal_selesai',
        'sertifikat_file',
        'catatan',
    ];

    protected $casts = [
        'festival_id' => 'integer',
        'panitia_id' => 'integer',
        'divisi_id' => 'integer',
        'atasan_id' => 'integer',
        'tanggal_bergabung' => 'date',
        'tanggal_selesai' => 'date',
        'created_at' => 'datetime',
    ];

    public function festival(): BelongsTo
    {
        return $this->belongsTo(Festival::class, 'festival_id');
    }

    public function panitia(): BelongsTo
    {
        return $this->belongsTo(Panitia::class, 'panitia_id');
    }

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(DivisiPanitia::class, 'divisi_id');
    }

    public function atasan(): BelongsTo
    {
        return $this->belongsTo(self::class, 'atasan_id');
    }

    public function bawahan(): HasMany
    {
        return $this->hasMany(self::class, 'atasan_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(PanitiaTugas::class, 'panitia_festival_id');
    }
}
