<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterUserController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [RegisterUserController::class, 'store']);

Route::group(['prefix' => '/', 'middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});
