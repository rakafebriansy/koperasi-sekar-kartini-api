<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_download_a_file()
    {
        $filePath = 'test-files/sample.txt';
        $fullPath = storage_path('app/public/' . $filePath);

        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        file_put_contents($fullPath, 'Hello World');

        $response = $this->get("/api/download/$filePath");

        $response->assertStatus(200);

        $this->assertStringContainsString('text/plain', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment; filename=sample.txt', $response->headers->get('Content-Disposition'));
        $this->assertEquals(filesize($fullPath), $response->headers->get('Content-Length'));

        unlink($fullPath);
    }


    /** @test */
    public function it_returns_404_when_downloading_non_existent_file()
    {
        Storage::fake('public');

        $response = $this->get('/api/download/non-existent.txt');

        $response->assertStatus(404)
            ->assertJson(['error' => 'File not found']);
    }

    /** @test */
    public function it_can_show_a_file_inline()
    {
        $filePath = 'test-files/sample.txt';
        $fullPath = storage_path('app/public/' . $filePath);

        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        file_put_contents($fullPath, 'Hello Inline');

        $response = $this->get("/api/file/$filePath");

        $response->assertStatus(200);

        $this->assertStringContainsString('inline; filename="sample.txt"', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('text/plain', $response->headers->get('Content-Type'));

        unlink($fullPath);
    }


    /** @test */
    public function it_returns_404_when_showing_non_existent_file()
    {
        Storage::fake('public');

        $response = $this->get('/api/file/non-existent.pdf');

        $response->assertStatus(404)
            ->assertJson(['error' => 'File not found']);
    }
}
