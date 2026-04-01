<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ContactController;
use Laravel\Sanctum\TransientToken;

// Public routes
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/posts',    [PostController::class, 'index']);
Route::get('/posts/{post:slug}', [PostController::class, 'show']);
Route::post('/contact', [ContactController::class, 'store']);

// Login
Route::post('/admin/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::once($credentials)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $request->user()->createToken('admin-token')->plainTextToken;
    return response()->json(['token' => $token]);
});

// Logout
Route::post('/admin/logout', function (Request $request) {
    $currentAccessToken = $request->user()->currentAccessToken();

    if ($currentAccessToken && !($currentAccessToken instanceof TransientToken)) {
        $currentAccessToken->delete();
    }

    return response()->json(['message' => 'Logged out']);
})->middleware('auth:sanctum');

// Protected admin routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/projects',          [ProjectController::class, 'store']);
    Route::put('/projects/{project}', [ProjectController::class, 'update']);
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);
});
