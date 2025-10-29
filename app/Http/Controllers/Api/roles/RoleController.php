<?php

namespace App\Http\Controllers\Api\roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\roles\StoreRoleRequest;
use App\Http\Requests\roles\UpdateRoleRequest;
use App\Http\Resources\permissions\PermissionResource;
use App\Http\Resources\roles\RoleResource;
use App\Http\Responses\ApiResponse;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $roles = Role::query()
                ->when($request->search, function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('display_name', 'like', "%{$search}%");
                })
                ->with('permissions')
                ->paginate($request->per_page ?? 15);

            return ApiResponse::success(
                RoleResource::collection($roles),
                'Roles retrieved successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to retrieve roles', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        try {
            $role = Role::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
            ]);

            return ApiResponse::success(
                new RoleResource($role->load('permissions')),
                'Role created successfully',
                201
            );
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to create role', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): JsonResponse
    {
        try {
            $role->load('permissions');
            
            return ApiResponse::success(
                new RoleResource($role),
                'Role retrieved successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to retrieve role', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        try {
            $role->update([
                'name' => $request->name ?? $role->name,
                'display_name' => $request->display_name ?? $role->display_name,
                'description' => $request->description ?? $role->description,
            ]);

            return ApiResponse::success(
                new RoleResource($role->load('permissions')),
                'Role updated successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to update role', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): JsonResponse
    {
        try {
            // Cek apakah role masih digunakan oleh user
            if ($role->users()->exists()) {
                return ApiResponse::error('Cannot delete role that is still assigned to users', 422);
            }

            $role->delete();

            return ApiResponse::success(
                null,
                'Role deleted successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to delete role', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get permissions for a specific role.
     */
    public function getPermissions(Role $role): JsonResponse
    {
        try {
            $permissions = $role->permissions()->paginate(15);

            return ApiResponse::success(
                PermissionResource::collection($permissions),
                'Role permissions retrieved successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to retrieve role permissions', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Assign permission to role.
     */
    public function assignPermission(Request $request, Role $role): JsonResponse
    {
        try {
            $request->validate([
                'permission_id' => 'required|exists:permissions,id'
            ]);

            $permission = Permission::findOrFail($request->permission_id);
            
            // Cek apakah permission sudah ter-assign
            if ($role->hasPermission($permission->name)) {
                return ApiResponse::error('Permission already assigned to role', 422);
            }

            $role->givePermission($permission);

            return ApiResponse::success(
                new PermissionResource($permission),
                'Permission assigned to role successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to assign permission to role', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Revoke permission from role.
     */
    public function revokePermission(Role $role, Permission $permission): JsonResponse
    {
        try {
            // Cek apakah permission ter-assign ke role
            if (!$role->hasPermission($permission->name)) {
                return ApiResponse::error('Permission not assigned to role', 422);
            }

            $role->removePermission($permission);

            return ApiResponse::success(
                null,
                'Permission revoked from role successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to revoke permission from role', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Sync permissions for role.
     */
    public function syncPermissions(Request $request, Role $role): JsonResponse
    {
        try {
            $request->validate([
                'permission_ids' => 'required|array',
                'permission_ids.*' => 'exists:permissions,id'
            ]);

            $permissions = Permission::whereIn('id', $request->permission_ids)->get();
            $role->syncPermissions($permissions);

            return ApiResponse::success(
                PermissionResource::collection($permissions),
                'Role permissions synced successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to sync role permissions', 500, ['error' => $e->getMessage()]);
        }
    }
}
