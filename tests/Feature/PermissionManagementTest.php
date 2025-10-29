<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $moderator;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed(\Database\Seeders\LaratrustSeeder::class);
        
        $this->admin = User::factory()->create();
        $this->admin->addRole('admin');
        
        $this->moderator = User::factory()->create();
        $this->moderator->addRole('moderator');
        
        $this->user = User::factory()->create();
    }

    public function test_admin_can_list_all_permissions()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/v1/permissions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'data' => [
                        '*' => ['id', 'name', 'display_name', 'description']
                    ]
                ]
            ]);
    }

    public function test_admin_can_create_permission()
    {
        $permissionData = [
            'name' => 'create-posts',
            'display_name' => 'Create Posts',
            'description' => 'Can create new posts'
        ];

        $response = $this->actingAs($this->admin)
            ->postJson('/api/v1/permissions', $permissionData);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Permission created successfully'
            ]);

        $this->assertDatabaseHas('permissions', [
            'name' => 'create-posts'
        ]);
    }

    public function test_admin_can_view_permission()
    {
        $permission = Permission::create([
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'description' => 'Test permission description'
        ]);

        $response = $this->actingAs($this->admin)
            ->getJson("/api/v1/permissions/{$permission->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'id' => $permission->id,
                    'name' => 'test-permission'
                ]
            ]);
    }

    public function test_admin_can_update_permission()
    {
        $permission = Permission::create([
            'name' => 'old-permission',
            'display_name' => 'Old Permission',
            'description' => 'Old description'
        ]);

        $updateData = [
            'name' => 'new-permission',
            'display_name' => 'New Permission',
            'description' => 'New description'
        ];

        $response = $this->actingAs($this->admin)
            ->putJson("/api/v1/permissions/{$permission->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Permission updated successfully'
            ]);

        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id,
            'name' => 'new-permission'
        ]);
    }

    public function test_admin_can_delete_permission()
    {
        $permission = Permission::create([
            'name' => 'delete-permission',
            'display_name' => 'Delete Permission',
            'description' => 'Permission to be deleted'
        ]);

        $response = $this->actingAs($this->admin)
            ->deleteJson("/api/v1/permissions/{$permission->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Permission deleted successfully'
            ]);

        $this->assertDatabaseMissing('permissions', [
            'id' => $permission->id
        ]);
    }

    public function test_moderator_cannot_manage_permissions()
    {
        $response = $this->actingAs($this->moderator)
            ->getJson('/api/v1/permissions');

        $response->assertStatus(403);
    }

    public function test_cannot_delete_permission_assigned_to_roles_or_users()
    {
        $permission = Permission::create([
            'name' => 'assigned-permission',
            'display_name' => 'Assigned Permission',
            'description' => 'Permission assigned to role'
        ]);

        $role = Role::create([
            'name' => 'test-role',
            'display_name' => 'Test Role',
            'description' => 'Test role'
        ]);

        $role->permissions()->attach($permission);

        $response = $this->actingAs($this->admin)
            ->deleteJson("/api/v1/permissions/{$permission->id}");

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'Cannot delete permission that is still assigned to roles or users'
            ]);
    }

    public function test_can_search_permissions_by_name()
    {
        Permission::create([
            'name' => 'create-articles',
            'display_name' => 'Create Articles',
            'description' => 'Can create articles'
        ]);

        Permission::create([
            'name' => 'edit-posts',
            'display_name' => 'Edit Posts',
            'description' => 'Can edit posts'
        ]);

        $response = $this->actingAs($this->admin)
            ->getJson('/api/v1/permissions?search=articles');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.name', 'create-articles');
    }

    public function test_can_search_permissions_by_display_name()
    {
        Permission::create([
            'name' => 'manage-settings',
            'display_name' => 'Manage Settings',
            'description' => 'Can manage settings'
        ]);

        Permission::create([
            'name' => 'view-analytics',
            'display_name' => 'View Analytics',
            'description' => 'Can view analytics'
        ]);

        $response = $this->actingAs($this->admin)
            ->getJson('/api/v1/permissions?search=Settings');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.display_name', 'Manage Settings');
    }

    public function test_validation_fails_on_duplicate_permission_name()
    {
        Permission::create([
            'name' => 'existing-permission',
            'display_name' => 'Existing Permission',
            'description' => 'Existing permission'
        ]);

        $response = $this->actingAs($this->admin)
            ->postJson('/api/v1/permissions', [
                'name' => 'existing-permission',
                'display_name' => 'New Permission',
                'description' => 'New permission'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_validation_fails_on_duplicate_permission_name_on_update()
    {
        $permission1 = Permission::create([
            'name' => 'permission-1',
            'display_name' => 'Permission 1',
            'description' => 'Permission 1'
        ]);

        $permission2 = Permission::create([
            'name' => 'permission-2',
            'display_name' => 'Permission 2',
            'description' => 'Permission 2'
        ]);

        $response = $this->actingAs($this->admin)
            ->putJson("/api/v1/permissions/{$permission2->id}", [
                'name' => 'permission-1',
                'display_name' => 'Updated Permission',
                'description' => 'Updated permission'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_validation_fails_on_missing_required_fields()
    {
        $response = $this->actingAs($this->admin)
            ->postJson('/api/v1/permissions', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_validation_fails_on_invalid_data_types()
    {
        $response = $this->actingAs($this->admin)
            ->postJson('/api/v1/permissions', [
                'name' => 123,
                'display_name' => ['invalid'],
                'description' => ['invalid']
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'display_name', 'description']);
    }
}
