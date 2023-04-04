<?php

namespace App\Http\Controllers;

use App\Models\CsActivity;
use App\Models\DriverActivity;
use App\Models\Survey;
use App\Models\User;
use App\Models\UserActivity;

class ActivityController extends Controller
{
    public function index()
    {
        $orders = Survey::all();
        $users = User::all();
        return view('admin.index', compact('orders', 'users'));
    }

    public function user($id)
    {
        $user = User::find($id);
        if ($user == ""){
            return redirect()->back();
        }
        $activities = UserActivity::all()->where('user_id', $user->id);
        return view('activity.user', compact('activities'));
    }

    public function driver($id)
    {
        $user = User::find($id);
        if ($user == ""){
            return redirect()->back();
        }
        if (!isset($user->driver)){
            return redirect()->back();
        }else{
            $driver_id = $user->driver->id;
        }
        $activities = DriverActivity::all()->where('driver_id', $driver_id);
        return view('activity.driver', compact('activities'));
    }

    public function cs($id)
    {
        $activities = CsActivity::all()->where('user_id', $id);
        return view('admin.index', compact('activities'));
    }
}
