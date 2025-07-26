<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/admins/login', [AdminAuthController::class, 'login']);
Route::post('/users/login', [UserAuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Admin routes
    Route::prefix('admins')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout']);
    });

    // User routes
    Route::prefix('users')->group(function () {
        Route::post('/logout', [UserAuthController::class, 'logout']);
    });
});

// Posts routes (accessible by both users and admins)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('posts/stats', [PostController::class, 'stats']);
    Route::apiResource('posts', PostController::class);
});
