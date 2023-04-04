<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CS
{
    public function handle($request, Closure $next)
    {
        $membership = Auth::user()->membership;
        if ($membership < 2) {
            return redirect('home');
        }
        return $next($request);
    }
}
