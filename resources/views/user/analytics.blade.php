@extends('layouts.app')

@section('content')
    <title>النشاطات</title>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <div align="center">
{{--                <div align="end">--}}
{{--                    <a href="#" onclick="history.back()">--}}
{{--                        <i class="fa-solid fa-circle-left fa-2x"></i>--}}
{{--                    </a>--}}
{{--                </div>--}}
                <h3>إدارة نشاطاتي</h3>
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
                                    لقد قمت بإنشاء طلب رحلة جديدة
                                    <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                </td>
                            @elseif($activity->option == 1)
                                <td>لقد قمت بإلغاء طلب الرحلة </td>
                            @elseif($activity->option == 2)
                                <td>لقد تم تأكيد طلب رحلتك
                                    <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                    وتحويلك الي السائق
                                    <a href="{{ route('driver.profile', ($activity->driver->user_id ?? "000000")) }}">{{ $activity->driver->fullname ?? "محذوف" }}</a>
                                </td>
                            @elseif($activity->option == 3)
                                <td>لقد قام السائق
                                    <a href="{{ route('driver.profile', ($activity->driver->user_id ?? "000000")) }}">{{ $activity->driver->fullname ?? "محذوف" }}</a>
                                     بإلغاء طلب الرحلة خاصتك
                                    <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                </td>
                            @elseif($activity->option == 4)
                                <td>تهانين لقد وصلت رحلتك بنجاح
                                    <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                </td>
                            @elseif($activity->option == 5)
                                <td>لقد بدءت رحلتك للتو
                                    <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                    و السائق
                                    <a href="{{ route('driver.profile', ($activity->driver->user_id ?? "000000")) }}">{{ $activity->driver->fullname ?? "محذوف" }}</a>
                                    يتحو منطقة الإنطلاق
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
