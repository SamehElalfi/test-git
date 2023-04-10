@extends('layouts.app')

@section('content')
    <?php use Illuminate\Support\Facades\Session as Session;?>
    <title>الملف الشخصي</title>
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <div align="center">
                <div align="start">
                    @if(Auth()->user()->membership == 3)
                        <a href="{{ route('admin.panel.users') }}" class="btn btn-dark">الرجوع</a>
                    @elseif(Auth()->user()->membership == 2)
                        <a href="#" onclick="history.back()" class="btn btn-dark">الرجوع</a>
                    @elseif(Auth()->user()->membership == 1)
                        <a href="#" onclick="history.back()" class="btn btn-dark">الرجوع</a>
                    @elseif(Auth()->user()->membership == 0)
                        <a href="#" onclick="history.back()" class="btn btn-dark">الرجوع</a>
                    @endif
                </div>
                <h3>بيانات المستخدم</h3>
                <hr>
                <div class="row">
                    <div id="container-img-user" align="center" style="margin: auto auto;position:relative;" onclick="$('input[type=file]').click()">
                        <img id="img-user" src="{{ asset("uploads/users/".($user->profile->photo ?? "user.png")) }}" onerror="this.onerror=null; this.src='{{ asset('uploads/users/user.png') }}'">
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
                <form method="post" action="{{ route('admin.panel.user.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
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
                            <input type="text" placeholder="اسم المستخدم" name="name" class="form-control" value="{{ $user->name }}" >
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
                    @if($user->membership == 0)
                        <div class="row">
                            <div class="col-3 my-auto">
                                <h6>اسم المستفيد بالخدمة</h6>
                            </div>
                            <div class="col-9">
                                <input type="text" placeholder="اسم المستفيد..." name="fullname" class="form-control" value="{{ $user->profile->fullname ?? "" }}" >
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
                                <input type="number" placeholder="العمر..." class="form-control" name="age" value="{{ $user->profile->age ?? "" }}" >
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-3 my-auto">
                                <h6>رقم هاتف منشئ الخدمة</h6>
                            </div>
                            <div class="col-9">
                                <input type="tel" placeholder="رقم هاتف منشئ الخدمة..." name="other_phone" class="form-control" value="{{ $user->profile->other_phone ?? "" }}" >
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
                    @endif
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
