@extends('layouts.app')

@section('content')
    <title>النشاطات</title>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <div align="center">
                <div align="end">
                    <a href="#" onclick="history.back()">
                        <i class="fa-solid fa-circle-left fa-2x"></i>
                    </a>
                </div>
                <h3>نشاطات خدمة العملاء</h3>
                <hr>
                <br>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">التاريخ</th>
                        <th scope="col">الحدث</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($activities as $activity)
                        <tr>
                            <th>{{ $activity->created_at }}</th>
                            @if($activity->option == 0)
                                <td>
                                    قام
                                    <a href="{{ route('admin.panel.user', ($activity->cs_id ?? "000000")) }}">{{ $activity->cs->name ?? "محذوف" }}</a>
                                     بتأكيد طلب الرحلة
                                    <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                </td>
                            @elseif($activity->option == 1)
                                <td>
                                    قام
                                    <a href="{{ route('admin.panel.user', ($activity->cs_id ?? "000000")) }}">{{ $activity->cs->name ?? "محذوف" }}</a>
                                    بإلغاء طلب الرحلة
                                    <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                </td>
                            @elseif($activity->option == 2)
                                <td>
                                    قام
                                    <a href="{{ route('admin.panel.user', ($activity->cs_id ?? "000000")) }}">{{ $activity->cs->name ?? "محذوف" }}</a>
                                    بتمييز طلب الرحلة
                                    <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                     كرحلة ناجحة
                                </td>
                            @elseif($activity->option == 3)
                                <td>
                                    قام
                                    <a href="{{ route('admin.panel.user', ($activity->cs_id ?? "000000")) }}">{{ $activity->cs->name ?? "محذوف" }}</a>
                                    بإنشاء طلب رحلة جديد
                                    <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                </td>
                            @elseif($activity->option == 4)
                                <td>
                                    قام
                                    <a href="{{ route('admin.panel.user', ($activity->cs_id ?? "000000")) }}">{{ $activity->cs->name ?? "محذوف" }}</a>
                                    بإنشاء طلبات رحلة متعددة
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <br><br>
    <br>
@endsection
