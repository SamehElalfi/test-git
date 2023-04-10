@extends('layouts.app')

@section('content')
    <title>الإيرادات</title>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <div align="end">
                <a href="{{ route('admin.panel') }}" class="btn btn-outline-success">
                    لوحة التحكم
                </a>
            </div>
            <h3 class="text-primary text-center">الإيرادات</h3>
            <br>
            <?php require_once public_path("panel_admin.php")?>
            <hr>
            <br>

            {{--            Sort bar--}}
            <div id="sort-bar">
                <form method="get">
                    @csrf
                    @method("GET")

                    <?php
                    $sort = $_GET['sort'] ?? 'created_at$$$DESC';
                    $max_price = $_GET['max-price'] ?? '7500';
                    $min_price = $_GET['min-price'] ?? '2500';
                    $from = $_GET['from'] ?? date('Y-m-d', strtotime("-30 days"));
                    $to = $_GET['to'] ?? date('Y-m-d', strtotime("+6 hours"));
                    $user_id = $_GET['user_id'] ?? "";
                    $date = $_GET['date'] ?? "90";
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
                                        <option value="cost$$$ASC" {{ ($sort == 'cost$$$ASC') ? "selected":"" }}>المبلغ الأقل - الأكبر</option>
                                        <option value="cost$$$DESC" {{ ($sort == 'cost$$$DESC') ? "selected":"" }}>المبلغ الأكثر - الأقل</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{--                        Time--}}
                        <div class="col-6 my-2">
                            <div class="row">
                                <div class="col-5">
                                    <h6>المعاملات من</h6>
                                </div>
                                <div class="col-7">
                                    <select class="form-control" name="date" onchange="custom_date()">
                                        <option value="90" {{ ($date == '90') ? "selected":"" }}>اخر 90 يوم</option>
                                        <option value="30" {{ ($date == '30') ? "selected":"" }}>اخر 30 يوم</option>
                                        <option value="7" {{ ($date == '7') ? "selected":"" }}>اخر 7 ايام</option>
                                        <option value="1" {{ ($date == '1') ? "selected":"" }}>اليوم</option>
                                        <option value="all" {{ ($date == 'all') ? "selected":"" }}>تاريخ محدد</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{--                        Date From, To--}}
                        <div class="col-6 my-2" id="date-from-to" style="display: none">
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

                        {{--                        range price--}}
                        <div class="col-12 my-2">
                            <div class="price-input">
                                <div class="field" style="display: inline-block;width: 30%">
                                    {{--                                    <span>من</span>--}}
                                    <input type="number" class="form-control" value="{{ $min_price ?? 2500 }}" style="display: inline-block">
                                </div>
                                <div class="separator">-</div>
                                <div class="field" style="display: inline-block;width: 30%">
                                    {{--                                    <span>الي</span>--}}
                                    <input type="number" class="form-control" value="{{ $max_price ?? 7500 }}">
                                </div>
                            </div>
                            <div class="slider" style="display: inline-block">
                                <div class="progress"></div>
                            </div>
                            <div class="range-input">
                                <input type="range" class="range-min" name="min-price" min="0" max="10000" value="{{ $min_price ?? 2500 }}" step="1">
                                <input type="range" class="range-max" name="max-price" min="0" max="10000" value="{{ $max_price ?? 7500 }}" step="1">
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
            <h3 class="text-dark" align="start">
                إجمالي المعاملات (<span class="text-primary">{{ $incomes->total() }}</span>)
            </h3>
            <br>
            <table class="table table-bordered table-secondary table-responsive-lg">
                <thead>
                <th>#</th>
                <th>الجهة الدائنة</th>
                <th>المبلغ المدفوع</th>
                <th>تاريخ المعاملة</th>
                </thead>
                <tbody>
                <?php require_once public_path('date_sum.php')?>
                <?php $balance=[];?>
                @foreach($incomes as $income)
                    <tr>
                        <td>
                            #{{ $income->id }}
                        </td>
                        <td>
                            @if(isset($income->user))
                                <a href="?user_id={{ $income->user_id }}">{{ $income->user->name ?? "غير محدد" }}</a>
                            @else
                                غير محدد
                            @endif
                        </td>
                        <td>{{ $income->cost ?? 0 }} ريال</td>
                        <td>{{ $income->updated_at ?? "غير محدد" }}</td>
                    </tr>
                    <?php $balance[] = $income['cost'];?>
                @endforeach
                </tbody>
            </table>
            <h4 align="start">إجمالي المبالغ المحصلة من المعاملات (<span class="text-success">{{ array_sum($balance) }} ريال</span>) </h4>
            <br><br>

            @if ($incomes->hasPages())
                <ul class="pagination">
                    @if ($incomes->onFirstPage())
                        <li class="disabled"><span>{{ __('Prev') }}</span></li>
                    @else
                        <li><a href="{{ $incomes->previousPageUrl() }}" rel="prev">{{ __('Prev') }}</a></li>
                    @endif
                    {{ " Page " . $incomes->currentPage() . "  of  " . $orders->lastPage() }}
                    @if ($incomes->hasMorePages())
                        <li><a href="{{ $incomes->nextPageUrl() }}" rel="next">{{ __('Next') }}</a></li>
                    @else
                        <li class="disabled"><span>{{ __('Next') }}</span></li>
                    @endif
                </ul>
            @endif
        </div>
    </div>
    <br>
    <script src="{{ asset('js/range_price.js') }}"></script>
    <script>
        function custom_date(){
            $input_date = $('select[name=date]').val();
            if ($input_date == 'all'){
                $('#date-from-to').css('display', 'flex');
            }else{
                $('#date-from-to').css('display', 'none');
            }
        }
    </script>
@endsection
