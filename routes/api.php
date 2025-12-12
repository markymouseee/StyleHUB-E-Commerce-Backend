<?php

use App\Http\Controllers\Api\FrontendController;
use App\Http\Controllers\Auth\AuthenticatedTokenController;
use App\Http\Controllers\Auth\RegisterUserController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/email/verify', function () {
    return 'Please verify your email';
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [RegisterUserController::class, 'verifyEmail'])
    ->middleware('signed')->name('verification.verify');

Route::get('login', [FrontendController::class, 'login'])
    ->name('login');

Route::post('/login', [AuthenticatedTokenController::class, 'store']);
Route::post('/register', [RegisterUserController::class, 'store']);

Route::group(['prefix' => '/', 'middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthenticatedTokenController::class, 'destroy']);
});
