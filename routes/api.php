<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::namespace('Api')->group(function() {
	Route::any('/login', 'LoginController@login');
	Route::any('/socialLogin', 'LoginController@socialLogin');
	Route::any('/signup', 'RegisterController@signup');
	Route::any('/search', 'TripController@search');
	Route::any('/trip_detail', 'TripController@trip_detail');
	Route::any('/addtrip', 'TripController@addtrip');
	Route::any('/removetrip', 'TripController@removetrip');
	Route::any('/updatetrip', 'TripController@updatetrip');
	Route::any('/getUserInfo', 'UserController@getUserInfo');
	Route::any('/messageSave', 'MessageController@messageSave');
	Route::any('/getNewMsg', 'MessageController@getNewMsg');
    Route::any('/getMessages' , 'MessageController@getMessages');
    Route::any('/getNotifications' , 'NotificationController@get');
	Route::any('/getLocation', 'RidelineController@getLocation');
	Route::any('/getMyRide' , 'TripController@listtrip');
	Route::any('/get_customer_info', 'CustomerController@get');
	Route::any('/deletearide', 'TripController@deletearide');
	Route::any('/edit_profile', 'CustomerController@editProfile');
	Route::any('/change_password', 'CustomerController@changePassword');
	Route::any('/upload_avatar', 'CustomerController@uploadAvatar');
	Route::any('/upload_id', 'CustomerController@uploadID');
	Route::any('/send_verify_sms', 'CustomerController@sendVerifySMS');
	Route::any('/verify_phone', 'CustomerController@verifyPhone');
	Route::any('/verify_email', 'CustomerController@verifyEmail');
	Route::any('/send_verify_email', 'CustomerController@sendVerifyEmail');
	Route::any('/verify_id/{id}', 'CustomerController@verifyId');
	Route::any('/addAvailable' , 'AvailableController@addAvailable');
    Route::any('/getAvailables' , 'AvailableController@getAvailables');    
    Route::any('/addComment' , 'CommentController@add');
    Route::any('/getRatingsReceived' , 'CommentController@getRatingsReceived');
    Route::any('/getRatingsLeft' , 'CommentController@getRatingsLeft');
    Route::any('/bookRide' , 'BookingController@book');
    Route::any('/cancelBooking' , 'BookingController@cancel');
    Route::any('/confirmBooking' , 'BookingController@confirm');
    Route::any('/verifyPayment' , 'CustomerController@verifyPayment');
    Route::any('/acceptPaymentTerms' , 'CustomerController@acceptPaymentTerms');
    Route::any('/getInstagramProfile' , 'InstagramController@getProfile');

    Route::any('/getPaymentInfo' , 'PaymentController@getPaymentInfo');
    Route::any('/addPaymentMethod' , 'PaymentController@addPaymentMethod');
    Route::any('/deletePaymentMethod' , 'PaymentController@deletePaymentMethod');
    Route::any('/withdraw' , 'PaymentController@withdraw');

    Route::any('/pushTest' , 'CustomerController@test');
});
