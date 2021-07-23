<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Place extends Model
{
    protected $table = 'places';

    protected $fillable = [
        'pname', 'city_id', 'lat', 'lng'
    ];

    public $timestamps = false;

}