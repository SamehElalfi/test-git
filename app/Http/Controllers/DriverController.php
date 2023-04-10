<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\State;
use App\Models\Driver;
use App\Models\Survey;
use App\Models\Country;
use App\Models\DriverActivity;

class DriverController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id());
        $countries = Country::all();
        $states = State::all();
        return view('driver.show', compact('user', 'states', 'countries'));
    }

    public function show($id)
    {
        $user = User::find($id);
        if ($user == "") {
            return redirect("home");
        }
        $countries = Country::all();
        $states = State::all();
        return view('driver.profile', compact('user', 'states', 'countries'));
    }

    public function analytics($id)
    {
        $curr_id = Auth::user()->driver->id ?? "";
        if ($id != $curr_id) {
            if (Auth::user()->membership < 3) {
                return redirect()->back();
            }
        }
        $activities = DriverActivity::all()->where('driver_id', $id);
        return view('driver.analytics', compact('activities'));
    }

    public function users(Request $request)
    {
        //        Parameters
        $from = $_GET['from'] ?? date('Y-m-d', strtotime("-30 days"));
        $to = $_GET['to'] ?? date('Y-m-d', strtotime("+6 hours"));
        $driver_id = Auth()->user()->driver->id ?? Auth()->id();
        $search = '%'.($_GET['search'] ?? "").'%';

        //        Sort
        $sort = $_GET['sort'] ?? 'created_at$$$DESC';
        $sorter_array = explode('$$$', $sort);
        $sorter = $sorter_array[0];
        $arrange = $sorter_array[1];

        $list_id = [];
        $users = DB::table('surveys')
            ->join('users', 'surveys.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->select('users.*')
            ->whereBetween('users.created_at', [$from, $to])
            ->where('users.membership', 0)
            ->where('surveys.driver_id', $driver_id)
            ->where(function ($contents) {
                $search = '%'.($_GET['search'] ?? "").'%';
                $contents->where('users.name', 'LIKE', $search)
                    ->orWhere('users.phone', 'LIKE', $search)
                    ->orWhere('users.email', 'LIKE', $search)
                    ->orWhere('profiles.fullname', 'LIKE', $search)
                    ->orWhere('profiles.age', 'LIKE', $search)
                    ->orWhere('profiles.other_phone', 'LIKE', $search)
                    ->orWhere('profiles.relation', 'LIKE', $search)
                    ->orWhere('profiles.redirect', 'LIKE', $search);
            })
            ->distinct()->get();
        foreach ($users as $user){
            $list_id[] = $user->id;
        }
        $users = User::orderBy($sorter, $arrange)->whereIn('id', $list_id)->latest()->paginate(20);

        return view('driver.users', compact('users'));
    }

    public function orders(Request $request)
    {
        //        Parameters
        $from = $_GET['from'] ?? date('Y-m-d', strtotime("-30 days"));
        $to = $_GET['to'] ?? date('Y-m-d', strtotime("+6 hours"));
        $driver_id = Auth()->user()->driver->id ?? Auth()->id();
        $search = '%'.($_GET['search'] ?? "").'%';
        $status = '%'.($_GET['status'] ?? 'all').'%';
        $status = str_replace('all', "", $status);
        $country = '%'.($_GET['country'] ?? 'all').'%';
        $country = str_replace('all', "", $country);
        $state = '%'.($_GET['state'] ?? 'all').'%';
        $state = str_replace('all', "", $state);

        //        Sort
        $sort = $_GET['sort'] ?? 'created_at$$$DESC';
        $sorter_array = explode('$$$', $sort);
        $sorter = $sorter_array[0];
        $arrange = $sorter_array[1];

        $list_id = [];
        $orders = DB::table('surveys')
            ->join('users', 'surveys.user_id', '=', 'users.id')
            ->select('surveys.*')
            ->whereBetween('surveys.created_at', [$from, $to])
            ->where('surveys.driver_id', $driver_id)
            ->where('surveys.status', 'LIKE', $status)
            ->where('surveys.country_id', 'LIKE', $country)
            ->where('surveys.state_id', 'LIKE', $state)
            ->where(function ($contents) {
                $search = '%'.($_GET['search'] ?? "").'%';
                $contents->where('users.name', 'LIKE', $search)
                    ->orWhere('users.phone', 'LIKE', $search)
                    ->orWhere('users.email', 'LIKE', $search);
            })
            ->distinct()->get();
        foreach ($orders as $order){
            $list_id[] = $order->id;
        }
        $orders = Survey::orderBy($sorter, $arrange)->whereIn('id', $list_id)->latest()->paginate(20);

        return view('driver.surveys', compact('orders'));
    }

    public function done_orders(Request $request)
    {
        $from = date($request->from ?? date('Y-m-d', strtotime("-7 days")));
        $to = date($request->to ?? date('Y-m-d', strtotime("+1 days")));

        $sort = $_GET['sort'] ?? 'created_at$$$DESC';
        $sorter_array = explode('$$$', $sort);
        $sorter = $sorter_array[0];
        $arrange = $sorter_array[1];

        $surveys = DB::table('surveys')
            ->join('users', 'users.id', '=', 'surveys.user_id')
            ->join('drivers', 'drivers.id', '=', 'surveys.driver_id')
            ->select('surveys.*')
            ->orderBy('surveys.' . $sorter, $arrange)
            ->where('drivers.user_id', Auth::id())
            ->whereIn('surveys.status', [3, 4])
            ->whereBetween('surveys.created_at', [$from, $to])
            ->where(function ($contents) {
                if (isset($_GET['doc_name'])) {
                    $search = '%' . $_GET['search'] . '%';
                } else {
                    $search = '%%';
                }
            })
            ->get();
        $id_surveys = [];
        foreach ($surveys as $survey) {
            $id_surveys[] = $survey->id;
            //            echo "ID:- $survey->id" . "<br>";
        }
        $orders = Survey::whereIn("id", $id_surveys)->latest()->paginate(20);
        return view('driver.surveys', compact('orders'));
    }

    public function pending_orders(Request $request)
    {
        $from = date($request->from ?? date('Y-m-d', strtotime("-7 days")));
        $to = date($request->to ?? date('Y-m-d', strtotime("+1 days")));

        $sort = $_GET['sort'] ?? 'created_at$$$DESC';
        $sorter_array = explode('$$$', $sort);
        $sorter = $sorter_array[0];
        $arrange = $sorter_array[1];

        $surveys = DB::table('surveys')
            ->join('users', 'users.id', '=', 'surveys.user_id')
            ->join('drivers', 'drivers.id', '=', 'surveys.driver_id')
            ->select('surveys.*')
            ->orderBy('surveys.' . $sorter, $arrange)
            ->where('drivers.user_id', Auth::id())
            ->whereIn('surveys.status', [1, 2])
            ->whereBetween('surveys.created_at', [$from, $to])
            ->where(function ($contents) {
                if (isset($_GET['doc_name'])) {
                    $search = '%' . $_GET['search'] . '%';
                } else {
                    $search = '%%';
                }
            })
            ->get();
        $id_surveys = [];
        foreach ($surveys as $survey) {
            $id_surveys[] = $survey->id;
            //            echo "ID:- $survey->id" . "<br>";
        }
        $orders = Survey::whereIn("id", $id_surveys)->latest()->paginate(20);
        return view('driver.surveys', compact('orders'));
    }

    public function update(Request $request, Driver $driver)
    {
        $user = User::find(Auth::id());
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'fullname' => ['required', 'string', 'max:255'],
            'password' => ['required'],
        ]);

        if (!Hash::check($request['password'], $user->password)) {
            return redirect()->back()->with('error', 'كلمة السر خاطئة');
        }
        if (isset($request->c_password)) {
            $user->password = Hash::make($request->c_password);
        }

        $user->name = $request->name ?? $user->name;
        if (isset($user->driver)) {
            $user->driver->fullname = $request->fullname ?? $user->driver->fullname;
            $user->driver->bio = $request->bio ?? $user->driver->bio;
            $user->driver->personal_id = $request->personal_id ?? $user->driver->personal_id;
            $user->driver->country_id = $request->country ?? $user->driver->country_id;
            $user->driver->state_id = $request->state ?? $user->driver->state_id;
            $user->driver->fullname = $request->fullname ?? $user->driver->fullname;
            $user->driver->status = $request->status ?? 0;
        }

        $user->driver->save();
        $user->save();
        return redirect()->back()->with('success', 'تم التعديل بنجاح');
    }

    public function info_driver($id)
    {
        $user = User::find($id);
        if ($user == "") {
            return redirect("home");
        }
        $countries = Country::all();
        $states = State::all();
        return view('driver.driver_info', compact('user', 'states', 'countries'));
    }

    public function panel()
    {
        $orders = Survey::all()->where('status', '>=', 2);
        return view('driver.index', compact('orders'));
    }

    public function update_driver(Request $request, $id)
    {
        $user = User::find($id);
        if ($user == "") {
            return redirect("home");
        }

        if (isset($request->c_password)) {
            $user->password = Hash::make($request->c_password);
        }

        $user->name = $request->name ?? $user->name;
        if (isset($user->driver)) {
            $user->driver->fullname = $request->fullname ?? $user->driver->fullname;
            $user->driver->bio = $request->bio ?? $user->driver->bio;
            $user->driver->personal_id = $request->personal_id ?? $user->driver->personal_id;
            $user->driver->country_id = $request->country ?? $user->driver->country_id;
            $user->driver->state_id = $request->state ?? $user->driver->state_id;
            $user->driver->fullname = $request->fullname ?? $user->driver->fullname;
            $user->driver->status = $request->status ?? 0;
        }


//        Changing membership
        if (Auth()->user()->membership == 3 and in_array($request->membership, [0, 1, 2])){
            if ($user->membership != $request->membership){
                $user->membership = $request->membership;
                if ($user->membership == 0){
                    if (isset($user->driver)){
                        $user->driver->delete();
                    }
                }elseif ($user->membership == 1){
                    if (isset($user->profile)){
                        $user->profile->delete();
                    }
                    if (!isset($user->driver)){
                        Driver::create([
                            "user_id" => $user->id,
                            "fullname" => $user->name,
                            "bio" => "مرحبا انا سائق, ......",
                            "country_id" => 1,
                            "state_id" => 1,
                            "photo" => "user.png",
                        ]);
                    }
                }elseif ($user->membership == 2){
                    if (isset($user->driver)){
                        $user->driver->delete();
                    }
                    if (isset($user->profile)){
                        $user->profile->delete();
                    }
                }
            }
        }

        if (isset($user->driver)){
            $user->driver->save();
        }
        $user->save();
        return redirect()->back()->with('success', 'تم التعديل بنجاح');
    }
}
