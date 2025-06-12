<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserDashboardController;

Route::get('/', [HomeController::class, 'index'])->name('dashboard');

// Protected routes with rate limiting
Route::middleware(['auth', 'throttle:web'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Dashboard Route
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
});

// Order routes with specific rate limiting
Route::middleware(['auth', 'throttle:orders'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [InvoiceController::class, 'print'])->name('order.print');
});

// Product routes with specific rate limiting
Route::middleware(['throttle:products'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
});

// Cart routes with web rate limiting
Route::middleware(['auth', 'throttle:web'])->group(function () {
    Route::get('/cart', [ProductController::class, 'cart'])->name('cart.index');
    Route::post('/cart/add', [ProductController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{product}', [ProductController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/update/{product}', [ProductController::class, 'update'])->name('cart.update');
});

// Wishlist routes with web rate limiting
Route::middleware(['auth', 'throttle:web'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/remove/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
});

// Checkout routes with web rate limiting
Route::middleware(['auth', 'throttle:web'])->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
    Route::post('/midtrans/pay', [CheckoutController::class, 'midtransPay'])->name('midtrans.pay');
});

// Midtrans Callback Routes (no rate limiting for callbacks)
Route::post('/midtrans/callback', [CheckoutController::class, 'callback'])->name('midtrans.callback');
Route::get('/midtrans/finish', [CheckoutController::class, 'finish'])->name('midtrans.finish');
Route::get('/midtrans/unfinish', [CheckoutController::class, 'unfinish'])->name('midtrans.unfinish');
Route::get('/midtrans/error', [CheckoutController::class, 'error'])->name('midtrans.error');

require __DIR__ . '/auth.php';
