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

        $workAreas = DB::table('work_areas')->pluck('id', 'name_work_area')->toArray();

        $employees = [
            [
                'name' => 'Siti Karyawan',
                'member_number' => 'EMP-001',
                'identity_number' => '3201111111110002',
                'birth_date' => '1992-06-05',
                'phone_number' => '081200000002',
                'address' => 'Jl. Kenanga No. 5, Patrang, Jember',
                'occupation' => 'Staf Lapangan',
                'work_area_key' => 'Kecamatan Patrang',
            ],
            [
                'name' => 'Budi Karyawan',
                'member_number' => 'EMP-002',
                'identity_number' => '3201111111110003',
                'birth_date' => '1990-11-20',
                'phone_number' => '081200000003',
                'address' => 'Jl. Mawar No. 7, Kaliwates, Jember',
                'occupation' => 'Petugas Kredit',
                'work_area_key' => 'Kecamatan Kaliwates',
            ],
            [
                'name' => 'Dewi Karyawan',
                'member_number' => 'EMP-003',
                'identity_number' => '3201111111110004',
                'birth_date' => '1994-01-18',
                'phone_number' => '081200000004',
                'address' => 'Jl. Melati No. 3, Ambulu, Jember',
                'occupation' => 'Petugas Administrasi',
                'work_area_key' => 'Kecamatan Ambulu',
            ],
            [
                'name' => 'Andi Karyawan',
                'member_number' => 'EMP-004',
                'identity_number' => '3201111111110005',
                'birth_date' => '1989-09-02',
                'phone_number' => '081200000005',
                'address' => 'Jl. Teratai No. 2, Sumbersari, Jember',
                'occupation' => 'Koordinator Lapangan',
                'work_area_key' => 'Kecamatan Sumbersari',
            ],
        ];

        $rows = [];

        foreach ($employees as $emp) {
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
                'is_verified' => true,
                'is_active' => true,
                'work_area_id' => $workAreas[$emp['work_area_key']] ?? null,
                'group_id' => null,
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('users')->insert($rows);
    }
}


