<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/newsletter/subscribe', [HomeController::class, 'subscribe'])->name('home.newsletter-subscribe');

Route::prefix('/products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{product:slug}', [ProductController::class, 'show'])->name('show');
    Route::post('/{product:slug}/new-comment', [ProductController::class, 'storeComment'])
        ->name('comment.store')
        ->middleware(['auth', 'verified']);
});

Route::prefix('/news')->name('news.')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('index');
    Route::get('/{news:slug}', [NewsController::class, 'show'])->name('show');
});

Route::prefix('/carts')->name('carts.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout')->middleware(['auth', 'verified']);
    Route::post('/{product:slug}/increment', [CartController::class, 'increment'])->name('increment');
    Route::delete('/{product:slug}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
});

Route::prefix('/orders')->name('orders.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/confirmation', [OrdersController::class, 'confirmation'])
        ->name('confirmation');

    Route::post('/', [OrdersController::class, 'store'])
        ->name('store');

    Route::delete('/{order}', [OrdersController::class, 'cancel'])
        ->name('cancel');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('/dashboard')->name('dashboard.')->group(function () {
        Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [DashboardController::class, 'orderDetails'])->name('orders.details');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
