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

    public function incomes()
    {
        //        Parameters

        $min_price = $_GET['min-price'] ?? '0';
        $max_price = $_GET['max-price'] ?? '1000';
        $date = $_GET['date'] ?? 30;
        if ($date == "all"){
            $from = $_GET['from'] ?? date('Y-m-d', strtotime("-30 days"));
            $to = $_GET['to'] ?? date('Y-m-d', strtotime("+6 hours"));
        }else{
            $from = date('Y-m-d', strtotime("-".$date."days"));
            $to = date('Y-m-d', strtotime("+6 hours"));
        }
        $user_id = '%'.($_GET['user_id'] ?? "").'%';

        //        Sort
        $sort = $_GET['sort'] ?? 'created_at$$$DESC';
        $sorter_array = explode('$$$', $sort);
        $sorter = $sorter_array[0];
        $arrange = $sorter_array[1];

        $incomes = Survey::orderBy($sorter, $arrange)
            ->whereBetween('created_at', [$from, $to])
            ->whereBetween('cost', [$min_price, $max_price])
            ->where('user_id', 'LIKE', $user_id)
            ->latest()->paginate(30);

        return view('admin.incomes', compact('incomes'));
    }

    public function activities(Request $request)
    {
//        Parameters
        $from = $_GET['from'] ?? date('Y-m-d', strtotime("-30 days"));
        $to = $_GET['to'] ?? date('Y-m-d', strtotime("+6 hours"));
        $search = '%'.($_GET['search'] ?? "").'%';

        //        Sort
        $sort = $_GET['sort'] ?? 'created_at$$$DESC';
        $sorter_array = explode('$$$', $sort);
        $sorter = $sorter_array[0];
        $arrange = $sorter_array[1];

        $list_id = [];
        $activities = DB::table('cs_activities')
            ->join('users', 'cs_activities.cs_id', '=', 'users.id')
            ->select('cs_activities.*')
            ->whereBetween('cs_activities.created_at', [$from, $to])
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
        $activities = CsActivity::orderBy($sorter, $arrange)->whereIn('id', $list_id)->latest()->paginate(20);

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
        $min_price = $_GET['min-price'] ?? '0';
        $max_price = $_GET['max-price'] ?? '1000';

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
            ->whereBetween('surveys.cost', [$min_price, $max_price])
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
        return view('admin.orders', compact('orders'));
    }

    public function pending_orders(Request $request)
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
        $min_price = $_GET['min-price'] ?? '0';
        $max_price = $_GET['max-price'] ?? '1000';

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
            ->whereBetween('surveys.cost', [$min_price, $max_price])
            ->where('surveys.status', 'LIKE', 0)
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

        return view('admin.pending_orders', compact('orders'));
    }

    public function awaits_orders(Request $request)
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
        $min_price = $_GET['min-price'] ?? '0';
        $max_price = $_GET['max-price'] ?? '1000';

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
            ->whereBetween('surveys.cost', [$min_price, $max_price])
            ->where('surveys.status', 'LIKE', 1)
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

        return view('admin.awaits_orders', compact('orders'));
    }

    public function confirmed_orders(Request $request)
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
        $min_price = $_GET['min-price'] ?? '0';
        $max_price = $_GET['max-price'] ?? '1000';

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
            ->whereBetween('surveys.cost', [$min_price, $max_price])
            ->where('surveys.status', 'LIKE', 2)
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

        return view('admin.confirm_orders', compact('orders'));
    }

    public function finished_orders(Request $request)
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
        $min_price = $_GET['min-price'] ?? '0';
        $max_price = $_GET['max-price'] ?? '1000';

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
            ->whereBetween('surveys.cost', [$min_price, $max_price])
            ->whereIn('surveys.status', [3, 4])
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

        return view('admin.finished_orders', compact('orders'));
    }

    public function go_back_orders(Request $request)
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
        $min_price = $_GET['min-price'] ?? '0';
        $max_price = $_GET['max-price'] ?? '1000';

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
            ->whereBetween('surveys.cost', [$min_price, $max_price])
            ->where('surveys.status', 'LIKE', $status)
            ->where('surveys.country_id', 'LIKE', $country)
            ->where('surveys.state_id', 'LIKE', $state)
            ->where('surveys.type_tour', 1)
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
        return view('admin.go_back_orders', compact('orders'));
    }

    public function orders_car(Request $request)
    {
        $cars = Car::all();

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
        $min_price = $_GET['min-price'] ?? '0';
        $max_price = $_GET['max-price'] ?? '1000';
        $car = '%'.($_GET['car'] ?? "all").'%';
        $car = str_replace('all', "", $car);

        //        Sort
        $sort = $_GET['sort'] ?? 'created_at$$$DESC';
        $sorter_array = explode('$$$', $sort);
        $sorter = $sorter_array[0];
        $arrange = $sorter_array[1];

        $list_id = [];
        $orders = DB::table('surveys')
            ->join('users', 'surveys.user_id', '=', 'users.id')
            ->join('cars', 'cars.id', '=', 'surveys.car_id')
            ->select('surveys.*')
            ->whereBetween('surveys.created_at', [$from, $to])
            ->whereBetween('surveys.cost', [$min_price, $max_price])
            ->where('surveys.car_id', 'LIKE', $car)
            ->where('surveys.status', 'LIKE', $status)
            ->where('surveys.country_id', 'LIKE', $country)
            ->where('surveys.state_id', 'LIKE', $state)
            ->where('cars.id', 'LIKE', $car)
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
        return view('admin.orders_car', compact('orders', 'cars'));
    }

    public function cars(Request $request)
    {
        //        Parameters
        $search = '%'.($_GET['search'] ?? "").'%';

        //        Sort
        $sort = $_GET['sort'] ?? 'created_at$$$DESC';
        $sorter_array = explode('$$$', $sort);
        $sorter = $sorter_array[0];
        $arrange = $sorter_array[1];

        $list_id = [];
        $cars = DB::table('cars')
            ->leftJoin('drivers', 'drivers.id', '=', 'cars.driver_id')
            ->select('cars.*')
            ->where(function ($contents) {
                $search = '%'.($_GET['search'] ?? "").'%';
                $contents->where('cars.number', 'LIKE', $search)
                    ->orWhere('cars.made_from', 'LIKE', $search)
                    ->orWhere('cars.type', 'LIKE', $search)
                    ->orWhere('cars.last_repair', 'LIKE', $search);
            })->distinct()->get();
        foreach ($cars as $car){
            $list_id[] = $car->id;
        }
        $cars = Car::orderBy($sorter, $arrange)->whereIn('id', $list_id)->latest()->paginate(20);
        return view('admin.cars', compact('cars'));
    }

    public function clients(Request $request)
    {
        //        Parameters
        $from = $_GET['from'] ?? date('Y-m-d', strtotime("-30 days"));
        $to = $_GET['to'] ?? date('Y-m-d', strtotime("+6 hours"));
        $search = '%'.($_GET['search'] ?? "").'%';

        //        Sort
        $sort = $_GET['sort'] ?? 'created_at$$$DESC';
        $sorter_array = explode('$$$', $sort);
        $sorter = $sorter_array[0];
        $arrange = $sorter_array[1];

        $list_id = [];

        $users = DB::table('users')
            ->leftJoin('profiles', 'profiles.user_id', '=', 'users.id')
            ->select('users.*')
            ->whereBetween('users.created_at', [$from, $to])
            ->where('users.membership', 0)
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

        return view('admin.clients', compact('users'));
    }

    public function users(Request $request)
    {
        //        Parameters
        $from = $_GET['from'] ?? date('Y-m-d', strtotime("-30 days"));
        $to = $_GET['to'] ?? date('Y-m-d', strtotime("+6 hours"));
        $search = '%'.($_GET['search'] ?? "").'%';
        $membership = '%'.($_GET['membership'] ?? "all").'%';
        $membership = str_replace('all', "", $membership);


        //        Sort
        $sort = $_GET['sort'] ?? 'created_at$$$DESC';
        $sorter_array = explode('$$$', $sort);
        $sorter = $sorter_array[0];
        $arrange = $sorter_array[1];

        $list_id = [];
        $users = DB::table('users')
            ->select('users.*')
            ->whereBetween('users.created_at', [$from, $to])
            ->where('users.membership', 'LIKE', $membership)
            ->where(function ($contents) {
                $search = '%'.($_GET['search'] ?? "").'%';
                $contents->where('users.name', 'LIKE', $search)
                    ->orWhere('users.phone', 'LIKE', $search)
                    ->orWhere('users.email', 'LIKE', $search);
            })
            ->distinct()->get();
        foreach ($users as $user){
            $list_id[] = $user->id;
        }
        $users = User::orderBy($sorter, $arrange)->whereIn('id', $list_id)->latest()->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function drivers(Request $request)
    {
        //        Parameters
        $from = $_GET['from'] ?? date('Y-m-d', strtotime("-30 days"));
        $to = $_GET['to'] ?? date('Y-m-d', strtotime("+6 hours"));
        $search = '%'.($_GET['search'] ?? "").'%';
        $membership = '%'.($_GET['membership'] ?? "all").'%';
        $membership = str_replace('all', "", $membership);
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
        $users = DB::table('users')
            ->join('drivers', 'drivers.user_id', '=', 'users.id')
            ->select('users.*')
            ->whereBetween('users.created_at', [$from, $to])
            ->where('users.membership', 'LIKE', 1)
            ->where('drivers.country_id', 'LIKE', $country)
            ->where('drivers.state_id', 'LIKE', $state)
            ->where(function ($contents) {
                $search = '%'.($_GET['search'] ?? "").'%';
                $contents->where('users.name', 'LIKE', $search)
                    ->orWhere('users.phone', 'LIKE', $search)
                    ->orWhere('users.email', 'LIKE', $search)
                    ->orWhere('drivers.fullname', 'LIKE', $search)
                    ->orWhere('drivers.bio', 'LIKE', $search);
            })
            ->distinct()->get();
        foreach ($users as $user){
            $list_id[] = $user->id;
        }
        $users = User::orderBy($sorter, $arrange)->whereIn('id', $list_id)->latest()->paginate(20);

        return view('admin.drivers', compact('users'));
    }
}
