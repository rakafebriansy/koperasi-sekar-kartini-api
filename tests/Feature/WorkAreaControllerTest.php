<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WorkArea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkAreaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    /** @test */
    public function it_can_list_all_work_areas()
    {
        $response = $this->getJson('/api/work-areas');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'name', 'created_at', 'updated_at']
                ]
            ]);
    }

    /** @test */
    public function it_can_create_a_work_area()
    {
        $user = User::first();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/work-areas', [
            'name' => 'Wilayah Bandung'
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Work area created successfully.',
            ]);

        $this->assertDatabaseHas('work_areas', [
            'name' => 'Wilayah Bandung'
        ]);
    }

    /** @test */
    public function it_validates_work_area_name_on_create()
    {
        $user = User::first();
        $this->actingAs($user, 'sanctum');

        $existing = WorkArea::first();

        $response = $this->postJson('/api/work-areas', [
            'name' => $existing->name
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }


    /** @test */
    public function it_cannot_delete_work_area_in_use()
    {
        $user = User::first();
        $this->actingAs($user, 'sanctum');

        $workArea = WorkArea::first();

        $response = $this->deleteJson("/api/work-areas/{$workArea->id}");

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Wilayah kerja tidak dapat dihapus karena masih digunakan.',
            ]);
    }

    /** @test */
    public function it_can_delete_work_area_not_in_use()
    {
        $user = User::first();
        $this->actingAs($user, 'sanctum');

        $workArea = WorkArea::whereDoesntHave('users')->whereDoesntHave('groups')->first();

        $response = $this->deleteJson("/api/work-areas/{$workArea->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Work area deleted successfully.',
            ]);

        $this->assertDatabaseMissing('work_areas', ['id' => $workArea->id]);
    }


    /** @test */
    public function it_can_show_a_work_area()
    {
        $user = User::first();
        $this->actingAs($user, 'sanctum');

        $workArea = WorkArea::first();

        $response = $this->getJson("/api/work-areas/{$workArea->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $workArea->id,
                    'name' => $workArea->name,
                ],
            ]);
    }

    /** @test */
    public function it_returns_404_when_work_area_not_found()
    {
        $user = User::first();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/work-areas/9999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Work area not found.',
            ]);
    }

    /** @test */
    public function it_can_update_a_work_area()
    {
        $user = User::first();
        $this->actingAs($user, 'sanctum'); 

        $workArea = WorkArea::first();

        $response = $this->patchJson("/api/work-areas/{$workArea->id}", [
            'name' => 'Updated Name'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Work area updated successfully.',
                'data' => [
                    'id' => $workArea->id,
                    'name' => 'Updated Name',
                ]
            ]);
    }
}

