<?php

namespace App\Http\Controllers\Api\permissions;

use App\Http\Controllers\Controller;
use App\Http\Requests\permissions\StorePermissionRequest;
use App\Http\Requests\permissions\UpdatePermissionRequest;
use App\Http\Resources\permissions\PermissionResource;
use App\Http\Responses\ApiResponse;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $permissions = Permission::query()
                ->when($request->search, function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('display_name', 'like', "%{$search}%");
                })
                ->paginate($request->per_page ?? 15);

            return ApiResponse::success(
                ['data' => PermissionResource::collection($permissions)],
                'Permissions retrieved successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to retrieve permissions', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request): JsonResponse
    {
        try {
            $permission = Permission::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
            ]);

            return ApiResponse::success(
                new PermissionResource($permission),
                'Permission created successfully',
                201
            );
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to create permission', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission): JsonResponse
    {
        try {
            return ApiResponse::success(
                new PermissionResource($permission),
                'Permission retrieved successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to retrieve permission', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission): JsonResponse
    {
        try {
            $permission->update([
                'name' => $request->name ?? $permission->name,
                'display_name' => $request->display_name ?? $permission->display_name,
                'description' => $request->description ?? $permission->description,
            ]);

            return ApiResponse::success(
                new PermissionResource($permission),
                'Permission updated successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to update permission', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission): JsonResponse
    {
        try {
            // Cek apakah permission masih digunakan oleh role atau user
            if ($permission->roles()->exists() || $permission->users()->exists()) {
                return ApiResponse::error('Cannot delete permission that is still assigned to roles or users', 422);
            }

            $permission->delete();

            return ApiResponse::success(
                null,
                'Permission deleted successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to delete permission', 500, ['error' => $e->getMessage()]);
        }
    }
}
