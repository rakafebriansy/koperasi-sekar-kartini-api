<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shu extends Model
{
    protected $fillable = [
        'id',
        'tahun',
        'total_pendapatan',
        'total_biaya',
        'shu_bersih',
    ];
}
