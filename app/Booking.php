<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'passenger_id','b_departure','b_arrival','b_leave_time','b_arrive_time','b_price','b_passengers','trip_id'
    ];

    public $timestamps = true;

}