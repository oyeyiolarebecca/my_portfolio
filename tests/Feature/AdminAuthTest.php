<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_logout_with_sanctum_token(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $loginResponse = $this->postJson('/api/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $loginResponse->assertOk();
        $token = $loginResponse->json('token');
        $this->assertIsString($token);
        $this->assertNotEmpty($token);

        $logoutResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/admin/logout');

        $logoutResponse->assertOk()
            ->assertJson(['message' => 'Logged out']);
    }
}

