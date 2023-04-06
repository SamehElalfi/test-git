<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table = 'surveys';

    protected $fillable = [
        'user_id',
        'driver_id',
        'car_id',
        'country_id',
        'state_id',
        'reason',
        'needs',
        'date_tour',
        'time_date_tour',
        'start_point',
        'end_point',
        'address',
        'notes',
        'distance_time',
        'distance_kilo',
        'slug',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function driver()
    {
        return $this->belongsTo('App\Models\Driver', 'driver_id');
    }

    public function car()
    {
        return $this->belongsTo('App\Models\Car', 'car_id');
    }
}
