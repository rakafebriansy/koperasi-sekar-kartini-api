<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggotaKelompok extends Model
{
    protected $fillable = [
        'id',
        'id_user',
        'id_kelompok',
        'nomor_anggota',
        'status_aktif',
        'file_kartu_tanda_anggota',
        'catatan',
    ];
}
