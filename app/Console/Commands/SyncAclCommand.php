<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use App\Models\Permission;

class SyncAclCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acl:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all named routes as permissions in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting ACL sync...');
        
        // Dapatkan semua route yang memiliki nama
        $routes = collect(Route::getRoutes())->filter(function ($route) {
            return $route->getName() !== null;
        });

        $this->info('Found ' . $routes->count() . ' named routes');
        
        $createdCount = 0;
        $skippedCount = 0;

        foreach ($routes as $route) {
            $routeName = $route->getName();
            $routeUri = $route->uri();
            $routeMethod = implode('|', $route->methods());
            
            // Buat display name dan description
            $displayName = $this->generateDisplayName($routeName);
            $description = "Access to {$routeMethod} {$routeUri}";
            
            // Cek apakah permission sudah ada
            $existingPermission = Permission::where('name', $routeName)->first();
            
            if ($existingPermission) {
                $this->line("Skipped: {$routeName} (already exists)");
                $skippedCount++;
                continue;
            }
            
            // Buat permission baru
            Permission::create([
                'name' => $routeName,
                'display_name' => $displayName,
                'description' => $description,
            ]);
            
            $this->info("Created: {$routeName}");
            $createdCount++;
        }
        
        $this->info("\nACL sync completed!");
        $this->info("Created: {$createdCount} permissions");
        $this->info("Skipped: {$skippedCount} permissions");
        
        return Command::SUCCESS;
    }
    
    /**
     * Generate display name from route name
     */
    private function generateDisplayName(string $routeName): string
    {
        // Ubah dot notation menjadi readable text
        // users.index -> Users Index
        // users.store -> Users Store
        // users.update -> Users Update
        
        $parts = explode('.', $routeName);
        $lastPart = array_pop($parts);
        
        // Mapping action names ke readable format
        $actionMap = [
            'index' => 'List',
            'show' => 'View',
            'create' => 'Create Form',
            'store' => 'Create',
            'edit' => 'Edit Form',
            'update' => 'Update',
            'destroy' => 'Delete',
            'assign' => 'Assign',
            'remove' => 'Remove',
            'sync' => 'Sync',
        ];
        
        $action = $actionMap[$lastPart] ?? ucfirst($lastPart);
        $resource = ucfirst(implode(' ', $parts));
        
        return "{$resource} {$action}";
    }
}