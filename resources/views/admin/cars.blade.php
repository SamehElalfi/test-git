@extends('layouts.app')

@section('content')
    <title>إدارة السيارات</title>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <div align="end">
                <a href="{{ route('admin.panel') }}" class="btn btn-outline-success">
                    لوحة التحكم
                </a>
            </div>
            <h3 class="text-primary text-center">إدارة السائقين</h3>
            <br>
            <?php require_once public_path("panel_admin.php")?>
            <hr>
            <br>
            <div align="start">
                <a href="{{ route('admin.panel.car.create') }}" class="btn btn-outline-success">
                    <i class="fa-solid fa-plus"></i>
                    اضف سيارة جديدة
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
                                        <option value="orders$$$ASC" {{ ($sort == 'orders$$$ASC') ? "selected":"" }}>الرحلات الأكثر - الأقل</option>
                                        <option value="orders$$$DESC" {{ ($sort == 'orders$$$DESC') ? "selected":"" }}>الرحلات الأقل - الأكثر</option>
                                        <option value="name$$$ASC" {{ ($sort == 'name$$$ASC') ? "selected":"" }}>أ الي ي</option>
                                        <option value="name$$$DESC" {{ ($sort == 'name$$$DESC') ? "selected":"" }}>ي الي أ</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{--                        Search--}}
                        <div class="col-6 my-2">
                            <input type="search" name="search" class="form-control" placeholder="ابحث عن..." value="{{ $search ?? "" }}">
                        </div>

                        {{--                        Submit--}}
                        <div class="col-6 my-3">
                            <input type="submit" class="btn btn-primary" value="فرز" style="margin: auto auto">
                        </div>
                    </div>
                </form>
            </div>

            <br>
            <h2 class="text-dark" align="start">النتائج (<span class="text-primary">{{ $cars->total() }}</span>)</h2>
            <br>
            <table class="table table-bordered table-secondary table-responsive-lg">
                <thead>
                <th>#</th>
                <th>الكابتن</th>
                <th>نوع السيارة</th>
                <th>رقم لوحة السيارة</th>
                <th>تاريخ الصنع</th>
                <th>تاريخ اخر صيانة</th>
                <th>صورة السيارة</th>
                <th>عدد الرحلات</th>
                <th>الحدث</th>
                </thead>
                <tbody>
                <?php require_once public_path('date_sum.php')?>
                @foreach($cars as $car)
                    <tr>
                        <td>#{{ $car->id }}</td>
                        <td>
                            <a href="{{ route('admin.panel.driver', ($car->driver->user_id ?? 0)) }}">
                                {{ $car->driver->fullname ?? "غير محدد" }}
                            </a>
                        </td>
                        <td>{{ $car->type }}</td>
                        <td>({{ $car->number }})</td>
                        <td>{{ $car->made_from }}</td>
                        <td>{{ $car->last_repair }}</td>
                        <td>
                            <a target="_blank" href="{{ asset('uploads/cars/'.$car->photo) }}">
                                <img style="max-width: 100px" src="{{ asset('uploads/cars/'.$car->photo) }}" alt="car_photo">
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.panel.orders_car', 'car='.$car->id) }}">
                                {{ $car->orders }} رحلة
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.panel.car.delete', $car->id) }}" class="text-decoration-none">
                                <i class="fa-solid fa-trash text-danger"></i>
                            </a>
                            <a href="{{ route('admin.panel.car.edit', $car->id) }}" class="text-decoration-none">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <br><br>

            @if ($cars->hasPages())
                <ul class="pagination">
                    @if ($cars->onFirstPage())
                        <li class="disabled"><span>{{ __('Prev') }}</span></li>
                    @else
                        <li><a href="{{ $cars->previousPageUrl() }}" rel="prev">{{ __('Prev') }}</a></li>
                    @endif
                    {{ " Page " . $cars->currentPage() . "  of  " . $cars->lastPage() }}
                    @if ($cars->hasMorePages())
                        <li><a href="{{ $cars->nextPageUrl() }}" rel="next">{{ __('Next') }}</a></li>
                    @else
                        <li class="disabled"><span>{{ __('Next') }}</span></li>
                    @endif
                </ul>
            @endif
        </div>
    </div>
    <br>
@endsection
