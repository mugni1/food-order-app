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

    // LIST ORDER
    public function index(){
        $result = Order::orderBy('id', 'desc')->select(['id','customer_name','table_no','order_date','order_time','status','total'])->get();
        return response()->json(["data"=>$result]);
    }

    // SHOW DETAIL ORDER
    public function show($id){
        $result = Order::select(['id','customer_name','table_no','status','waitress_id','cashier_id','total','order_date','order_time'])->findOrFail($id);
        return response()->json(["data"=> $result->loadMissing('OrderDetail:order_id,item_id,price,qty','OrderDetail.Item:id,name','Waitress:id,name,email,role_id','Waitress.Role:id,name','Cashier:id,name,email,role_id','Waitress.Role:id,name')]);
    }

    // CREATE ORDER
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
            $FoodDrink = Item::where('id', $item[0])->first(); // get firt item in item table 
            // send to order_detail table
            OrderDetail::create([
                'order_id' => $result->id,
                'item_id' => $item[0],
                'price' => $FoodDrink->price,
                'qty' =>$item[1],
            ]);
        });
         //////////////////////////// END ORDER_DETAIL //////////////////////////////////

        // ////////////////////////////// EDIT TOTAL IN ORDER TABLE ///////////////////////////
        // change total column, before 0 to real price
        $result['total'] = function () use($result) {
            $orderDetail = OrderDetail::where('order_id', $result->id)->select('price','qty')->get(); // get all price on order id
            $priceQty = collect($orderDetail)->map(function($item){
                return $item->price * $item->qty;
            });
            $sum = collect($priceQty)->sum(); // jumlahkan semua harganya
            return $sum;
        };
        // send update to order table
        $result->save();
        //  ////////////////////////////// EDIT TOTAL IN ORDER TABLE ///////////////////////////

        // //return 
        return response()->json(['data'=>$result->all()]);
    }
    
    // SET AS DONE ORDER
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

    // SET AS PAID ORDER
    public function setAsPaid($id){
        //cari order dengan id yg sudah di tentuukan di params
        $order = Order::findOrFail($id);
        
        //cek order apakah status done
        if ($order->status != "done") {
            return response()->json(['message'=>'Cannot be read and edit this status'],403);
        }
        
        $order->status = "paid";
        $order->save();
        return response()->json(['data'=>$order]);
    }

    //DELETE
    public function drop($id){
        $order = Order::findOrFail($id);
        $order->delete();
        
        return response()->json(['message'=>'Success delete order']);
    }
    
    // ORDER REPORT
    public function orderReport(Request $request){
        $result = Order::whereMonth('order_date', $request->month)->orderBy('id', 'desc')->select(['id','customer_name','table_no','order_date','order_time','status','total'])->get();
        $countResult =  Order::whereMonth('order_date', $request->month)->orderBy('id', 'desc')->select(['id','customer_name','table_no','order_date','order_time','status','total'])->count();
        $maxPayment =  Order::whereMonth('order_date', $request->month)->orderBy('id', 'desc')->select(['id','customer_name','table_no','order_date','order_time','status','total'])->max('total');
        $minPayment =  Order::whereMonth('order_date', $request->month)->orderBy('id', 'desc')->select(['id','customer_name','table_no','order_date','order_time','status','total'])->min('total');

        $data = [
            'orderCount' => $countResult,
            'maxPayment' => $maxPayment,
            'minPayment' => $minPayment,
            'orderList' => $result
        ];
        return response()->json(["data"=>$data]);
    }
}