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
        //////  CARA 1
        // $data = OrderDetail::where('order_id', $order_id)
        //->with('Item:id,price')
        //->get();
        // $sum = $data->sum(function($orderDetail) {
        //     return $orderDetail->Item->price ?? 0;
        // });
        // return $sum;

        //////  CARA 2
        $prices = OrderDetail::where('order_id', $order_id)
        ->with('Item') // Memastikan hanya kolom id dan price dari Item yang diambil
        ->get()
        ->pluck('Item.price') // Mengambil hanya kolom price dari setiap Item terkait
        ->filter(); // Menghapus nilai null jika ada Item yang tidak memiliki price

        $sum = collect($prices)->sum();
        return $sum;
    }

    /**
     * Get all of the comments for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OrderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }
}