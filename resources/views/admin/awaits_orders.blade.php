@extends('layouts.app')

@section('content')
    <title>إدارة الرحلات بإنتظار التحرك</title>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <div align="end">
                <a href="{{ route('admin.panel') }}" class="btn btn-outline-success">
                    لوحة التحكم
                </a>
            </div>
            <h3 class="text-primary text-center">إدارة الرحلات بإنتظار التحرك</h3>
            <br>
            <?php require_once public_path("panel_admin.php")?>
            <hr>
            <div align="start">
                <a href="{{ route('quiz.add.solid') }}" style="display: inline-block" class="btn btn-outline-success px-2">
                    <i class="fa-solid fa-plus"></i>
                    اضافة رحلة
                </a>
                <a style="display: inline-block" href="{{ route('quiz.add.multiple') }}" class="btn btn-outline-danger px-2">
                    <i class="fa-solid fa-list"></i>
                    اضافة رحلات متعددة
                </a>
            </div>
            <br>
            <table class="table table-bordered table-secondary table-responsive-lg">
                <thead>
                <th>#</th>
                <th>السائق</th>
                <th>إسم العميل</th>
                <th>رقم السيارة</th>
                <th>موعد بدء الرحلة</th>
                <th>مكان الإقلاع</th>
                <th>مكان الوصول</th>
                <th>تاريخ الإنشاء</th>
                <th>الحالة</th>
                <th>الحدث</th>
                </thead>
                <tbody>
                <?php require_once public_path('date_sum.php')?>
                @foreach($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('quiz.show', $order->slug) }}" class="text-decoration-none text-primary">
                                #{{ $order->slug }}
                            </a>
                        </td>
                        <td>
                            @if(isset($order->driver))
                                <a href="{{ route('driver.profile', $order->driver->user_id) }}">{{ $order->driver->fullname ?? "غير محدد" }}</a>
                            @else
                                غير محدد
                            @endif
                        </td>
                        <td>
                            @if(isset($order->user))
                                <a href="{{ route('admin.panel.user', $order->user_id) }}">{{ $order->user->name ?? "فارغ" }}</a>
                            @else
                                غير محدد
                            @endif
                        </td>
                        <td>{{ $order->car->number ?? "غير محدد" }}</td>
                        <td>{{ ($order->date_tour ?? "غير محدد").($order->time_date_tour ?? "غير محدد") }}</td>
                        <td>{{ $order->start_point ?? "فارغ" }}</td>
                        <td>{{ $order->end_point ?? "فارغ" }}</td>
                        <td>{{ rest_date($order->created_at) }}</td>
                        <td>
                            @if($order->status == 0)
                                <div class="alert alert-secondary">
                                    <b>قيد المعالجة</b>
                                </div>
                            @elseif($order->status == 1)
                                <div class="alert alert-warning">
                                    <b>بإنتظار التحرك</b>
                                </div>
                            @elseif($order->status == 2)
                                <div class="alert alert-warning">
                                    <b>جاري التنفيذ</b>
                                </div>
                            @elseif($order->status == 3)
                                <div class="alert alert-success">
                                    <b>تم التوصيل بنجاح</b>
                                </div>
                            @elseif($order->status == 4)
                                <div class="alert alert-danger">
                                    <b>تم الإلغاء</b>
                                </div>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('quiz.process', $order->id) }}" class="text-decoration-none btn btn-primary">
                                معالجة_الطلب
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
