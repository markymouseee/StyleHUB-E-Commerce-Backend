<?php

use App\Http\Controllers\Api\FrontendController;
use App\Http\Controllers\Auth\AuthenticatedTokenController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Product\ProductController;
use Illuminate\Support\Facades\Route;


Route::get('/email/verify', function () {
    return 'Please verify your email';
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [RegisterUserController::class, 'verifyEmail'])
    ->middleware('signed')->name('verification.verify');

Route::get('login', [FrontendController::class, 'login'])
    ->name('login');

Route::post('/login', [AuthenticatedTokenController::class, 'store']);

Route::post('/register', [RegisterUserController::class, 'store']);

Route::get('/get-all-products', [ProductController::class, 'getAll']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/store-product', [ProductController::class, 'store']);

    Route::get('/get-products', [ProductController::class, 'index']);

    Route::get('/get-categories', [CategoryController::class, 'index']);

    Route::post('/store-category', [CategoryController::class, 'store']);

    Route::delete('/delete-category/{id}', [CategoryController::class, 'destroy']);

    Route::post('/cart/add', [CartController::class, 'store']);

    Route::get('/get-cart-count', [CartController::class, 'index']);

    Route::get('/cart/items/{id}', [CartController::class, 'show']);

    Route::delete('/cart/remove/{id}', [CartController::class, 'destroy']);

    Route::post('/cart/checkout', [CartController::class, 'checkOut']);

    Route::get('/get-orders', [OrderController::class, 'index']);

    Route::post('/orders/approve', [OrderController::class, 'approve']);
    Route::post('/orders/{order}/decline', [OrderController::class, 'decline']);
});
