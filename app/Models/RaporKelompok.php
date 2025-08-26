<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaporKelompok extends Model
{
    protected $fillable = [
        'id',
        'id_kelompok',
        'perkembangan_anggota_masuk',
        'perkembangan_anggota_masuk_%',
        'perkembangan_anggota_keluar',
        'perkembangan_anggota_keluar_%',
        'anggota_kelompok_jumlah',
        'anggota_kelompok_jumlah_%',
        'tertib_administrasi_%',
        'tertib_setor_%',
        'kehadiran_%',
        'nilai_akhir_organisasi_%',
        'partisipasi_pinjaman_pb',
        'partisipasi_pinjaman_bbm',
        'partisipasi_pinjaman_toko',
        'partisipasi_tunai',
        'partisipasi_tunai_%',
        'partisipasi_simpanan',
        'partisipasi_simpanan_%',
        'setor_di_pertemuan_%',
        'saldo_pinjaman_pb',
        'saldo_pinjaman_bbm',
        'saldo_pinjaman_toko',
        'nilai_akhir_keuangan_%',
        'nilai_gabungan_%',
        'kriteria',
    ];
}
