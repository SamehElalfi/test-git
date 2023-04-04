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
            <h3 class="text-primary text-center">إدارة الرحلات</h3>
            <br>
            <?php require_once public_path("driver_panel.php")?>
            <hr>
            <br>
            <table class="table table-bordered table-secondary">
                <thead>
                <th>#</th>
                <th>اسم المستخدم</th>
                <th>رقم الهاتف</th>
                <th>اسم المستفيد</th>
                <th>المنطقة/الحي</th>
                <th>التاريخ</th>
                <th>الحالة</th>
                <th>الحدث</th>
                </thead>
                <tbody>
                <?php require_once public_path('date_sum.php')?>
                @foreach($orders as $order)
                    <tr>
                        <td>#{{ $order->slug }}</td>
                        <td>{{ $order->user->name ?? "غير موجود" }}</td>
                        <td>{{ $order->user->phone ?? "غير موجود" }}</td>
                        <td>{{ $order->user->profile->fullname ?? "غير محدد" }}</td>
                        <td>{{ (Auth::user()->driver->state->country->name ?? "فارغ")." / ".(Auth::user()->driver->state->name ?? "فارغ") }}</td>
                        <td>{{ rest_date($order->created_at) }}</td>
                        <td>
                            <b>
                                @if($order->status == 0)
                                    <b class="text-secondary">قيد المراجعة</b>
                                @elseif($order->status == 1)
                                    <b class="text-warning">تم تأكيد</b>
                                @elseif($order->status == 2)
                                    <b class="text-danger">فشل الوصول</b>
                                @elseif($order->status == 3)
                                    <b class="text-success">نجح الوصول</b>
                                @endif
                            </b>
                        </td>
                        <td>
                            <a href="{{ route('quiz.show', $order->slug) }}" class="text-decoration-none">
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
