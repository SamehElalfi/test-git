<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    protected $fillable = [
        'user_id',
        'driver_id',
        'survey_id',
        'option',
        'seen'
    ];

    protected $table = 'user_activities';

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function driver()
    {
        return $this->belongsTo('App\Models\Driver', 'driver_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Survey', 'survey_id');
    }
}
