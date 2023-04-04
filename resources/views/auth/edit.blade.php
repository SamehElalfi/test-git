@extends('layouts.app')

@section('content')
    <div class="mx-2">
        <a href="{{ route('admin.users') }}"><i class="fa-solid fa-circle-left fa-2x text-primary"></i></a>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ 'User ' . $user->name }}</div>
                    <div class="card-body">
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
                        @if (\Session::has('error'))
                            <div class="alert alert-danger text-center" role="alert">
                                {!! \Session::get('error') !!}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('user.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" placeholder="{{ $user->name }}" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

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
                                <label for="new_password" class="col-md-4 col-form-label text-md-right">{{ __('New Password') }}</label>

                                <div class="col-md-6">
                                    <input id="new_password" type="password" placeholder="******" class="form-control @error('new_password') is-invalid @enderror" name="new_password" autocomplete="new-new_password">

                                    @error('new_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
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
