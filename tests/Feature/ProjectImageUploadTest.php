<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProjectImageUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_can_be_created_with_uploaded_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->post('/api/projects', [
            'title' => 'Test Project',
            'description' => 'Test description',
            'tech_stack' => 'Laravel',
            'image' => UploadedFile::fake()->create('project.jpg', 50, 'image/jpeg'),
        ]);

        $response->assertCreated();
        $imageUrl = $response->json('image_url');
        $this->assertIsString($imageUrl);
        $this->assertStringStartsWith('/storage/projects/', $imageUrl);

        $imageSrc = $response->json('image_src');
        $this->assertIsString($imageSrc);
        $this->assertStringStartsWith('http://localhost/storage/projects/', $imageSrc);

        $storedPath = ltrim(substr($imageUrl, strlen('/storage/')), '/');
        Storage::disk('public')->assertExists($storedPath);
    }
}
