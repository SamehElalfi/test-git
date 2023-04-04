@extends('layouts.app')

@section('content')
    <?php use Illuminate\Support\Facades\Auth;?>
    <?php use Illuminate\Support\Facades\Session as Session;?>
    <title>طلب الرحلة #{{ $order->slug }}</title>
    <style>

    </style>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <div align="center">
                @if(Auth::user()->membership == 2 or Auth::user()->membership == 3)
                    <div align="start">
                        <a href="{{ route('quiz.edit', $order->slug) }}" class="btn btn-outline-primary">
                            <i class="fa-solid fa-pen"></i>
                            تعديل البيانات
                        </a>
                    </div>
                @else
                    <div align="end">
                        <a href="#" onclick="history.back()">
                            <i class="fa-solid fa-circle-left fa-2x"></i>
                        </a>
                    </div>
                @endif
                <h3 id="text-step-1" class="text-primary">عرض بيانات الطلب #{{ $order->slug }}</h3>
                <hr>
                <br>
                @if($order->status == 0)
                    <div class="alert alert-warning">
                        <h6>تم إنشاء طلب الرحلة بنجاح وبإنتظار عملية تأكيد الرحلة و تحويلها الي السائق برجاء الإنتظار</h6>
                    </div>
                @elseif($order->status == 1)
                    <div class="alert alert-warning">
                        <h6>تم تأكيد الطلب النجاح وتحويلك مع السائق</h6>
                    </div>
                @elseif($order->status == 2)
                    <div class="alert alert-danger">
                        <h6>للأسف تم إلغاء الرحلة خاصتك</h6>
                    </div>
                @elseif($order->status == 3)
                    <div class="alert alert-success">
                        <h6>تمت الرحلة بنجاح</h6>
                    </div>
                @endif
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
                <form method="post" action="{{ route('quiz.update', $order->slug) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if(isset($order->driver))
                        {{--                    driver--}}
                        <div id="driver" class="row my-3">
                            <div class="col-3 my-auto">
                                <h6>
                                    السائق
                                </h6>
                            </div>
                            <div class="col-9">
                                <h5 align="start">
                                    <a href="{{ (isset($order->driver)==1) ? route('driver.profile', $order->driver->user_id):"#" }}">
                                        {{ $order->driver->fullname ?? "غير محدد" }}
                                    </a>
                                </h5>
                            </div>
                        </div>

                        <div id="phone-driver" class="row my-3">
                            <div class="col-3 my-auto">
                                <h6>
                                    رقم هاتف السائق
                                </h6>
                            </div>
                            <div class="col-9">
                                <h5 style="opacity: 90%" align="start">
                                    <i class="fa-solid fa-phone fa-beat" style="color: #8bd576;"></i>
                                    &nbsp;
                                    {{ $order->driver->user->phone ?? "غير محدد" }}
                                </h5>
                            </div>
                        </div>
                    @else
                        {{--                    driver--}}
                        <div id="driver" class="row my-3">
                            <div class="col-3 my-auto">
                                <h6>
                                    السائق
                                </h6>
                            </div>
                            <div class="col-9">
                                <input type="text" placeholder="إسم السائق..." name="name" class="form-control" value="{{ $order->driver->fullname ?? "غير محدد بعد" }}" disabled>
                            </div>
                        </div>
                    @endif

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
                            <select class="form-control" name="reason" disabled>
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
                            @foreach($needs as $need)
                                <a style="display: inline-block" class="btn btn-outline-primary px-2 mx-1">{{ $need }}</a>
                            @endforeach
                        </div>
                    </div>

                    {{--                    notes--}}
                    <div id="notes" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                ملحوظة
                            </h6>
                        </div>
                        <div class="col-9">
                            <textarea name="address" placeholder="ملحوظة..." class="form-control" rows="4" disabled>{{ $order->notes ?? "" }}</textarea>
                        </div>
                    </div>

                    {{--                    date of tour--}}
                    <div id="date_tour" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>تاريخ الرحلة</h6>
                        </div>
                        <div class="col-9">
                            <input type="date" class="form-control" name="date_tour" value="{{ $order->date_tour ?? "" }}" disabled required>
                        </div>
                    </div>

                    {{--                    time of start tour--}}
                    <div id="time_tour" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>توقيت بدء الرحلة</h6>
                        </div>
                        <div class="col-9">
                            <input type="time" class="form-control" name="time_tour" value="{{ $order->time_date_tour ?? "" }}" disabled required>
                        </div>
                    </div>

                    {{--                    start point--}}
                    <div id="start_point" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>نقطة الإنطلاق</h6>
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="نقطة الإنطلاق..." class="form-control" value="{{ $order->start_point ?? "" }}" name="start_point" disabled required>
                        </div>
                    </div>

                    {{--                    end point--}}
                    <div id="end_point" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>نقطة الوصول</h6>
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="نقطة الوصول..." class="form-control" value="{{ $order->end_point ?? "" }}" name="end_point" disabled required>
                        </div>
                    </div>

                    {{--                    full address--}}
                    <div id="full_address" class="row">
                        <div class="col-3 my-auto">
                            <h6>العنوان كامل</h6>
                        </div>
                        <div class="col-9">
                            <textarea name="address" placeholder="عنوان بالكامل..." class="form-control" rows="3" disabled required>{{ $order->address ?? "" }}</textarea>
                        </div>
                    </div>
                    <br>
                    {{--                    status--}}
                    <div id="status" class="row">
                        <div class="col-3 my-auto">
                            <h6>حالة الطلب</h6>
                        </div>
                        <div class="col-9">
                            @if($order->status == 0)
                                <div class="alert alert-secondary" style="width: auto;max-width: 300px">
                                    <b>قيد المراجعة</b>
                                </div>
                            @elseif($order->status == 1)
                                <div class="alert alert-warning" style="width: auto;max-width: 300px">
                                    <b>تم التأكيد</b>
                                </div>
                            @elseif($order->status == 2)
                                <div class="alert alert-danger" style="width: auto;max-width: 300px">
                                    <b>تم إلغاء الرحلة</b>
                                </div>
                            @elseif($order->status == 3)
                                <div class="alert alert-success" style="width: auto;max-width: 300px">
                                    <b>نجح التوصيل</b>
                                </div>
                            @endif
                        </div>
                    </div>
                    <br>
                    @if($order->status < 2)
                        <a href="{{ route('quiz.cancel', $order->id) }}" class="btn btn-outline-danger mx-3" style="display: inline-block">إلغاء الرحلة</a>
                        @if($order->status == 1)
                            @if(Auth::user()->membership < 2)
                                <a href="{{ route('quiz.success', $order->id) }}" class="btn btn-success mx-3" style="display: inline-block">تمت الرحلة</a>
                            @endif
                        @endif
                        <br><br>
                    @endif
                    @if(Auth::user()->membership == 2 or Auth::user()->membership == 3)
                        <div id="status" class="row">
                            <div class="col-3 my-auto">
                                <h6>معالجة الرحلة</h6>
                            </div>
                            <div class="col-9">
                                @if($order->status == 0)
                                    <div align="start">
                                        <a href="{{ route('quiz.process', $order->id) }}" class="btn btn-primary">معالجة الطلب</a>
                                    </div>
                                @else
                                    <input type="text" value="غير متاح التعديل علي حالة الرحلة" class="form-control" disabled>
                                @endif
                            </div>
                        </div>
                    @endif
                    <br><br>
                </form>
            </div>
        </div>
    </div>
    <br>
@endsection
