<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'driver_id',
        'number',
        'made_from',
        'type',
        'last_repair',
        'photo',
        'orders',
        'status',
    ];

    protected $table = 'cars';

    public function driver(){
        return $this->belongsTo('App\Models\Driver', 'driver_id');
    }

    public function surveys()
    {
        return $this->hasMany('App\Models\Survey');
    }
}
