<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\UserOrderMiddleware;
use App\Http\Middleware\AbleCreateUpdateItem;
use App\Http\Middleware\UserFinishOrderMiddleware;
use App\Http\Middleware\ManagerCreateUserMiddleware;

//Auth Login
Route::post('/login/auth',[AuthController::class,'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    // me
    Route::get('/me',[AuthController::class,'me']);

    // create order
    Route::post('/create_order', function(){
    return Auth::user() ;
    })->middleware([UserOrderMiddleware::class]);

    // finish order 
    Route::get('/finish_order', function () {
    return "finish order";
    })->middleware([UserFinishOrderMiddleware::class]);

    // create user khusus manager
    Route::post('/create_user',[UserController::class,'store'])->middleware([ManagerCreateUserMiddleware::class]);
    Route::post('/create_item',[UserController::class,'storeNewItem'])->middleware([AbleCreateUpdateItem::class]);
});