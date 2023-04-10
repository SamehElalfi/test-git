<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Block
{
    public function handle($request, Closure $next)
    {
        if (Auth()->user()->block == 1) {
            return redirect('home')->with('error', 'ليس لديك الصلاحية لاداء تلك العملية لأن حسابك محظور');
        }
        return $next($request);
    }
}
