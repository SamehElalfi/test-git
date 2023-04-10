@extends('layouts.app')

@section('content')
    <?php use Illuminate\Support\Facades\Session as Session;?>
    <title>معلومات الحساب</title>
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
    <style>
        .content {
            display: {{ (isset($user->profile) == 1) ? "none":"block" }};
        }
    </style>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <div align="center">
                <div align="end">
                    <a href="#" onclick="history.back()">
                        <i class="fa-solid fa-circle-left fa-2x"></i>
                    </a>
                </div>
                <h3>معلومات عن السائق</h3>
                <hr>
                <div class="row">
                    <div id="container-img-user" align="center" style="margin: auto auto;position:relative;" onclick="$('input[type=file]').click()">
                        <img id="img-user" src="{{ asset("uploads/users/".($user->profile->photo ?? "user.png")) }}" onerror="this.onerror=null; this.src='{{ asset('uploads/users/user.png') }}'">
                        <div id="btn-edit-photo" style="">
                            <i class="fa-solid fa-arrow-up-right-from-square fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
                <br>
                @if(count($errors) > 0)
                    @foreach($errors->all() as $i)
                        <div class="alert alert-warning text-center">
                            <h6>{{ $i }}</h6>
                        </div>
                    @endforeach
                @endif
                @if (Session::has('error'))
                    <div class="alert alert-danger text-center" role="alert">
                        {!! Session::get('error') !!}
                    </div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-success text-center" role="alert">
                        {!! Session::get('success') !!}
                    </div>
                @endif
                @if (Session::has('exist'))
                    <div class="alert alert-warning text-center" role="alert">
                        {!! Session::get('exist') !!}
                    </div>
                @endif
                <br>
                <form method="post" action="{{ route('admin.panel.driver.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="file" name="photo" style="width: 1px;height: 1px;opacity: 0">
                    <div class="row">
                        <div class="col-3 my-auto">
                            <h6>السيارة</h6>
                        </div>
                        <div class="col-9" align="start">
                            @if(isset($user->driver->car))
                                <h6 class="text-dark">{{ "(".$user->driver->car->id.").". $user->driver->car->type . " (" . $user->driver->car->number . ")" }}</h6>
                            @else
                                <h6 class="text-danger">لا يمتلك سيارة</h6>
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-3 my-auto">
                            <h6>اسم المستخدم</h6>
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="اسم المستخدم" name="name" class="form-control" value="{{ $user->name }}" required>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-3 my-auto">
                            <h6>البريد الإلكتروني</h6>
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="البريد الإلكتروني..." class="form-control" value="{{ $user->email }}" disabled>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-3 my-auto">
                            <h6>رقم الهاتف</h6>
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="رقم الهاتف..." class="form-control" value="{{ $user->phone }}" disabled>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-3 my-auto">
                            <h6>الإسم بالكامل</h6>
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="الإسم بالكامل..." name="fullname" class="form-control" value="{{ $user->driver->fullname ?? "" }}" required>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-3 my-auto">
                            <h6>رقم الهوية:</h6>
                        </div>
                        <div class="col-9" align="start">
                            <input type="text" placeholder="رقم الهوية..." name="personal_id" class="form-control" value="{{ $user->driver->personal_id ?? "" }}" required>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-3 my-auto">
                            <h6>السيرة الذاتية</h6>
                        </div>
                        <div class="col-9">
                            <textarea name="bio" class="form-control" placeholder="اكتب مسيرتك المهنية هنا..." rows="8">{{ $user->driver->bio ?? "" }}</textarea>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-3 my-auto">
                            <h6>المنطقة</h6>
                        </div>
                        <div class="col-9">
                            <select name="country" class="form-control">
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ ($user->driver->country_id == $country->id) ? "selected":"" }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-3 my-auto">
                            <h6>الحي</h6>
                        </div>
                        <div class="col-9">
                            <select name="state" class="form-control">
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ ($user->driver->state_id == $state->id) ? "selected":"" }}>{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-3 my-auto">
                            <h6>متاح للإستلام الرحلات</h6>
                        </div>
                        <div class="col-9" align="start">
                            <input name="status" value="1" type="checkbox" class="form-control" style="width: 40px" {{ ($user->driver->status == 1) ? "checked":"" }}>
                        </div>
                    </div>
                    <br>
                    <div id="new-password-input">
                        <div class="row">
                            <div class="col-3 my-auto">
                                <h6>كلمة السر الجديدة</h6>
                            </div>
                            <div class="col-9">
                                <input type="password" placeholder="******" name="c_password" class="form-control">
                            </div>
                        </div>
                    </div>
                    @if(Auth()->user()->membership == 3)
                        <br>
                        <div id="new-password-input">
                            <div class="row">
                                <div class="col-3 my-auto">
                                    <h6>العضوية</h6>
                                </div>
                                <div class="col-9">
                                    <select class="form-control" name="membership">
                                        <option value="0" {{ ($user->membership == 0) ? "selected":"" }}>عميل</option>
                                        <option value="1" {{ ($user->membership == 1) ? "selected":"" }}>سائق</option>
                                        <option value="2" {{ ($user->membership == 2) ? "selected":"" }}>خدمة عملاء</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    <br>
                    <div>
                        <input type="submit" value="حفظ" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <br><br>
    <br>
@endsection
