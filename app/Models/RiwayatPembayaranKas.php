<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPembayaranKas extends Model
{
    protected $fillable = [
        'id',
        'id_anggota',
        'jenis',
        'keterangan',
    ];
}
