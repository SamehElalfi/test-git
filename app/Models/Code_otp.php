<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code_otp extends Model
{
    use HasFactory;

    protected $table = "codes_otp";

    protected $fillable = [
        'user_id', 'code', 'expire_date'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
