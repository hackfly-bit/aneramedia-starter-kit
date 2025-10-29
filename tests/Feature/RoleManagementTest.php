<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleManagementTest extends TestCase
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
        $this->admin->refresh(); // Memuat roles yang baru ditambahkan
        
        $this->moderator = User::factory()->create();
        $this->moderator->addRole('moderator');
        $this->moderator->refresh(); // Memuat roles yang baru ditambahkan
        
        $this->user = User::factory()->create();
    }

    public function test_admin_can_list_all_roles()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/api/v1/roles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['id', 'name', 'display_name', 'description']
                ]
            ]);
    }

    public function test_admin_can_create_role()
    {
        $roleData = [
            'name' => 'editor',
            'display_name' => 'Editor',
            'description' => 'Can edit content'
        ];

        $response = $this->actingAs($this->admin)
            ->postJson('/api/v1/roles', $roleData);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Role created successfully'
            ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'editor'
        ]);
    }

    public function test_admin_can_view_role()
    {
        $role = Role::create([
            'name' => 'test-role',
            'display_name' => 'Test Role',
            'description' => 'Test role description'
        ]);

        $response = $this->actingAs($this->admin)
            ->getJson("/api/v1/roles/{$role->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'id' => $role->id,
                    'name' => 'test-role'
                ]
            ]);
    }

    public function test_admin_can_update_role()
    {
        $role = Role::create([
            'name' => 'old-role',
            'display_name' => 'Old Role',
            'description' => 'Old description'
        ]);

        $updateData = [
            'name' => 'new-role',
            'display_name' => 'New Role',
            'description' => 'New description'
        ];

        $response = $this->actingAs($this->admin)
            ->putJson("/api/v1/roles/{$role->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Role updated successfully'
            ]);

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'new-role'
        ]);
    }

    public function test_admin_can_delete_role()
    {
        $role = Role::create([
            'name' => 'delete-role',
            'display_name' => 'Delete Role',
            'description' => 'Role to be deleted'
        ]);

        $response = $this->actingAs($this->admin)
            ->deleteJson("/api/v1/roles/{$role->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Role deleted successfully'
            ]);

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id
        ]);
    }

    public function test_moderator_cannot_manage_roles()
    {
        $response = $this->actingAs($this->moderator)
            ->getJson('/api/v1/roles');

        $response->assertStatus(403);
    }

    public function test_cannot_delete_role_assigned_to_user()
    {
        $role = Role::create([
            'name' => 'user-role',
            'display_name' => 'User Role',
            'description' => 'Role assigned to user'
        ]);

        $this->user->addRole($role);

        $response = $this->actingAs($this->admin)
            ->deleteJson("/api/v1/roles/{$role->id}");

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'Cannot delete role that is still assigned to users'
            ]);
    }

    public function test_admin_can_assign_permission_to_role()
    {
        $role = Role::create([
            'name' => 'test-role',
            'display_name' => 'Test Role',
            'description' => 'Test role'
        ]);

        $permission = \App\Models\Permission::create([
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'description' => 'Test permission'
        ]);

        $response = $this->actingAs($this->admin)
            ->postJson("/api/v1/roles/{$role->id}/permissions/{$permission->id}", [
                'permission_id' => $permission->id
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Permission assigned to role successfully'
            ]);
    }

    public function test_admin_can_revoke_permission_from_role()
    {
        $role = Role::create([
            'name' => 'test-role',
            'display_name' => 'Test Role',
            'description' => 'Test role'
        ]);

        $permission = \App\Models\Permission::create([
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'description' => 'Test permission'
        ]);

        $role->permissions()->attach($permission);

        $response = $this->actingAs($this->admin)
            ->deleteJson("/api/v1/roles/{$role->id}/permissions/{$permission->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Permission revoked from role successfully'
            ]);
    }

    public function test_admin_can_sync_permissions_to_role()
    {
        $role = Role::create([
            'name' => 'test-role',
            'display_name' => 'Test Role',
            'description' => 'Test role'
        ]);

        $permission1 = \App\Models\Permission::create([
            'name' => 'permission-1',
            'display_name' => 'Permission 1',
            'description' => 'Permission 1'
        ]);

        $permission2 = \App\Models\Permission::create([
            'name' => 'permission-2',
            'display_name' => 'Permission 2',
            'description' => 'Permission 2'
        ]);

        $response = $this->actingAs($this->admin)
            ->putJson("/api/v1/roles/{$role->id}/permissions", [
                'permission_ids' => [$permission1->id, $permission2->id]
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Role permissions synced successfully'
            ]);
    }

    public function test_admin_can_get_role_permissions()
    {
        $role = Role::create([
            'name' => 'test-role',
            'display_name' => 'Test Role',
            'description' => 'Test role'
        ]);

        $permission = \App\Models\Permission::create([
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'description' => 'Test permission'
        ]);

        $role->permissions()->attach($permission);

        $response = $this->actingAs($this->admin)
            ->getJson("/api/v1/roles/{$role->id}/permissions");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Role permissions retrieved successfully'
            ]);
    }

    public function test_validation_fails_on_duplicate_role_name()
    {
        Role::create([
            'name' => 'existing-role',
            'display_name' => 'Existing Role',
            'description' => 'Existing role'
        ]);

        $response = $this->actingAs($this->admin)
            ->postJson('/api/v1/roles', [
                'name' => 'existing-role',
                'display_name' => 'New Role',
                'description' => 'New role'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_validation_fails_on_duplicate_role_name_on_update()
    {
        $role1 = Role::create([
            'name' => 'role-1',
            'display_name' => 'Role 1',
            'description' => 'Role 1'
        ]);

        $role2 = Role::create([
            'name' => 'role-2',
            'display_name' => 'Role 2',
            'description' => 'Role 2'
        ]);

        $response = $this->actingAs($this->admin)
            ->putJson("/api/v1/roles/{$role2->id}", [
                'name' => 'role-1',
                'display_name' => 'Updated Role',
                'description' => 'Updated role'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }
}
