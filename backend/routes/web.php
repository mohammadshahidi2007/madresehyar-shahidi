<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;
Route::prefix('api/v1')->group(function (): void {
    Route::get('/csrf-token', fn () => response()->json(['csrf_token' => csrf_token()]));
    Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::middleware('auth')->group(function (): void {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/auth/password', [AuthController::class, 'changePassword']);
        Route::middleware('password.changed')->group(function (): void {
            Route::get('/me', [AuthController::class, 'me']);
            Route::middleware('role:admin')->prefix('admin')->group(function (): void {
                Route::get('/users', [AdminUserController::class, 'index']);
                Route::post('/users', [AdminUserController::class, 'store']);
                Route::patch('/users/{user}', [AdminUserController::class, 'update']);
                Route::post('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword']);
            });
        });
    });
});