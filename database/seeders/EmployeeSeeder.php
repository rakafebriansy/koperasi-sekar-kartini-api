<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('users')->insert([
            [
                'name' => 'Ahmad Fauzi',
                'member_number' => null,
                'identity_number' => '3509011501900001',
                'birth_date' => '1990-01-15',
                'phone_number' => '081234567801',
                'address' => 'Jl. Letjen Panjaitan, Jember',
                'occupation' => 'Staff Administrasi',
                'identity_card_photo' => null,
                'self_photo' => null,
                'member_card_photo' => null,
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
                'role' => 'employee',
                'is_active' => true,
                'work_area_id' => null,
                'group_id' => null,
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Dewi Lestari',
                'member_number' => null,
                'identity_number' => '3509014202920002',
                'birth_date' => '1992-02-02',
                'phone_number' => '081234567802',
                'address' => 'Jl. Kaliurang, Jember',
                'occupation' => 'Petugas Lapangan',
                'identity_card_photo' => null,
                'self_photo' => null,
                'member_card_photo' => null,
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
                'role' => 'employee',
                'is_active' => true,
                'work_area_id' => null,
                'group_id' => null,
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Rizky Pratama',
                'member_number' => null,
                'identity_number' => '3509011006950003',
                'birth_date' => '1995-06-10',
                'phone_number' => '081234567803',
                'address' => 'Jl. Mastrip, Jember',
                'occupation' => 'Operator Data',
                'identity_card_photo' => null,
                'self_photo' => null,
                'member_card_photo' => null,
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
                'role' => 'employee',
                'is_active' => true,
                'work_area_id' => null,
                'group_id' => null,
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Siti Nurhaliza',
                'member_number' => null,
                'identity_number' => '3509010808890004',
                'birth_date' => '1989-08-08',
                'phone_number' => '081234567804',
                'address' => 'Jl. Trunojoyo, Jember',
                'occupation' => 'Sekretaris',
                'identity_card_photo' => null,
                'self_photo' => null,
                'member_card_photo' => null,
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
                'role' => 'employee',
                'is_active' => true,
                'work_area_id' => null,
                'group_id' => null,
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Budi Santoso',
                'member_number' => null,
                'identity_number' => '3509010303870005',
                'birth_date' => '1987-03-03',
                'phone_number' => '081234567805',
                'address' => 'Jl. Gajah Mada, Jember',
                'occupation' => 'Koordinator Lapangan',
                'identity_card_photo' => null,
                'self_photo' => null,
                'member_card_photo' => null,
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
                'role' => 'employee',
                'is_active' => true,
                'work_area_id' => null,
                'group_id' => null,
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}


