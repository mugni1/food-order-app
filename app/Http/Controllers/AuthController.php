<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\Cast\Unset_;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //auth for user
    public function login(Request $request){
         //validasi terlebih dahulu
         $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // cek email apakah adda
        $result = User::where('email', $request->email)->first();
        
          //jika email notfound atau password yang di input notfound
        if ($result == false || Hash::check($request->password, $result->password) == false) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        
        // unset beberapa detail login
        unset($result->email_verified_at);
        unset($result->created_at);
        unset($result->updated_at);

        // delete token sebelumnya | untuk mengatasi multiple login
        $result->tokens()->delete();
        // create new token
        $token = $result->createToken("User Login")->plainTextToken;
        
        // add token ke detail user
        $result->token = $token;

        // return | tampikan hasil 
        return response()->json(['data'=>$result]);
    }
    
    public function logout(Request $request){
        // cara 1
        //$user = Auth::user()->tokens()->delete();

        //cara 2
        $request->user()->tokens()->delete();

        //return message succes delete token
        return response()->json(['message'=>'Succes Logout']);
    }

    public function me(){
        $user = Auth::user();
        return response()->json(["data"=> $user->loadMissing('Role:id,name')]);
    }
}