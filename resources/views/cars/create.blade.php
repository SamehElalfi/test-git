@extends('layouts.app')

@section('content')
    <?php use Illuminate\Support\Facades\Session as Session;?>?>
    <title>اضافة سيارة جديدة</title>
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
                <h5 id="text-step-2" class="text-dark">يرجي الحرص علي كتابة البيانات الصحيحة</h5>
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
                <form method="post" action="{{ route('admin.panel.car.store') }}">
                    @csrf
                    @method('POST')

                    {{--                    the name of car or model--}}
                    <div id="name" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                إسم السيارة
                            </h6>
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="إسم السيارة..." name="name" class="form-control">
                        </div>
                    </div>

                    {{--                    type of car--}}
                    <div id="phone" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                نوع السيارة
                            </h6>
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="نوع السيارة..." name="type" class="form-control">
                        </div>
                    </div>

                    {{--                    passengers--}}
                    <div id="phone" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                عدد الركاب
                            </h6>
                        </div>
                        <div class="col-9">
                            <input type="number" placeholder="عدد الركاب..." name="count" class="form-control">
                        </div>
                    </div>

                    {{--                    Car Number--}}
                    <div id="phone" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                رقم السيارة
                            </h6>
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="رقم السيارة..." name="number" class="form-control">
                        </div>
                    </div>

                    <br>
                    {{--                    Submit--}}
                    <div class="row">
                        <input type="submit" class="btn btn-primary" value="إضافة" style="margin: auto auto">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>
@endsection
