@extends('layouts.app')

@section('content')
    <?php use Illuminate\Support\Facades\Session as Session;?>

    <title>طلب الرحلة #{{ $order->slug }}</title>
    <style>

    </style>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <div align="center">
                <div align="end">
                    <a href="#" onclick="history.back()">
                        <i class="fa-solid fa-circle-left fa-2x"></i>
                    </a>
                </div>
                <h3 id="text-step-1" class="text-primary">معالجة طلب الرحلة </h3>
                <hr>
                <br>
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
                <form method="post" action="{{ route('quiz.process.change', $order->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <div id="driver" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                رقم الرحلة
                            </h6>
                        </div>
                        <div class="col-9">
                            <input type="text" value="#{{ $order->slug ?? "" }}" class="form-control" disabled>
                        </div>
                    </div>

                    <div id="driver" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                المستخدم
                            </h6>
                        </div>
                        <div class="col-9">
                            <input type="text" value="{{ $order->user->name ?? "فارغ" }}" class="form-control" disabled>
                        </div>
                    </div>

                    <div id="driver" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                رقم الهاتف
                            </h6>
                        </div>
                        <div class="col-9">
                            <input type="text" value="{{ $order->user->phone ?? "فارغ" }}" class="form-control" disabled>
                        </div>
                    </div>

                    <div id="driver" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                نقطة الإنطلاق
                            </h6>
                        </div>
                        <div class="col-9">
                            <input type="text" value="منقطة / حي" class="form-control" disabled>
                        </div>
                    </div>

                    <div id="driver" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                نقطة الوصول
                            </h6>
                        </div>
                        <div class="col-9">
                            <input type="text" value="منقطة / حي" class="form-control" disabled>
                        </div>
                    </div>

                    <div id="status-show" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                حالة الطلب
                            </h6>
                        </div>
                        <div class="col-9" align="start">
                            @if($order->status == 0)
                                <h6>قيد المعالجة</h6>
                            @elseif($order->status == 1)
                                <h6>بإنتظار التحرك</h6>
                            @elseif($order->status == 2)
                                <h6>جاري التنفيذ</h6>
                            @elseif($order->status == 3)
                                <h6>تم التوصيل بنجاح</h6>
                            @elseif($order->status == 4)
                                <h6>تم الإلغاء</h6>
                            @endif
                        </div>
                    </div>

                    @if(in_array($order->status, [0, 1, 2]))
                        <div id="driver" class="row my-3">
                            <div class="col-3 my-auto">
                                <h6>
                                    معالجة حالة الطلب
                                </h6>
                            </div>
                            @if($order->status == 0)
                                <div class="col-9">
                                    <select name="status" class="form-control" onchange="drivers()">
                                        <option value="0" {{ ($order->status==0) ? "selected":"" }}>قيد المعالجة</option>
                                        <option value="1" {{ ($order->status==1) ? "selected":"" }}>بإنتظار التحرك</option>
                                        <option value="4" {{ ($order->status==4) ? "selected":"" }}>تم الإلغاء</option>
                                    </select>
                                </div>
                            @elseif($order->status == 1)
                                <div class="col-9">
                                    <select name="status" class="form-control">
                                        <option value="1" {{ ($order->status==1) ? "selected":"" }}>بإنتظار التحرك</option>
                                        <option value="2" {{ ($order->status==2) ? "selected":"" }}>جاري التنفيذ</option>
                                        <option value="4" {{ ($order->status==4) ? "selected":"" }}>تم الإلغاء</option>
                                    </select>
                                </div>
                            @elseif($order->status == 2)
                                <div class="col-9">
                                    <select name="status" class="form-control">
                                        <option value="2" {{ ($order->status==2) ? "selected":"" }}>جاري التنفيذ</option>
                                        <option value="3" {{ ($order->status==3) ? "selected":"" }}>تم التوصيل بنجاح</option>
                                        <option value="4" {{ ($order->status==4) ? "selected":"" }}>تم الإلغاء</option>
                                    </select>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($order->status == 0)
                        <div id="drivers" class="row my-3" onchange="cars()" style="display: none">
                            <div class="col-3 my-auto">
                                <h6>
                                    السائقين
                                </h6>
                            </div>
                            <div class="col-9">
                                <select name="driver" class="form-control">
                                    <option value="">اختر السائق</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->driver->id ?? $user->id }}">{{ "($user->id)" . ". " . ($user->driver->fullname ?? "غير محدد") . " " . (($user->driver->country->name ?? "غير محدد") . " / " . ($user->driver->state->name ?? "غير محدد")) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="cars" class="row my-3" style="display: none">
                            <div class="col-3 my-auto">
                                <h6>
                                    السيارات
                                </h6>
                            </div>
                            <div class="col-9">
                                <select name="car" class="form-control">
                                    @foreach($cars as $car)
                                        <option value="{{ $car->id }}">{{ "($car->id). $car->type - $car->number" }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    <div class="row" align="center">
                        <div style="margin: auto auto">
                            <input type="submit" class="btn btn-primary" value="تأكيد الرحلة">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>
    <script>
        function drivers(){
            $val_status = $("select[name=status]").val();
            if ($val_status == 1){
                $('#drivers').toggle();
            }else{
                $('#drivers').css('display', 'none');
                $('#cars').css('display', 'none');
            }
        }
        function cars(){
            $val_drivers = $("select[name=driver]").val();
            if ($val_drivers == ""){
                $('#cars').css('display', 'none');
            }else{
                $('#cars').toggle();
            }
        }
    </script>
@endsection
