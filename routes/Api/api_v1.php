<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Api\Auth\LoginController;
use App\Http\Controllers\V1\Api\Auth\SocialAuthController;
use App\Http\Controllers\V1\Api\Auth\PasswordResetController;
use App\Http\Controllers\V1\Api\ClientController;
use App\Http\Controllers\V1\Api\PromotionController;
use App\Http\Controllers\V1\Api\VehicleController;
use App\Http\Controllers\V1\Api\ServiceController;
use App\Http\Controllers\V1\Api\ClientNotificationController;
use App\Http\Controllers\V1\Api\AppointmentController;
use App\Http\Controllers\V1\Api\TimezoneController;

// Auth routes
Route::post('/login-with-email', [LoginController::class, 'loginWithEmail']);
Route::post('/login-with-social', [SocialAuthController::class, 'loginWithSocial']);

// Password reset routes (no authentication required)
Route::post('/password-reset/send-otp', [PasswordResetController::class, 'sendOtp']);
Route::post('/password-reset/verify-otp', [PasswordResetController::class, 'verifyOtp']);
Route::post('/password-reset/reset-password', [PasswordResetController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::get('/logout', [LoginController::class, 'logout']);
    Route::post('/refresh-token', [LoginController::class, 'refreshToken']);
    Route::post('/refresh-fcm-token', [LoginController::class, 'refreshFcmToken']);

    // Social auth routes
    Route::post('/connect-social-account', [SocialAuthController::class, 'connectSocialAccount']);
    Route::delete('/disconnect-social-account', [SocialAuthController::class, 'disconnectSocialAccount']);
    Route::get('/social-accounts', [SocialAuthController::class, 'getSocialAccounts']);

    // User routes
    Route::get('/profile', [ClientController::class, 'profile']);
    Route::put('/profile', [ClientController::class, 'update']);
    Route::post('/profile-photo', [ClientController::class, 'updateProfilePhoto']);

    // Promotion routes
    Route::get('/promotions', [PromotionController::class, 'index']);

    // Cars routes
    Route::get('/vehicles', [VehicleController::class, 'index']);

    // Services routes
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/services/catalog', [ServiceController::class, 'catalog']);
    Route::get('/vehicles/{vehicle_id}/services', [ServiceController::class, 'byVehicle']);

    // Notifications routes
    Route::get('/notifications', [ClientNotificationController::class, 'getNotifications']);
    Route::get('/notifications/{notification_id}/mark-as-read', [ClientNotificationController::class, 'markAsRead']);
    Route::put('/notifications/mark-all-as-read', [ClientNotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{notification_id}', [ClientNotificationController::class, 'delete']);

    // Appointments routes
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::get('/appointments', [AppointmentController::class, 'index']);

    // Timezone routes
    Route::get('/timezones', [TimezoneController::class, 'index']);
});


