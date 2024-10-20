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
        
        // buat token jika semua kondisi sudah memenuhi
        unset($result->email_verified_at);
        unset($result->created_at);
        unset($result->updated_at);

        // delete token sebelumnya
        $result->tokens()->delete();
        // create new token
        $token = $result->createToken("User Login")->plainTextToken;
        
        // add token ke detail user
        $result->token = $token;
        // retiurn hasil 
        return response()->json(['data'=>$result]);
    }

    public function me(){
        return response()->json(['data'=>Auth::user()]);
    }
}