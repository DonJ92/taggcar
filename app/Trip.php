<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Trip extends Model
{
    protected $table = 'trips';

    protected $fillable = [
        'departure','arrival', 'path','path_detail','leave_time','arrive_time','price','passengers','start_date','driver_id'
    ];

    public $timestamps = false;

}