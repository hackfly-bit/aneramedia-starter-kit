<?php

use App\Http\Controllers\Api\permissions\PermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'check.acl'])->prefix('v1/permissions')->group(function () {
    // Permission CRUD endpoints
    Route::get('/', [PermissionController::class, 'index'])->name('permissions.index');
    Route::post('/', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/{permission}', [PermissionController::class, 'show'])->name('permissions.show');
    Route::put('/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
});
