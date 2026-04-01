<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProjectValidationNormalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_create_accepts_empty_string_optional_urls(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/projects', [
            'title' => 'Test Project',
            'description' => 'Test description',
            'tech_stack' => 'Laravel',
            'live_url' => '',
            'github_url' => '',
            'image_url' => '',
        ]);

        $response->assertCreated();

        $projectId = $response->json('id');
        $project = Project::query()->findOrFail($projectId);

        $this->assertNull($project->live_url);
        $this->assertNull($project->github_url);
        $this->assertNull($project->image_url);
    }

    public function test_project_create_accepts_array_tech_stack(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/projects', [
            'title' => 'Test Project',
            'description' => 'Test description',
            'tech_stack' => ['Laravel', 'React'],
        ]);

        $response->assertCreated();
        $this->assertSame('Laravel, React', $response->json('tech_stack'));
    }

    public function test_project_create_accepts_camel_case_fields(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/projects', [
            'title' => 'Test Project',
            'description' => 'Test description',
            'techStack' => ['Laravel', 'React'],
            'githubUrl' => '',
            'liveUrl' => '',
        ]);

        $response->assertCreated();
        $this->assertSame('Laravel, React', $response->json('tech_stack'));
        $this->assertNull($response->json('github_url'));
        $this->assertNull($response->json('live_url'));
    }

    public function test_project_create_accepts_relative_image_url(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/projects', [
            'title' => 'Test Project',
            'description' => 'Test description',
            'tech_stack' => 'Laravel',
            'image_url' => '/storage/projects/example.webp',
        ]);

        $response->assertCreated();
        $this->assertSame('/storage/projects/example.webp', $response->json('image_url'));
        $this->assertSame('http://localhost/storage/projects/example.webp', $response->json('image_src'));
    }
}
