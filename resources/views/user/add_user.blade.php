@extends('layouts.app')

@section('content')
    <?php use Illuminate\Support\Facades\Session as Session;?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div align="end" class="px-3 py-3">
                        <a href="#" onclick="history.back()">
                            <i class="fa-solid fa-circle-left fa-2x"></i>
                        </a>
                    </div>
                    <h3 align="center">اضف عميل جديد</h3>
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
                        <form method="POST" action="{{ route('client.store') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">اسم المستخدم</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" placeholder="اسم المستخدم..." class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="phone" class="col-md-4 col-form-label text-md-right">رقم الهاتف</label>

                                <div class="col-md-6">
                                    <input id="phone" maxlength="11" type="tel" placeholder="رقم الهاتف..." class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">البريد الإلكتروني</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" placeholder="البريد الإلكتروني" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">كلمة السر</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" placeholder="******" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">تأكيد كلمة السر</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" placeholder="******" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        اضافة
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
