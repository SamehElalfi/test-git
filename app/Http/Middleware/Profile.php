<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use App\Models\Driver;

class Profile
{

    public function handle($request, Closure $next)
    {
        if (Auth::check() == 1) {
            $user = Auth::user();
            $membership = Auth::user()->membership;
            if ($membership == 1 and !isset($user->driver)) {
                $user->driver = Driver::create([
                    "user_id" => $user->id,
                    "fullname" => $user->name,
                    "bio" => "مرحبا انا سائق, ......",
                    "country_id" => 1,
                    "state_id" => 1,
                    "photo" => "user.png",
                ]);
            }
        }
        return $next($request);
    }
}
