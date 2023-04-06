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
                <form method="post" action="{{ route('quiz.store.multiple') }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    {{--                    Excel--}}
                    <div id="excel" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                بيانات الرحلة
                                (<span class="text-primary"> XLSX </span>)
                            </h6>
                        </div>
                        <div class="col-9" align="start">
                            <input type="file" name="xlsx" class="btn btn-dark">
                        </div>
                    </div>

                    <br>
                    {{--                    Buttons--}}
                    <div id="btn-step-2" class="row">
                        <div class="col-12" align="center">
                            <input type="submit" class="btn btn-primary" value="رفع">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>
@endsection
