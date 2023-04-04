@extends('layouts.app')

@section('content')
    <title>إدارة الطلبات</title>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <h3 class="text-primary text-center">إدارة الرحلات</h3>
            <hr>
            <br>
            <table class="table table-bordered table-secondary">
                <thead>
                    <th>#</th>
                    <th>إسم المستفيد</th>
                    <th>رقم الهاتف</th>
                    <th>السبب</th>
                    <th>الحالة</th>
                    <th>الحدث</th>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>#{{ $order->slug }}</td>
                        <td>{{ $order->user->profile->fullname ?? "غير محدد" }}</td>
                        <td>{{ $order->user->phone ?? "غير محدد" }}</td>
                        <td>{{ $order->reason ?? "غير محدد" }}</td>
                        <td>
                            @if($order->status == 0)
                                <b class="text-dark">قيد المعالجة</b>
                            @elseif($order->status == 1)
                                <b class="text-warning">بإنتظار التحرك</b>
                            @elseif($order->status == 2)
                                <b class="text-warning">جاري التنفيذ</b>
                            @elseif($order->status == 3)
                                <b class="text-success">تم التوصيل بنجاح</b>
                            @elseif($order->status == 4)
                                <b class="text-danger">تم الإلغاء</b>
                            @endif
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
