<?php

use App\Http\Controllers\Api\auth\AuthController;
use Illuminate\Support\Facades\Route;

// Public routes (tidak memerlukan autentikasi)
Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

// Protected routes (memerlukan autentikasi Sanctum)
Route::middleware(['auth:sanctum', 'check.acl'])->prefix('v1')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/me', [AuthController::class, 'me'])->name('auth.me');
});