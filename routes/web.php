<?php

use App\Http\Controllers\InertiaProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [InertiaProductController::class, 'index'])->name('home');
Route::get('/products/{product}', [InertiaProductController::class, 'show'])->name('products.show');
Route::post('/products/{product}/new-comment', [InertiaProductController::class, 'store'])->name('products.comment.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
