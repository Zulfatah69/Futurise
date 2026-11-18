<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PanitiaTugas extends Model
{
    use HasFactory;

    protected $table = 'panitia_tugas';

    public $timestamps = false;

    protected $fillable = [
        'panitia_festival_id',
        'judul',
        'lokasi_id',
        'jadwal_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status',
        'check_in_pada',
        'catatan',
    ];

    protected $casts = [
        'panitia_festival_id' => 'integer',
        'lokasi_id' => 'integer',
        'jadwal_id' => 'integer',
        'tanggal' => 'date',
        'check_in_pada' => 'datetime',
    ];

    public function panitiaFestival(): BelongsTo
    {
        return $this->belongsTo(PanitiaFestival::class, 'panitia_festival_id');
    }
}
