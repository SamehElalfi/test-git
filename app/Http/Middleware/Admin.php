<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    public function handle($request, Closure $next)
    {
        $membership = Auth::user()->membership;

        if ($membership != 3) {
            return redirect("home");
        }

        return $next($request);
    }
}
