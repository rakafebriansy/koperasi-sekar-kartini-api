<?php

namespace Tests\Feature;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MeetingControllerTest extends TestCase
{
    use RefreshDatabase;

    private $member;
    private $admin;

    public function setUp(): void
    {
        parent::setUp();

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
    public function index_displays_meetings()
    {
        $meeting = Meeting::create([
            'name' => 'Rapat Bulanan',
            'type' => 'group',
            'datetime' => now()->addDays(1),
            'location' => 'Ruang Meeting 1',
            'description' => 'Pembahasan agenda bulanan',
            'user_id' => $this->admin->id,
        ]);

        $response = $this->getJson('/api/meetings');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [['id' => $meeting->id]]
            ]);
    }

    /** @test */
    public function upcoming_displays_meetings_within_24_hours()
    {
        $past = Meeting::create([
            'name' => 'Rapat Lama',
            'type' => 'group',
            'datetime' => now()->subDays(1),
            'location' => 'Ruang Lama',
            'description' => 'Rapat sudah lewat',
            'user_id' => $this->admin->id,
        ]);

        $future = Meeting::create([
            'name' => 'Rapat Mendatang',
            'type' => 'coop',
            'datetime' => now()->addHours(12),
            'location' => 'Ruang Mendatang',
            'description' => 'Rapat akan datang',
            'user_id' => $this->admin->id,
        ]);

        $this->actingAs($this->member, 'sanctum');
        $response = $this->getJson('/api/meetings/upcoming');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [['id' => $future->id]]
            ]);
    }

    /** @test */
    public function store_creates_meeting()
    {
        Storage::fake('public');

        $payload = [
            'name' => 'Rapat Baru',
            'type' => 'group',
            'datetime' => '2025-12-30 10:00:00',
            'location' => 'Ruang Meeting 3',
            'description' => 'Agenda rapat baru',
            'photo' => UploadedFile::fake()->image('photo.jpg'),
        ];

        $response = $this->postJson('/api/meetings', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => ['name' => 'Rapat Baru']
            ]);

        $this->assertDatabaseHas('meetings', [
            'name' => 'Rapat Baru',
        ]);
    }

    /** @test */
    public function show_displays_single_meeting()
    {
        $meeting = Meeting::create([
            'name' => 'Rapat Penting',
            'type' => 'group',
            'datetime' => now()->addDays(2),
            'location' => 'Ruang Meeting 4',
            'description' => 'Detail rapat penting',
            'user_id' => $this->admin->id,
        ]);

        $response = $this->getJson("/api/meetings/{$meeting->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => ['id' => $meeting->id]
            ]);
    }

    /** @test */
    public function update_modifies_meeting()
    {
        $meeting = Meeting::create([
            'name' => 'Rapat Lama',
            'type' => 'group',
            'datetime' => now()->addDay(),
            'location' => 'Ruang Lama',
            'description' => 'Deskripsi lama',
            'user_id' => $this->admin->id,
        ]);

        $payload = [
            'name' => 'Rapat Diperbarui',
            'location' => 'Ruang Baru',
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/meetings/{$meeting->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Meeting updated successfully.',
            ]);

        $this->assertDatabaseHas('meetings', [
            'id' => $meeting->id,
            'name' => 'Rapat Diperbarui',
            'location' => 'Ruang Baru',
        ]);
    }



    /** @test */
    public function destroy_deletes_meeting()
    {
        $meeting = Meeting::create([
            'name' => 'Rapat Hapus',
            'type' => 'coop',
            'datetime' => '2025-12-22 14:00:00',
            'location' => 'Ruang Meeting Hapus',
            'description' => 'Rapat yang akan dihapus',
            'user_id' => $this->admin->id,
        ]);

        $response = $this->deleteJson("/api/meetings/{$meeting->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Meeting deleted successfully.'
            ]);
    }
}
