<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'as'], function () {
    
    //auth
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    });

    Route::group(['middleware' => 'auth:api'], function () {
        //auth
        Route::prefix('auth')->group(function () {
            Route::get('logout', [AuthController::class, 'logout']);
            Route::get('refresh', [AuthController::class, 'refresh']);
            Route::get('me', [AuthController::class, 'me']);
        });
    });
});