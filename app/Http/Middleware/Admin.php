<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // admin role = 1
        // user role = 0 
        if (Auth::check()) {
            if(Auth::user()->role == '1'){
                return $next($request);
            }
            else{
                return response()->json([
                    'message'=>'Access denied,not an admin'
                ],400);
                
            }
        }else{
            return response()->json([
                'message'=>'Access denied, please log in'
            ],400);
        }
        
    }
}
