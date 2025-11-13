<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\AuthController;

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// About Page
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Catalog Page
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/product-detail', [CatalogController::class, 'show'])->name('product.detail');

// Order Status Page (on progress)
Route::get('/order-status', function () {
    return view('order-status');
})->name('order-status');

// Admin
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Get Sales Data API (for chart filters)
    Route::get('/dashboard/sales-data', [AdminDashboardController::class, 'getSalesData']);
    
    // Catalog Management (untuk nanti)
    Route::get('/catalog', function () {
        return view('admin.catalog');
    })->name('catalog');
    
    // Orders Management (untuk nanti)
    Route::get('/orders', function () {
        return view('admin.orders');
    })->name('orders');
    
    // Profile (untuk nanti)
    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('profile');
    
    // Settings (untuk nanti)
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Get Sales Data API (for chart filters)
    Route::get('/dashboard/sales-data', [AdminDashboardController::class, 'getSalesData']);
    
    // Catalog Management
    Route::get('/catalog', function () {
        return view('admin.catalog');
    })->name('catalog');
    
    // Orders Management
    Route::get('/orders', function () {
        return view('admin.orders');
    })->name('orders');
    
    // Profile
    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('profile');
    
    // Settings
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Forgot Password
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

// Reset Password
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');