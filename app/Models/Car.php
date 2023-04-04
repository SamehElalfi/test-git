<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'name',
        'type',
        'passengers',
        'number'
    ];

    protected $table = 'cars';
}
