@extends('layouts.app')

@section('content')
    <?php use Illuminate\Support\Facades\Session as Session;?>
    <style>
        @media (max-width: 450px){
            #btn-google{
                font-size: 14px;
            }
        }
        .header__center {
            font-size: 2rem;
            display: grid;
            grid-template-columns: 1fr max-content 1fr;
            grid-column-gap: 1.2rem;
            align-items: center;
        }

        .header__center::before,
        .header__center::after {
            content: "";
            display: block;
            height: 1px;
            background-color: #c7c8c9;
        }
    </style>
    <title>Login</title>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h3 class="card-header" align="start">تسجيل الدخول</h3>
                    <br>
                    <div align="center">
                        <a id="btn-google" href="{{ route('auth_redirect') }}" class="btn btn-outline-primary no-class py-md-3 px-md-5 py-sm-3 px-sm-5 py-2 px-3" style="border-radius: 100px">
                            التسجيل بإستخدام حساب جوجل
                            &nbsp;
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                                <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                            </svg>
                        </a>
                    </div>
                    <br>
                    <header>
                        <div class="header__center">
                            <h5 class="text-dark">او</h5>
                        </div>
                    </header>
                    <br>
                    <div class="card-body">
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
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="login" class="col-md-4 col-form-label text-md-right">البريد الإلكتروني او رقم الهاتف</label>

                                <div class="col-md-6">
                                    <input id="login" type="text" placeholder="الإيميل او رقم الهاتف" class="form-control @error('login') is-invalid @enderror" name="login" value="{{ old('login') }}" required autocomplete="login" autofocus>

                                    @error('login')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">كلمة السر</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" placeholder="******" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        &nbsp;&nbsp;&nbsp;
                                        <label class="form-check-label" for="remember">تذكرني</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        تسجيل
                                    </button>
                                    <a class="btn btn-link" href="{{ route('password.request') }}">نسيت كلمة السر؟</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.login').addClass('active');
        $('.active').css('color', '#e76b6b');
    </script>
@endsection
