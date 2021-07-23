<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Auth;
use Carbon\Carbon;
use Validator;
use App\Customer;
use App\Trip;
use Twilio\Rest\Client;
use DB;
use App\Services\FCMPush;

class CustomerController extends Controller
{
    public function index() {

    }
    public function get(Request $request) {
        $customer = Customer::where('id', $request->id)->get()->first();
        $tripCount = Trip::where('driver_id', $customer->id)->where('state', 0)->count();
        $customer->trip_count = $tripCount;
        $customer->since_date = date('F Y', strtotime($customer->created_at));

        return json_encode($customer);
    }

    public function editProfile(Request $request){
        Customer::where('id' , '=', $request->id)->update([
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
            "birthday" => $request->birthday,
            "bio" => $request->bio,
            "avatar_url" => $request->avatar_url,
            "car_details" => $request->car_details,
        ]);
        return json_encode(Customer::where('id', $request->id)->get()->first());
    }

    public function changePassword(Request $request){
        Customer::where('id' , '=', $request->id)->update([
            "password" => $request->password,
        ]);
        return json_encode(Customer::where('id', $request->id)->get()->first());
    }
    
    public function uploadAvatar(Request $request){
        Customer::where('id' , '=', $request->id)->update([
            "avatar_url" => $request->avatar_url,
        ]);
        return json_encode(Customer::where('id', $request->id)->get()->first());
    }
    
    public function uploadID(Request $request){
        Customer::where('id' , '=', $request->id)->update([
            "id_verification_image" => $request->url,
            "verified_id" => 1,
        ]);
        return json_encode(Customer::where('id', $request->id)->get()->first());
    }
    
    public function sendVerifySMS(Request $request) {
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        
        $twilio = new Client($sid, $token);

        try {
            $verification = $twilio->verify->v2->services("VAd68bb4e5f9c60131dab063c9c4675e45")
                                   ->verifications
                                   ->create('+' . $request->phone_number, "sms");
            return json_encode(array('status' => 1));
        } catch (\Exception $ex) {
            return json_encode(array('status' => 0, 'message' => $ex->getMessage()));
        }       
    }
    

    public function verifyPhone(Request $request){
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        
        $twilio = new Client($sid, $token);

        try {
            $verification_check = $twilio->verify->v2->services("VAd68bb4e5f9c60131dab063c9c4675e45")
                                         ->verificationChecks
                                         ->create($request->code, array("to" => '+' . $request->phone_number));            
            if ($verification_check->status == 'approved')
            {
                Customer::where('id' , '=', $request->id)->update([
                    "verified_phone" => $request->phone_number,
                ]);
                return json_encode(array('status' => 1));
            }
            else
                return json_encode(array('status' => 0));
        } catch (\Exception $ex) {
            return json_encode(array('status' => 0, 'message' => $ex->getMessage()));
        }
    }

    public function sendVerifyEmail(Request $request) {
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);

