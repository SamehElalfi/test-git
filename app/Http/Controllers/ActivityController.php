<?php

namespace App\Http\Controllers;

use App\Models\CsActivity;
use App\Models\DriverActivity;
use App\Models\Survey;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;

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
        $activities = CsActivity::all()->where('cs_id', $id);
        return view('activity.cs', compact('activities'));
    }

    public function seen()
    {
        $user_id = Auth::id();
        $driver_id = Auth::user()->driver->id ?? Auth::id();
        if (in_array(Auth::user()->membership, [0, 3])){
            $activities = UserActivity::all()->where('user_id', $user_id)->where('seen', 0);
            foreach ($activities as $sol_activity){
                $activity = UserActivity::find($sol_activity->id);
                $activity->seen = 1;
                $activity->save();
            }
        }elseif (Auth::user()->membership == 1){
            $activities = DriverActivity::all()->where('driver_id', $driver_id)->where('seen', 0);
            foreach ($activities as $sol_activity){
                $activity = DriverActivity::find($sol_activity->id);
                $activity->seen = 1;
                $activity->save();
            }
        }elseif (Auth::user()->membership == 2){
            $activities = CsActivity::all()->where('cs_id', $user_id)->where('seen', 0);
            foreach ($activities as $sol_activity){
                $activity = CsActivity::find($sol_activity->id);
                $activity->seen = 1;
                $activity->save();
            }
        }
    }
}
