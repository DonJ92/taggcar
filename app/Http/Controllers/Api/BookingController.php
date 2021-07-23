<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use App\Booking;
use App\Charge;
use App\Trip;
use App\Customer;
use App\Services\FCMPush;
use Validator;
use DB;

class BookingController extends Controller
{
    public function book(Request $request) {
        $validator = Validator::make($request->all(), [
            // 'number' => 'required|string',
            // 'exp_month' => 'required|integer',
            // 'exp_year' => 'required|integer',
            // 'cvc' => 'required|integer',
            'trip_id' => 'required|integer',
            'passenger_id' => 'required|integer',
            'b_departure' => 'string',
            'b_arrival' => 'string',
            'b_arrive_time' => 'nullable|string',
            'b_leave_time' => 'nullable|string',
            'b_passengers' => 'nullable|int',
            'b_price' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
            return json_encode(array('status' => 0, 'message' => $message));
        }

        $passenger = Customer::where('id', $request->passenger_id)->get()->first();

        $trip = Trip::where('id', $request->trip_id)->get()->first();
        if ($trip == null) {
            return json_encode(array('status' => 0, 'message' => 'No trip exist'));
        }
        $driver = Customer::where('id', $trip->driver_id)->get()->first();

        $stripe = new \Stripe\StripeClient(
            Config::get('services.stripe.key')
        );

        try {
            // Book a ride
            $booking = Booking::create($validator->validated());

            // Get Payment Method
            $paymentMethods = $stripe->paymentMethods->all([
                'customer' => $passenger->stripe_customer_id,
                'type' => 'card',
            ]);

            if (count($paymentMethods->data) == 0) {
                return json_encode(array('status' => 0, 'message' => 'Please add payment method first.'));
            }
            $paymentMethod = $paymentMethods->data[0];

            // Create Payment Intent
            $paymentIntent = $stripe->paymentIntents->create([
                'customer' => $passenger->stripe_customer_id,
                'amount' => $request->b_price * 100,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'description' => 'Paid for ride',
                'payment_method' => $paymentMethod->id,
            ]);

            $orderId = Str::random(32);

            if ($paymentIntent->status == 'requires_confirmation') {

                DB::table('charges')->insert(array(
                    'booking_id' => $booking->id,
                    'user_id' => $request->passenger_id,
                    'stripe_pm_id' => $paymentIntent->id,
                    'stripe_order_id' => $orderId,
                    'amount' => $request->b_price,
                    'state' => Config::get('constants.charge.created')
                ));

                DB::table('bookings')->where('id', $booking->id)->update(array('state' => Config::get('constants.booking.charged')));
                
                DB::table('trips')->where('id', $booking->trip_id)->update(array('state' => Config::get('constants.trip.booked')));

                // Send notification
                $fcm = new FCMPush();
                $fcm->send($driver->id, $passenger->avatar_url, 'Trip Booked', $passenger->name . ' has booked your trip', Config::get('constants.notification.booked'), $booking->id);

                return json_encode(array('status' => 1));
            } else {
                DB::table('bookings')->where('id', $booking->id)->update(array('state' => Config::get('constants.booking.failed')));
                return json_encode(array('status' => 0, 'message' => 'Payment failed'));
            } 
        } catch (\Exception $ex) {
            DB::table('bookings')->where('id', $booking->id)->update(array('state' => Config::get('constants.booking.failed')));
            return json_encode(array('status' => 0, 'message' => $ex->getMessage()));
        }    
    }

    public function confirm(Request $request) {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|integer',            
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
            return json_encode(array('status' => 0, 'message' => $message));
        }

        $stripe = new \Stripe\StripeClient(
            Config::get('services.stripe.key')
        );

        $booking = Booking::where('id', $request->booking_id)->get()->first();
        if ($booking == null) {
            return json_encode(array('status' => 0, 'message' => 'Invalid Id'));
        }

        $passenger = Customer::where('id', $booking->passenger_id)->get()->first();
        if ($passenger == null) {
            return json_encode(array('status' => 0, 'message' => 'Invalid data'));
        }

        $charge = Charge::where('booking_id', $request->booking_id)->where('state', Config::get('constants.charge.created'))->get()->first();

        if ($charge == null) {
            return json_encode(array('status' => 0, 'message' => 'No Chargement'));
        }
        $trip = Trip::where('id', $booking->trip_id)->get()->first();

        $driver = Customer::where('id', $trip->driver_id)->get()->first();


        try {
                        
            $amount = $charge->amount * 0.8 * 100;

            $stripe->paymentIntents->confirm(
                $charge->stripe_pm_id
            );

            DB::table('bookings')->where('id', $booking->id)->update(array('state' => Config::get('constants.booking.confirmed')));

            DB::table('trips')->where('id', $trip->id)->update(array('state' => Config::get('constants.trip.confirmed')));

            DB::table('transfers')->insert(array(
                'user_id' => $driver->id,
                'booking_id' => $booking->id,                
                'amount' => $amount / 100,
                'stripe_order_id' => $charge->stripe_order_id,
                'status' => Config::get('constants.transfer.incomplete')
            ));

            // Send notification
            $fcm = new FCMPush();
            $fcm->send($driver->id, $passenger->avatar_url, 'Booking Confirmed', $passenger->name . ' has confirmed your trip', Config::get('constants.notification.confirmed'), $booking->id);

            return json_encode(array('status' => 1));

        } catch (\Exception $ex) {
            return json_encode(array('status' => 0, 'message' => $ex->getMessage()));
        }             
    }

