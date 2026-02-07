<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\BookingController;

// ============================================================================
// PUBLIC ROUTES (No authentication required)
// ============================================================================

Route::get('events', [EventController::class, 'index']);
Route::get('events/{id}', [EventController::class, 'show']);


// ============================================================================
// AUTH ROUTES
// ============================================================================

Route::prefix('auth')->group(function () {
    // Public auth endpoints
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // Protected auth endpoints
    Route::middleware('auth:api')->group(function () {
        Route::post('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
});


// ============================================================================
// AUTHENTICATED USER ROUTES (Requires auth:api)
// ============================================================================

Route::middleware('auth:api')->group(function () {
    // Booking routes
    Route::post('events/{id}/book', [BookingController::class, 'book']);
    Route::get('me/bookings', [BookingController::class, 'userBookings']);
});


// ============================================================================
// ADMIN-ONLY ROUTES (Requires auth:api + admin role)
// ============================================================================

Route::middleware(['auth:api', 'admin'])->group(function () {
    // Event management
    Route::post('events', [EventController::class, 'store']);
    Route::patch('events/{id}', [EventController::class, 'update']);
    Route::delete('events/{id}', [EventController::class, 'destroy']);
});
