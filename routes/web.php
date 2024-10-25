<?php

use App\Models\Order;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Order::findOrFail(4)->sumTotalPrice(4);
});