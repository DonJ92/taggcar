<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::namespace('Auth')->group(function() {
	Route::get('/register', 'RegisterController@register'); 
	Route::get('/login', 'LoginController@login')->name('login');
	Route::any('/postRegister', 'RegisterController@postRegister');
	Route::any('/postLogin', 'LoginController@postLogin');

});

Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::prefix('dashboard')->middleware('auth')->group(function() {
	Route::get('/', 'HomeController@index')->name('dashboard');
});

Route::get('/admins', 'Auth\LoginController@getAdmins')->middleware('auth');
Route::get('/adminDetail/{admin_id}', 'Auth\LoginController@adminDetail')->middleware('auth');
Route::get('/adminDel/{admin_id}', 'Auth\LoginController@adminDel')->middleware('auth');
Route::post('/adminEdit', 'Auth\LoginController@adminEdit')->middleware('auth');

Route::post('ajaxUploadCertImg', 'Auth\LoginController@ajaxUploadCertImg')->middleware('auth');

Route::prefix('customer')->namespace('Customer')->middleware('auth')->group(function() {
	Route::get('/drivers', 'CustomerController@getDrivers');
	Route::get('/passengers', 'CustomerController@getPassengers');
	Route::get('/driverDel/{driver_id}', 'CustomerController@driverDel');
	Route::get('/passengerDel/{passenger_id}', 'CustomerController@passengerDel');
	Route::get('/driverDetail/{driver_id}', 'CustomerController@driverDetail');
	Route::get('/passengerDetail/{passenger_id}', 'CustomerController@passengerDetail');
	Route::get('/driverActive/{driver_id}', 'CustomerController@driverActive');
	Route::get('/driverInactive/{driver_id}', 'CustomerController@driverInactive');
});

Route::prefix('city')->namespace('City')->middleware('auth')->group(function() {
	Route::get('/cities', 'CityController@cityLists');
	Route::post('/cityCreate', 'CityController@cityCreate');
	Route::get('/cityDel/{city_id}', 'CityController@cityDel');
	Route::post('/cityUpdate', 'CityController@cityUpdate');
	Route::get('/places', 'CityController@placeLists');
	Route::post('/placeCreate', 'CityController@placeCreate');
	Route::get('/placeDel/{place_id}', 'CityController@placeDel');
	Route::post('/placeUpdate', 'CityController@placeUpdate');
});

Route::prefix('ride')->namespace('Ride')->middleware('auth')->group(function() {
	Route::get('/rideLists', 'RideController@rideLists');
	Route::post('/rideCreate', 'RideController@rideCreate');
	Route::post('/rideUpdate', 'RideController@rideUpdate');
	Route::get('/rideDel/{line_id}', 'RideController@rideDel');
	Route::get('/to_setting', 'RideController@to_setting');
	Route::post('/setting', 'RideController@setting');
});

Route::get('/trips', 'Trip\TripController@getTrips')->middleware('auth');
Route::get('/bookings', 'Trip\TripController@getBookings')->middleware('auth');
Route::get('/tripDetail/{trip_id}', 'Trip\TripController@tripDetail')->middleware('auth');
Route::get('/bookingDetail/{booking_id}', 'Trip\TripController@bookingDetail')->middleware('auth');
Route::post('/tripUpdate', 'Trip\TripController@tripUpdate')->middleware('auth');
Route::post('/bookingUpdate', 'Trip\TripController@bookingUpdate')->middleware('auth');

Route::get('/messages', 'Message\MessageController@getMessages')->middleware('auth');
Route::get('/messageDel/{message_id}', 'Message\MessageController@messageDel')->middleware('auth');
Route::get('/messageDetail/{message_id}', 'Message\MessageController@messageDetail')->middleware('auth');

Route::get('/terms', 'About\TermsController@index');
Route::get('/privacy', 'About\PrivacyController@index');
Route::get('/help', 'About\HelpController@index');