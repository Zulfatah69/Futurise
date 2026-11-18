<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferensiInstitusi extends Model
{
    protected $table = 'referensi_institusi';

    protected $fillable = [
        'nama',
        'kategori',
        'npsn',
        'provinsi',
        'kab_kota',
    ];
}
