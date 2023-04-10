<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CsActivity;
use App\Models\Driver;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function create()
    {
        $drivers = Driver::all();
        return view('cars.create', compact('drivers'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "number" => "required",
            "made_from" => "required",
            "type" => "required",
            "last_repair" => "required",
            "photo" => "required"
        ]);

        if (isset($request->photo)){
            $tmp = $_FILES['photo']['name'];
            $name_tmp = 'photo';
            $array = explode('.', $_FILES['photo']['name']);
            $thumb_width = 350;
            $thumb_height = 350;
            $path_page = 'cars';
            require public_path('crop_image.php');
        }

        echo $request->driver_id;
//        return;
        $car = Car::create([
            "driver_id" => $request->driver_id,
            "number" => $request->number ?? null,
            "made_from" => $request->made_from ?? null,
            "type" => $request->type ?? null,
            "last_repair" => $request->last_repair ?? null,
            "photo" => $NewImageName ?? null,
        ]);

        //            Cs action
        $activity = CsActivity::create([
            "cs_id" => Auth()->id(),
            "user_id" => null,
            "driver_id" => null,
            "survey_id" => null,
            "car_id" => $car->id,
            "option" => 9,
            "seen" => 0,
        ]);

        return redirect()->back()->with('success', 'تم إضافة سيارة جديدة');
    }

    public function edit($id)
    {
        $drivers = Driver::all();
        $car = Car::find($id);
        if ($car == "") {
            return redirect()->back();
        }
        return view('cars.edit', compact('car', 'drivers'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "number" => "required",
            "made_from" => "required",
            "type" => "required",
            "last_repair" => "required",
        ]);

        $car = Car::find($id);
        if ($car == "") {
            return redirect()->back();
        }
        $car->number = $request->number ?? $car->number;
        $car->made_from = $request->made_from ?? $car->made_from;
        $car->type = $request->type ?? $car->type;
        $car->last_repair = $request->last_repair ?? $car->last_repair;
        if (isset($request->photo)){
            $tmp = $_FILES['photo']['name'];
            $name_tmp = 'photo';
            $array = explode('.', $_FILES['photo']['name']);
            $thumb_width = 350;
            $thumb_height = 350;
            $path_page = 'cars';
            require public_path('crop_image.php');
            $car->photo = $NewImageName;
        }

        $car->save();

        //            Cs action
        $activity = CsActivity::create([
            "cs_id" => Auth()->id(),
            "user_id" => null,
            "driver_id" => null,
            "survey_id" => null,
            "car_id" => $car->id,
            "option" => 10,
            "seen" => 0,
        ]);

        return redirect()->back()->with('success', 'تم التعديل بنجاح');
    }

    public function destroy($id)
    {
        $car = Car::find($id);
        $car->delete();

        //            Cs action
        $activity = CsActivity::create([
            "cs_id" => Auth()->id(),
            "user_id" => null,
            "driver_id" => null,
            "survey_id" => null,
            "car_id" => null,
            "option" => 11,
            "seen" => 0,
        ]);

        return redirect()->back();
    }
}
