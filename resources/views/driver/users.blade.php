@extends('layouts.app')

@section('content')
    <title>إدارة المستخدمين</title>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <div align="end">
                <a href="{{ route('driver.panel') }}" class="btn btn-outline-success">
                    لوحة التحكم
                </a>
            </div>
            <h3 class="text-primary text-center">إدارة المستخدمين</h3>
            <br>
            <?php require_once public_path("driver_panel.php")?>
            <hr>
            <br>
            <table class="table table-bordered table-secondary">
                <thead>
                <th>#</th>
                <th>اسم المستخدم</th>
                <th>رقم الهاتف</th>
                <th>عدد الرحلات</th>
                <th>تاريخ اخر رحلة</th>
{{--                <th>العضوية</th>--}}
                </thead>
                <tbody>
                <?php require_once public_path('date_sum.php')?>
                @foreach($users as $user)
                    <tr>
                        <td>#{{ $user->id }}</td>
                        <td>{{ $user->name ?? "غير موجود" }}</td>
                        <td>{{ $user->phone ?? "غير موجود" }}</td>
                        <td>{{ count($user->surveys) }}</td>
                        <td>{{ $user->surveys[0]->created_at }}</td>
{{--                        <td>--}}
{{--                            <b>--}}
{{--                                @if($user->membership == 0)--}}
{{--                                    عميل--}}
{{--                                @elseif($user->membership == 1)--}}
{{--                                    سائق--}}
{{--                                @elseif($user->membership == 2)--}}
{{--                                    دعم فني--}}
{{--                                @elseif($user->membership == 3)--}}
{{--                                    أدمن--}}
{{--                                @endif--}}
{{--                            </b>--}}
{{--                        </td>--}}
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>
@endsection
