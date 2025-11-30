<?php

namespace Database\Seeders;

use App\Models\MemberGroup;
use App\Models\WorkArea;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $groups = MemberGroup::pluck('id')->toArray();
        $workAreas = WorkArea::pluck('id')->toArray();

        DB::table('users')->insert([
            [
                'name' => 'Budi Anggota',
                'member_number' => 'GRP-001',
                'identity_number' => '3201123456789003',
                'birth_date' => '2000-01-20',
                'phone_number' => '087712345678',
                'address' => 'Jl. Melati No. 3, Jember',
                'occupation' => 'Petani',
                'identity_card_photo' => null,
                'self_photo' => null,
                'email_verified_at' => null,
                'password' => Hash::make('password'),
                'role' => 'group_member',
                'is_verified' => true,
                'is_active' => true,
                'work_area_id' => $workAreas[array_rand($workAreas)] ?? null,
                'group_id' => $groups[array_rand($groups)] ?? null,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
