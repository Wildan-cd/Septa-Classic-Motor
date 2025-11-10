<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AdminDashboardController;

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// About Page
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Catalog Page (on progress)
Route::get('/catalog', function () {
    return view('catalog');
})->name('catalog');

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

// Route::post('/logout', function () {
//     auth()->logout();
//     return redirect('/');
// })->name('logout');
