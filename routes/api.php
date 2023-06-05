<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Private\AboutController;
use App\Http\Controllers\Api\Private\PortfolioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('register', [AuthController::class, 'register'])->name('api.register');
Route::post('login', [AuthController::class, 'login'])->name('api.login');
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', function (Request $request) {
        return auth()->user();
    });

    Route::apiResource('private/portfolio', PortfolioController::class);
    Route::apiResource('private/about', AboutController::class);
    // API route for logout user
    Route::post('logout', [AuthController::class, 'logout'])->name('api.logout');
});
