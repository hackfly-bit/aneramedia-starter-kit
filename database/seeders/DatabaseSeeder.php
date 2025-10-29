<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed permissions first
        $this->call(PermissionSeeder::class);
        
        // Then seed roles and assign permissions
        $this->call(RoleSeeder::class);

        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        // Create admin user for testing
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $adminUser->addRole('admin');
        
        // Create moderator user for testing
        $moderatorUser = User::factory()->create([
            'name' => 'Moderator User',
            'email' => 'moderator@example.com',
        ]);
        $moderatorUser->addRole('moderator');

        // Seed menus
        $this->call(MenuSeeder::class);
    }
}
