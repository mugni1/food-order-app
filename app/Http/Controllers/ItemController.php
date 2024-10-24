<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|integer',
            'image' => 'mimes:png,jpg'
        ]);
        
        $newNameImage = null; //nama default null jika tidak ada gambar yg di upload

        if($request->file('image')){
            $exten = $request->file('image')->extension(); // get extensi file
            $name = strtolower(str_replace(' ','', $request->name)); // mengubah nama menjadi hruf kecil dan tanpa spasi
            $newNameImage = $name . '-' . now()->timestamp . '.' . $exten; // merangkai nama baru untuk gambar
            $request->file('image')->storeAs('img', $newNameImage); // simpan ke public storage
        };
        
        // STORE KE DATABASE 
        $dataPost = $request->all();
        $dataPost['image'] = $newNameImage;
        Item::create($dataPost);

        //RETURN 
        return response()->json(["status"=>"Succes create new item","data"=>$dataPost]);
    }

    public function update($id, Request $request){
        $request->validate([
            'name' => 'required|max:225',
            'price' => 'required|integer',
            'image' => 'mimes:png,jpg'
        ]);

        //jalan kan jika ada gambar yg di upload
        if($request->file('image')){
            $exten = $request->file('image')->extension();
            $name = strtolower(str_replace(' ','', $request->name));
            $newNameImage = $name . '_' . now()->timestamp . '.' . $exten;
            $request->file('image')->storeAs('img', $newNameImage);

            $data = $request->all();
            $data['image'] = $newNameImage;
            
            //STORE JIKA ADA FILE BARU
            $update = Item::findOrFail($id);
            $update->update($data);
            
            return response()->json(['status'=>"Succes Update Item","data"=>$update]);
        }

        //STORE JIKA TIDAK ADA FILE BARU
        $update = Item::findOrFail($id);
        $update->update($request->all()); 

        return response()->json(['status'=>"Succes Update Item","data"=>$update]);
    }

    public function show(){
        $items = Item::get(['id','name','price','created_at','updated_at',]);
        return response()->json(["data"=>$items]);
    }
}