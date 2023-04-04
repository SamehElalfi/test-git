@extends('layouts.app')

@section('content')
    <title>طلب الرحلة #{{ $order->slug }}</title>
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
                <h3 id="text-step-1" class="text-primary">تعديل بيانات الطلب #{{ $order->slug }}</h3>
                <hr>
                <br>
                @if(count($errors) > 0)
                    @foreach($errors->all() as $i)
                        <div class="alert alert-warning text-center">
                            <h6>{{ $i }}</h6>
                        </div>
                    @endforeach
                @endif
                @if (\Session::has('success'))
                    <div class="alert alert-success text-center" role="alert">
                        {!! \Session::get('success') !!}
                    </div>
                @endif
                @if (\Session::has('exist'))
                    <div class="alert alert-warning text-center" role="alert">
                        {!! \Session::get('exist') !!}
                    </div>
                @endif
                <br>
                <form method="post" action="{{ route('quiz.update', $order->slug) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

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
                                <option value="متابعة المستشفى" {{ ($order->reason == "متابعة المستشفى") ? "selected":"" }}>متابعة المستشفى</option>
                                <option value="زيارة عائلية -من /الى المطار" {{ ($order->reason == "زيارة عائلية -من /الى المطار") ? "selected":"" }}>زيارة عائلية -من /الى المطار</option>
                                <option value="مراجعات حكومية" {{ ($order->reason == "مراجعات حكومية") ? "selected":"" }}>مراجعات حكومية</option>
                                <option value="وسائل ترفيه" {{ ($order->reason == "وسائل ترفيه") ? "selected":"" }}>وسائل ترفيه</option>
                                <option value="أخرى" {{ ($order->reason == "أخرى") ? "selected":"" }}>أخرى</option>
                            </select>
                        </div>
                    </div>

                    {{--                    needs--}}
                    <div id="needs" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>مطلوب توفير</h6>
                        </div>
                        <?php $needs = explode(", ", $order->needs);?>
                        <div class="col-9" align="start">
                            <input id="needs-1" type="checkbox" name="needs[]" value="كرسى" {{ (in_array("كرسى", $needs)) ? "checked":"" }}>
                            <label for="needs-1">كرسي</label>
                            &nbsp;&nbsp;
                            <input id="needs-2" type="checkbox" name="needs[]" value="سرير" {{ (in_array("سرير", $needs)) ? "checked":"" }}>
                            <label for="needs-2">سرير</label>
                            &nbsp;&nbsp;
                            <input id="needs-3" type="checkbox" name="needs[]" value="مساعد" {{ (in_array("مساعد", $needs)) ? "checked":"" }}>
                            <label for="needs-3">مساعد</label>
                            &nbsp;&nbsp;
                            <input id="needs-4" type="checkbox" name="needs[]" value="ليس بحاجة لمساعد" {{ (in_array("ليس بحاجة لمساعد", $needs)) ? "checked":"" }}>
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
                            <textarea name="address" placeholder="ملحوظة..." class="form-control" rows="4">{{ $order->notes ?? "" }}</textarea>
                        </div>
                    </div>

                    {{--                    date of tour--}}
                    <div id="date_tour" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>تاريخ الرحلة</h6>
                        </div>
                        <div class="col-9">
                            <input type="date" class="form-control" name="date_tour" value="{{ $order->date_tour ?? "" }}" required>
                        </div>
                    </div>

                    {{--                    time of start tour--}}
                    <div id="time_tour" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>توقيت بدء الرحلة</h6>
                        </div>
                        <div class="col-9">
                            <input type="time" class="form-control" name="time_tour" value="{{ $order->time_date_tour ?? "" }}" required>
                        </div>
                    </div>

                    {{--                    start point--}}
                    <div id="start_point" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>نقطة الإنطلاق</h6>
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="نقطة الإنطلاق..." class="form-control" value="{{ $order->start_point ?? "" }}" name="start_point" required>
                        </div>
                    </div>

                    {{--                    end point--}}
                    <div id="end_point" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>نقطة الوصول</h6>
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="نقطة الوصول..." class="form-control" value="{{ $order->end_point ?? "" }}" name="end_point" required>
                        </div>
                    </div>

                    {{--                    full address--}}
                    <div id="full_address" class="row">
                        <div class="col-3 my-auto">
                            <h6>العنوان كامل</h6>
                        </div>
                        <div class="col-9">
                            <textarea name="address" placeholder="عنوان بالكامل..." class="form-control" rows="3" required>{{ $order->address ?? "" }}</textarea>
                        </div>
                    </div>
                    <br><br>

                    {{--                    Buttons--}}
                    <div id="btn-step-1" class="row">
                        <div class="col-6" style="margin: auto auto">
                            <input type="submit" value="حفظ" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>
@endsection