    public function cancel(Request $request){
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|integer',            
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
            return json_encode(array('status' => 0, 'message' => $message));
        }

        $stripe = new \Stripe\StripeClient(
            Config::get('services.stripe.key')
        );

        $booking = Booking::where('id', $request->booking_id)->get()->first();
        if ($booking == null) {
            return json_encode(array('status' => 0, 'message' => 'Invalid Id'));
        }

        $passenger = Customer::where('id', $booking->passenger_id)->get()->first();
        if ($passenger == null) {
            return json_encode(array('status' => 0, 'message' => 'Invalid data'));
        }

        $charge = Charge::where('booking_id', $request->booking_id)->where('state', Config::get('constants.charge.paid'))->get()->first();
        if ($charge == null) {
            return json_encode(array('status' => 0, 'message' => 'No Chargement'));
        }


        $trip = Trip::where('id', $booking->trip_id)->get()->first();

        $driver = Customer::where('id', $trip->driver_id)->get()->first();

        try {            
            $refund = $stripe->refunds->create([
                'charge' => $charge->stripe_charge_id,
            ]);

            DB::table('bookings')->where('id', $booking->id)->update(array('state' => Config::get('constants.booking.cancelled')));

            DB::table('trips')->where('id', $booking->trip_id)->update(array('state' => Config::get('constants.trip.created')));

            DB::table('charges')->where('id', $charge->id)->update(array('state' => Config::get('constants.charge.refund')));

            DB::table('refunds')->insert(array(
                'booking_id' => $booking->id,
                'charge_id' => $charge->id,
                'stripe_refund_id' => $refund->id,
            ));

            // Send notification
            $fcm = new FCMPush();
            $fcm->send($driver->id, $passenger->avatar_url, 'Booking Canceled', $passenger->name . ' has canceled booking', Config::get('constants.notification.canceled'), $booking->id);

            return json_encode(array('status' => 1));

        } catch (\Exception $ex) {
            return json_encode(array('status' => 0, 'message' => $ex->getMessage()));
        }
    }
    
    public function listbook(Request $request){
        $customer_id = $request->customer_id;

        $listbook = DB::table('bookings')->where('passenger_id','=',$customer_id)->get();
        return json_encode($listbook);
    }
}