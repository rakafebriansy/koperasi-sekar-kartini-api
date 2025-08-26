<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiUsaha extends Model
{
    protected $fillable = [
        'id',
        'id_anggota',
        'jenis_transaksi',
        'jumlah',
    ];
}
