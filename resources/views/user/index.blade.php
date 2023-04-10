@extends('layouts.app')

@section('content')
    <?php use Illuminate\Support\Facades\Session as Session;?>
    <title>الملف الشخصي</title>
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
    <style>
        .content {
            display: {{ (isset($user->profile) == 1) ? "none":"block" }};
        }
    </style>
{{--    {{ Auth()->user() }}--}}
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <div align="center">
                <h3>الملف الشخصي</h3>
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
                <form method="post" action="{{ route('user.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="file" name="photo" style="width: 1px;height: 1px;opacity: 0">
                    <div class="row">
                        <div class="col-3 my-auto">
                            <h6>رقم العميل</h6>
                        </div>
                        <div class="col-9">
                            <input type="text" name="id" class="form-control" value="#{{ $user->id }}" disabled>
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
                        <div class="col-9" align="start">
                            <div class="btn-group">
                                <input type="text" placeholder="رقم الهاتف..." class="form-control" value="{{ $user->phone }}" style="border-radius: 0 5px 5px 0" disabled>
                                <a class="btn btn-primary" onclick="togglePopupOTP()" style="border-radius: 20px 0 0 20px">تأكيد</a>
                            </div>
                        </div>
                    </div>
                    <br>
                    @if(Auth()->user()->membership == 0)
                        <div class="row">
                            <div class="col-3 my-auto">
                                <h6>اسم المستفيد بالخدمة</h6>
                            </div>
                            <div class="col-9">
                                <input type="text" placeholder="اسم المستفيد..." name="fullname" class="form-control" value="{{ $user->profile->fullname ?? "" }}" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-3 my-auto">
                                <h6>جنس المستفيد</h6>
                            </div>
                            <div class="col-9">
                                @if(isset($user->profile->gender))
                                    <select class="form-control" name="gender">
                                        <option value="1" {{ ($user->profile->gender == 1) ? "selected":"" }}>ذكر</option>
                                        <option value="0" {{ ($user->profile->gender == 0) ? "selected":"" }}>انثي</option>
                                    </select>
                                @else
                                    <select class="form-control" name="gender">
                                        <option value="1" selected>ذكر</option>
                                        <option value="0">انثي</option>
                                    </select>
                                @endif
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-3 my-auto">
                                <h6>العمر</h6>
                            </div>
                            <div class="col-9">
                                <input type="number" placeholder="العمر..." class="form-control" name="age" value="{{ $user->profile->age ?? "" }}" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-3 my-auto">
                                <h6>رقم هاتف منشئ الخدمة</h6>
                            </div>
                            <div class="col-9">
                                <input type="tel" placeholder="رقم هاتف منشئ الخدمة..." name="other_phone" class="form-control" value="{{ $user->profile->other_phone ?? "" }}" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-3 my-auto">
                                <h6>صفتك بالمستفيد</h6>
                            </div>
                            <div class="col-9">
                                @if(isset($user->profile->relation))
                                    <select class="form-control" name="relation">
                                        <option value="الابن" {{ ($user->profile->relation == "الابن") ? "selected":"" }}>الابن</option>
                                        <option value="الوالد" {{ ($user->profile->relation == "الوالد") ? "selected":"" }}>الوالد</option>
                                        <option value="الزوجة" {{ ($user->profile->relation == "الزوجة") ? "selected":"" }}>الزوجة</option>
                                        <option value="الزوج" {{ ($user->profile->relation == "الزوج") ? "selected":"" }}>الزوج</option>
                                        <option value="المستفيد نفسه" {{ ($user->profile->relation == "المستفيد نفسه") ? "selected":"" }}>المستفيد نفسه</option>
                                    </select>
                                @else
                                    <select class="form-control" name="relation">
                                        <option value="الابن">الابن</option>
                                        <option value="الوالد">الوالد</option>
                                        <option value="الزوجة">الزوجة</option>
                                        <option value="الزوج">الزوج</option>
                                        <option value="المستفيد نفسه">المستفيد نفسه</option>
                                    </select>
                                @endif
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-3 my-auto">
                                <h6>جئت من طرف</h6>
                            </div>
                            <div class="col-9">
                                @if(isset($user->profile->redirect))
                                    <select class="form-control" name="redirect">
                                        <option value="جوجل" {{ ($user->profile->redirect == "جوجل") ? "selected":"" }}>جوجل</option>
                                        <option value="احد الاصدقاء" {{ ($user->profile->redirect == "احد الاصدقاء") ? "selected":"" }}>احد الاصدقاء</option>
                                        <option value="منصة تواصل اجتماعي" {{ ($user->profile->redirect == "منصة تواصل اجتماعي") ? "selected":"" }}>منصة تواصل اجتماعي</option>
                                    </select>
                                @else
                                    <select class="form-control" name="redirect">
                                        <option value="جوجل">جوجل</option>
                                        <option value="احد الاصدقاء">احد الاصدقاء</option>
                                        <option value="منصة تواصل اجتماعي">منصة تواصل اجتماعي</option>
                                    </select>
                                @endif
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-3 my-auto">
                                <h6>حالة الحساب</h6>
                            </div>
                            <div class="col-9">
                                @if($user->profile == "" and $user->phone_verfied_at == null)
                                    <input type="text" class="form-control" placeholder="حالة الحساب..." value="غير مفعل" disabled style="color: red">
                                @elseif($user->profile != "" and $user->phone_verfied_at == null)
                                    <input type="text" class="form-control" placeholder="حالة الحساب..." value="البيانات مكتملة | غير مربوط بالواتساب" disabled>
                                @elseif($user->profile == "" and $user->phone_verfied_at != null)
                                    <input type="text" class="form-control" placeholder="حالة الحساب..." value="البيانات غير مكتملة | مربوط بالواتساب" disabled>
                                @elseif($user->profile != "" and $user->phone_verfied_at != null)
                                    <input type="text" class="form-control" placeholder="حالة الحساب..." value="مفعل" disabled style="color: #19ad19">
                                @endif
                            </div>
                        </div>
                    @endif
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
    <div class="content" id="content_otp" align="center" style="display: none">
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

    @if(Auth()->user()->membership == 0)
        {{--    Pop-up--}}
        <div class="content" id="content" align="center">
            <audio id="audio" src="{{ asset("mp3/pop-up.mp3") }}" {{ (isset($user->profile) == 1) ? "":"autoplay" }}></audio>
            <div align="start">
                <button class="btn btn-outline-danger" onclick="togglePopup()">X</button>
            </div>
            <h3 class="text-primary">برجاء إستكمال الملف الشخصي</h3>
            <br>
            <h5 id="text-step-1">الخطوة 1 من 2</h5>
            <h5 id="text-step-2" style="display: none">الخطوة 2 من 2</h5>
            @if(count($errors) > 0)
                @foreach($errors->all() as $i)
                    <div class="alert alert-warning text-center">
                        <h6>{{ $i }}</h6>
                    </div>
                @endforeach
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
            <form method="post" action="{{ route('user.update') }}">
                @csrf
                @method('PUT')

                {{--            fullname--}}
                <div id="pop-fullname" class="mb-3 row">
                    <div class="col-3 my-auto">
                        <h6>اسم المستفيد بالخدمة</h6>
                    </div>
                    <div class="col-9">
                        <input type="text" name="fullname" placeholder="اسم المستفيد..." class="form-control" value="{{ $user->profile->fullname ?? "" }}">
                    </div>
                </div>
                {{--            gender--}}
                <div id="pop-gender" class="mb-3 row">
                    <div class="col-3 my-auto">
                        <h6>جنس المستفيد</h6>
                    </div>
                    <div class="col-9">
                        <select class="form-control" name="gender">
                            <option value="1" selected>ذكر</option>
                            <option value="0">انثي</option>
                        </select>
                    </div>
                </div>
                {{--            age--}}
                <div id="pop-age" class="mb-3 row">
                    <div class="col-3 my-auto">
                        <h6>العمر</h6>
                    </div>
                    <div class="col-9">
                        <input type="number" name="age" placeholder="العمر..." class="form-control">
                    </div>
                </div>
                {{--            phone--}}
                <div style="display: none" id="pop-phone" class="mb-3 row">
                    <div class="col-3 my-auto">
                        <h6>رقم هاتف منشئ الخدمة</h6>
                    </div>
                    <div class="col-9">
                        <input type="tel" name="other_phone" placeholder="رقم هاتف منشئ الخدمة..." class="form-control">
                    </div>
                </div>
                {{--            relation--}}
                <div style="display: none" id="pop-relation" class="mb-3 row">
                    <div class="col-3 my-auto">
                        <h6>صفتك بالمستفيد</h6>
                    </div>
                    <div class="col-9">
                        <select class="form-control" name="relation">
                            <option value="الابن">الابن</option>
                            <option value="الوالد">الوالد</option>
                            <option value="الزوجة">الزوجة</option>
                            <option value="الزوج">الزوج</option>
                            <option value="المستفيد نفسه">المستفيد نفسه</option>
                        </select>
                    </div>
                </div>
                {{--            redirect--}}
                <div style="display: none" id="pop-redirect" class="mb-3 row">
                    <div class="col-3 my-auto">
                        <h6>جئت من طرف</h6>
                    </div>
                    <div class="col-9">
                        <select class="form-control" name="redirect">
                            <option value="جوجل">جوجل</option>
                            <option value="احد الاصدقاء">احد الاصدقاء</option>
                            <option value="منصة تواصل اجتماعي">منصة تواصل اجتماعي</option>
                        </select>
                    </div>
                </div>
                <br><br>

                {{--            buttons of step(1)--}}
                <div id="step-1">
                    <div style="position:absolute;right: 5%;bottom: 3%">
                        <a onclick="next()" class="btn btn-primary" href="#2">التالي</a>
                    </div>
                    <div style="position:absolute;left: 5%;bottom: 3%">
                        <a onclick="togglePopup()" class="btn btn-dark" href="#">إغلاق</a>
                    </div>
                </div>
                {{--            buttons of step(2)--}}
                <div id="step-2" style="display: none">
                    <div style="position:absolute;right: 5%;bottom: 3%">
                        <input type="submit" name="continue" value="تأكيد" class="btn btn-primary">
                    </div>
                    <div style="position:absolute;left: 5%;bottom: 3%">
                        <a onclick="prev()" class="btn btn-dark" href="#">الرجوع</a>
                    </div>
                </div>
            </form>
        </div>
    @endif
    <br><br>
    <br>
    <script src="{{ asset('js/profile.js') }}" type="text/javascript"></script>
@endsection
