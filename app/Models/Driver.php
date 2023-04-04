<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $table = 'drivers';

    protected $fillable = [
        'user_id',
        'fullname',
        'bio',
        'country_id',
        'state_id',
        'photo',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public function state()
    {
        return $this->belongsTo('App\Models\State', 'state_id');
    }
}
