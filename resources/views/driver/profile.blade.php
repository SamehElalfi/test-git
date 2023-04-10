@extends('layouts.app')

@section('content')
    <title>الملف الشخصي</title>
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
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
                    <div id="container-img-user" align="center" style="margin: auto auto;position:relative;">
                        <img id="img-user" src="{{ asset("uploads/users/".($user->driver->photo ?? "user.png")) }}" onerror="this.onerror=null; this.src='{{ asset('uploads/users/user.png') }}'">
                    </div>
                </div>
                <br>
                <br>
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
                        <h6>اسم المستخدم:</h6>
                    </div>
                    <div class="col-9" align="start">
                        <h6 style="opacity: 80%">{{ $user->name ?? "فارغ" }}</h6>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-3 my-auto">
                        <h6>البريد الإلكتروني:</h6>
                    </div>
                    <div class="col-9" align="start">
                        <h6 style="opacity: 80%">{{ $user->email ?? "فارغ" }}</h6>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-3 my-auto">
                        <h6>رقم الهاتف:</h6>
                    </div>
                    <div class="col-9" align="start">
                        <h6 style="opacity: 80%">{{ $user->phone ?? "فارغ" }}</h6>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-3 my-auto">
                        <h6>الإسم بالكامل:</h6>
                    </div>
                    <div class="col-9" align="start">
                        <h6 style="opacity: 80%">{{ $user->driver->fullname ?? "فارغ" }}</h6>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-3 my-auto">
                        <h6>رقم الهوية:</h6>
                    </div>
                    <div class="col-9" align="start">
                        <h6 style="opacity: 80%">{{ $user->driver->personal_id ?? "فارغ" }}</h6>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-3 my-auto">
                        <h6>السيرة الذاتية:</h6>
                    </div>
                    <div class="col-9" align="start">
                        <h6 style="opacity: 80%">
                            <?php echo $user->driver->bio ?? "مرحبا انا سسائق";?>
                        </h6>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-3 my-auto">
                        <h6>المنطقة:</h6>
                    </div>
                    <div class="col-9" align="start">
                        <h6 style="opacity: 80%">{{ ($user->driver->country->name ?? "فارغ")." / ".($user->driver->state->name ?? "فارغ") }}</h6>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-3 my-auto">
                        <h6>متاح لإستلام الرحلات:</h6>
                    </div>
                    <div class="col-9" align="start">
                        @if(isset($user->driver->status))
                            @if($user->driver->status == 1)
                                <h6 class="text-success">نشط</h6>
                            @else
                                <h6 class="text-danger">مشغول</h6>
                            @endif
                        @else
                            <h6 class="text-danger">عير مفعل</h6>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br><br>
    <br>
@endsection
