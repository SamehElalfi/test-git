<?php

function awaits_user($slug, $fullname, $tour_date, $tour_time, $driver, $car_num, $start_point, $end_point, $cost){
    $msg_awaits_user = "
            شكرا لك لاختيارك يسر, لقد تم تأكيد الرحلة وبإنتظار التحرك في الوقت المحدد من طرفكم ان شاء الله.
            \n
            تفاصيل الرحلة:
            - رقم الرحلة:- #" . ($slug) . "
            - اسم المستفيد:- " . ($fullname ?? "") . "
            - موعد بدء الرحلة:- " . ($tour_date . " | " . $tour_time) . "
            - اسم الكابتن:- " . ($driver ?? "غير محدد") . "
            - رقم السيارة:- " . ($car_num ?? "غير محدد") . "
            - مركز الانطلاق:- " . ($start_point) . "
            - مركز الوصول:- " . ($end_point) . "
            - التكلفة المتوقعة:- " . ($cost) . " ريال
            \n
            نتمني لكم رحلة مريحة - مع دوام الصحة و العافية ان شاء الله
    ";
    return $msg_awaits_user;
}

function awaits_driver($slug, $name, $fullname, $phone, $start_point, $end_point, $cost){
    $msg_awaits_driver = "
                لديك رحلة في انتظار التحرك ان شاء الله, وتفاصيلها كالاتي:-
            \n
             - رقم الرحلة:- #" . ($slug ?? "غير محدد") . "
            -اسم العميل: " . ($name ?? "غير محدد") . "
            -اسم المستفيد: " . ($fullname ?? "غير محدد") . "
            -رقم جوال العميل: " . ($phone ?? "غير محدد") . "
            -مركز الانطلاق: " . ($start_point ?? "غير محدد") . "
            -مركز الوصول: " . ($end_point ?? "غير محدد") . "
            -التكلفة المتوقعة: " . ($cost) . " ريال
    ";
    return $msg_awaits_driver;
}

function pending_user(){
    $msg_pending_user = "";
    return $msg_pending_user;
}

function pending_driver(){
    $msg_pending_driver = "";
    return $msg_pending_driver;
}

function fail_user(){
    $msg_success_user = "";
    return $msg_success_user;
}

function fail_driver(){
    $msg_success_driver = "";
    return $msg_success_driver;
}

function success_user(){
    $msg_fail_user = "";
    return $msg_fail_user;
}

function success_driver(){
    $msg_fail_driver = "";
    return $msg_fail_driver;
}
