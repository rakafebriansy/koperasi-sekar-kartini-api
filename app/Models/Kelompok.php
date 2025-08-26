<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    protected $fillable = [
        'id',
        'id_wilayah_kerja',
        'id_pjk',
        'id_sekretaris',
        'id_bendahara',
        'id_ppk',
        'nomor',
        'ketetapan',
        'besaran_kas_tanggung_renteng',
        'besaran_kas_kelompok',
        'besaran_kas_dana_sosial',
        'jumlah_kas_tanggung_renteng',
        'jumlah_kas_kelompok',
        'jumlah_kas_dana_sosial',
        'status_aktif',
    ];
}
