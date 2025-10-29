<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates comprehensive roles and permissions for the application
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Full system access with all permissions'
        ]);

        $moderatorRole = Role::firstOrCreate([
            'name' => 'moderator',
            'display_name' => 'Moderator',
            'description' => 'Content management and user moderation'
        ]);

        $userRole = Role::firstOrCreate([
            'name' => 'user',
            'display_name' => 'User',
            'description' => 'Basic user with limited permissions'
        ]);

        // Create comprehensive permissions
        $permissions = [
            // User management permissions
            ['name' => 'users-list', 'display_name' => 'List Users', 'description' => 'View list of users'],
            ['name' => 'users-create', 'display_name' => 'Create User', 'description' => 'Create new users'],
            ['name' => 'users-edit', 'display_name' => 'Edit User', 'description' => 'Edit existing users'],
            ['name' => 'users-delete', 'display_name' => 'Delete User', 'description' => 'Delete users'],
            
            // Role management permissions
            ['name' => 'roles-list', 'display_name' => 'List Roles', 'description' => 'View list of roles'],
            ['name' => 'roles-create', 'display_name' => 'Create Role', 'description' => 'Create new roles'],
            ['name' => 'roles-edit', 'display_name' => 'Edit Role', 'description' => 'Edit existing roles'],
            ['name' => 'roles-delete', 'display_name' => 'Delete Role', 'description' => 'Delete roles'],
            
            // Permission management permissions
            ['name' => 'permissions-list', 'display_name' => 'List Permissions', 'description' => 'View list of permissions'],
            ['name' => 'permissions-create', 'display_name' => 'Create Permission', 'description' => 'Create new permissions'],
            ['name' => 'permissions-edit', 'display_name' => 'Edit Permission', 'description' => 'Edit existing permissions'],
            ['name' => 'permissions-delete', 'display_name' => 'Delete Permission', 'description' => 'Delete permissions'],
            
            // Content management permissions
            ['name' => 'content-list', 'display_name' => 'List Content', 'description' => 'View content'],
            ['name' => 'content-create', 'display_name' => 'Create Content', 'description' => 'Create new content'],
            ['name' => 'content-edit', 'display_name' => 'Edit Content', 'description' => 'Edit existing content'],
            ['name' => 'content-delete', 'display_name' => 'Delete Content', 'description' => 'Delete content'],
            ['name' => 'content-publish', 'display_name' => 'Publish Content', 'description' => 'Publish content'],
            
            // System management permissions
            ['name' => 'system-settings', 'display_name' => 'System Settings', 'description' => 'Manage system settings'],
            ['name' => 'system-logs', 'display_name' => 'System Logs', 'description' => 'View system logs'],
            ['name' => 'system-backup', 'display_name' => 'System Backup', 'description' => 'Perform system backup'],
        ];

        // Create permissions
        foreach ($permissions as $permissionData) {
            $permission = Permission::firstOrCreate($permissionData);
            
            // Attach permissions to roles based on hierarchy
            if ($permissionData['name'] === 'users-list' || 
                $permissionData['name'] === 'content-list') {
                // Basic permissions for all users
                $userRole->attachPermission($permission);
                $moderatorRole->attachPermission($permission);
                $adminRole->attachPermission($permission);
            } elseif (str_starts_with($permissionData['name'], 'content-')) {
                // Content permissions for moderators and admins
                $moderatorRole->attachPermission($permission);
                $adminRole->attachPermission($permission);
            } elseif (str_starts_with($permissionData['name'], 'users-') || 
                      str_starts_with($permissionData['name'], 'roles-') ||
                      str_starts_with($permissionData['name'], 'permissions-') ||
                      str_starts_with($permissionData['name'], 'system-')) {
                // Admin-only permissions
                $adminRole->attachPermission($permission);
            }
        }

        $this->command->info('Roles and permissions have been seeded successfully!');
        $this->command->info('Admin role has ' . $adminRole->permissions()->count() . ' permissions');
        $this->command->info('Moderator role has ' . $moderatorRole->permissions()->count() . ' permissions');
        $this->command->info('User role has ' . $userRole->permissions()->count() . ' permissions');
    }
}
