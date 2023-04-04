@extends('layouts.app')

@section('content')
    <title>لوحة التحكم الرئيسية</title>
    <div class="container-xl">
        <div class="card shadow-lg bg-white py-4 px-4" style="border-radius: 40px">
            <h3 class="text-primary text-center">لوحة التحكم</h3>
            <br>
            <?php require_once public_path("panel_admin.php")?>
            <hr>
            <br>
            <div class="row" align="start">
                <div class="col-6" id="orders">
                    <?php
                    $count_orders = count($orders);
                    $count_orders_pending = [];
                    $count_orders_confirmed = [];
                    $count_orders_fails = [];
                    $count_orders_success = [];
                    foreach ($orders as $order){
                        if ($order->status == 0){
                            $count_orders_pending[]=1;
                        }elseif ($order->status == 1){
                            $count_orders_confirmed[]=1;
                        }elseif ($order->status == 2){
                            $count_orders_fails[]=1;
                        }elseif ($order->status == 3){
                            $count_orders_success[]=1;
                        }
                    }
                    ?>
                    <h3 class="text-primary">الطلبات</h3>
                    <br>
                    <h5 class="text-dark">
                        إجمالي الطلبات:- <span class="text-secondary">({{ count($orders) }})</span>
                        <br>
                        قيد المراجعة:- <span class="text-secondary">{{ count($count_orders_pending) }}</span>
                        <br>
                        تم التأكيد:- <span class="text-secondary">{{ count($count_orders_confirmed) }}</span>
                        <br>
                        فشل الوصول:- <span class="text-secondary">{{ count($count_orders_fails) }}</span>
                        <br>
                        نجح التوصيل:- <span class="text-secondary">{{ count($count_orders_success) }}</span>
                    </h5>
                </div>
                <div class="col-6" id="users">
                    <?php
                    $count_users_mem_0 = [];
                    $count_users_mem_1 = [];
                    $count_users_mem_2 = [];
                    $count_users_mem_3 = [];
                    foreach ($users as $user){
                        if (isset($user->profile)){
                            if ($user->membership == 0){
                                $count_users_mem_0[]=1;
                            }elseif ($user->membership == 1){
                                $count_users_mem_1[]=1;
                            }elseif ($user->membership == 2){
                                $count_users_mem_2[]=1;
                            }elseif ($user->membership == 3){
                                $count_users_mem_3[]=1;
                            }
                        }else{
                            $count_users_mem_0[]=1;
                        }
                    }
                    ?>
                    <h3 class="text-primary">المستخدمين</h3>
                    <br>
                    <h5 class="text-dark">
                        إجمالي عدد مستخدمين الموقع:- <span class="text-secondary">({{ count($users) }})</span>
                        <br>
                        عميل:- <span class="text-secondary">{{ count($count_users_mem_0) }}</span>
                        <br>
                        سائق:- <span class="text-secondary">{{ count($count_users_mem_1) }}</span>
                        <br>
                        دعم فني:- <span class="text-secondary">{{ count($count_users_mem_2) }}</span>
                        <br>
                        أدمن:- <span class="text-secondary">{{ count($count_users_mem_3) }}</span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <br>
@endsection
