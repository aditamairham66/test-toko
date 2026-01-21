<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\Sale\SaleController;
use App\Http\Controllers\Api\Store\StoreController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    /*
    |--------------------------------------------------------------------------
    | SUPER ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/stores', [StoreController::class, 'index']);
        Route::post('/stores', [StoreController::class, 'store']);
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);

        Route::post('/kasir', [UserController::class, 'storeKasir']);
        Route::get('/kasir', [UserController::class, 'listKasir']);
    });

    /*
    |--------------------------------------------------------------------------
    | KASIR
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:kasir')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/sales', [SaleController::class, 'store']);
        Route::get('/sales', [SaleController::class, 'index']);
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN & KASIR (PROFILE)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,kasir')->group(function () {
        Route::put('/profile', [UserController::class, 'updateProfile']);
    });
});
