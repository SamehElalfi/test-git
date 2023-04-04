<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function create()
    {
        return view('cars.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "name" => "required",
            "type" => "required",
            "count" => "required|min:1|max:2",
            "number" => "required"
        ]);

        Car::create([
            "name" => $request->name,
            "type" => $request->type,
            "passengers" => $request->count,
            "number" => $request->number
        ]);

        return redirect()->back()->with('success', 'تم إضافة سيارة جديدة');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $car = Car::find($id);
        if ($car == "") {
            return redirect()->back();
        }
        return view('cars.edit', compact('car'));
    }

    public function update(Request $request, $id)
    {
        $car = Car::find($id);
        if ($car == "") {
            return redirect()->back();
        }
        $car->name = $request->name ?? $car->name;
        $car->type = $request->type ?? $car->type;
        $car->passengers = $request->count ?? $car->passengers;
        $car->number = $request->number ?? $car->number;
        $car->save();

        return redirect()->back()->with('success', 'تم التعديل بنجاح');
    }

    public function destroy($id)
    {
        $car = Car::find($id);
        $car->delete();
        return redirect()->back();
    }
}
