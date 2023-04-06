<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CsActivity;
use App\Models\Driver;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $orders = Survey::all();
        $users = User::all();
        return view('admin.index', compact('orders', 'users'));
    }

    public function analytics()
    {
        $activities = CsActivity::where('cs_id', Auth()->id())->latest()->paginate(25);
        return view('admin.analytics', compact('activities'));
    }

    public function activities(Request $request)
    {
//        Parameters
        $from = $_GET['from'] ?? date('Y-m-d', strtotime("-30 days"));
        $to = $_GET['to'] ?? date('Y-m-d', strtotime("+6 hours"));
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
        $activities = DB::table('cs_activities')
//            ->join('countries', 'countries.id', '=', 'surveys.country_id')
//            ->join('states', 'states.id', '=', 'surveys.state_id')
            ->join('users', 'cs_activities.cs_id', '=', 'users.id')
            ->select('cs_activities.*')
            ->whereBetween('cs_activities.created_at', [$from, $to])
//            ->where('surveys.status', 'LIKE', $status)
//            ->where('countries.id', 'LIKE', $country)
//            ->where('states.id', 'LIKE', $state)
            ->where(function ($contents) {
                $search = '%'.($_GET['search'] ?? "").'%';
                $contents->where('users.name', 'LIKE', $search)
                    ->orWhere('users.phone', 'LIKE', $search)
                    ->orWhere('users.email', 'LIKE', $search);
            })
            ->distinct()->get();
        foreach ($activities as $activity){
            $list_id[] = $activity->id;
        }
        $activities = CsActivity::orderBy($sorter, $arrange)->whereIn('id', $list_id)->get();

        return view('admin.activities', compact('activities'));
    }

    public function orders(Request $request)
    {
        //        Parameters
        $from = $_GET['from'] ?? date('Y-m-d', strtotime("-30 days"));
        $to = $_GET['to'] ?? date('Y-m-d', strtotime("+6 hours"));
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
//            ->join('countries', 'countries.id', '=', 'surveys.country_id')
//            ->join('states', 'states.id', '=', 'surveys.state_id')
            ->join('users', 'surveys.user_id', '=', 'users.id')
            ->select('surveys.*')
            ->whereBetween('surveys.created_at', [$from, $to])
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
        $orders = Survey::orderBy($sorter, $arrange)->whereIn('id', $list_id)->get();;
        return view('admin.orders', compact('orders'));
    }

    public function pending_orders(Request $request)
    {
        $orders = Survey::where('status', 0)->latest()->paginate(20);
        return view('admin.pending_orders', compact('orders'));
    }

    public function awaits_orders(Request $request)
    {
        $orders = Survey::where('status', 1)->latest()->paginate(20);
        return view('admin.awaits_orders', compact('orders'));
    }

    public function confirmed_orders(Request $request)
    {
        $orders = Survey::where('status', 2)->latest()->paginate(20);
        return view('admin.confirm_orders', compact('orders'));
    }

    public function finished_orders(Request $request)
    {
        $orders = Survey::where('status', '>=', 3)->latest()->paginate(20);
        return view('admin.finished_orders', compact('orders'));
    }

    public function cars(Request $request)
    {
        $cars = Car::all();
        return view('admin.cars', compact('cars'));
    }

    public function clients(Request $request)
    {
        // $users = User::all()->where('membership', 0);
        $users = User::all()->where('membership', 0);
        return view('admin.clients', compact('users'));
    }

    public function users(Request $request)
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function drivers(Request $request)
    {
        $users = User::all()->where('membership', 1);
        return view('admin.drivers', compact('users'));
    }
}
