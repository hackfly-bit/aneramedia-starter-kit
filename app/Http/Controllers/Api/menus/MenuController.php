<?php

namespace App\Http\Controllers\Api\menus;

use App\Http\Controllers\Api\BaseController;
use App\Http\Responses\ApiResponse;
use App\Models\Menu;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends BaseController
{
    protected MenuService $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * Get user menu based on their roles
     */
    public function userMenu(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return ApiResponse::error('User not authenticated', 401);
        }

        $menu = $this->menuService->getUserMenu($user);
        
        return ApiResponse::success(['menu' => $menu]);
    }

    /**
     * Display a listing of all menus (admin only)
     */
    public function index()
    {
        $menus = $this->menuService->getAllMenusWithRoles();
        
        return ApiResponse::success(['menus' => $menus]);
    }

    /**
     * Store a newly created menu
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'integer|min:0',
            'is_active' => 'boolean',
            'permission' => 'nullable|string|max:255',
            'role_ids' => 'array',
            'role_ids.*' => 'exists:roles,id',
        ]);

        $menu = Menu::create($validated);

        if (isset($validated['role_ids'])) {
            $this->menuService->updateMenuRoles($menu, $validated['role_ids']);
        }

        return ApiResponse::success(['menu' => $menu->load('roles')], 'Menu created successfully', 201);
    }

    /**
     * Display the specified menu
     */
    public function show(Menu $menu)
    {
        return ApiResponse::success(['menu' => $menu->load('roles', 'children')]);
    }

    /**
     * Update the specified menu
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'integer|min:0',
            'is_active' => 'boolean',
            'permission' => 'nullable|string|max:255',
            'role_ids' => 'array',
            'role_ids.*' => 'exists:roles,id',
        ]);

        $menu->update($validated);

        if (isset($validated['role_ids'])) {
            $this->menuService->updateMenuRoles($menu, $validated['role_ids']);
        }

        return ApiResponse::success(['menu' => $menu->load('roles')]);
    }

    /**
     * Remove the specified menu
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        $this->menuService->clearAllMenuCache();
        
        return ApiResponse::success([], 'Menu deleted successfully');
    }
}
