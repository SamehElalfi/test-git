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
        $from = date($request->from ?? date('Y-m-d', strtotime("-7 days")));
        $to = date($request->to ?? date('Y-m-d', strtotime("+1 days")));

        $sort = $_GET['sort'] ?? 'created_at$$$DESC';
        $sorter_array = explode('$$$', $sort);
        $sorter = $sorter_array[0];
        $arrange = $sorter_array[1];

        $users = DB::table('surveys')
            ->join('users', 'users.id', '=', 'surveys.user_id')
            ->join('drivers', 'drivers.id', '=', 'surveys.driver_id')
            ->select('users.*')
            ->where('drivers.user_id', Auth::id())
            ->orderBy('surveys.' . $sorter, $arrange)
            ->whereBetween('surveys.created_at', [$from, $to])
            ->where(function ($contents) {
                if (isset($_GET['doc_name'])) {
                    $search = '%' . $_GET['search'] . '%';
                } else {
                    $search = '%%';
                }
            })
            ->distinct()->get();
        $id_users = [];
        foreach ($users as $user) {
            $id_users[] = $user->id;
            //            echo "ID:- $survey->id" . "<br>";
        }
        $users = User::whereIn("id", $id_users)->latest()->paginate(20);
        return view('driver.users', compact('users'));
    }

    public function orders(Request $request)
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
            $user->driver->country_id = $request->country ?? $user->driver->country_id;
            $user->driver->state_id = $request->state ?? $user->driver->state_id;
            $user->driver->fullname = $request->fullname ?? $user->driver->fullname;
            $user->driver->status = $request->status ?? 0;
        }

        $user->driver->save();
        $user->save();
        return redirect()->back()->with('success', 'تم التعديل بنجاح');
    }
}
