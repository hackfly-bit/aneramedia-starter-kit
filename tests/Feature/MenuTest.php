<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use App\Services\MenuService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Role $adminRole;
    protected Role $userRole;
    protected MenuService $menuService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->menuService = app(MenuService::class);
        
        // Create roles
        $this->adminRole = Role::create(['name' => 'admin', 'display_name' => 'Administrator']);
        $this->userRole = Role::create(['name' => 'user', 'display_name' => 'User']);
        
        // Create user
        $this->user = User::factory()->create();
    }

    public function test_can_create_menu_with_roles(): void
    {
        // Assign admin role to user for menu creation permission
        $this->user->roles()->attach($this->adminRole);
        
        $menuData = [
            'name' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'dashboard-icon',
            'order' => 1,
            'is_active' => true,
            'role_ids' => [$this->adminRole->id, $this->userRole->id]
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/menus', $menuData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'menu' => [
                        'id', 'name', 'route', 'icon', 'order', 'is_active'
                    ]
                ],
                'message',
                'code'
            ]);

        $this->assertDatabaseHas('menus', [
            'name' => 'Dashboard',
            'route' => 'dashboard'
        ]);

        $menu = Menu::where('name', 'Dashboard')->first();
        $this->assertCount(2, $menu->roles);
    }

    public function test_can_create_hierarchical_menu(): void
    {
        // Create parent menu
        $parentMenu = Menu::factory()->create(['name' => 'Settings']);
        $parentMenu->roles()->attach($this->adminRole);

        // Create child menu
        $childMenu = Menu::factory()->childOf($parentMenu)->create(['name' => 'User Settings']);
        $childMenu->roles()->attach($this->adminRole);

        $this->assertDatabaseHas('menus', [
            'name' => 'User Settings',
            'parent_id' => $parentMenu->id
        ]);

        $this->assertCount(1, $parentMenu->children);
    }

    public function test_user_can_get_their_menu_based_on_roles(): void
    {
        // Assign user role
        $this->user->roles()->attach($this->userRole);

        // Create menus for different roles
        $userMenu = Menu::factory()->create(['name' => 'User Dashboard']);
        $userMenu->roles()->attach($this->userRole);

        $adminMenu = Menu::factory()->create(['name' => 'Admin Dashboard']);
        $adminMenu->roles()->attach($this->adminRole);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/user-menu');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data.menu')
            ->assertJsonFragment(['name' => 'User Dashboard'])
            ->assertJsonMissing(['name' => 'Admin Dashboard']);
    }

    public function test_menu_service_builds_hierarchical_structure(): void
    {
        // Assign admin role to user
        $this->user->roles()->attach($this->adminRole);

        // Create hierarchical menu structure
        $parentMenu = Menu::factory()->create([
            'name' => 'Parent Menu',
            'order' => 1
        ]);
        $parentMenu->roles()->attach($this->adminRole);

        $childMenu1 = Menu::factory()->childOf($parentMenu)->create([
            'name' => 'Child Menu 1',
            'order' => 1
        ]);
        $childMenu1->roles()->attach($this->adminRole);

        $childMenu2 = Menu::factory()->childOf($parentMenu)->create([
            'name' => 'Child Menu 2',
            'order' => 2
        ]);
        $childMenu2->roles()->attach($this->adminRole);

        $menu = $this->menuService->getUserMenu($this->user);

        $this->assertCount(1, $menu);
        $this->assertEquals('Parent Menu', $menu[0]['name']);
        $this->assertCount(2, $menu[0]['children']);
        $this->assertEquals('Child Menu 1', $menu[0]['children'][0]['name']);
        $this->assertEquals('Child Menu 2', $menu[0]['children'][1]['name']);
    }

    public function test_menu_caching_works_correctly(): void
    {
        // Clear cache
        Cache::flush();
        
        // Assign role and create menu
        $this->user->roles()->attach($this->userRole);
        $menu = Menu::factory()->create(['name' => 'Cached Menu']);
        $menu->roles()->attach($this->userRole);

        // First call should cache the result
        $startTime = microtime(true);
        $firstResult = $this->menuService->getUserMenu($this->user);
        $firstCallTime = microtime(true) - $startTime;

        // Second call should be faster due to caching
        $startTime = microtime(true);
        $secondResult = $this->menuService->getUserMenu($this->user);
        $secondCallTime = microtime(true) - $startTime;

        $this->assertEquals($firstResult, $secondResult);
        $this->assertLessThan($firstCallTime, $secondCallTime);
    }

    public function test_menu_cache_cleared_on_role_update(): void
    {
        // Setup initial state
        $this->user->roles()->attach($this->userRole);
        $menu = Menu::factory()->create(['name' => 'Test Menu']);
        $menu->roles()->attach($this->userRole);

        // Get cached menu
        $cachedMenu = $this->menuService->getUserMenu($this->user);
        $this->assertCount(1, $cachedMenu);

        // Update menu roles
        $this->menuService->updateMenuRoles($menu, [$this->adminRole->id]);

        // Cache should be cleared, user should not see the menu anymore
        $updatedMenu = $this->menuService->getUserMenu($this->user);
        $this->assertCount(0, $updatedMenu);
    }

    public function test_inactive_menus_are_not_shown(): void
    {
        $this->user->roles()->attach($this->userRole);

        // Create active menu
        $activeMenu = Menu::factory()->create(['name' => 'Active Menu', 'is_active' => true]);
        $activeMenu->roles()->attach($this->userRole);

        // Create inactive menu
        $inactiveMenu = Menu::factory()->create(['name' => 'Inactive Menu', 'is_active' => false]);
        $inactiveMenu->roles()->attach($this->userRole);

        $menu = $this->menuService->getUserMenu($this->user);

        $this->assertCount(1, $menu);
        $this->assertEquals('Active Menu', $menu[0]['name']);
    }

    public function test_user_without_roles_gets_empty_menu(): void
    {
        // Don't assign any roles to user
        $menu = $this->menuService->getUserMenu($this->user);

        $this->assertEmpty($menu);
    }

    public function test_unauthenticated_user_cannot_access_user_menu(): void
    {
        $response = $this->getJson('/api/v1/user-menu');

        // Laravel Sanctum returns 401 for unauthenticated requests
        $response->assertStatus(401);
    }
}
