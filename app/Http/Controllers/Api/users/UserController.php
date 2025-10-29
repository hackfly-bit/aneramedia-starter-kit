<?php

namespace App\Http\Controllers\Api\users;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\users\AssignRoleRequest;
use App\Http\Requests\users\StoreUserRequest;
use App\Http\Requests\users\UpdateUserRequest;
use App\Http\Resources\users\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends BaseController
{
    // Middleware will be applied at route level

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = User::with('roles')->paginate(10);

        return ApiResponse::success(
            UserResource::collection($users),
            'Users retrieved successfully'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            if (isset($validated['role'])) {
                $user->addRole($validated['role']);
            }

            return ApiResponse::success(
                new UserResource($user->load('roles')),
                'User berhasil dibuat',
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Gagal membuat user',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $user = User::with('roles', 'permissions')->findOrFail($id);

        return ApiResponse::success(
            new UserResource($user),
            'User retrieved successfully'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $validated = $request->validated();

            if (isset($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            }

            $user->update($validated);

            return ApiResponse::success(
                new UserResource($user->load('roles')),
                'User berhasil diupdate'
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Gagal mengupdate user',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting current authenticated user
        if ($user->id === auth()->id()) {
            return ApiResponse::error(
                'Cannot delete your own account',
                Response::HTTP_FORBIDDEN
            );
        }

        $user->delete();

        return ApiResponse::success(
            null,
            'User deleted successfully'
        );
    }

    /**
     * Assign role to user.
     */
    public function assignRole(AssignRoleRequest $request, string $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $validated = $request->validated();

            $user->addRole($validated['role']);

            return ApiResponse::success(
                new UserResource($user->load('roles')),
                'Role berhasil ditambahkan'
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Gagal menambahkan role',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Remove role from user.
     */
    public function removeRole(AssignRoleRequest $request, string $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $validated = $request->validated();

            $user->removeRole($validated['role']);

            return ApiResponse::success(
                new UserResource($user->load('roles')),
                'Role berhasil dihapus'
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Gagal menghapus role',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
