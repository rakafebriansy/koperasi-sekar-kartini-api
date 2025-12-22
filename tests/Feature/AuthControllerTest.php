<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WorkArea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Jalankan seeder WorkArea agar work_area_id valid
        $this->seed(\WorkAreaSeeder::class);
    }

    /** @test */
    public function user_can_register()
    {
        Storage::fake('public');

        $workArea = WorkArea::first(); // ambil work_area dari seeder

        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'identity_number' => '1234567890',
            'birth_date' => '1990-01-01',
            'phone_number' => '08123456789',
            'address' => 'Jl. Contoh No.1',
            'occupation' => 'Developer',
            'identity_card_photo' => UploadedFile::fake()->image('ktp.jpg'),
            'self_photo' => UploadedFile::fake()->image('self.jpg'),
            'password' => 'password123',
            'work_area_id' => $workArea->id,
        ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('users', [
            'phone_number' => '08123456789',
            'identity_number' => '1234567890',
        ]);
    }

    /** @test */
    public function user_cannot_register_with_existing_phone_or_identity_number()
    {
        $workArea = WorkArea::first();

        User::create([
            'name' => 'Existing User',
            'phone_number' => '08123456789',
            'identity_number' => '1234567890',
            'birth_date' => '1990-01-01',
            'address' => 'Jl. Test',
            'occupation' => 'Tester',
            'password' => Hash::make('password123'),
            'role' => 'group_member',
            'is_active' => true,
            'work_area_id' => $workArea->id,
        ]);

        Storage::fake('public');

        $response = $this->postJson('/api/register', [
            'name' => 'Jane Doe',
            'identity_number' => '1234567890', // duplicate
            'birth_date' => '1990-01-01',
            'phone_number' => '08123456789',  // duplicate
            'address' => 'Jl. Contoh No.2',
            'occupation' => 'Designer',
            'identity_card_photo' => UploadedFile::fake()->image('ktp2.jpg'),
            'self_photo' => UploadedFile::fake()->image('self2.jpg'),
            'password' => 'password123',
            'work_area_id' => $workArea->id,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['phone_number', 'identity_number']);
    }

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        $workArea = WorkArea::first();

        $user = User::create([
            'name' => 'Login User',
            'phone_number' => '08123456789',
            'identity_number' => '1234567891',
            'birth_date' => '1990-01-01',
            'address' => 'Jl. Login',
            'occupation' => 'Developer',
            'password' => Hash::make('password123'),
            'role' => 'group_member',
            'is_active' => true,
            'work_area_id' => $workArea->id,
        ]);

        $response = $this->postJson('/api/login', [
            'phone_number' => '08123456789',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true])
                 ->assertJsonStructure([
                     'success',
                     'token',
                     'data' => [
                         'id',
                         'name',
                         'phone_number',
                         'identity_number',
                         'role',
                         'is_active',
                     ]
                 ]);
    }

    /** @test */
    public function inactive_user_cannot_login()
    {
        $workArea = WorkArea::first();

        $user = User::create([
            'name' => 'Inactive User',
            'phone_number' => '08123456789',
            'identity_number' => '1234567892',
            'birth_date' => '1990-01-01',
            'address' => 'Jl. Test',
            'occupation' => 'Tester',
            'password' => Hash::make('password123'),
            'role' => 'group_member',
            'is_active' => false,
            'work_area_id' => $workArea->id,
        ]);

        $response = $this->postJson('/api/login', [
            'phone_number' => '08123456789',
            'password' => 'password123',
        ]);

        $response->assertStatus(403)
                 ->assertJson(['success' => false]);
    }

    /** @test */
    public function user_can_logout()
    {
        $workArea = WorkArea::first();

        $user = User::create([
            'name' => 'Logout User',
            'phone_number' => '08123456789',
            'identity_number' => '1234567893',
            'birth_date' => '1990-01-01',
            'address' => 'Jl. Logout',
            'occupation' => 'Developer',
            'password' => Hash::make('password123'),
            'role' => 'group_member',
            'is_active' => true,
            'work_area_id' => $workArea->id,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);
    }

    /** @test */
    public function user_can_refresh_token()
    {
        $workArea = WorkArea::first();

        $user = User::create([
            'name' => 'Refresh User',
            'phone_number' => '08123456789',
            'identity_number' => '1234567894',
            'birth_date' => '1990-01-01',
            'address' => 'Jl. Refresh',
            'occupation' => 'Developer',
            'password' => Hash::make('password123'),
            'role' => 'group_member',
            'is_active' => true,
            'work_area_id' => $workArea->id,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/refresh');

        $response->assertStatus(200)
                 ->assertJson(['success' => true])
                 ->assertJsonStructure([
                     'success',
                     'token',
                     'data' => [
                         'id',
                         'name',
                         'phone_number',
                         'identity_number',
                         'role',
                         'is_active',
                     ]
                 ]);
    }

    /** @test */
    public function user_can_store_fcm_token()
    {
        $workArea = WorkArea::first();

        $user = User::create([
            'name' => 'FCM User',
            'phone_number' => '08123456789',
            'identity_number' => '1234567895',
            'birth_date' => '1990-01-01',
            'address' => 'Jl. FCM',
            'occupation' => 'Developer',
            'password' => Hash::make('password123'),
            'role' => 'group_member',
            'is_active' => true,
            'work_area_id' => $workArea->id,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson('/api/refresh-fcm-token', [
            'fcm_token' => 'dummy_fcm_token_123'
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'fcm_token' => 'dummy_fcm_token_123',
        ]);
    }
}
