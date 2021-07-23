<?php 

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Config;
use Validator;
use DB;
use Auth;
use App\Customer;



class RegisterController extends Controller
{
    public function __construct()
    {
    //    $this->middleware('auth');
    }
    protected function getToken(){
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }

    public function signup(Request $request) {
        //--- Validation Section
        $rules = [
		        'email'   => 'required|email|unique:customers',
		        'password' => 'nullable'
                ];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
          return response()->json(array('status'=>'0','errors' => $validator->getMessageBag()->toArray()));
        }

        try {            
            $birthday = explode('-', $request['birthday']);
            $birth_year = 1990;
            if (count($birthday)>0) 
                $birth_year = (int)($birthday[0]);

            $cyear = date('Y');
            $age = $cyear - $birth_year ;   
            
            $tmpemail = explode("@",$request['email']);
            $user_id = $tmpemail[0];

            $customer = new Customer;
            $input = $request->all();
            $input['password'] = $request['password'];
            $input['remember_token'] = $this->getToken();
            $avatar = $this->generalFileUpload($request, 'img', date("Ymd"));
            $input['avatar_url'] = $avatar;
            $input['age'] = $age;
            $input['user_id'] = $user_id;
            $input['google'] = $request['google'];
            $input['facebook'] = $request['facebook'];
            $input['apple'] = $request['apple'];

            $customer->fill($input)->save();

            $customer = $customer::where('email','=',$request['email'])->get()->first();

            if ($customer) {
                $stripe = new \Stripe\StripeClient(
                    Config::get('services.stripe.key')
                );    
                $stripeAccount = $stripe->accounts->create([
                    'type' => 'custom',
                    'email' => $customer->email,
                    'capabilities' => [
                        'card_payments' => ['requested' => true],
                        'transfers' => ['requested' => true],
                    ],
                ]);

                $stripeCustomer = $stripe->customers->create([
                   'email' => $customer->email,
                   'name' => $customer->name,
                   'phone' => $customer->phone,
                ]);

                Customer::where('id' , '=', $customer->id)->update([
                    "stripe_account_id" => $stripeAccount->id,
                    "stripe_customer_id" => $stripeCustomer->id
                ]);
            }

            echo json_encode(['status'=>'1', 'customer' => $customer]);
        } catch (\Exception $ex) {
            return json_encode(array('status' => 0, 'message' => $ex->getMessage()));
        }
    }


}