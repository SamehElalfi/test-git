<?php
//use Illuminate\Support\Facades\Session as Session;
?>

    <!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
</head>
<style>
    @font-face { font-family: DG-Shamael-Black; src: url('{{ asset('fonts/DGShamael-Black.ttf') }}'); }
    @font-face { font-family: Tajawal medium; src: url('{{ asset('fonts/Tajawal-Medium.ttf') }}'); }
    h1, h2, h3, h4, h5, h6, a, span, input, textarea,select, select option, .btn, label, .alert, th{
        font-family: 'DG-Shamael-Black';
        font-weight: 600;
    }
    h6, .btn{
        font-weight: 500;
    }
    .col-xl-2 .expanded{
        overflow: auto;
    }
    /*a:not(.no-class, .image-cate, .image-offer){*/
    /*    transition: width 500ms linear, height 500ms linear;*/
    /*}*/
    /*a:not(.btn, .text-nav, .no-opacity, .no-class, .image-cate, .image-offer):hover{*/
    /*    opacity: 75%;*/
    /*}*/
</style>
<body>
<section class="wrapper-box">
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light" dir="rtl">
            <a class="navbar-brand" href="{{ route('home') }}">Yossr</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">تسجيل الدخول</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">إنشاء حساب</a>
                        </li>
                    @else
                        <li class="nav-item">
                            @if(Auth()->user()->membership == 0)
                                <a class="nav-link" href="{{ route('quiz.create') }}">إنشاء رحلة</a>
                            @elseif(Auth()->user()->membership == 1)
                                <a class="nav-link" href="{{ route('driver.panel') }}">لوحة التحكم</a>
                            @elseif(Auth()->user()->membership == 2)
                                <a class="nav-link" href="{{ route('quiz.add.solid') }}">إنشاء رحلة</a>
                            @elseif(Auth()->user()->membership == 3)
                                <a class="nav-link" href="{{ route('admin.panel') }}">إدارة الموقع</a>
                            @endif
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                الحساب
                            </a>
                            <div class="dropdown-menu px-4 text-right" aria-labelledby="navbarDropdown">
                                <h6>اهلا وسهلا, {{ Auth()->user()->name }}</h6>
                                <h6 class="text-secondary">
                                    العضوية (
                                    @if(Auth()->user()->membership == 0)
                                        عميل
                                    @elseif(Auth()->user()->membership == 1)
                                        سائق
                                    @elseif(Auth()->user()->membership == 2)
                                        دعم فني
                                    @elseif(Auth()->user()->membership == 3)
                                        أدمن
                                    @endif
                                    )
                                </h6>
                                <hr>
                                @if(Auth()->user()->membership == 1)
                                    <a class="dropdown-item" href="{{ route('driver.profile.edit') }}">الملف الشخصي</a>
                                @else
                                    <a class="dropdown-item" href="{{ route('profile') }}">الملف الشخصي</a>
                                    <a class="dropdown-item" href="{{ route('user.analytics', Auth()->id()) }}">نشاطاتي</a>
                                @endif
                                <a class="dropdown-item" href="{{ route('quizzes') }}">إدارة الطلبات</a>
                                @if(Auth()->user()->membership == 1)
                                    <a class="dropdown-item" href="{{ route('driver.analytics', Auth()->user()->driver->id) }}">نشاطاتي</a>
                                    <a class="dropdown-item" href="{{ route('driver.panel') }}">لوحة إدارة الرحلات</a>
                                @elseif(Auth()->user()->membership == 2)
                                    <a class="dropdown-item" href="{{ route('cs.analytics', Auth()->id()) }}">نشاطاتي</a>
                                    <a class="dropdown-item" href="{{ route('admin.panel') }}">لوحة التحكم</a>
                                @elseif(Auth()->user()->membership == 3)
                                    <a class="dropdown-item" href="{{ route('admin.panel') }}">إدارة الموقع</a>
                                @endif
                                <br>
                                <a>
                                    <a href="{{ route('logout') }}" class="text-decoration-none"
                                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        <i class="fa-sharp fa-solid fa-door-open text-danger"></i>&nbsp;
                                        <span class="font-weight-bold text-danger">تسجيل الخروج</span>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </a>
                            </div>
                        </li>
                    @endguest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about_us') }}">من نحن</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('home') }}">الرئيسية <span class="sr-only">(current)</span></a>
                    </li>
                        @guest
{{--                            Nothing--}}
                        @else
                            <?php
                            require_once public_path("date_sum.php");
                            if (in_array(Auth()->user()->membership, [0, 3])){
                                $all_notice = \App\Models\UserActivity::where('user_id', Auth()->id())->latest()->paginate(10);
                                $seen = \App\Models\UserActivity::all()->where('user_id', Auth()->id())->where('seen', 0);
                            }elseif (Auth()->user()->membership == 1){
                                $all_notice = \App\Models\DriverActivity::where('driver_id', Auth()->user()->driver->id)->latest()->paginate(10);
                                $seen = \App\Models\DriverActivity::all()->where('driver_id', Auth()->user()->driver->id)->where('seen', 0);
                            }elseif (Auth()->user()->membership == 2){
                                $all_notice = \App\Models\CsActivity::where('cs_id', Auth()->id())->latest()->paginate(10);
                                $seen = \App\Models\CsActivity::all()->where('cs_id', Auth()->id())->where('seen', 0);
                            }

                            $num_notice=0;
                            ?>

                            <li class="nav-item dropdown px-1 my-lg-0 my-md-0 my-3 mx-auto" style="max-width: 100%">
                                <a onclick="seen();$('#hot_notice').css('display', 'none')" id="navbarDropdown" class="nav-link fw-bolder" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    @if(count($seen) > 0)
                                        <sup id="hot_notice" class="bg-danger text-white rounded-circle px-2 py-1">{{ count($seen) }}</sup>
                                    @endif
                                    <i class="fa-solid fa-bell fa-lg"></i>
                                </a>

                                <div id="dropdown-card" class="dropdown-menu dropdown-menu-right px-3 py-2" aria-labelledby="navbarDropdown" style="text-align: start;width: 300px">
                                    <div class="card" id="toggle-card" style="border: none;background: none;box-shadow: none">
                                        <h4 class="fw-bolder text-primary text-center">أحدث الإشعارات</h4>
                                        <hr class="my-0">
                                        <br>
                                        @foreach($all_notice as $activity)
                                            <?php $num_notice+=1;?>
                                            @if(in_array(Auth()->user()->membership, [0, 3]))

                                                {{--                                            User notice--}}
                                                <div id="user-notice" class="my-0">
                                                    @if($activity->option == 0)
                                                        <h6 class="text-danger break3">
                                                            لقد قمت بإنشاء طلب رحلة جديدة
                                                            <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                                            ...</h6>
                                                    @elseif($activity->option == 1)
                                                        <h6 class="text-danger break3">لقد قمت بإلغاء طلب الرحلة ...</h6>
                                                    @elseif($activity->option == 2)
                                                        <h6 class="text-danger break3">لقد تم تأكيد طلب رحلتك
                                                            <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                                            وتحويلك الي السائق
                                                            <a href="{{ route('driver.profile', ($activity->driver->user_id ?? "000000")) }}">{{ $activity->driver->fullname ?? "محذوف" }}</a>
                                                            ...</h6>
                                                    @elseif($activity->option == 3)
                                                        <h6 class="text-danger break3">لقد قام السائق
                                                            <a href="{{ route('driver.profile', ($activity->driver->user_id ?? "000000")) }}">{{ $activity->driver->fullname ?? "محذوف" }}</a>
                                                            بإلغاء طلب الرحلة خاصتك
                                                            <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                                            ...</h6>
                                                    @elseif($activity->option == 4)
                                                        <h6 class="text-danger break3">تهانين لقد وصلت رحلتك بنجاح
                                                            <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                                            ...</h6>
                                                    @elseif($activity->option == 5)
                                                        <h6 class="text-danger break3">لقد بدءت رحلتك للتو
                                                            <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                                            و السائق
                                                            <a href="{{ route('driver.profile', ($activity->driver->user_id ?? "000000")) }}">{{ $activity->driver->fullname ?? "محذوف" }}</a>
                                                            يتحو منطقة الإنطلاق
                                                            ...</h6>
                                                    @endif
                                                    <div class="text-left"><b class="text-dark date-notice">{{ rest_date($activity->created_at) }}</b></div>
                                                </div>

                                            @elseif(Auth()->user()->membership == 1)

                                                {{--                                                Driver notice--}}
                                                <div id="driver-notice" class="my-0">
                                                    @if($activity->option == 0)
                                                        <h6 class="text-danger break3">
                                                            لديك طلب رحلة
                                                            <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                                            بإنتظار المراجعة
                                                            ...</h6>
                                                    @elseif($activity->option == 1)
                                                        <h6 class="text-danger break3">لقد قمت بإلغاء الرحلة
                                                            <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                                            ...</h6>
                                                    @elseif($activity->option == 2)
                                                        <h6 class="text-danger break3">لقد نجحت رحلتك بنجاح
                                                            <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                                            ...</h6>
                                                    @elseif($activity->option == 3)
                                                        <h6 class="text-danger break3">لقد بدءت رحلتك للتو وبإنتظار التنفيذ
                                                            <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                                            ...</h6>
                                                    @endif
                                                    <div class="text-left"><b class="text-dark date-notice">{{ rest_date($activity->created_at) }}</b></div>
                                                </div>

                                            @elseif(Auth()->user()->membership == 2)

                                                {{--                                            Customer service--}}
                                                <div id="cs-notice" class="my-0">
                                                    @if($activity->option == 0)
                                                        <h6 class="text-danger break3">
                                                            تم تأكيد طلب الرحلة
                                                            <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                                            ...</h6>
                                                    @elseif($activity->option == 1)
                                                        <h6 class="text-danger break3">
                                                            تم إلغاء طلب الرحلة
                                                            <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                                             ...</h6>
                                                    @elseif($activity->option == 2)
                                                        <h6 class="text-danger break3">
                                                            تم وصول طلب الرحلة
                                                            <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                                            بنجاح
                                                            ...</h6>
                                                    @elseif($activity->option == 3)
                                                        <h6 class="text-danger break3">
                                                            تم إنشاء طلب رحلة جديدة
                                                            <a href="{{ route('quiz.show', ($activity->order->slug ?? "000000")) }}">#{{ $activity->order->slug ?? "محذوف" }}</a>
                                                            ...</h6>
                                                    @elseif($activity->option == 4)
                                                        <h6 class="text-danger break3">
                                                            تم إنشاء طلبات رحلة متعددة
                                                            ...</h6>
                                                    @endif
                                                    <div class="text-left"><b class="text-dark date-notice">{{ rest_date($activity->created_at) }}</b></div>
                                                </div>

                                            @endif
                                            <hr>
                                            <?php if ($num_notice >= 5){break;}?>
                                        @endforeach
                                        <br>
                                        @if(Auth()->user()->membership == 0)
                                            <div class="text-center">
                                                <a href="{{ route('user.analytics', Auth()->id()) }}" class="text-decoration-none btn btn-outline-primary" style="border-radius: 100px">
                                                    <i class="fa-regular fa-envelope-open"></i>
                                                    جميع الإشعارات
                                                </a>
                                            </div>
                                        @elseif(Auth()->user()->membership == 1)
                                            <div class="text-center">
                                                <a href="{{ route('driver.analytics', Auth()->id()) }}" class="text-decoration-none btn btn-outline-primary" style="border-radius: 100px">
                                                    <i class="fa-regular fa-envelope-open"></i>
                                                    جميع الإشعارات
                                                </a>
                                            </div>
                                        @elseif(Auth()->user()->membership == 2)
                                            <div class="text-center">
                                                <a href="{{ route('cs.analytics', Auth()->id()) }}" class="text-decoration-none btn btn-outline-primary" style="border-radius: 100px">
                                                    <i class="fa-regular fa-envelope-open"></i>
                                                    جميع الإشعارات
                                                </a>
                                            </div>
                                        @elseif(Auth()->user()->membership == 3)
                                            <div class="text-center">
                                                <a href="{{ route('user.analytics', Auth()->id()) }}" class="text-decoration-none btn btn-outline-primary" style="border-radius: 100px">
                                                    <i class="fa-regular fa-envelope-open"></i>
                                                    جميع الإشعارات
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endif

                </ul>
                {{--                <form class="form-inline my-2 my-lg-0">--}}
                {{--                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">--}}
                {{--                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>--}}
                {{--                </form>--}}
            </div>
        </nav>

        <main class="py-4" style="background: linear-gradient(-60deg, #bdbdbd,#f1f1f1);border-radius: 0">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-dark text-center text-white">
            <!-- Grid container -->
            <div class="container p-4 pb-0">
                <!-- Section: Social media -->
                <section class="mb-4">
                    <a class="m-1" href="{{ route('about_us') }}" role="button" style="display: inline-block"><h6 class="text-white">من نحن</h6></a>

                    <a class="m-1" href="{{ route('privacy') }}" role="button" style="display: inline-block"><h6 class="text-white">الحماية و الخصوصية</h6></a>

                    <a class="m-1" href="{{ route('terms') }}" role="button" style="display: inline-block"><h6 class="text-white">البنود</h6></a>

                    <a class="m-1" href="{{ route('contact_us') }}" role="button" style="display: inline-block"><h6 class="text-white">تواصل معنا</h6></a>

                    <a class="m-1" href="{{ route('home') }}" role="button" style="display: inline-block"><h6 class="text-white">الرئيسية</h6></a>
                </section>
                <!-- Section: Social media -->
            </div>
            <div class="container p-4 pb-0">
                <!-- Section: Social media -->
                <section class="mb-4">
                    <!-- Facebook -->
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"
                    ><i class="fab fa-facebook-f"></i
                        ></a>

                    <!-- Twitter -->
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"
                    ><i class="fab fa-twitter"></i
                        ></a>

                    <!-- Google -->
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"
                    ><i class="fab fa-google"></i
                        ></a>

                    <!-- Instagram -->
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"
                    ><i class="fab fa-instagram"></i
                        ></a>

                    <!-- Linkedin -->
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"
                    ><i class="fab fa-linkedin-in"></i
                        ></a>

                    <!-- Github -->
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"
                    ><i class="fab fa-github"></i
                        ></a>
                </section>
                <!-- Section: Social media -->
            </div>
            <!-- Grid container -->

            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                © 2023 Copyright:
                <a class="text-white" href="#">Yossr</a>
            </div>
            <!-- Copyright -->
        </footer>
    </div>
</section>
<br><br>
<script src="{{ asset('js/seen.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
</html>
