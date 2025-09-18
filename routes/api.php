<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\PaymentController;

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('password/forgot', [AuthController::class, 'forgotPassword']);
    Route::post('password/reset', [AuthController::class, 'resetPassword']);
    
    Route::middleware('jwt.auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

// Monthly User Donation Routes (Authenticated)
Route::middleware('jwt.auth')->prefix('donation/monthly')->group(function () {
    Route::post('amount', [DonationController::class, 'monthlyAmount']);
    Route::post('payment', [DonationController::class, 'monthlyPayment']);
    Route::post('submit', [DonationController::class, 'monthlySubmit']);
});

// Guest User Donation Routes
Route::prefix('donation/guest')->group(function () {
    Route::post('info', [DonationController::class, 'guestInfo']);
    Route::post('amount', [DonationController::class, 'guestAmount']);
    Route::post('payment', [DonationController::class, 'guestPayment']);
    Route::post('submit', [DonationController::class, 'guestSubmit']);
});


Route::post('/donation/create', [PaymentController::class, 'createPayment']);
Route::get('/donation/capture', [PaymentController::class, 'capturePayment']);

Route::post('/donation/paypal/create', [DonationController::class, 'paypalCreate']);
Route::post('/donation/paypal/checkout', [DonationController::class, 'paypalCheckout']);
Route::post('/donation/paypal/capture', [DonationController::class, 'paypalCapture']);



// Donation History (Authenticated Users)
Route::middleware('jwt.auth')->group(function () {
    Route::get('donation/history', [DonationController::class, 'history']);
});

// Notifications Routes
Route::middleware('jwt.auth')->prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::post('{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('mark-all-read', [NotificationController::class, 'markAllAsRead']);
});

// Reminders Route
Route::post('reminders/send', [NotificationController::class, 'sendReminder']);