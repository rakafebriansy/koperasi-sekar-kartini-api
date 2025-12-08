<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $workAreas = DB::table('work_areas')->pluck('id', 'name')->toArray();

        $users = [
            'name' => 'Rahmat Admin',
            'member_number' => 'ADM-001',
            'identity_number' => '3201111111110001',
            'birth_date' => '1988-03-12',
            'phone_number' => '081200000001',
            'address' => 'Jl. Anggur No. 10, Sumbersari, Jember',
            'occupation' => 'Administrator Sistem',
            'identity_card_photo' => 'uploads/ktp/rahmat_admin.jpg',
            'self_photo' => 'uploads/self/rahmat_admin.jpg',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'work_area_id' => $workAreas['Kecamatan Sumbersari'] ?? null,
            'group_id' => null,
            'remember_token' => Str::random(10),
            'created_at' => $now,
            'updated_at' => $now,
        ];

        DB::table('users')->insert($users);
    }
}
