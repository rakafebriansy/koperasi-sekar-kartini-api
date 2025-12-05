<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'member_number',
        'identity_number',
        'birth_date',
        'phone_number',
        'address',
        'occupation',
        'member_card_photo',
        'identity_card_photo',
        'self_photo',
        'password',
        'role',
        'is_verified',
        'is_active',
        'work_area_id',
        'group_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'date',
            'password' => 'hashed',
            'is_verified' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function workArea()
    {
        return $this->belongsTo(WorkArea::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function meetings()
    {
        return $this->belongsToMany(Meeting::class, 'users_meetings')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function savings()
    {
        return $this->hasMany(Saving::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
