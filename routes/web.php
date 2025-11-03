<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\DashboardController;

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
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
