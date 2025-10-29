<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    private const CACHE_PREFIX = 'user_menu_';
    private const CACHE_TTL = 3600; // 1 hour

    /**
     * Get hierarchical menu for user based on their roles
     */
    public function getUserMenu(User $user): array
    {
        $cacheKey = self::CACHE_PREFIX . $user->id;
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
            $roleIds = $user->roles()->pluck('id');
            
            if ($roleIds->isEmpty()) {
                return [];
            }

            $menus = Menu::with(['children', 'roles'])
                ->whereNull('parent_id')
                ->active()
                ->ordered()
                ->whereHas('roles', function ($query) use ($roleIds) {
                    $query->whereIn('roles.id', $roleIds);
                })
                ->get();

            return $this->buildMenuTree($menus, $roleIds);
        });
    }

    /**
     * Build hierarchical menu tree
     */
    private function buildMenuTree(Collection $menus, Collection $roleIds): array
    {
        return $menus->map(function ($menu) use ($roleIds) {
            $menuData = [
                'id' => $menu->id,
                'name' => $menu->name,
                'route' => $menu->route,
                'icon' => $menu->icon,
                'order' => $menu->order,
                'permission' => $menu->permission,
            ];

            // Get children that user has access to
            $children = $menu->children()
                ->active()
                ->ordered()
                ->whereHas('roles', function ($query) use ($roleIds) {
                    $query->whereIn('roles.id', $roleIds);
                })
                ->get();

            if ($children->isNotEmpty()) {
                $menuData['children'] = $this->buildMenuTree($children, $roleIds);
            }

            return $menuData;
        })->values()->toArray();
    }

    /**
     * Clear user menu cache
     */
    public function clearUserMenuCache(User $user): void
    {
        Cache::forget(self::CACHE_PREFIX . $user->id);
    }

    /**
     * Clear all menu cache
     */
    public function clearAllMenuCache(): void
    {
        Cache::flush();
    }

    /**
     * Get all active menus with their roles
     */
    public function getAllMenusWithRoles(): Collection
    {
        return Menu::with(['roles', 'children.roles'])
            ->active()
            ->ordered()
            ->get();
    }

    /**
     * Update menu roles
     */
    public function updateMenuRoles(Menu $menu, array $roleIds): void
    {
        $menu->roles()->sync($roleIds);
        $this->clearAllMenuCache();
    }
}
