<?php

use App\Http\Controllers\ApiAuthenticationController;
use App\Http\Controllers\ApiNewsController;
use App\Http\Controllers\ApiOrdersController;
use App\Http\Controllers\ApiProductsController;
use App\Http\Controllers\ApiUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function () {
    Route::post('/login', [ApiAuthenticationController::class, 'login'])->name('login');
    Route::post('/register', [ApiAuthenticationController::class, 'register'])->name('register');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [ApiAuthenticationController::class, 'logout'])->name('logout');
    });
})->name('api.auth.');

Route::prefix('/me')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ApiUserController::class, 'index']);
    Route::put('/', [ApiUserController::class, 'update']);
    Route::put('/password', [ApiUserController::class, 'updatePassword']);
    Route::delete('/', [ApiUserController::class, 'destroy']);
})->name('api.me.');

Route::resource('/news', ApiNewsController::class)
    ->only(['index', 'show'])
    ->names('api.news');

Route::resource('/products', ApiProductsController::class)
    ->only(['index', 'show'])
    ->names('api.products');

Route::get('/products/{product}/comments', [ApiProductsController::class, 'comments'])
    ->name('api.products.comments');
Route::post('/products/{product}/comments', [ApiProductsController::class, 'storeComment'])
    ->middleware('auth:sanctum')
    ->name('api.products.comments.store');

Route::resource('/orders', ApiOrdersController::class)
    ->only(['index', 'show', 'store', 'destroy'])
    ->middleware('auth:sanctum')
    ->names('api.orders');
