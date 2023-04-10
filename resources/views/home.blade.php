@extends('layouts.app')

@section('content')
    <?php use Illuminate\Support\Facades\Session as Session;?>

    <title>الصفحة الرئيسية</title>

    @if (Session::has('error'))
        <div class="alert alert-danger text-center" role="alert">
            {!! Session::get('error') !!}
        </div>
    @endif
@endsection
