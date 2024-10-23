<?php

namespace App\Http\Controllers;

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
}