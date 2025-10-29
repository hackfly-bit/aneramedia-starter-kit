<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate existing data
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('role_user')->truncate();
        DB::table('permission_user')->truncate();
        DB::table('permission_role')->truncate();

        // Create roles
        $adminRole = \App\Models\Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Full access to all system features'
        ]);

        $moderatorRole = \App\Models\Role::create([
            'name' => 'moderator',
            'display_name' => 'Moderator',
            'description' => 'Limited administrative access'
        ]);

        $userRole = \App\Models\Role::create([
            'name' => 'user',
            'display_name' => 'User',
            'description' => 'Regular user access'
        ]);

        // Create permissions
        $permissions = [
            // Authentication permissions
            ['name' => 'auth.me', 'display_name' => 'Access Profile', 'description' => 'Can access own profile information'],
            
            // Role permissions
            ['name' => 'create-roles', 'display_name' => 'Create Roles', 'description' => 'Can create new roles'],
            ['name' => 'read-roles', 'display_name' => 'Read Roles', 'description' => 'Can view roles'],
            ['name' => 'update-roles', 'display_name' => 'Update Roles', 'description' => 'Can update roles'],
            ['name' => 'delete-roles', 'display_name' => 'Delete Roles', 'description' => 'Can delete roles'],

            // Permission permissions
            ['name' => 'create-permissions', 'display_name' => 'Create Permissions', 'description' => 'Can create new permissions'],
            ['name' => 'read-permissions', 'display_name' => 'Read Permissions', 'description' => 'Can view permissions'],
            ['name' => 'update-permissions', 'display_name' => 'Update Permissions', 'description' => 'Can update permissions'],
            ['name' => 'delete-permissions', 'display_name' => 'Delete Permissions', 'description' => 'Can delete permissions'],
            
            // User permissions
            ['name' => 'create-users', 'display_name' => 'Create Users', 'description' => 'Can create new users'],
            ['name' => 'read-users', 'display_name' => 'Read Users', 'description' => 'Can view users'],
            ['name' => 'update-users', 'display_name' => 'Update Users', 'description' => 'Can update users'],
            ['name' => 'delete-users', 'display_name' => 'Delete Users', 'description' => 'Can delete users'],
            
            // Content permissions
            ['name' => 'create-content', 'display_name' => 'Create Content', 'description' => 'Can create content'],
            ['name' => 'read-content', 'display_name' => 'Read Content', 'description' => 'Can view content'],
            ['name' => 'update-content', 'display_name' => 'Update Content', 'description' => 'Can update content'],
            ['name' => 'delete-content', 'display_name' => 'Delete Content', 'description' => 'Can delete content'],
        ];

        foreach ($permissions as $permissionData) {
            $permission = \App\Models\Permission::create($permissionData);
            
            // Assign permissions to roles
            if (in_array($permissionData['name'], ['create-roles', 'read-roles', 'update-roles', 'delete-roles',
                'create-permissions', 'read-permissions', 'update-permissions', 'delete-permissions'])) {
                $adminRole->permissions()->attach($permission);
            }
            
            if (in_array($permissionData['name'], ['read-content', 'update-content'])) {
                $moderatorRole->permissions()->attach($permission);
            }
            
            if (in_array($permissionData['name'], ['read-content', 'auth.me'])) {
                $userRole->permissions()->attach($permission);
            }
            
            // Assign auth.me permission to all roles
            if ($permissionData['name'] === 'auth.me') {
                $adminRole->permissions()->attach($permission);
                $moderatorRole->permissions()->attach($permission);
            }
        }

        // Assign route-based permissions to admin role for ACL middleware
        $routePermissions = [
            'roles.index', 'roles.store', 'roles.show', 'roles.update', 'roles.destroy',
            'roles.permissions', 'roles.assign-permission', 'roles.revoke-permission', 'roles.sync-permissions',
            'permissions.index', 'permissions.store', 'permissions.show', 'permissions.update', 'permissions.destroy',
            'users.index', 'users.store', 'users.show', 'users.update', 'users.destroy',
            'users.assign-role', 'users.remove-role',
            'menus.index', 'menus.store', 'menus.show', 'menus.update', 'menus.destroy',
            'menu.user'
        ];

        foreach ($routePermissions as $routePermission) {
            $permission = \App\Models\Permission::firstOrCreate(
                ['name' => $routePermission],
                [
                    'display_name' => ucfirst(str_replace(['.', '-'], ' ', $routePermission)),
                    'description' => 'Access to ' . $routePermission . ' route'
                ]
            );
            $adminRole->permissions()->attach($permission);
        }
    }
}