		try {
            $verification = $twilio->verify->v2->services("VAd68bb4e5f9c60131dab063c9c4675e45")
                                   ->verifications
                                   ->create($request->email, "email");
            return json_encode(array('status' => 1));
        } catch (\Exception $ex) {
            return json_encode(array('status' => 0, 'message' => $ex->getMessage()));
        }
    }
    
    public function verifyEmail(Request $request){
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        
        $twilio = new Client($sid, $token);

        try {
            $verification_check = $twilio->verify->v2->services("VAd68bb4e5f9c60131dab063c9c4675e45")
                                         ->verificationChecks
                                         ->create($request->code, array("to" => $request->email));            
            if ($verification_check->status == 'approved')
            {
                Customer::where('id' , '=', $request->id)->update([
                    "verified_email" => $request->email,
                ]);
                return json_encode(array('status' => 1));
            }

            return json_encode(array('status' => 0));
        } catch (\Exception $ex) {
            return json_encode(array('status' => 0, 'message' => $ex->getMessage()));
        }
    }
    
    public function verifyId($id){
        Customer::where('id' , '=', $id)->update([
            "verified_id" => 2,
            //"verified_id" => 2,
        ]);
        return redirect()->back()->with('err', '-1');
    }

    public function verifyPayment(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
            'city' => 'required|string',
            'line1' => 'required|string',
            'line2' => 'nullable|string',
            'postal_code' => 'required|string',
            'state' => 'required|string',
            'ssn' => 'required|string',
            'ssn_last_4' => 'required|numeric',
            'card_number' => 'required|string',
            'exp_date' => 'required|string',
            'cvc' => 'required|integer',
            'document' => 'nullable|string'
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

        $customer = Customer::where('id', $request->id)->get()->first();

        $stripe = new \Stripe\StripeClient(
            Config::get('services.stripe.key')
        );

        $name = trim($request->name);
        $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $first_name = trim( preg_replace('#'.preg_quote($last_name,'#').'#', '', $name ) );
        
        // set document
        $document = $request->document;
        if (empty($document))
            $document = $customer->id_verification_image;

        if (empty($document)) {
            return json_encode(array('status' => 0, 'message' => 'Please upload verification image.'));
        }

        Customer::where('id' , '=', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            // 'id_verification_image' => $document,
        ]);

        try {
            
            $birthday = explode('-', $customer->birthday);

            $stripe_account = $stripe->accounts->retrieve($customer->stripe_account_id);
            
            $params = [
                'business_type' => 'individual',
                'individual' => [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $request->email,
                    'address' => [
                        'city' => $request->city,
                        'country' => 'US',
                        'line1' => $request->line1,
                        'line2' => $request->line2,
                        'postal_code' => $request->postal_code,
                        'state' => $request->state,                        
                    ],
                    'dob' => [
                        'day' => $birthday[2],
                        'month' => $birthday[1],
                        'year' => $birthday[0],
                    ],
                    'phone' => $request->phone,
                    'id_number' => $request->ssn,
                    'ssn_last_4' => $request->ssn_last_4,
                    'verification' => [
                        'document' => [
                            // 'back' => $document_back->id,
                            // 'front' => $document_front->id,
                        ]
                    ]
                ],
                'tos_acceptance' => [
                    'date' => time(),
                    'ip' => $_SERVER['REMOTE_ADDR'], // Assumes you're not using a proxy
                ],
                'business_profile' => [
                    'url' => 'http://taggcar.com/',
                    'mcc' => '1520'
                ]
            ];

            if (empty($stripe_account->individual->verification->document->front)) {
                $document_front = $stripe->files->create([
                    'purpose' => 'identity_document',
                    'file' => fopen(base_path() . '/../assets/img/' . $document, 'r'),
                ]);
                
                $params['individual']['verification']['document']['front'] = $document_front->id;
            }
            
            $stripe->accounts->update($customer->stripe_account_id, $params);

            $token = $stripe->tokens->create([
                'card' => [
                    'number' => $request->card_number,
                    'exp_month' => $expMonth,
                    'exp_year' => $expYear,
                    'cvc' => $request->cvc,
                    'currency' => 'USD'
                ],
            ]);

            $stripe->accounts->createExternalAccount(
                $customer->stripe_account_id,
                ['external_account' => $token]
            );

        } catch (\Exception $ex) {            
            return json_encode(array('status' => 0, 'message' => $ex->getMessage()));
        }   

        return json_encode(array('status' => 1));
    }

    public function acceptPaymentTerms(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
            return json_encode(array('status' => 0, 'message' => $message));
        }

        $customer = Customer::where('id', $request->id)->get()->first();

        $stripe = new \Stripe\StripeClient(
            Config::get('services.stripe.key')
        );


        try {

            $params = [               
                'tos_acceptance' => [
                    'date' => time(),
                    'ip' => $_SERVER['REMOTE_ADDR'], // Assumes you're not using a proxy
                ],
            ];
            
            $stripe->accounts->update($customer->stripe_account_id, $params);

        } catch (\Exception $ex) {            
            return json_encode(array('status' => 0, 'message' => $ex->getMessage()));
        }   

        return json_encode(array('status' => 1));
    }

    public function test() {
        $fcm = new FCMPush();

        return $fcm->send(5, '', 'Test', 'Test', 0, 0);
    }
}