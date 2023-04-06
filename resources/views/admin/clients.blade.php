@extends('layouts.app')

@section('content')
    <title>إدارة العملاء</title>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <div align="end">
                <a href="{{ route('admin.panel') }}" class="btn btn-outline-success">
                    لوحة التحكم
                </a>
            </div>
            <h3 class="text-primary text-center">إدارة العملاء</h3>
            <br>
            <?php require_once public_path("panel_admin.php")?>
            <hr>
            <br>
            <table class="table table-bordered table-secondary table-responsive-lg">
                <thead>
                <th>#</th>
                <th>اسم العميل</th>
                <th>رقم الهاتف</th>
                <th>عدد الرحلات</th>
                <th>تاريخ اخر رحلة</th>
                <th>النشاطات</th>
                </thead>
                <tbody>
                <?php require_once public_path('date_sum.php')?>
                @foreach($users as $user)
                    <tr>
                        <td>
                            <a href="{{ route('user.analytics', $user->id) }}" class="text-decoration-none">
                                #{{ $user->id }}
                            </a>
                        </td>
                        <td>{{ $user->name ?? "غير موجود" }}</td>
                        <td>
                            {{ ($user->phone ?? "فارغ") . " | " }}
                            @if($user->phone_verfied_at == null)
                                <span class="text-danger">غير مربوط بالواتساب</span>
                            @else
                                <span class="text-success">مربوط بالواتساب</span>
                            @endif
                        </td>
                        <td>{{ count($user->surveys) }}</td>
                        <td>{{ $user->surveys[count($user->surveys)-1]->created_at ?? "لا يوجد" }}</td>
                        <td>
                            <a href="{{ route("user.activities", $user->id) }}" class="btn btn-primary">إطلاع</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>
@endsection
