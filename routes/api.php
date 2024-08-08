<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/user/store',[UserController::class, 'store']);
Route::post('/user/login',[AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('payments', PaymentController::class);
});    

