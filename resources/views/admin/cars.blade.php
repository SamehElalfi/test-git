@extends('layouts.app')

@section('content')
    <title>إدارة السيارات</title>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <div align="end">
                <a href="{{ route('admin.panel') }}" class="btn btn-outline-success">
                    لوحة التحكم
                </a>
            </div>
            <h3 class="text-primary text-center">إدارة السائقين</h3>
            <br>
            <?php require_once public_path("panel_admin.php")?>
            <hr>
            <br>
            <div align="start">
                <a href="{{ route('admin.panel.car.create') }}" class="btn btn-outline-success">
                    <i class="fa-solid fa-plus"></i>
                    اضف سيارة جديدة
                </a>
            </div>
            <br>
            <table class="table table-bordered table-secondary table-responsive-lg">
                <thead>
                <th>#</th>
                <th>الإسم</th>
                <th>النوع</th>
                <th>رقم السيارة</th>
                <th>عدد الركاب</th>
                <th>الحدث</th>
                </thead>
                <tbody>
                <?php require_once public_path('date_sum.php')?>
                @foreach($cars as $car)
                    <tr>
                        <td>#{{ $car->id }}</td>
                        <td>{{ $car->name }}</td>
                        <td>{{ $car->type }}</td>
                        <td>({{ $car->number }})</td>
                        <td>{{ $car->passengers }} راكب</td>
                        <td>
                            <a href="{{ route('admin.panel.car.delete', $car->id) }}" class="text-decoration-none">
                                <i class="fa-solid fa-trash text-danger"></i>
                            </a>
                            <a href="{{ route('admin.panel.car.edit', $car->id) }}" class="text-decoration-none">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>
@endsection
