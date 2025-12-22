<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\User;
use App\Models\WorkArea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupControllerTest extends TestCase
{
    use RefreshDatabase;

    private $workArea;
    private $member;
    private $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->workArea = WorkArea::create([
            'name' => 'Area Test',
        ]);

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

        $this->actingAs($this->employee, 'sanctum');
    }

    /** @test */
    public function index_displays_groups()
    {
        $group = Group::create([
            'number' => 1,
            'description' => 'Deskripsi Test',
            'work_area_id' => $this->workArea->id,
        ]);

        $response = $this->getJson('/api/groups');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    [
                        'id' => $group->id,
                        'number' => $group->number,
                        'description' => $group->description,
                    ]
                ]
            ]);
    }

    /** @test */
    public function store_creates_group()
    {
        $payload = [
            'number' => 2,
            'description' => 'Deskripsi Baru',
            'work_area_id' => $this->workArea->id,
        ];

        $response = $this->postJson('/api/groups', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'number' => 2,
                    'description' => 'Deskripsi Baru',
                ]
            ]);

        $this->assertDatabaseHas('groups', [
            'number' => 2,
            'description' => 'Deskripsi Baru',
        ]);
    }

    /** @test */
    public function show_displays_single_group()
    {
        $group = Group::create([
            'number' => 3,
            'description' => 'Deskripsi Show',
            'work_area_id' => $this->workArea->id,
        ]);

        $response = $this->getJson("/api/groups/{$group->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $group->id,
                    'number' => $group->number,
                    'description' => $group->description,
                ]
            ]);
    }

    /** @test */
    public function update_modifies_group()
    {
        $group = Group::create([
            'number' => 4,
            'description' => 'Deskripsi Lama',
            'work_area_id' => $this->workArea->id,
        ]);

        $payload = [
            'description' => 'Deskripsi Baru',
        ];

        $response = $this->putJson("/api/groups/{$group->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'description' => 'Deskripsi Baru',
                ]
            ]);

        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'description' => 'Deskripsi Baru',
        ]);
    }

    /** @test */
    public function destroy_deletes_group()
    {
        $group = Group::create([
            'number' => 5,
            'description' => 'Deskripsi Hapus',
            'work_area_id' => $this->workArea->id,
        ]);

        $response = $this->deleteJson("/api/groups/{$group->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Group deleted successfully.',
            ]);

        $this->assertDatabaseMissing('groups', [
            'id' => $group->id,
        ]);
    }

    /** @test */
    public function update_chairman_success()
    {
        $group = Group::create([
            'number' => 6,
            'description' => 'Deskripsi Ketua',
            'work_area_id' => $this->workArea->id,
        ]);

        $response = $this->patchJson("/api/groups/{$group->id}/chairman/{$this->member->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'chairman_id' => $this->member->id,
        ]);
    }

    /** @test */
    public function update_facilitator_success()
    {
        $group = Group::create([
            'number' => 7,
            'description' => 'Deskripsi Fasilitator',
            'work_area_id' => $this->workArea->id,
        ]);

        $response = $this->patchJson("/api/groups/{$group->id}/facilitator/{$this->employee->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'facilitator_id' => $this->employee->id,
        ]);
    }

    /** @test */
    public function update_fund_amount_success()
    {
        $group = Group::create([
            'number' => 8,
            'description' => 'Deskripsi Dana',
            'work_area_id' => $this->workArea->id,
        ]);

        $payload = [
            'fund_type' => 'kas_tanggung_renteng',
            'fund_amount' => 100000,
        ];

        $response = $this->patchJson("/api/groups/{$group->id}/update-fund-amount", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'shared_liability_fund_amount' => 100000,
        ]);
    }
}
