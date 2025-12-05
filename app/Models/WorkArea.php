<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_work_area',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }
}


