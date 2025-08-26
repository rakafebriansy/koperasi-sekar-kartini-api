<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimpananAnggota extends Model
{
    protected $fillable = [
        'id',
        'id_anggota',
        'jumlah_simpanan_pokok',
        'jumlah_simpanan_wajib',
        'jumlah_simpanan_wajib_khusus',
        'jumlah_simpanan_sukarela',
        'jumlah_simpanan_tanggung_renteng',
        'jumlah_simpanan_berjangka_smile',
        'jumlah_simpanan_hari_raya',
        'jumlah_simpanan_hari_tua',
        'jumlah_simpanan_rekreasi',
    ];
}
