@extends('layouts.app')

@section('content')
    <?php use Illuminate\Support\Facades\Session as Session;?>?>
    <title>الملف الشخصي</title>
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
    <style>
        .content {
            display: {{ (isset($user->profile) == 1) ? "none":"block" }};
        }
    </style>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <div align="center">
                <div align="start">
                    <a href="{{ route('driver.panel') }}" class="btn btn-outline-danger">لوحة إدارة الرحلات</a>
                </div>
                <h3>معلومات الحساب</h3>
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
                <form method="post" action="{{ route('driver.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="file" name="photo" style="width: 1px;height: 1px;opacity: 0">
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
                        <div class="col-9" align="start">
                            <div class="btn-group">
                                <input type="text" placeholder="رقم الهاتف..." class="form-control" value="{{ $user->phone }}" style="border-radius: 0 5px 5px 0" disabled>
                                <a class="btn btn-primary" onclick="togglePopupOTP()" style="border-radius: 20px 0 0 20px">تأكيد</a>
                            </div>
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
                    <div class="row">
                        <div class="col-3 my-auto">
                            <h6>كلمة السر القديمة</h6>
                        </div>
                        <div class="col-9">
                            <input type="password" placeholder="******" name="password" class="form-control" required>
                        </div>
                    </div>
                    <br>
                    <div id="btn-change-password" class="row px-5" align="center" onclick="$('#btn-change-password').toggle();$('#new-password-input').toggle()">
                        <a class="btn btn-primary">تغيير كلمة السر</a>
                    </div>
                    <div id="new-password-input" style="display: none">
                        <div class="row">
                            <div class="col-3 my-auto">
                                <h6>كلمة السر الجديدة</h6>
                            </div>
                            <div class="col-9">
                                <input type="password" placeholder="******" name="c_password" class="form-control">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div>
                        <input type="submit" value="حفظ" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--    Pop-up OTP    --}}
    <div class="content" id="content_otp" align="center">
        <div align="start">
            <button class="btn btn-outline-danger" onclick="togglePopupOTP()">X</button>
        </div>
        <div align="end">
            <a class="btn btn-outline-success" onclick="get_otp()">
                إرسال مرة اخري
                <i class="fa-solid fa-arrow-rotate-right fa-spin"></i>
            </a>
        </div>
        <br>
        <h5>رمز تفعيل رقم الهاتف سيصلك علي شكل رسالة <span class="text-success">WhatsApp</span> مكون من 6 ارقام</h5>
        <br>
        <form method="post" action="{{ route('user.active.otp') }}">
            @csrf
            @method('POST')

            <div class="btn-group">
                <input name="code" maxlength="6" type="text" placeholder="رمز السري مكون من 6 ارقام" class="form-control" style="border-radius: 0 5px 5px 0" required>
                <input type="submit" class="btn btn-primary" value="تفعيل" style="border-radius: 5px 0 0 5px">
            </div>
        </form>
    </div>
    <script src="{{ asset('js/profile.js') }}" type="text/javascript"></script>
@endsection
