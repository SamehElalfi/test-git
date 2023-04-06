@extends('layouts.app')

@section('content')
    <title>إدارة المستخدمين</title>
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
            <table class="table table-bordered table-secondary table-responsive-lg">
                <thead>
                <th>#</th>
                <th>الإسم بالكامل</th>
                <th>رقم الهاتف</th>
                <th>المنطقة</th>
                <th>الحي</th>
                <th>تاريخ الإنضمام</th>
                <th>النشاطات</th>
                <th>الحدث</th>
                </thead>
                <tbody>
                <?php require_once public_path('date_sum.php')?>
                @foreach($users as $user)
                    <tr>
                        <td>#{{ $user->id }}</td>
                        <td>{{ $user->driver->fullname ?? "فارغ" }}</td>
                        <td>
                            {{ ($user->phone ?? "فارغ") . " | " }}
                            @if($user->phone_verfied_at == null)
                                <span class="text-danger">غير مربوط بالواتساب</span>
                            @else
                                <span class="text-success">مربوط بالواتساب</span>
                            @endif
                        </td>
                        <td>{{ $user->driver->country->name ?? "غير محدد" }}</td>
                        <td>{{ $user->driver->state->name ?? "غير محدد" }}</td>
                        <td>{{ rest_date($user->created_at) }}</td>
                        <td>
                            <a href="{{ route("driver.activities", $user->id) }}" class="btn btn-primary">إطلاع</a>
                        </td>
                        <td>
                            <a href="{{ route('admin.panel.driver', $user->id) }}" class="text-decoration-none">
                                <i class="fa-solid fa-eye"></i>
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
