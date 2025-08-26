<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PertemuanRutin extends Model
{
    protected $fillable = [
        'id',
        'id_kelompok',
        'tanggal',
        'lokasi',
        'status',
    ];
}
