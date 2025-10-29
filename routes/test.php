<?php

use Illuminate\Support\Facades\Route;

// Test route untuk debugging middleware
Route::middleware(['auth:sanctum'])->get('/test-auth', function () {
    return response()->json(['message' => 'Authenticated']);
});

Route::get('/test-no-auth', function () {
    return response()->json(['message' => 'No auth required']);
});