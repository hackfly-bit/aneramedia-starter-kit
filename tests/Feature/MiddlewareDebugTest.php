<?php

namespace Tests\Feature;

use Tests\TestCase;

class MiddlewareDebugTest extends TestCase
{
    public function test_no_auth_route_works(): void
    {
        $response = $this->getJson('/api/test-no-auth');
        $response->assertStatus(200);
        $response->assertJson(['message' => 'No auth required']);
    }

    public function test_auth_route_requires_authentication(): void
    {
        $response = $this->getJson('/api/test-auth');
        $response->assertStatus(401);
    }
}