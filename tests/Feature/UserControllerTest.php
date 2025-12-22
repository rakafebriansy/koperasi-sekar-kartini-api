<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\User;
use App\Models\WorkArea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected WorkArea $workArea;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->workArea = WorkArea::create([
            'name' => 'Area Test',
        ]);

        $this->admin = User::create([
            'name' => 'Admin',
            'member_number' => 'ADM001',
            'identity_number' => 'IDADM001',
            'birth_date' => '1990-01-01',
            'phone_number' => '0800000001',
            'address' => 'Admin Address',
            'occupation' => 'Admin',
            'role' => 'admin',
            'is_active' => true,
            'password' => Hash::make('password'),
        ]);
    }

    /** @test */
    public function index_returns_users()
    {
        User::create([
            'name' => 'Member A',
            'member_number' => 'MBR001',
            'identity_number' => 'IDMBR001',
            'birth_date' => '2000-01-01',
            'phone_number' => '0800000002',
            'address' => 'Address',
            'occupation' => 'Worker',
            'role' => 'group_member',
            'is_active' => true,
            'password' => Hash::make('password'),
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function store_creates_user()
    {
        $payload = [
            'name' => 'User Baru',
            'member_number' => 'MBR002',
            'identity_number' => 'IDMBR002',
            'birth_date' => '2001-01-01',
            'phone_number' => '0800000003',
            'address' => 'Alamat',
            'occupation' => 'Karyawan',
            'role' => 'group_member',
            'password' => 'password123',
            'identity_card_photo' => UploadedFile::fake()->image('ktp.jpg'),
            'self_photo' => UploadedFile::fake()->image('self.jpg'),
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/users', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('users', [
            'member_number' => 'MBR002',
            'identity_number' => 'IDMBR002',
            'role' => 'group_member',
        ]);
    }

    /** @test */
    public function show_returns_user()
    {
        $user = User::create([
            'name' => 'User Show',
            'member_number' => 'MBR003',
            'identity_number' => 'IDMBR003',
            'birth_date' => '2002-01-01',
            'phone_number' => '0800000004',
            'address' => 'Alamat',
            'occupation' => 'Staff',
            'role' => 'group_member',
            'is_active' => true,
            'password' => Hash::make('password'),
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                ],
            ]);
    }

    /** @test */
    public function update_modifies_user()
    {
        $user = User::create([
            'name' => 'User Lama',
            'member_number' => 'MBR004',
            'identity_number' => 'IDMBR004',
            'birth_date' => '2003-01-01',
            'phone_number' => '0800000005',
            'address' => 'Alamat Lama',
            'occupation' => 'Staff',
            'role' => 'group_member',
            'is_active' => true,
            'password' => Hash::make('password'),
        ]);

        $payload = [
            'name' => 'User Diperbarui',
            'address' => 'Alamat Baru',
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/users/{$user->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'User Diperbarui',
            'address' => 'Alamat Baru',
        ]);
    }

    /** @test */
    public function destroy_deletes_user()
    {
        $user = User::create([
            'name' => 'User Hapus',
            'member_number' => 'MBR005',
            'identity_number' => 'IDMBR005',
            'birth_date' => '2004-01-01',
            'phone_number' => '0800000006',
            'address' => 'Alamat',
            'occupation' => 'Staff',
            'role' => 'group_member',
            'is_active' => true,
            'password' => Hash::make('password'),
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    /** @test */
    public function update_group_assigns_group_to_member()
    {
        $group = Group::create([
            'number' => 1,
            'description' => 'Lorem ipsum',
            'work_area_id' => $this->workArea->id
        ]);

        $member = User::create([
            'name' => 'Member',
            'member_number' => 'MBR006',
            'identity_number' => 'IDMBR006',
            'birth_date' => '2005-01-01',
            'phone_number' => '0800000007',
            'address' => 'Alamat',
            'occupation' => 'Staff',
            'role' => 'group_member',
            'is_active' => true,
            'password' => Hash::make('password'),
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->patchJson("/api/users/{$member->id}/groups/{$group->id}");

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $member->id,
            'group_id' => $group->id,
        ]);
    }

    /** @test */
    public function unlisted_members_returns_members_without_group()
    {
        User::create([
            'name' => 'Unlisted',
            'member_number' => 'MBR007',
            'identity_number' => 'IDMBR007',
            'birth_date' => '2006-01-01',
            'phone_number' => '0800000008',
            'address' => 'Alamat',
            'occupation' => 'Staff',
            'role' => 'group_member',
            'is_active' => true,
            'password' => Hash::make('password'),
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/unlisted-members');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    /** @test */
    public function inactive_members_returns_only_inactive_members()
    {
        User::create([
            'name' => 'Inactive',
            'member_number' => 'MBR008',
            'identity_number' => 'IDMBR008',
            'birth_date' => '2007-01-01',
            'phone_number' => '0800000009',
            'address' => 'Alamat',
            'occupation' => 'Staff',
            'role' => 'group_member',
            'is_active' => false,
            'password' => Hash::make('password'),
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/inactive-members');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    /** @test */
    public function activate_member_changes_status()
    {
        $member = User::create([
            'name' => 'Inactive Member',
            'member_number' => 'MBR009',
            'identity_number' => 'IDMBR009',
            'birth_date' => '2008-01-01',
            'phone_number' => '0800000010',
            'address' => 'Alamat',
            'occupation' => 'Staff',
            'role' => 'group_member',
            'is_active' => false,
            'password' => Hash::make('password'),
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->patchJson("/api/users/{$member->id}/activate", [
                'is_active' => true,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $member->id,
            'is_active' => true,
        ]);
    }
}
