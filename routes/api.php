<?php

use App\Http\Controllers\ApiNewsController;
use App\Http\Controllers\ApiOrdersController;
use App\Http\Controllers\ApiProductsController;
use App\Http\Controllers\ApiUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthenticationController;

Route::prefix('/auth')->group(function () {
    Route::post('/login', [ApiAuthenticationController::class, 'login'])->name('login');
    Route::post('/register', [ApiAuthenticationController::class, 'register'])->name('register');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [ApiAuthenticationController::class, 'logout'])->name('logout');
    });
});

Route::prefix('/me')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ApiUserController::class, 'index']);
    Route::put('/', [ApiUserController::class, 'update']);
    Route::put('/password', [ApiUserController::class, 'updatePassword']);
    Route::delete('/', [ApiUserController::class, 'destroy']);
});

Route::resource('/news', ApiNewsController::class)
    ->only(['index', 'show']);

Route::resource('/products', ApiProductsController::class)
    ->only(['index', 'show']);
Route::get('/products/{product}/comments', [ApiProductsController::class, 'comments']);
Route::post('/products/{product}/comments', [ApiProductsController::class, 'storeComment'])
    ->middleware('auth:sanctum');

Route::resource('/orders', ApiOrdersController::class)
    ->only(['index', 'show', 'store', 'destroy'])
    ->middleware('auth:sanctum');
