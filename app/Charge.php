<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/25/2018
 * Time: 10:36 AM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    protected $table = 'charges';

    protected $fillable = [
        'booking_id', 'card_number', 'exp_month', 'exp_year', 'cvc', 'amount', 'stripe_token', 'stripe_order_id', 'state'
    ];

    public $timestamps = false;

}