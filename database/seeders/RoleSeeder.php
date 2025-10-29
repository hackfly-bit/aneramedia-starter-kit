<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin Role
        $adminRole = Role::firstOrCreate([
            'name' => 'admin'
        ], [
            'display_name' => 'Administrator',
            'description' => 'Full system access with all permissions'
        ]);

        // Create Moderator Role
        $moderatorRole = Role::firstOrCreate([
            'name' => 'moderator'
        ], [
            'display_name' => 'Moderator',
            'description' => 'Content management and user moderation'
        ]);

        // Create User Role
        $userRole = Role::firstOrCreate([
            'name' => 'user'
        ], [
            'display_name' => 'User',
            'description' => 'Basic user with limited permissions'
        ]);

        // Assign permissions to Admin (all permissions)
        $allPermissions = Permission::all();
        $adminRole->syncPermissions($allPermissions);

        // Assign permissions to Moderator
        $moderatorPermissions = Permission::whereIn('name', [
            'users-list',
            'users-edit',
            'content-list',
            'content-create',
            'content-edit',
            'content-delete',
            'content-publish',
            'system-logs'
        ])->get();
        $moderatorRole->syncPermissions($moderatorPermissions);

        // Assign permissions to User (basic permissions)
        $userPermissions = Permission::whereIn('name', [
            'content-list'
        ])->get();
        $userRole->syncPermissions($userPermissions);

        $this->command->info('Roles and their permissions seeded successfully.');
        $this->command->info('Admin role: ' . $adminRole->permissions->count() . ' permissions');
        $this->command->info('Moderator role: ' . $moderatorRole->permissions->count() . ' permissions');
        $this->command->info('User role: ' . $userRole->permissions->count() . ' permissions');
    }
}
