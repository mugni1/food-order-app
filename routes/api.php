<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\ManagerCreateUserMiddleware;
use App\Http\Middleware\UserOrderMiddleware;
use App\Http\Middleware\UserFinishOrderMiddleware;

//Auth Login
Route::post('/login/auth',[AuthController::class,'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    // me
    Route::get('/me',[AuthController::class,'me']);

    // create order
    Route::get('/create_order', function(){
    return Auth::user() ;
    })->middleware([UserOrderMiddleware::class]);

    // finish order 
    Route::get('/finish_order', function () {
    return "finish order";
    })->middleware([UserFinishOrderMiddleware::class]);

    // create user khusus manager
    Route::post('/create_user',function(){
        return "Created User";
    })->middleware([ManagerCreateUserMiddleware::class]);
});