@extends('layouts.app')

@section('content')
    <title>إدارة الرحلات قيد المعالجة</title>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <div align="end">
                <a href="{{ route('admin.panel') }}" class="btn btn-outline-success">
                    لوحة التحكم
                </a>
            </div>
            <h3 class="text-primary text-center">إدارة الرحلات قيد المعالجة</h3>
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
                                        <option value="cost$$$ASC" {{ ($sort == 'created_at$$$DESC') ? "selected":"" }}>المبلغ الأقل - الأكبر</option>
                                        <option value="cost$$$DESC" {{ ($sort == 'created_at$$$ASC') ? "selected":"" }}>المبلغ الأكثر - الأقل</option>
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
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{--                        range price--}}
                        <div class="col-12 my-2">
                            <div class="price-input">
                                <div class="field" style="display: inline-block;width: 30%">
                                    {{--                                    <span>من</span>--}}
                                    <input type="number" class="form-control" value="2500" style="display: inline-block">
                                </div>
                                <div class="separator">-</div>
                                <div class="field" style="display: inline-block;width: 30%">
                                    {{--                                    <span>الي</span>--}}
                                    <input type="number" class="form-control" value="7500">
                                </div>
                            </div>
                            <div class="slider" style="display: inline-block">
                                <div class="progress"></div>
                            </div>
                            <div class="range-input">
                                <input type="range" class="range-min" name="min-price" min="0" max="10000" value="2500" step="1">
                                <input type="range" class="range-max" name="max-price" min="0" max="10000" value="7500" step="1">
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
            <table class="table table-bordered table-secondary table-responsive-lg">
                <thead>
                <th>#</th>
                <th>إسم العميل</th>
                <th>حجم المبالغ</th>
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
                        <td style="width: 150px">
                            @if(isset($order->user))
                                <a href="{{ route('admin.panel.user', $order->user_id) }}">
                                    @if($order->user->vip == 1)
                                        <sup class="text-warning"><i class="fa-solid fa-star"></i></sup>
                                    @endif
                                    {{ $order->user->name ?? "فارغ" }}
                                </a>
                            @else
                                غير محدد
                            @endif
                        </td>
                        <td>{{ $order->cost ?? "غير محدد" }} ريال</td>
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
    <script src="{{ asset('js/range_price.js') }}"></script>
@endsection
