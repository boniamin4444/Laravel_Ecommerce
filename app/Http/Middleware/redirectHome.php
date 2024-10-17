<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class redirectHome
{
   
    public function handle(Request $request, Closure $next)
    {
        //If user not authenticated

        if(!Auth::check())
        {
            return redirect('/')->with('showLoginModal',true);
        }
        return $next($request);
    }
}
