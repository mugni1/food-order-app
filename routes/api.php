<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\UserFinishOrderMiddleware;
use App\Http\Middleware\UserOrderMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

//Auth Login
Route::post('/login/auth',[AuthController::class,'login']);

//create order
Route::get('/create_order', function(){
    return "create order";
})->middleware(['auth:sanctum',UserOrderMiddleware::class]);

//finish order 
Route::get('/finish_order', function () {
    return "finish order";
})->middleware(['auth:sanctum',UserFinishOrderMiddleware::class]);