<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PinjamanAnggota extends Model
{
    protected $fillable = [
        'id',
        'id_anggota',
        'jumlah_pinjaman_biasa',
        'jumlah_pinjaman_pengadaan_barang',
        'jumlah_pinjaman_bbm',
        'jumlah_pinjaman_bahan_pokok',
        'jumlah_pinjaman_barang_dagangan',
        'jumlah_pinjaman_lebaran',
        'jumlah_pinjaman_rekreasi',
        'jumlah_pinjaman_khusus',
        'jumlah_pinjaman_jadwal_ulang',
    ];
}
