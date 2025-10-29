<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\menus\MenuController;

// User-specific menu (requires authentication)
Route::middleware('auth:sanctum')->get('/v1/user-menu', [MenuController::class, 'userMenu'])->name('menu.user');

// Admin menu management (requires authentication and admin role)
Route::middleware(['auth:sanctum', 'check.acl'])->prefix('v1')->group(function () {
    Route::apiResource('menus', MenuController::class);
});
