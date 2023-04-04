<?php

namespace App\Http\Middleware;

use App\Models\Survey;
use Illuminate\Support\Facades\Auth;
use Closure;

class OrderStatus
{
    public function handle($request, Closure $next)
    {
        $membership = Auth::user()->membership;
        if ($membership < 2) {
            return redirect('home');
        }
        $order_id = $request->route()->parameter('id');
        $status = $_POST['status'] ?? "";
        $order = Survey::find($order_id);

        if ($order == "" or $status == "") {
            return redirect()->back();
        } else {
            if ($order->status == 0 and in_array($status, [0, 2, 3])) {
                return redirect()->back();
            } elseif ($order->status == 1 and in_array($status, [0, 1, 3])) {
                return redirect()->back();
            } elseif ($order->status == 2 and in_array($status, [0, 1, 2])) {
                return redirect()->back();
            } elseif ($order->status == 3 and in_array($status, [0, 1, 2, 3, 4])) {
                return redirect()->back();
            } elseif ($order->status == 4 and in_array($status, [0, 1, 2, 3, 4])) {
                return redirect()->back();
            }
        }

        return $next($request);
    }
}
