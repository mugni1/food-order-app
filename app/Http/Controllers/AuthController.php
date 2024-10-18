<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        return $result->createToken("User Login")->plainTextToken;
    }
}