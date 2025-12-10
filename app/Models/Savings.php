<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Savings extends Model
{

    protected $fillable = [
        'type',
        'nominal',
        'year',
        'month',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
