<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\OrderController;
use App\Models\User;




// Admin Routes
Route::post('user/register', [UserController::class, 'register']);
Route::post('user/login', [UserController::class, 'login']);

Route::post('user/logout', [UserController::class, 'logout'])->middleware('auth:api');

// Size Routes
Route::middleware('auth:api')->group(function () {
    Route::post('/customer/size', [SizeController::class, 'store']);
    Route::get('/customer/size', [SizeController::class, 'index']);
    Route::get('/customer/size/{id}', [SizeController::class, 'show']);
    Route::put('/customer/size/{id}', [SizeController::class, 'update']);
    Route::delete('/customer/size/{id}', [SizeController::class, 'delete']);
    Route::get('/customer/size/getByCustomer/{customer_id}', [SizeController::class, 'getByCustomer']);
});

// Customer Routes
Route::middleware('auth:api')->group(function () {
    Route::post('/customer', [CustomerController::class, 'create']);
    Route::get('/customer', [CustomerController::class, 'index']);
    Route::get('/customer/{id}', [CustomerController::class, 'show']);
    Route::put('/customer/{id}', [CustomerController::class, 'update']);
    Route::delete('/customer/{id}', [CustomerController::class, 'delete']);
});

// Order Routes
Route::middleware('auth:api')->group(function () {
    Route::post('/order', [OrderController::class, 'create']);
    Route::get('/order', [OrderController::class, 'index']);
    Route::get('/order/{id}', [OrderController::class, 'show']);
    Route::put('/order/{id}', [OrderController::class, 'update']);
    Route::patch('/order/{id}', [OrderController::class, 'setStatus']);
    Route::delete('/order/{id}', [OrderController::class, 'delete']);
}); 




