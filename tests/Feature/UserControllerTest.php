<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles and permissions
        $this->artisan('db:seed', ['--class' => 'PermissionSeeder']);
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    public function test_admin_can_list_users(): void
    {
        $admin = User::factory()->create();
        $admin->addRole('admin');
        
        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                            'roles'
                        ]
                    ],
                    'code'
                ]);
    }

    public function test_user_cannot_list_users(): void
    {
        $user = User::factory()->create();
        $user->addRole('user');
        
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->create();
        $admin->addRole('admin');
        
        Sanctum::actingAs($admin);

        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user'
        ];

        $response = $this->postJson('/api/v1/users', $userData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'roles'
                    ],
                    'code'
                ]);

        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email']
        ]);
    }

    public function test_user_cannot_create_user(): void
    {
        $user = User::factory()->create();
        $user->addRole('user');
        
        Sanctum::actingAs($user);

        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->postJson('/api/v1/users', $userData);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_user(): void
    {
        $admin = User::factory()->create();
        $admin->addRole('admin');
        
        $targetUser = User::factory()->create();
        
        Sanctum::actingAs($admin);

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ];

        $response = $this->putJson("/api/v1/users/{$targetUser->id}", $updateData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'email'
                    ],
                    'code'
                ]);

        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);
    }

    public function test_admin_can_delete_user(): void
    {
        $admin = User::factory()->create();
        $admin->addRole('admin');
        
        $targetUser = User::factory()->create();
        
        Sanctum::actingAs($admin);

        $response = $this->deleteJson("/api/v1/users/{$targetUser->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $targetUser->id]);
    }

    public function test_admin_can_assign_role_to_user(): void
    {
        $admin = User::factory()->create();
        $admin->addRole('admin');
        
        $targetUser = User::factory()->create();
        
        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/v1/users/{$targetUser->id}/assign-role", [
            'role' => 'moderator'
        ]);

        $response->assertStatus(200);
        
        $targetUser->refresh();
        $this->assertTrue($targetUser->hasRole('moderator'));
    }

    public function test_admin_can_remove_role_from_user(): void
    {
        $admin = User::factory()->create();
        $admin->addRole('admin');
        
        $targetUser = User::factory()->create();
        $targetUser->addRole('moderator');
        
        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/v1/users/{$targetUser->id}/remove-role", [
            'role' => 'moderator'
        ]);

        $response->assertStatus(200);
        
        $targetUser->refresh();
        $this->assertFalse($targetUser->hasRole('moderator'));
    }

    public function test_validation_errors_for_create_user(): void
    {
        $admin = User::factory()->create();
        $admin->addRole('admin');
        
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/v1/users', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_validation_errors_for_assign_role(): void
    {
        $admin = User::factory()->create();
        $admin->addRole('admin');
        
        $targetUser = User::factory()->create();
        
        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/v1/users/{$targetUser->id}/assign-role", []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['role']);
    }

    public function test_unauthenticated_user_cannot_access_endpoints(): void
    {
        $this->assertGuest();
        
        $response = $this->getJson('/api/v1/users');
        $response->assertStatus(401);

        $response = $this->postJson('/api/v1/users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'user'
        ]);
        $response->assertStatus(401);

        $response = $this->putJson('/api/v1/users/1', [
            'name' => 'Updated User',
            'email' => 'updated@example.com'
        ]);
        $response->assertStatus(401);

        $response = $this->deleteJson('/api/v1/users/1');
        $response->assertStatus(401);
    }
}
