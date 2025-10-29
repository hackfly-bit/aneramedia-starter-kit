<?php

use App\Http\Controllers\Api\roles\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'check.acl'])->prefix('v1/roles')->group(function () {
    // Role CRUD endpoints
    Route::get('/', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::put('/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Role Permission Management endpoints
    Route::get('/{role}/permissions', [RoleController::class, 'getPermissions'])->name('roles.permissions');
    Route::post('/{role}/permissions/{permission}', [RoleController::class, 'assignPermission'])->name('roles.assign-permission');
    Route::delete('/{role}/permissions/{permission}', [RoleController::class, 'revokePermission'])->name('roles.revoke-permission');
    Route::put('/{role}/permissions', [RoleController::class, 'syncPermissions'])->name('roles.sync-permissions');
});
