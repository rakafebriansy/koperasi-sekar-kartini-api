<?php

namespace Tests\Feature;

use App\Models\Savings;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavingsControllerTest extends TestCase
{
    use RefreshDatabase;

    private $member;
    private $admin;

    public function setUp(): void
    {
        parent::setUp();

        // Member user (read only)
        $this->member = User::create([
            'name' => 'Member Test',
            'identity_number' => '1234567890',
            'birth_date' => '1990-01-01',
            'phone_number' => '081234567890',
            'address' => 'Jl. Test No.1',
            'occupation' => 'Wiraswasta',
            'role' => 'group_member',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        // Admin user (full access)
        $this->admin = User::create([
            'name' => 'Admin Test',
            'identity_number' => '9876543210',
            'birth_date' => '1990-01-01',
            'phone_number' => '081234567891',
            'address' => 'Jl. Admin No.1',
            'occupation' => 'Admin',
            'role' => 'admin',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $this->actingAs($this->admin, 'sanctum');

    }


    /** @test */
    public function index_displays_savings()
    {
        $savings = Savings::create([
            'type' => 'simpanan_pokok',
            'nominal' => 100000,
            'year' => 2025,
            'month' => 12,
            'user_id' => $this->member->id,
        ]);

        $response = $this->getJson('/api/savings');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    [
                        'id' => $savings->id,
                        'type' => $savings->type,
                        'nominal' => $savings->nominal,
                    ]
                ]
            ]);
    }

    /** @test */
    public function store_creates_savings()
    {
        $payload = [
            'type' => 'simpanan_wajib',
            'nominal' => 50000,
            'year' => 2025,
            'month' => 12,
            'user_id' => $this->member->id,
        ];

        $response = $this->postJson('/api/savings', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'type' => 'simpanan_wajib',
                    'nominal' => 50000,
                ]
            ]);

        $this->assertDatabaseHas('savings', [
            'type' => 'simpanan_wajib',
            'nominal' => 50000,
            'user_id' => $this->member->id,
        ]);
    }

    /** @test */
    public function show_displays_single_savings()
    {
        $savings = Savings::create([
            'type' => 'simpanan_pokok',
            'nominal' => 100000,
            'year' => 2025,
            'month' => 12,
            'user_id' => $this->member->id,
        ]);

        $response = $this->getJson("/api/savings/{$savings->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $savings->id,
                    'type' => $savings->type,
                ]
            ]);
    }

    /** @test */
    public function update_modifies_savings()
    {
        $savings = Savings::create([
            'type' => 'simpanan_wajib',
            'nominal' => 50000,
            'year' => 2025,
            'month' => 12,
            'user_id' => $this->member->id,
        ]);

        $payload = [
            'nominal' => 75000,
        ];

        $response = $this->putJson("/api/savings/{$savings->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'nominal' => 75000,
                ]
            ]);

        $this->assertDatabaseHas('savings', [
            'id' => $savings->id,
            'nominal' => 75000,
        ]);
    }

    /** @test */
    public function destroy_deletes_savings()
    {
        $savings = Savings::create([
            'type' => 'simpanan_pokok',
            'nominal' => 100000,
            'year' => 2025,
            'month' => 12,
            'user_id' => $this->member->id,
        ]);

        $response = $this->deleteJson("/api/savings/{$savings->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Savings deleted successfully.',
            ]);

        $this->assertDatabaseMissing('savings', [
            'id' => $savings->id,
        ]);
    }

    /** @test */
    public function sum_by_month_returns_total()
    {
        Savings::create([
            'type' => 'simpanan_pokok',
            'nominal' => 100000,
            'year' => 2025,
            'month' => 12,
            'user_id' => $this->member->id,
        ]);

        $response = $this->getJson('/api/savings/sum-by-month?year=2025&month=12');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => 100000,
            ]);
    }

    /** @test */
    public function distribution_returns_grouped_data()
    {
        Savings::create([
            'type' => 'simpanan_pokok',
            'nominal' => 100000,
            'year' => 2025,
            'month' => 12,
            'user_id' => $this->member->id,
        ]);

        Savings::create([
            'type' => 'simpanan_wajib',
            'nominal' => 50000,
            'year' => 2025,
            'month' => 12,
            'user_id' => $this->member->id,
        ]);

        $response = $this->getJson('/api/savings-distribution-chart');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    ['type' => 'simpanan_pokok', 'total' => 100000],
                    ['type' => 'simpanan_wajib', 'total' => 50000],
                ]
            ]);
    }
}
