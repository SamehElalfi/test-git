<?php
use Illuminate\Support\Facades\Auth;


?>
<div class="btn-group" style="border-radius: 100px">
    <a href="/admin/panel/orders" class="btn btn-outline-primary" style="border-radius: 0 100px 100px 0">الرحلات</a>
    <a href="/admin/panel/pending/orders" class="btn btn-outline-primary" style="border-radius: 0">رحلات_قيد_المعالجة</a>
    <a href="/admin/panel/awaits/orders" class="btn btn-outline-primary" style="border-radius: 0">رحلات_بإنتظار_التحرك</a>
    <a href="/admin/panel/confirmed/orders" class="btn btn-outline-primary" style="border-radius: 0">رحلات_جاري_التنفيذ</a>
    <a href="/admin/panel/finished/orders" class="btn btn-outline-primary" style="border-radius: 0">رحلات_منتهية</a>
    <a href="/admin/panel/clients" class="btn btn-outline-primary" style="border-radius: 0">العملاء</a>
    <a href="/admin/panel/cars" class="btn btn-outline-primary" style="border-radius: 0">السيارات</a>
    <?php if(Auth::user()->membership == 3){?>
        <a href="/admin/panel/users" class="btn btn-outline-primary" style="border-radius: 0">المستخدمين</a>
        <a href="/admin/panel/activities" class="btn btn-outline-primary" style="border-radius: 0">النشاطات</a>
    <?php }?>
    <a href="/admin/panel/drivers" class="btn btn-outline-primary" style="border-radius: 100px 0 0 100px">السائقين</a>
</div>
