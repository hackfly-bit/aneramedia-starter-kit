<?php

use App\Http\Controllers\Api\users\UserController;
use Illuminate\Support\Facades\Route;

// All user routes require authentication and ACL check
Route::middleware(['auth:sanctum', 'check.acl'])->prefix('v1')->group(function () {
    // User management routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Role management routes
    Route::post('/users/{id}/assign-role', [UserController::class, 'assignRole'])->name('users.assign-role');
    Route::post('/users/{id}/remove-role', [UserController::class, 'removeRole'])->name('users.remove-role');
});