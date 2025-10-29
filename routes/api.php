<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Responses\ApiResponse;



// Make new file each module (e.g., v1/auth.php, v1/users.php)
require __DIR__.'/v1/auth.php';
require __DIR__.'/v1/users.php';
require __DIR__.'/v1/roles.php';
require __DIR__.'/v1/permissions.php';
require __DIR__.'/v1/menu.php';








Route::get('/status', function () {
    return response()->json(['status' => 'ok']);
});

Route::get('/health', function () {
    return response()->json(['status' => 'healthy']);
});

// Test routes for debugging
Route::middleware(['auth:sanctum'])->get('/test-auth', function () {
    return response()->json(['message' => 'Authenticated']);
});

Route::get('/test-no-auth', function () {
    return response()->json(['message' => 'No auth required']);
});

// Example routes (moved to prevent conflicts with v1/users.php)
Route::get('/example', function () {
    return ApiResponse::success(['message' => 'Example endpoint'], 'Example response');
});
