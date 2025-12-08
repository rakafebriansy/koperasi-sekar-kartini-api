<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();


        $users = [
            [
                'name' => 'Siti Qomaria',
                'member_number' => 'EMP-101',
                'identity_number' => '3201111111110102',
                'birth_date' => '1992-06-05',
                'phone_number' => '081200000102',
                'address' => 'Jl. Kenanga No. 5, Patrang, Jember',
                'occupation' => 'Staf Lapangan',
            ],
            [
                'name' => 'Budi Santoso',
                'member_number' => 'EMP-102',
                'identity_number' => '3201111111110103',
                'birth_date' => '1990-11-20',
                'phone_number' => '081200000103',
                'address' => 'Jl. Mawar No. 7, Kaliwates, Jember',
                'occupation' => 'Petugas Kredit',
            ],
            [
                'name' => 'Dewi Hapsari',
                'member_number' => 'EMP-103',
                'identity_number' => '3201111111110104',
                'birth_date' => '1994-01-18',
                'phone_number' => '081200000104',
                'address' => 'Jl. Melati No. 3, Ambulu, Jember',
                'occupation' => 'Petugas Administrasi',
            ],
            [
                'name' => 'Andi Mania',
                'member_number' => 'EMP-104',
                'identity_number' => '3201111111110105',
                'birth_date' => '1989-09-02',
                'phone_number' => '081200000105',
                'address' => 'Jl. Teratai No. 2, Sumbersari, Jember',
                'occupation' => 'Koordinator Lapangan',
            ],
        ];

        $rows = [];

        foreach ($users as $emp) {
            $rows[] = [
                'name' => $emp['name'],
                'member_number' => $emp['member_number'],
                'identity_number' => $emp['identity_number'],
                'birth_date' => $emp['birth_date'],
                'phone_number' => $emp['phone_number'],
                'address' => $emp['address'],
                'occupation' => $emp['occupation'],
                'identity_card_photo' => 'uploads/ktp/' . Str::slug($emp['name']) . '.jpg',
                'self_photo' => 'uploads/self/' . Str::slug($emp['name']) . '.jpg',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'is_active' => true,
                'group_id' => null,
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('users')->insert($rows);
    }
}


