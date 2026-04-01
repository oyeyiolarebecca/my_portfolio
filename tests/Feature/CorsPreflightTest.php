<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CorsPreflightTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_preflight_includes_cors_headers(): void
    {
        $response = $this->withHeaders([
            'Origin' => 'http://localhost:5173',
            'Access-Control-Request-Method' => 'POST',
            'Access-Control-Request-Headers' => 'Content-Type, Authorization',
        ])->options('/api/projects');

        $response->assertNoContent();
        $this->assertSame('http://localhost:5173', $response->headers->get('Access-Control-Allow-Origin'));
    }
}
