@extends('layouts.app')

@section('content')
    <title>إدارة المستخدمين</title>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <div align="end">
                <a href="{{ route('admin.panel') }}" class="btn btn-outline-success">
                    لوحة التحكم
                </a>
            </div>
            <h3 class="text-primary text-center">إدارة المستخدمين</h3>
            <br>
            <?php require_once public_path("panel_admin.php")?>
            <hr>
            <div align="start">
                <a href="{{ route('user.add') }}" class="btn btn-outline-success">اضف مستخدم جديد</a>
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
                    $membership = $_GET['membership'] ?? 'all';
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
                                        <option value="name$$$ASC" {{ ($sort == 'name$$$ASC') ? "selected":"" }}>أ الي ي</option>
                                        <option value="name$$$DESC" {{ ($sort == 'name$$$DESC') ? "selected":"" }}>ي الي أ</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{--                        Memberships--}}
                        <div class="col-6 my-2">
                            <div class="row">
                                <div class="col-5">
                                    <h6>العضوية</h6>
                                </div>
                                <div class="col-7">
                                    <select class="form-control" name="membership">
                                        <option value="all" {{ ($membership == '0') ? "selected":"" }}>جميع العضويات</option>
                                        <option value="0" {{ ($membership == '0') ? "selected":"" }}>عميل</option>
                                        <option value="1" {{ ($membership == '1') ? "selected":"" }}>سائق</option>
                                        <option value="2" {{ ($membership == '2') ? "selected":"" }}>خدمة عملاء</option>
                                        <option value="3" {{ ($membership == '3') ? "selected":"" }}>أدمن</option>
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

                        {{--                        Submit--}}
                        <div class="col-6 my-3">
                            <input type="submit" class="btn btn-primary" value="فرز" style="margin: auto auto">
                        </div>
                    </div>
                </form>
            </div>

            <br>
            <h2 class="text-dark" align="start">النتائج (<span class="text-primary">{{ $users->total() }}</span>)</h2>
            <br>
            <table class="table table-bordered table-secondary table-responsive-lg">
                <thead>
                <th>#</th>
                <th>إسم المستخدم</th>
                <th>رقم الهاتف</th>
                <th>البريد الإلكتروني</th>
                <th>العضوية</th>
                <th>تاريخ الإنضمام</th>
                <th>الحدث</th>
                </thead>
                <tbody>
                <?php require_once public_path('date_sum.php')?>
                @foreach($users as $user)
                    <tr>
                        <td>#{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>
                            <div class="dropdown" style="display: inline-block">
                                <a class="text-decoration-none" type="button" id="dropdown-{{ $user->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="$('div[aria-labelledby=dropdown-{{ $user->id }}]').toggle()">
                                    {{ $user->phone }}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdown-{{ $user->id }}">
                                    <a href="https://api.whatsapp.com/send?phone=966{{ $user->phone }}" class="text-success">تواصل واتساب</a>
                                    <br>
                                    <a onclick="window.open('tel:966{{ $user->phone }}');" class="text-primary">مكالمة هاتفية</a>
                                </div>
                            </div>
                            |
                            @if($user->phone_verfied_at == null)
                                <span class="text-danger">غير مربوط بالواتساب</span>
                            @else
                                <span class="text-success">مربوط بالواتساب</span>
                            @endif
                        </td>
                        <td>{{ $user->email ?? "فارغ" }}</td>
                        <td>
                            <b>
                                @if($user->membership == 0)
                                    عميل
                                @elseif($user->membership == 1)
                                    سائق
                                @elseif($user->membership == 2)
                                    دعم فني
                                @elseif($user->membership == 3)
                                    أدمن
                                @endif
                            </b>
                        </td>
                        <td>{{ rest_date($user->created_at) }}</td>
                        <td>
                            @if($user->membership == 1)
                                <a href="{{ route('admin.panel.driver', $user->id) }}" class="text-decoration-none">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            @else
                                <a href="{{ route('admin.panel.user', $user->id) }}" class="text-decoration-none">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <br><br>

            @if ($users->hasPages())
                <ul class="pagination">
                    @if ($users->onFirstPage())
                        <li class="disabled"><span>{{ __('Prev') }}</span></li>
                    @else
                        <li><a href="{{ $users->previousPageUrl() }}" rel="prev">{{ __('Prev') }}</a></li>
                    @endif
                    {{ " Page " . $users->currentPage() . "  of  " . $users->lastPage() }}
                    @if ($users->hasMorePages())
                        <li><a href="{{ $users->nextPageUrl() }}" rel="next">{{ __('Next') }}</a></li>
                    @else
                        <li class="disabled"><span>{{ __('Next') }}</span></li>
                    @endif
                </ul>
            @endif
        </div>
    </div>
    <br>
@endsection
