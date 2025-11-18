<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminCatalogController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\AuthController;

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// About Page
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Catalog Page
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/product-detail/{id}', [CatalogController::class, 'show'])->name('product.detail');

Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Confirm Payment Routes
    Route::get('/confirm-payment', [CheckoutController::class, 'confirmPayment'])->name('confirm.payment');
    Route::post('/confirm-payment', [CheckoutController::class, 'confirmPaymentSubmit'])->name('confirm.payment.submit');
});


// Order Status Page (on progress)
Route::get('/order-status', function () {
    return view('order-status');
})->name('order-status');


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
    Route::get('/Admin-catalog', [AdminCatalogController::class, 'index'])->name('catalog.index');
    Route::get('/Admin-catalog/create', [AdminCatalogController::class, 'create'])->name('catalog.create');
    Route::post('/Admin-catalog', [AdminCatalogController::class, 'store'])->name('catalog.store');
    Route::get('/Admin-catalog/{id}/edit', [AdminCatalogController::class, 'edit'])->name('catalog.edit');
    Route::put('/Admin-catalog/{id}', [AdminCatalogController::class, 'update'])->name('catalog.update');
    Route::delete('/Admin-catalog/{id}', [AdminCatalogController::class, 'destroy'])->name('catalog.destroy');
    Route::get('/Admin-catalog/{id}/data', [AdminCatalogController::class, 'getData'])->name('catalog.getData');
    // Orders Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/orders/{id}/print', [AdminOrderController::class, 'print'])->name('orders.print');
    Route::get('/orders/{id}/data', [AdminOrderController::class, 'viewDetails'])->name('orders.viewDetails');
    
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

Route::get('/orders', [OrderController::class, 'index'])->name('order-status');

