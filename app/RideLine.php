<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class RideLine extends Model
{
    protected $table = 'ridelines';

    protected $fillable = [
        'line_from', 'line_to', 'to_place', 'line_price'
    ];

    public $timestamps = true;

}