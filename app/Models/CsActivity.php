<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CsActivity extends Model
{
    protected $fillable = [
        'cs_id',
        'user_id',
        'driver_id',
        'survey_id',
        'option',
        'seen'
    ];

    protected $table = 'cs_activities';

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function cs()
    {
        return $this->belongsTo('App\Models\User', 'cs_id');
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
