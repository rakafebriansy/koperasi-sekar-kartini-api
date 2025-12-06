<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_type',
        'date',
        'time',
        'location',
        'photo',
        'description',
        'group_id',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
