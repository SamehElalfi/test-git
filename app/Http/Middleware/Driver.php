<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Driver
{
    public function handle($request, Closure $next)
    {
        $membership = Auth::user()->membership;
        if ($membership != 1) {
            return redirect('home');
        }
        return $next($request);
    }
}
