@extends('layouts.app')

@section('content')
    <?php use Illuminate\Support\Facades\Session as Session;?>
    <title>طلب رحلة جديدة</title>
    {{--    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">--}}
    <style>

    </style>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <div align="end">
                <a href="#" onclick="history.back()">
                    <i class="fa-solid fa-circle-left fa-2x"></i>
                </a>
            </div>

            <div align="center">
                <h3 id="text-step-1" class="text-primary">برجاء ملئ البيانات التالية</h3>
                <hr>
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
                <form method="post" action="{{ route('quiz.store.solid') }}" id="my_radio_box2" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <div align="start">
                        <input id="op-user-0" type="radio" name="op_user" value="0" onclick="$('#user-isset').css('display', 'block');$('#user-not-isset').css('display', 'none')" checked>
                        <label for="op-user-0" onclick="$('#user-isset').css('display', 'block');$('#user-not-isset').css('display', 'none')">المستخدم موجود بالفعل</label>
                        &nbsp;
                        <input id="op-user-1" type="radio" name="op_user" value="1" onclick="$('#user-isset').css('display', 'none');$('#user-not-isset').css('display', 'block')">
                        <label for="op-user-1" onclick="$('#user-isset').css('display', 'none');$('#user-not-isset').css('display', 'block')">إنشاء مستخدم جديد</label>
                    </div>

                    <div id="user-isset">
                        {{--                    name--}}
                        <div id="client" class="row my-3">
                            <div class="col-3 my-auto">
                                <h6>
                                    العميل
                                </h6>
                            </div>
                            <div class="col-9">
                                <select name="user_id" class="form-control">
                                    <option value="" selected>اختر المستخدم</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ "($user->id). $user->name - $user->phone" }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="user-not-isset" style="display: none">
                        {{--                                        name--}}
                        <div id="username" class="row my-3">
                            <div class="col-3 my-auto">
                                <h6>
                                    اسم المستخدم
                                </h6>
                            </div>
                            <div class="col-9">
                                <input type="text" placeholder="اسم المستخدم..." name="username" class="form-control">
                            </div>
                        </div>

                        {{--                                        email--}}
                        <div id="email" class="row my-3">
                            <div class="col-3 my-auto">
                                <h6>
                                    البريد الإلكتروني
                                </h6>
                            </div>
                            <div class="col-9">
                                <input type="email" placeholder="email@examble.com" name="email" class="form-control">
                            </div>
                        </div>

                        {{--                                        phone--}}
                        <div id="phone" class="row my-3">
                            <div class="col-3 my-auto">
                                <h6>
                                    رقم الهاتف
                                </h6>
                            </div>
                            <div class="col-9">
                                <input type="tel" placeholder="رقم الهاتف..." name="phone" class="form-control">
                            </div>
                        </div>

                        {{--                                        password--}}
                        <div id="phone" class="row my-3">
                            <div class="col-3 my-auto">
                                <h6>
                                    كلمة السر
                                </h6>
                            </div>
                            <div class="col-9">
                                <input type="password" placeholder="******" name="password" class="form-control">
                            </div>
                        </div>
                    </div>

{{--                                        name--}}
                    <div id="name" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                إسم المستفيد من الخدمة
                            </h6>
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="اسم المستفيد..." name="name" class="form-control">
                        </div>
                    </div>

{{--                                        reason--}}
                    <div id="reason" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>الغرض من الرحلة</h6>
                        </div>
                        <div class="col-9">
                            <select class="form-control" name="reason">
                                <option value="متابعة المستشفى">متابعة المستشفى</option>
                                <option value="زيارة عائلية -من /الى المطار">زيارة عائلية -من /الى المطار</option>
                                <option value="مراجعات حكومية">مراجعات حكومية</option>
                                <option value="وسائل ترفيه">وسائل ترفيه</option>
                                <option value="أخرى">أخرى</option>
                            </select>
                        </div>
                    </div>

                    {{--                    needs--}}
                    <div id="needs" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>مطلوب توفير</h6>
                        </div>
                        <div class="col-9" align="start">
                            <input id="needs-1" type="checkbox" name="needs[]" value="كرسى">
                            <label for="needs-1">كرسي</label>
                            &nbsp;&nbsp;
                            <input id="needs-2" type="checkbox" name="needs[]" value="سرير">
                            <label for="needs-2">سرير</label>
                            &nbsp;&nbsp;
                            <input id="needs-3" type="checkbox" name="needs[]" value="مساعد">
                            <label for="needs-3">مساعد</label>
                            &nbsp;&nbsp;
                            <input id="needs-4" type="checkbox" name="needs[]" value="ليس بحاجة لمساعد">
                            <label for="needs-4">ليس بحاجة لمساعد</label>
                        </div>
                    </div>

                    {{--                    notes--}}
                    <div id="notes" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                ملحوظة
                                (<span class="text-primary">اختياري</span>)
                            </h6>
                        </div>
                        <div class="col-9">
                            <textarea name="address" placeholder="ملحوظة..." class="form-control" rows="4"></textarea>
                        </div>
                    </div>

                    {{--                    date of tour--}}
                    <div id="date_tour" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>تاريخ الرحلة</h6>
                        </div>
                        <div class="col-9">
                            <input type="date" class="form-control" name="date_tour" required>
                        </div>
                    </div>

                    {{--                    time of start tour--}}
                    <div id="time_tour" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>توقيت بدء الرحلة</h6>
                        </div>
                        <div class="col-9">
                            <input type="time" class="form-control" name="time_tour" required>
                        </div>
                    </div>

                    {{--                    start point--}}
                    <div id="start_point" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>نقطة الإنطلاق</h6>
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="نقطة الإنطلاق..." class="form-control" name="start_point" required>
                        </div>
                    </div>

                    {{--                    end point--}}
                    <div id="end_point" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>نقطة الوصول</h6>
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="نقطة الوصول..." class="form-control" name="end_point" required>
                        </div>
                    </div>

                    {{--                    full address--}}
                    <div id="full_address" class="row">
                        <div class="col-3 my-auto">
                            <h6>العنوان كامل</h6>
                        </div>
                        <div class="col-9">
                            <textarea name="address" placeholder="عنوان بالكامل..." class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <br><br>

                    {{--                    Terms--}}
                    <div class="row px-5" align="start">
                        <input id="terms" type="checkbox" name="terms" required>
                        &nbsp;
                        <label class="my-auto" for="terms">أوافق على <a target="_blank" class="text-decoration-none" href="{{ route('terms') }}">الشروط</a> والاحكام</label>
                    </div>
                    <br>

                    {{--                    Buttons--}}
                    <div id="btn-step-2" class="row">
                        <div class="col-12" align="center">
                            <input type="submit" class="btn btn-primary" value="تأكيد">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>
@endsection
