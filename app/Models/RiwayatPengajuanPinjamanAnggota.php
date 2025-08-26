<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPengajuanPinjamanAnggota extends Model
{
    protected $fillable = [
        'id',
        'id_anggota',
        'jenis',
        'telah_disetujui',
    ];
}
