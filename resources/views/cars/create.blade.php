@extends('layouts.app')

@section('content')
    <?php use Illuminate\Support\Facades\Session as Session;?>
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
                <form method="post" action="{{ route('admin.panel.car.store') }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    {{--                    driver--}}
                    <div id="name" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                الكابتن
                            </h6>
                        </div>
                        <div class="col-9">
                            <select class="form-control" name="driver_id" required>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}">{{ "($driver->id)" . ". " . ($driver->fullname ?? "غير محدد") . " " . (($driver->country->name ?? "غير محدد") . " / " . ($driver->state->name ?? "غير محدد")) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{--                    car number--}}
                    <div id="name" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                رقم السيارة
                            </h6>
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="رقم لوحة السيارة..." name="number" class="form-control">
                        </div>
                    </div>

                    {{--                    date made--}}
                    <div id="phone" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                سنة الصنع
                            </h6>
                        </div>
                        <div class="col-9">
                            <input type="date" name="made_from" class="form-control">
                        </div>
                    </div>

                    {{--                    date made--}}
                    <div id="phone" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                تاريخ اخر صيانة
                            </h6>
                        </div>
                        <div class="col-9">
                            <input type="date" name="last_repair" class="form-control">
                        </div>
                    </div>

                    {{--                    Type--}}
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

                    {{--                    Photo--}}
                    <div id="phone" class="row my-3">
                        <div class="col-3 my-auto">
                            <h6>
                                صورة السيارة
                            </h6>
                        </div>
                        <div class="col-9">
                            <input type="file"  name="photo" class="form-control">
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
