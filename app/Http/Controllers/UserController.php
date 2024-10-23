<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request){
        $request->validate([
            "name" => 'required|max:255',
            "email" => 'required|email|unique:users',
            "password" => 'required',
            "role_id" => 'required'
        ]);
        
        User::create($request->all());
        return response()->json(["data"=>$request->all()]);
    }
    
    public function storeNewItem(Request $request){
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
}