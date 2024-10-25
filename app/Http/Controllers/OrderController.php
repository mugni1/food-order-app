<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'customer_name' => 'required|max:225',
            'table_no' => 'required|max:5'
        ]);

        //ambil dat tampung data yang di request
        $data = $request->only(['customer_name','table_no']); // tampung nama dan table_no saja

        // tambahan | otomatis 
        $data['order_date'] = date('Y-m-d');
        $data['order_time'] = date('H:i:s');
        $data['status'] = "ordered";
        $data['total'] = 0;
        $data['waitress_id'] = Auth::user()->id;
        // list items
        $data['items'] = $request->items;

        // kirim ke Table Order
        $result = Order::create($data);

        collect($data['items'])->map(function($item) use($result) {
            $FoodDrink = Item::where('id', $item)->first();
            OrderDetail::create([
                'order_id' => $result->id,
                'item_id' => $item,
                'price' => $FoodDrink->price
            ]);
        });

        // kirm dan edit column total dari 0 menjadi total dari price
        $result['total'] = $result->sumTotalPrice($result->id);
        $result->save();

        //return 
        return response()->json(['data'=>$result->all()]);
    }
}