<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DonationController;
use App\Http\Controllers\Admin\PledgeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProfileController;

Route::view('/', 'welcome');

// Admin Authentication Routes
Route::get('admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin Panel Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Donations Management
    Route::resource('donations', DonationController::class)->only(['index', 'show']);
    Route::post('donations/{donation}/approve', [DonationController::class, 'approve'])->name('donations.approve');
    Route::post('donations/{donation}/reject', [DonationController::class, 'reject'])->name('donations.reject');
    
    // Pledges Management
    Route::get('pledges', [PledgeController::class, 'index'])->name('pledges.index');
    Route::post('pledges/send-reminders', [PledgeController::class, 'sendReminders'])->name('pledges.send-reminders');
    Route::post('pledges/{pledge}/toggle-status', [PledgeController::class, 'toggleStatus'])->name('pledges.toggle-status');
    
    // Users Management
    Route::resource('users', UserController::class)->only(['index', 'show']);
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // Notifications Management
    Route::resource('notifications', NotificationController::class)->only(['index', 'create', 'store']);
    
    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    
    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings/payment-methods/{paymentMethod}', [SettingController::class, 'updatePaymentMethod'])->name('settings.payment-methods.update');
    
    // Profile
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});