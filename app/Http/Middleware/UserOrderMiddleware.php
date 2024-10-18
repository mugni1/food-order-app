<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserOrderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        //cek apakah yang login adalah waitress atau manager
        if(Auth::user()->role_id !=4 && Auth::user()->role_id !=1){
            // return abort(404);
            return response()->json(['message'=>'Not Found'], 404);
        }
        
        return $next($request);
        // return Auth::user();
    }
}