<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberGroup extends Model
{
    use HasFactory;

    /**
     * Tabel yang digunakan oleh model ini.
     *
     * Secara default Laravel akan mengira nama tabel "member_groups",
     * sedangkan migrasi kita membuat tabel "groups", jadi perlu di-override.
     */
    protected $table = 'groups';

    /**
     * Kolom yang boleh diisi mass-assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'number',
        'description',
        'shared_liability_fund_amount',
        'group_fund_amount',
        'social_fund_amount',
        'total_shared_liability_fund',
        'total_group_fund',
        'total_social_fund',
        'is_active',
        'work_area_id',
        'chairman_id',
        'facilitator_id',
        'secretary_id',
        'treasurer_id',
    ];

    /**
     * Casting atribut.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function workArea(): BelongsTo
    {
        return $this->belongsTo(WorkArea::class);
    }

    public function chairman(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chairman_id');
    }

    public function facilitator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'facilitator_id');
    }

    public function secretary(): BelongsTo
    {
        return $this->belongsTo(User::class, 'secretary_id');
    }

    public function treasurer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'treasurer_id');
    }

    /**
     * Anggota (users) yang tergabung dalam kelompok ini.
     */
    public function members(): HasMany
    {
        return $this->hasMany(User::class, 'group_id');
    }
}


