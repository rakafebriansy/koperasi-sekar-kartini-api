<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *     schema="WorkArea",
 *     type="object",
 *     title="Work Area",
 *     description="Schema untuk data Work Area",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="ID unik Work Area",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name_work_area",
 *         type="string",
 *         description="Nama Work Area",
 *         example="IT Department"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Waktu dibuat",
 *         example="2025-11-22T12:34:56Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Waktu terakhir diupdate",
 *         example="2025-11-22T12:34:56Z"
 *     )
 * )
 */

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

    public function memberGroups(): HasMany
    {
        return $this->hasMany(MemberGroup::class);
    }
}


