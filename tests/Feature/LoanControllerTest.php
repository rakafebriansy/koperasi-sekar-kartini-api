<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class LoanControllerTest extends TestCase
{
    use RefreshDatabase;

    private $member;
    private $employee;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user manual sesuai spesifikasi
        $this->member = User::create([
            'name' => 'Member Test',
            'identity_number' => '1234567890',
            'birth_date' => '1990-01-01',
            'phone_number' => '081234567890',
            'address' => 'Jl. Test No.1',
            'occupation' => 'Wiraswasta',
            'password' => bcrypt('password'),
            'role' => 'group_member',
            'is_active' => true,
        ]);

        $this->employee = User::create([
            'name' => 'Employee Test',
            'identity_number' => '9876543210',
            'birth_date' => '1985-05-05',
            'phone_number' => '081298765432',
            'address' => 'Jl. Employee No.1',
            'occupation' => 'Staff',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'is_active' => true,
        ]);

        // Login sebagai employee untuk test auth
        $this->actingAs($this->employee, 'sanctum');
    }

    /** @test */
    public function index_returns_all_loans()
    {
        $loan = Loan::create([
            'type' => 'pinjaman_biasa',
            'nominal' => 100000,
            'year' => 2025,
            'month' => 12,
            'user_id' => $this->member->id,
        ]);

        $response = $this->getJson('/api/loans');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         [
                             'id' => $loan->id,
                             'type' => 'pinjaman_biasa',
                             'nominal' => 100000,
                         ]
                     ]
                 ]);
    }

    /** @test */
    public function store_creates_a_new_loan()
    {
        $payload = [
            'type' => 'pinjaman_biasa',
            'nominal' => 500000,
            'year' => 2025,
            'month' => 12,
            'user_id' => $this->member->id,
        ];

        $response = $this->postJson('/api/loans', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Loan created successfully.',
                     'data' => [
                         'type' => 'pinjaman_biasa',
                         'nominal' => 500000,
                     ]
                 ]);

        $this->assertDatabaseHas('loans', [
            'nominal' => 500000,
            'user_id' => $this->member->id,
        ]);
    }

    /** @test */
    public function show_displays_single_loan()
    {
        $loan = Loan::create([
            'type' => 'pinjaman_biasa',
            'nominal' => 200000,
            'year' => 2025,
            'month' => 12,
            'user_id' => $this->member->id,
        ]);

        $response = $this->getJson("/api/loans/{$loan->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'id' => $loan->id,
                         'type' => 'pinjaman_biasa',
                     ]
                 ]);
    }

    /** @test */
    public function update_modifies_a_loan()
    {
        $loan = Loan::create([
            'type' => 'pinjaman_biasa',
            'nominal' => 200000,
            'year' => 2025,
            'month' => 12,
            'user_id' => $this->member->id,
        ]);

        $payload = [
            'nominal' => 250000,
        ];

        $response = $this->putJson("/api/loans/{$loan->id}", $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Loan updated successfully.',
                     'data' => [
                         'nominal' => 250000,
                     ]
                 ]);

        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'nominal' => 250000,
        ]);
    }

    /** @test */
    public function destroy_deletes_a_loan()
    {
        $loan = Loan::create([
            'type' => 'pinjaman_biasa',
            'nominal' => 300000,
            'year' => 2025,
            'month' => 12,
            'user_id' => $this->member->id,
        ]);

        $response = $this->deleteJson("/api/loans/{$loan->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Loan deleted successfully.',
                 ]);

        $this->assertDatabaseMissing('loans', [
            'id' => $loan->id,
        ]);
    }

    /** @test */
    public function sum_by_month_returns_total_nominal()
    {
        Loan::create([
            'type' => 'pinjaman_biasa',
            'nominal' => 100000,
            'year' => 2025,
            'month' => 12,
            'user_id' => $this->member->id,
        ]);

        Loan::create([
            'type' => 'pinjaman_pengadaan_barang',
            'nominal' => 200000,
            'year' => 2025,
            'month' => 12,
            'user_id' => $this->member->id,
        ]);

        $response = $this->getJson('/api/loans/sum-by-month?year=2025&month=12');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => 300000,
                 ]);
    }

    /** @test */
    public function distribution_returns_grouped_data()
    {
        Loan::create([
            'type' => 'pinjaman_biasa',
            'nominal' => 100000,
            'year' => 2025,
            'month' => 12,
            'user_id' => $this->member->id,
        ]);

        Loan::create([
            'type' => 'pinjaman_pengadaan_barang',
            'nominal' => 200000,
            'year' => 2025,
            'month' => 12,
            'user_id' => $this->member->id,
        ]);

        $response = $this->getJson('/api/loan-distribution-chart');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         ['type' => 'pinjaman_biasa', 'total' => 100000],
                         ['type' => 'pinjaman_pengadaan_barang', 'total' => 200000],
                     ],
                 ]);
    }
}
