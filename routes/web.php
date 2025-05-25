<?php

use App\Http\Controllers\InertiaHomeController;
use App\Http\Controllers\InertiaNewsController;
use App\Http\Controllers\InertiaProductCartController;
use App\Http\Controllers\InertiaProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [InertiaHomeController::class, 'index'])->name('inertia-home');
Route::post('/newsletter/subscribe', [InertiaHomeController::class, 'subscribe'])->name('inertia-home.subscribe');
Route::get('/products', [InertiaProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [InertiaProductController::class, 'show'])->name('products.show');
Route::post('/products/{product}/new-comment', [InertiaProductController::class, 'store'])->name('products.comment.store');
Route::post('/products/{product}/add-to-cart', [InertiaProductCartController::class, 'store'])->name('inertia-product-cart.store');
Route::get('/news', [InertiaNewsController::class, 'index'])->name('inertia-news.index');
Route::get('/news/{slug}', [InertiaNewsController::class, 'show'])->name('inertia-news.show');
Route::get('/carts', [InertiaProductCartController::class, 'index'])->name('inertia-product-cart.index');
Route::post('/carts/clear', [InertiaProductCartController::class, 'clear'])->name('inertia-product-cart.clear');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
