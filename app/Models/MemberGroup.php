<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'description',
        'work_area_id',
        'shared_liability_fund_amount',
        'group_fund_amount',
        'social_fund_amount',
        'total_shared_liability_fund',
        'total_group_fund',
        'total_social_fund',
        'is_active',
        'chairman_id',
        'facilitator_id',
        'treasurer_id',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function workArea()
    {
        return $this->belongsTo(WorkArea::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'group_id');
    }
}

