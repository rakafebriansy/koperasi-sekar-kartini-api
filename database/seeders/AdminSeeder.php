<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

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
    }
}
