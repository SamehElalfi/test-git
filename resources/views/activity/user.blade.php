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
                <h3>نشاطات المستخدم</h3>
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
                                    قام بإنشاء طلب رحلة جديدة
                                    <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                </td>
                            @elseif($activity->option == 1)
                                <td>قام بإلغاء طلب رحلة </td>
                            @elseif($activity->option == 2)
                                <td>
                                    حصل علي تأكيد طلب الرحلة
                                    <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                    وتم تحويله الي السائق
                                    <a href="{{ route('driver.profile', ($activity->driver->user_id ?? "000000")) }}">{{ $activity->driver->fullname ?? "محذوف" }}</a>
                                </td>
                            @elseif($activity->option == 3)
                                <td>
                                    تم إلغاء طلب رحلته
                                    <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                </td>
                            @elseif($activity->option == 4)
                                <td>
                                    وصلت رحلته بنجاح
                                    <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
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
