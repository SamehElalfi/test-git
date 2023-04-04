<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Survey;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $orders = Survey::all();
        $users = User::all();
        return view('admin.index', compact('orders', 'users'));
    }

    public function orders()
    {
        $orders = Survey::latest()->paginate(20);
        return view('admin.orders', compact('orders'));
    }

    public function pending_orders()
    {
        $orders = Survey::where('status', 0)->latest()->paginate(20);
        return view('admin.pending_orders', compact('orders'));
    }

    public function awaits_orders()
    {
        $orders = Survey::where('status', 1)->latest()->paginate(20);
        return view('admin.awaits_orders', compact('orders'));
    }

    public function confirmed_orders()
    {
        $orders = Survey::where('status', 2)->latest()->paginate(20);
        return view('admin.confirm_orders', compact('orders'));
    }

    public function finished_orders()
    {
        $orders = Survey::where('status', '>=', 3)->latest()->paginate(20);
        return view('admin.finished_orders', compact('orders'));
    }

    public function cars()
    {
        $cars = Car::all();
        return view('admin.cars', compact('cars'));
    }

    public function clients()
    {
        // $users = User::all()->where('membership', 0);
        $users = User::all();
        return view('admin.clients', compact('users'));
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function drivers()
    {
        $users = User::all()->where('membership', 1);
        return view('admin.drivers', compact('users'));
    }
}
