<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Rahmat Admin',
            'member_number' => 'ADM-001',
            'identity_number' => '3201123456789001',
            'birth_date' => '1990-05-15',
            'address' => 'Jl. Angur No. 1, Jember',
            'phone_number' => '081234567890',
            'occupation' => 'System Administrator',
            'role' => 'admin',
            'is_verified' => true,
            'is_active' => true,
            'password' => Hash::make('admin123'),
        ]);
        DB::table('users')->insert([
           [
                'name' => 'Rahmat Admin',
                'member_number' => 'ADM-002',
                'identity_number' => '3201553456789001',
                'birth_date' => '1990-05-15',
                'phone_number' => '081234567870',
                'address' => 'Jl. Angur No. 1, Jember',
                'occupation' => 'System Administrator',
                'identity_card_photo' => null,
                'self_photo' => null,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_verified' => true,
                'is_active' => true,
                'work_area_id' => null,
                'group_id' => null,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Siti Employee',
                'member_number' => 'EMP-001',
                'identity_number' => '3201123456789002',
                'birth_date' => '1995-08-10',
                'phone_number' => '089876543210',
                'address' => 'Jl. Kenanga No. 5, Jember',
                'occupation' => 'Staff',
                'identity_card_photo' => null,
                'self_photo' => null,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'employee',
                'is_verified' => true,
                'is_active' => true,
                'work_area_id' => null,
                'group_id' => null,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
