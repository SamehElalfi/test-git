@extends('layouts.app')

@section('content')
    <title>لوحة التحكم الرئيسية</title>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <h3 class="text-primary text-center">لوحة تحكم السائقين</h3>
            <br>
            <?php require_once public_path("driver_panel.php")?>
            <hr>
            <br>
            <div class="row" align="start">
                <div class="col-6" id="orders">
                    <?php
                    $count_orders = count($orders);
                    $count_orders_fails = [];
                    $count_orders_success = [];
                    foreach ($orders as $order){
                        if ($order->status == 2){
                            $count_orders_fails[]=1;
                        }elseif ($order->status == 3){
                            $count_orders_success[]=1;
                        }
                    }
                    ?>
                    <h3 class="text-primary">الرحلات المسلمة</h3>
                    <br>
                    <h5 class="text-dark">
                        إجمالي الطلبات:- <span class="text-secondary">({{ count($orders) }})</span>
                        <br>
                        الرحلات الملغاه:- <span class="text-secondary">{{ count($count_orders_fails) }}</span>
                        <br>
                        الرحلات الناجحة:- <span class="text-secondary">{{ count($count_orders_success) }}</span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <br>
@endsection
