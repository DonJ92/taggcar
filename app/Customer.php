<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/25/2018
 * Time: 10:36 AM
 */

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $table = 'customers';

    protected $fillable = [
        'name', 'email', 'password', 'created_at', 'avatar_url', 'updated_at', 'remember_token', 'user_id', 'role', 'phone', 'birthday', 'age', 'experience', 'net_friends', 'bio', 'trip_id', 'rating', 'is_allow',
        'is_preference_1', 'is_preference_2','is_preference_3','is_preference_4','car_details', 'id_verification_image' , 'verified_id' , 'verified_email' , 'verified_phone', 'stripe_account_id', 'stripe_customer_id', 'google', 'facebook', 'apple'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public $timestamps = true;

}