<?php

use App\Http\Controllers\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API v1
Route::prefix('v1')->group(function () {
    // Product Comments
    Route::get('/products/{product}/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('/products/{product}/comments', [CommentController::class, 'store'])
        ->name('comments.store')
        ->middleware('auth:sanctum');
});
