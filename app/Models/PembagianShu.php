<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembagianShu extends Model
{
    protected $fillable = [
        'id',
        'shu_jasa_usaha',
        'shu_jasa_simpanan',
        'id_shu',
        'id_anggota',
    ];
}
