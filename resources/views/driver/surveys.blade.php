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
                                        <option value="notes$$$ASC" {{ ($sort == 'notes$$$ASC') ? "selected":"" }}>أ الي ي</option>
                                        <option value="notes$$$DESC" {{ ($sort == 'notes$$$DESC') ? "selected":"" }}>ي الي أ</option>
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
            <h2 class="text-dark" align="start">النتائج (<span class="text-primary">{{ $orders->total() }}</span>)</h2>
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
                        <td>{{ (auth()->user()->driver->state->country->name ?? "فارغ")." / ".(auth()->user()->driver->state->name ?? "فارغ") }}</td>
                        <td>{{ rest_date($order->created_at) }}</td>
                        <td>
                            <b>
                                @if($order->status == 0)
                                    <b class="text-secondary">قيد المعالجة</b>
                                @elseif($order->status == 1)
                                    <b class="text-warning">بإنتظار التحرك</b>
                                @elseif($order->status == 2)
                                    <b class="text-warning">جاري التنفيذ</b>
                                @elseif($order->status == 3)
                                    <b class="text-success">تم التوصيل بنجاح</b>
                                @elseif($order->status == 4)
                                    <b class="text-danger">تم الإلغاء</b>
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
            <br><br>

            @if ($orders->hasPages())
                <ul class="pagination">
                    @if ($orders->onFirstPage())
                        <li class="disabled"><span>{{ __('Prev') }}</span></li>
                    @else
                        <li><a href="{{ $orders->previousPageUrl() }}" rel="prev">{{ __('Prev') }}</a></li>
                    @endif
                    {{ " Page " . $orders->currentPage() . "  of  " . $orders->lastPage() }}
                    @if ($orders->hasMorePages())
                        <li><a href="{{ $orders->nextPageUrl() }}" rel="next">{{ __('Next') }}</a></li>
                    @else
                        <li class="disabled"><span>{{ __('Next') }}</span></li>
                    @endif
                </ul>
            @endif
        </div>
    </div>
    <br>
@endsection
