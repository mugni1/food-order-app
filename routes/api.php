<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\UserOrderMiddleware;
use App\Http\Middleware\AbleCreateUpdateItem;
use App\Http\Middleware\AbleDoneOrder;
use App\Http\Middleware\AbleFinishOrder;
use App\Http\Middleware\AblePayOrder;
use App\Http\Middleware\UserFinishOrderMiddleware;
use App\Http\Middleware\ManagerCreateUserMiddleware;

//Auth Login
Route::post('/login/auth',[AuthController::class,'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    // me 
    Route::get('/me',[AuthController::class,'me']);
    // logout
    Route::get('/logout', [AuthController::class,'logout']);

    // ORDERS
    // list order
    Route::get('/order', [OrderController::class,'index']);
    // show order
    Route::get('/order/{id}', [OrderController::class,'show',]);
    // create order | ORDERED
    Route::post('/create_order',[OrderController::class,'store'])->middleware([UserOrderMiddleware::class]);
    // finish order | DONE
    Route::get('/order/{id}/set_done', [OrderController::class,'setAsDone'])->middleware([AbleFinishOrder::class]);
    // done pay order | PAID
    Route::get('/order/{id}/set_paid', [OrderController::class,'setAsPaid'])->middleware([AblePayOrder::class]);

    
    // USERS
    // create user khusus manager
    Route::post('/create_user',[UserController::class,'store'])->middleware([ManagerCreateUserMiddleware::class]);

    //ITEMS
    // create items
    Route::post('/create_item',[ItemController::class,'store'])->middleware([AbleCreateUpdateItem::class]);
    // update items
    Route::put('/item/{id}/update',[ItemController::class,'update'])->middleware([AbleCreateUpdateItem::class]);
});

//GET ITEMS LIST
Route::get('/items',[ItemController::class,'show']);