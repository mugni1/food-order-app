<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Order extends Model
{
    use HasFactory,SoftDeletes,HasApiTokens;

    protected $fillable = ['customer_name','table_no','order_date','order_time','status','total','waitress_id'];

    public function sumTotalPrice($order_id){
        $orderDetail = OrderDetail::where('order_id', $order_id)->pluck('price');
        return collect($orderDetail)->sum();
    }
}