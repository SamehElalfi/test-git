<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Analytics
{
    public function handle($request, Closure $next)
    {
        $membership = Auth::user()->membership;
        $id = $request->route()->parameter('id');
        if ($membership < 2) {
            if (Auth::id() != $id) {
                return redirect('home');
            }
        }
        return $next($request);
    }
}
