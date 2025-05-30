<?php

use App\Http\Controllers\ApiNewsController;
use App\Http\Controllers\ApiOrdersController;
use App\Http\Controllers\ApiProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthenticationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/auth')->group(function () {
    Route::post('/login', [ApiAuthenticationController::class, 'login'])->name('login');
    Route::post('/register', [ApiAuthenticationController::class, 'register'])->name('register');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [ApiAuthenticationController::class, 'logout'])->name('logout');
    });
});

Route::resource('/news', ApiNewsController::class)
    ->only(['index', 'show']);

Route::resource('/products', ApiProductsController::class)
    ->only(['index', 'show']);

Route::resource('/orders', ApiOrdersController::class)
    ->middleware('auth:sanctum');
