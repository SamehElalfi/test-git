@extends('layouts.app')

@section('content')
    <title>إدارة نشاطات موظفين خدمة العملاء</title>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <div align="end">
                <a href="{{ route('admin.panel') }}" class="btn btn-outline-success">
                    لوحة التحكم
                </a>
            </div>
            <h3 class="text-primary text-center">إدارة نشاطات موظفين خدمة العملاء</h3>
            <br>
            <?php require_once public_path("panel_admin.php")?>
            <hr>

{{--            Sort bar--}}
            <div id="sort-bar">
                <form method="get">
                    @csrf
                    @method("GET")

                    <?php

                    $sort = $_GET['sort'] ?? 'created_at$$$DESC';
                    $from = $_GET['from'] ?? date('Y-m-d', strtotime("-30 days"));
                    $to = $_GET['to'] ?? date('Y-m-d', strtotime("+6 hours"));
                    $search = $_GET['search'] ?? "";
                    $status = $_GET['status'] ?? 'all';
                    $country = $_GET['country'] ?? 'all';
                    $state = $_GET['state'] ?? 'all';
                    ?>
                    <div class="row">
{{--                        Sort By--}}
                        <div class="col-6 my-2">
                            <div class="row">
                                <div class="col-5">
                                    <h6>فرز النتائج بواسطة</h6>
                                </div>
                                <div class="col-7">
                                    <select class="form-control" name="sort">
                                        <option value="created_at$$$DESC" {{ ($sort == 'created_at$$$DESC') ? "selected":"" }}>الأحدث للأقدم</option>
                                        <option value="created_at$$$ASC" {{ ($sort == 'created_at$$$ASC') ? "selected":"" }}>الأقدم للأحدث</option>
                                        <option value="name$$$ASC" {{ ($sort == 'users.name$$$ASC') ? "selected":"" }}>أ الي ي</option>
                                        <option value="name$$$DESC" {{ ($sort == 'users.name$$$DESC') ? "selected":"" }}>ي الي أ</option>
                                    </select>
                                </div>
                            </div>
                        </div>

{{--                        Date From, To--}}
                        <div class="col-6 my-2">
                            <div class="row">
                                <div class="col-1">
                                    <h6 style="display: inline-block">من</h6>
                                </div>
                                <div class="col-5">
                                    <input type="date" class="form-control" value="{{ $from ?? date('Y-m-d', strtotime("-7 days")) }}" name="from" style="max-width: 80%;display: inline-block">
                                </div>
                                <div class="col-1">
                                    <h6 style="display: inline-block">الي</h6>
                                </div>
                                <div class="col-5">
                                    <input type="date" class="form-control" value="{{ $to ?? date('Y-m-d', strtotime("+6 hours")) }}" name="to" style="max-width: 80%;display: inline-block">
                                </div>
                            </div>
                        </div>

{{--                        Search--}}
                        <div class="col-6 my-2">
                            <input type="search" name="search" class="form-control" placeholder="ابحث عن..." value="{{ $search ?? "" }}">
                        </div>

{{--                        Status--}}
                        <div class="col-6 my-2">
                            <div class="row">
                                <div class="col-3">
                                    <h6>حالة الطلب</h6>
                                </div>
                                <div class="col-9">
                                    <select class="form-control" name="status">
                                        <option value="all" {{ ($status == 'all') ? "selected":"" }}>جميع الحالات</option>
                                        <option value="0" {{ ($status == 0) ? "selected":"" }}>قيد المعالجة</option>
                                        <option value="1" {{ ($status == 1) ? "selected":"" }}>بإنتظار التحرك</option>
                                        <option value="2" {{ ($status == 2) ? "selected":"" }}>جاري التنفيذ</option>
                                        <option value="3" {{ ($status == 3) ? "selected":"" }}>تم التوصيل بنجاح</option>
                                        <option value="4" {{ ($status == 4) ? "selected":"" }}>تم الإلغاء</option>
                                    </select>
                                </div>
                            </div>
                        </div>

{{--                        Countries--}}
                        <div class="col-6 my-2">
                            <div class="row">
                                <div class="col-3">
                                    <h6>لمنطقة</h6>
                                </div>
                                <div class="col-9">
                                    <select class="form-control" name="country">
                                        <option value="all">جميع المناطق</option>
                                        @foreach(\App\Models\Country::all() as $country_get)
                                            <option value="{{ $country_get->id }}" {{ ($country == $country_get->id) ? "selected":"" }}>{{ $country_get->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

{{--                        States--}}
                        <div class="col-6 my-2">
                            <div class="row">
                                <div class="col-3">
                                    <h6>الحي</h6>
                                </div>
                                <div class="col-9">
                                    <select class="form-control" name="state">
                                        <option value="all">جميع الاحياء</option>
                                        @foreach(\App\Models\State::all() as $state_get)
                                            <option value="{{ $state_get->id }}" {{ ($state == $state_get->id) ? "selected":"" }}>{{ $state_get->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

{{--                        Submit--}}
                        <div class="col-6 my-3">
                            <input type="submit" class="btn btn-primary" value="فرز" style="margin: auto auto">
                        </div>
                    </div>
                </form>
            </div>
            <br>
            <table class="table table-bordered table-secondary table-responsive-lg">
                <thead>
                <th>#</th>
                <th>اسم المستخدم</th>
                <th>رقم الهاتف</th>
                <th>النشاط</th>
                <th>التاريخ</th>
                <th>الحدث</th>
                </thead>
                <tbody>
                <?php require_once public_path('date_sum.php')?>
                @foreach($activities as $activity)
                    <tr>
                        <td>#{{ $activity->id }}</td>
                        <td>{{ $activity->cs->name ?? "محذوف" }}</td>
                        <td>{{ $activity->cs->phone ?? "فارغ" }}</td>
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
                        <td>{{ $activity->created_at ?? "غير محدد" }}</td>
                        <td>
                            <a href="{{ route("cs.activities", $activity->cs_id) }}" class="btn btn-primary">إطلاع</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>
@endsection
