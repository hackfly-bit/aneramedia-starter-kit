<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User Management Permissions
            [
                'name' => 'users-list',
                'display_name' => 'List Users',
                'description' => 'View list of users'
            ],
            [
                'name' => 'users-create',
                'display_name' => 'Create User',
                'description' => 'Create new users'
            ],
            [
                'name' => 'users-edit',
                'display_name' => 'Edit User',
                'description' => 'Edit existing users'
            ],
            [
                'name' => 'users-delete',
                'display_name' => 'Delete User',
                'description' => 'Delete users'
            ],
            
            // Role Management Permissions
            [
                'name' => 'roles-list',
                'display_name' => 'List Roles',
                'description' => 'View list of roles'
            ],
            [
                'name' => 'roles-create',
                'display_name' => 'Create Role',
                'description' => 'Create new roles'
            ],
            [
                'name' => 'roles-edit',
                'display_name' => 'Edit Role',
                'description' => 'Edit existing roles'
            ],
            [
                'name' => 'roles-delete',
                'display_name' => 'Delete Role',
                'description' => 'Delete roles'
            ],
            
            // Permission Management Permissions
            [
                'name' => 'permissions-list',
                'display_name' => 'List Permissions',
                'description' => 'View list of permissions'
            ],
            [
                'name' => 'permissions-create',
                'display_name' => 'Create Permission',
                'description' => 'Create new permissions'
            ],
            [
                'name' => 'permissions-edit',
                'display_name' => 'Edit Permission',
                'description' => 'Edit existing permissions'
            ],
            [
                'name' => 'permissions-delete',
                'display_name' => 'Delete Permission',
                'description' => 'Delete permissions'
            ],
            
            // Content Management Permissions
            [
                'name' => 'content-list',
                'display_name' => 'List Content',
                'description' => 'View list of content'
            ],
            [
                'name' => 'content-create',
                'display_name' => 'Create Content',
                'description' => 'Create new content'
            ],
            [
                'name' => 'content-edit',
                'display_name' => 'Edit Content',
                'description' => 'Edit existing content'
            ],
            [
                'name' => 'content-delete',
                'display_name' => 'Delete Content',
                'description' => 'Delete content'
            ],
            [
                'name' => 'content-publish',
                'display_name' => 'Publish Content',
                'description' => 'Publish/unpublish content'
            ],
            
            // System Permissions
            [
                'name' => 'system-settings',
                'display_name' => 'System Settings',
                'description' => 'Access system settings'
            ],
            [
                'name' => 'system-logs',
                'display_name' => 'System Logs',
                'description' => 'View system logs'
            ],
            [
                'name' => 'system-backup',
                'display_name' => 'System Backup',
                'description' => 'Create and manage backups'
            ]
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        $this->command->info('Permissions seeded successfully.');
    }
}
