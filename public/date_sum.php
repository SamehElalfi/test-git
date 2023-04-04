<?php
function rest_date($date){
    date_default_timezone_set("Africa/Cairo");
    $date_1 = date_create($date);
    $date_2 = date_create('now');
    $diff = date_diff($date_1, $date_2);
    if ($diff->y == 0){
        if ($diff->m == 0){
            if ($diff->d == 0){
                if ($diff->h == 0){
                    if ($diff->i == 0){
                        $rest_time = "منذ $diff->s ثانية";
                    }else{$rest_time = "منذ $diff->i دقيقة و$diff->s ثانية";}
                }else{$rest_time = "منذ $diff->h ساعة و$diff->i دقيقة";}
            }else{$rest_time = "منذ $diff->d يوم و$diff->h ساعة";}
        }else{$rest_time = "منذ $diff->m شهر و$diff->d يوم";}
    }else{$rest_time = "منذ $diff->y سنة و$diff->m شهر";}

    return $rest_time;
}
