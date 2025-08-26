<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPemakaianKas extends Model
{
    protected $fillable = [
        'id',
        'id_kelompok',
        'jenis',
        'jumlah_pemakaian',
        'tujuan_pemakaian',
    ];
}
