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
use Validator;
use DB;

class PaymentController extends Controller
{
    public function index() {

    }

    public function getPaymentInfo(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
            return json_encode(array('status' => 0, 'message' => $message));
        }

        $stripe = new \Stripe\StripeClient(
            Config::get('services.stripe.key')
        );

        $customer = Customer::where('id', $request->user_id)->get()->first();
        
        $stripeCustomer = $stripe->customers->retrieve($customer->stripe_customer_id);

        $stripeAccount = $stripe->accounts->retrieve($customer->stripe_account_id);

        $transfers = DB::table('transfers')->where('user_id', $request->user_id)->where('status', Config::get('constants.transfer.incomplete'))->get();

        $balance = 0;
        foreach ($transfers as $transfer) {
            $balance += $transfer->amount;
        }


        $result = array(
            'balance' => [
                'amount' => $balance,
                'currency' => 'USD',
            ],
            'verified' => 0,
            'paymentMethods' => [],
            'transactions' => [],
        );
        if ($stripeAccount->capabilities->transfers == 'active') {
            $result['verified'] = 2;
        }
        if ($stripeAccount->capabilities->transfers == 'pending') {
            $result['verified'] = 1;
        }

        $paymentMethods = $stripe->paymentMethods->all([
            'customer' => $customer->stripe_customer_id,
            'type' => 'card',
        ]);

        foreach ($paymentMethods as $paymentMethod) {
            array_push($result['paymentMethods'], [
                'brand' => $paymentMethod->card->brand,
                'last4' => $paymentMethod->card->last4,
                'expYear' => $paymentMethod->card->exp_year,
                'expMonth' => date("M", mktime(0, 0, 0, $paymentMethod->card->exp_month, 10)),
            ]);
        }


        $paymentIntents = $stripe->paymentIntents->all(['customer' => $customer->stripe_customer_id]);

        foreach ($paymentIntents as $paymentIntent) {
            if ($paymentIntent->status != 'succeeded') {
                continue;
            }
            array_push($result['transactions'], [
                'amount' => $paymentIntent->amount / 100,
                'currency' => strtoupper($paymentIntent->currency),
                'date' => date('d M Y', $paymentIntent->created),
                'description' => $paymentIntent->description,
            ]);
        }

      foreach ($paymentIntents as $paymentIntent) {
            if ($paymentIntent->status != 'succeeded') {
                continue;
            }
            array_push($result['transactions'], [
                'amount' => $paymentIntent->amount / 100,
                'currency' => strtoupper($paymentIntent->currency),
                'date' => date('d M Y', $paymentIntent->created),
                'description' => $paymentIntent->description,
            ]);
        }


        return json_encode(array('status' => 1, 'data' => $result));

    }

     public function addPaymentMethod(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'card_number' => 'required|string',
            'exp_date' => 'required|string',
            'cvc' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
            return json_encode(array('status' => 0, 'message' => $message));
        }

        $expDate = $request->exp_date;
        if (strpos($expDate, '/') == false) {
            return json_encode(array('status' => 0, 'message' => 'Invalid valid date'));
        }

        $expMonth = explode('/', $expDate)[0];
        $expYear = explode('/', $expDate)[1];

        $customer = Customer::where('id', $request->user_id)->get()->first();

        $stripe = new \Stripe\StripeClient(
            Config::get('services.stripe.key')
        );

        try {
            $paymentMethod = $stripe->paymentMethods->create([
                'type' => 'card',
                'card' => [
                    'number' => $request->card_number,
                    'exp_month' => $expMonth,
                    'exp_year' => $expYear,
                    'cvc' => $request->cvc,
                ],
            ]);

            if ($paymentMethod == null)
                return json_encode(array('status' => 0, 'message' => 'Unexpected error'));

            $stripe->paymentMethods->attach(
                $paymentMethod->id,
                [
                    'customer' => $customer->stripe_customer_id
                ]
            );
        } catch (\Exception $ex) {            
            return json_encode(array('status' => 0, 'message' => $ex->getMessage()));
        }   

        return json_encode(array('status' => 1));
    }

    public function deletePaymentMethod(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
            return json_encode(array('status' => 0, 'message' => $message));
        }

        $customer = Customer::where('id', $request->user_id)->get()->first();

        $stripe = new \Stripe\StripeClient(
            Config::get('services.stripe.key')
        );

        try {
            $paymentMethods = $stripe->paymentMethods->all([
                'customer' => $customer->stripe_customer_id,
                'type' => 'card',
            ]);

            foreach ($paymentMethods as $paymentMethod) {
                $stripe->paymentMethods->detach(
                    $paymentMethod->id
                );
            }

        } catch (\Exception $ex) {            
            return json_encode(array('status' => 0, 'message' => $ex->getMessage()));
        }   

        return json_encode(array('status' => 1));
    }

    public function withdraw(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'amount' => 'required',

        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
            return json_encode(array('status' => 0, 'message' => $message));
        }

        $stripe = new \Stripe\StripeClient(
            Config::get('services.stripe.key')
        );

        $customer = Customer::where('id', $request->user_id)->get()->first();

        try {  
            $stripeAccount = $stripe->accounts->retrieve($customer->stripe_account_id);

            $transfers = DB::table('transfers')->where('user_id', $request->user_id)->where('status', Config::get('constants.transfer.incomplete'))->get();

            foreach ($transfers as $transfer) {
                $result = $stripe->transfers->create([
                    'amount' => 1,
                    'currency' => 'usd',
                    'destination' => $customer->stripe_account_id,
                    'transfer_group' => $transfer->stripe_order_id,
                ]);

                DB::table('transfers')->where('id', $transfer->id)->update(array('state' => Config::get('constants.transfer.complete')));
            }


            \Stripe\Stripe::setApiKey(Config::get('services.stripe.key'));
            $balance = \Stripe\Balance::retrieve(
              ['stripe_account' => $customer->stripe_account_id]
            );
            $amount = $balance->available[0]->amount;

            if ($amount < $request->amount) {
                return json_encode(array('status' => 0, 'message' => 'Insufficient money'));
            }

            $payout = \Stripe\Payout::create([
              'amount' => $request->amount * 100,
              'currency' => 'usd',
            ], [
              'stripe_account' => $customer->stripe_account_id,
            ]);

            return json_encode(array('status' => 1));
        } catch (\Exception $ex) {
            return json_encode(array('status' => 0, 'message' => $ex->getMessage()));
        }        
    }
}