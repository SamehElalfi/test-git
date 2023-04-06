<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticController extends Controller
{
    public function terms()
    {
        return view('static.terms');
    }

    public function privacy()
    {
        return view('static.privacy');
    }

    public function about_us()
    {
        return view('static.about_us');
    }

    public function contact_us()
    {
        return view('static.contact');
    }

    public function contact_store()
    {
        //
    }
}
