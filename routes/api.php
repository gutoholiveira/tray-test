<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('logout', 'logout')->middleware('auth:sanctum');
    });

    Route::middleware('auth:sanctum')->group(function () {

        Route::prefix('sellers')->controller(SellerController::class)->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::get('/{seller}', 'show');
            Route::put('/{seller}', 'update');
            Route::delete('/{seller}', 'destroy');
            Route::get('/{seller}/send-mail', 'sendReport');
        });

        Route::prefix('sales')->controller(SaleController::class)->group(function(){
            Route::get('', 'index');
            Route::post('', 'store');
            Route::get('/{sale}', 'show');
            Route::put('/{sale}', 'update');
            Route::delete('/{sale}', 'destroy');
            Route::get('/by/{seller}', 'getSalesBySeller');
        });
    });
});
