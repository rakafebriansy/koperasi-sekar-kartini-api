<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatSetoranSimpananAnggota extends Model
{
    protected $fillable = [
        'id',
        'id_anggota',
        'jenis',
    ];
}
