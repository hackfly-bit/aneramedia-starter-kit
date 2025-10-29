<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        // Dashboard menu (available for all roles)
        $dashboard = Menu::create([
            'name' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'dashboard',
            'order' => 1,
            'is_active' => true,
            'permission' => 'dashboard.view'
        ]);

        // User management (admin only)
        $userManagement = Menu::create([
            'name' => 'User Management',
            'route' => 'users',
            'icon' => 'people',
            'order' => 2,
            'is_active' => true,
            'permission' => 'users.manage'
        ]);

        // Settings menu (parent)
        $settings = Menu::create([
            'name' => 'Settings',
            'route' => 'settings',
            'icon' => 'settings',
            'order' => 3,
            'is_active' => true,
            'permission' => 'settings.view'
        ]);

        // Settings submenu
        $profileSettings = Menu::create([
            'name' => 'Profile Settings',
            'route' => 'settings.profile',
            'icon' => 'person',
            'parent_id' => $settings->id,
            'order' => 1,
            'is_active' => true,
            'permission' => 'profile.edit'
        ]);

        $systemSettings = Menu::create([
            'name' => 'System Settings',
            'route' => 'settings.system',
            'icon' => 'admin_panel_settings',
            'parent_id' => $settings->id,
            'order' => 2,
            'is_active' => true,
            'permission' => 'settings.system'
        ]);

        // Reports menu (admin only)
        $reports = Menu::factory()->create([
            'name' => 'Reports',
            'route' => 'reports',
            'icon' => 'analytics',
            'order' => 4,
            'is_active' => true,
            'permission' => 'reports.view'
        ]);

        // Attach roles to menus
        if ($userRole) {
            $dashboard->roles()->attach($userRole);
            $settings->roles()->attach($userRole);
            $profileSettings->roles()->attach($userRole);
        }

        if ($adminRole) {
            $dashboard->roles()->attach($adminRole);
            $userManagement->roles()->attach($adminRole);
            $settings->roles()->attach($adminRole);
            $profileSettings->roles()->attach($adminRole);
            $systemSettings->roles()->attach($adminRole);
            $reports->roles()->attach($adminRole);
        }
    }
}
