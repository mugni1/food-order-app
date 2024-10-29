<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\select;

class OrderController extends Controller
{

    public function index(){
        $result = Order::select(['id','customer_name','table_no','order_date','order_time','status','total'])->get();
        return response()->json(["data"=>$result]);
    }

    public function show($id){
        $result = Order::select(['id','customer_name','table_no','status','waitress_id','cashier_id','total','order_date','order_time'])->findOrFail($id);
        return response()->json(["data"=> $result->loadMissing('OrderDetail:order_id,item_id','OrderDetail.Item:id,name,price','Waitress:id,name,email,role_id','Waitress.Role:id,name','Cashier:id,name,email,role_id','Waitress.Role:id,name')]);
    }

    public function store(Request $request){
        $request->validate([
            'customer_name' => 'required|max:225',
            'table_no' => 'required|max:5'
        ]);
        
        //////////////////////////////// ORDER TABLE //////////////////////////////////
        $data = $request->only(['customer_name','table_no']); // get request only 'customer_name' and 'table_no'
        // add addons to send order table
        $data['order_date'] = date('Y-m-d');
        $data['order_time'] = date('H:i:s');
        $data['status'] = "ordered";
        $data['total'] = 0;
        $data['waitress_id'] = Auth::user()->id;
        // send to order table 
        $result = Order::create($data);
        ///////////////////////////// END ORDER TABLE /////////////////////////////////

        /////////////////////////////// ORDER_DETAIL ////////////////////////////////////
        $items = $request->items; //get request items
        // collect all items and mapping all and send to order_detail table
        collect($items)->map(function($item) use($result) {
            $FoodDrink = Item::where('id', $item)->first(); // get firt item in item table 
            // send to order_detail table
            OrderDetail::create([
                'order_id' => $result->id,
                'item_id' => $item,
                'price' => $FoodDrink->price
            ]);
        });
         //////////////////////////// END ORDER_DETAIL //////////////////////////////////

        ////////////////////////////// EDIT TOTAL IN ORDER TABLE ///////////////////////////
        // change total column, before 0 to real price
        $result['total'] = function () use($result) {
            $orderDetail = OrderDetail::where('order_id', $result->id)->pluck('price'); // get all price on order id
            $sum = collect($orderDetail)->sum(); // jumlahkan semua harganya
            return $sum;
        };
        // send update to order table
        $result->save();
         ////////////////////////////// EDIT TOTAL IN ORDER TABLE ///////////////////////////

        //return 
        return response()->json(['data'=>$result->all()]);
    }
    
    public function setAsDone($id){
        //cari order dengan id yg sudah di tentukan di params
        $order = Order::findOrFail($id);

        //cek apakah status ordered
        if ($order->status != "ordered") {
            return response()->json(['message'=>'Cannot be read and edit this status'],403);
        }
        
        //ubah status menjadi done
        $order->status = "done";
        $order->save();


        return response()->json(["data"=>$order]);
    }
}