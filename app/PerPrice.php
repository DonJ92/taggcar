<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class PerPrice extends Model
{
    protected $table = 'per_prices';

    protected $fillable = [
        'per_price'
    ];

    public $timestamps = false;

}