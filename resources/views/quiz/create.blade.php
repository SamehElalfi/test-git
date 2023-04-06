@extends('layouts.app')

@section('content')
    <?php use Illuminate\Support\Facades\Session as Session;?>
    <title>طلب رحلة جديدة</title>
{{--    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">--}}
    <style>

    </style>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <div align="center">
                <h3 id="text-step-1" class="text-primary">برجاء ملئ البيانات التالية</h3>
                <h3 id="text-step-2" class="text-dark" style="display: none">انت علي وشك تأكيد الرحلة الأن</h3>
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
                <form method="post" action="{{ route('quiz.store') }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

{{--                    name--}}
                    <div id="name" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                إسم المستفيد من الخدمة
                            </h6>
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="اسم المستفيد..." name="name" class="form-control" value="{{ $user->profile->fullname }}" disabled>
                        </div>
                    </div>

{{--                    phone--}}
                    <div id="phone" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                رقم الهاتف
                            </h6>
                        </div>
                        <div class="col-9">
                            <input type="tel" placeholder="رقم الهاتف..." class="form-control" value="{{ $user->phone }}" disabled>
                        </div>
                    </div>

{{--                    reason--}}
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
                    <div id="date_tour" class="row my-3" style="display: none">
                        <div class="col-3 my-auto">
                            <h6>تاريخ الرحلة</h6>
                        </div>
                        <div class="col-9">
                            <input type="date" class="form-control" name="date_tour" required>
                        </div>
                    </div>

{{--                    time of start tour--}}
                    <div id="time_tour" class="row my-3" style="display: none">
                        <div class="col-3 my-auto">
                            <h6>توقيت بدء الرحلة</h6>
                        </div>
                        <div class="col-9">
                            <input type="time" class="form-control" name="time_tour" required>
                        </div>
                    </div>

{{--                    start point--}}
                    <div id="start_point" class="row my-3" style="display: none">
                        <div class="col-3 my-auto">
                            <h6>نقطة الإنطلاق</h6>
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="نقطة الإنطلاق..." class="form-control" name="start_point" required>
                        </div>
                    </div>

{{--                    end point--}}
                    <div id="end_point" class="row my-3" style="display: none">
                        <div class="col-3 my-auto">
                            <h6>نقطة الوصول</h6>
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="نقطة الوصول..." class="form-control" name="end_point" required>
                        </div>
                    </div>

{{--                    full address--}}
                    <div id="full_address" class="row" style="display: none">
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
                    <div id="btn-step-1" class="row">
                        <div class="col-6" style="margin: auto auto">
                            <a href="#step-2" class="btn btn-primary" onclick="next()">التالي</a>
                        </div>
                    </div>
                    <div id="btn-step-2" class="row" style="display: none">
                        <div class="col-6" align="start">
                            <input type="submit" class="btn btn-primary" value="تأكيد">
                        </div>
                        <div class="col-6" align="end">
                            <a href="#step-1" class="btn btn-dark" onclick="back()">الرجوع</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>
    <script src="{{ asset('js/create_quiz.js') }}" type="text/javascript"></script>
@endsection
