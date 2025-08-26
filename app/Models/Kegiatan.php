<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $fillable = [
        'id',
        'id_user',
        'tanggal',
        'lokasi',
        'jenis',
        'status',
    ];
}
