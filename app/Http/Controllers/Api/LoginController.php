<?php 

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;
use DB;
use Auth;
use App\Customer;



class LoginController extends Controller
{
    public function __construct()
    {
    //    $this->middleware('auth');
    }

	protected function getToken(){
	    return hash_hmac('sha256', str_random(40), config('app.key'));
	}

    public function login(Request $request) {
        //--- Validation Section
        $rules = [
                  'email'   => 'required|email',
                  'password' => 'required',
                  'device_type' => 'nullable',
                  'device_token' => 'nullable',
                ]; 

        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends
        $customer_email_check = Customer::where('email', '=', $request->email)->count();
        if($customer_email_check > 0) {
            // $customer_check = Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password]);
            $customer_check = Customer::where('email', '=', $request->email)->where('password', '=', $request->password)->get();
            if($customer_check) {
                $customer = Customer::where('email', '=', $request->email)->firstOrFail();
                $customer->device_token = $request->device_token;
                $customer->device_type = $request->device_type;
                $customer->remember_token = $this->getToken();
                $customer->save();

                $customer->ratings = DB::table('comments')->where('driver_id','=', $customer->id)->get();

                if (count($customer->ratings) > 0) {
                    $total = 0;
                    foreach ($customer->ratings as $rating) {
                        $total += $rating->score;
                    }
                    $customer->rating_score = $total / count($customer->ratings);    
                } else {
                    $customer->rating_score = 0;
                }
                $customer->rating_count = count($customer->ratings); 
                
                $token = $this->getToken();

                $body = $customer;

                echo json_encode(['status'=>'1', 'info'=>$body]);
            } else {
                echo json_encode(['status'=>'0', 'msg'=>'Password error']);
            }
        } else {
            echo json_encode(['status'=>'0', 'msg'=>'Not exist customer']);
            
        }
        
    }

    public function socialLogin(Request $request) {
        //--- Validation Section
        $rules = [
                  'google'   => 'nullable',
                  'facebook' => 'nullable',
                  'apple' => 'nullable',
                  'device_type' => 'nullable',
                  'device_token' => 'nullable'
                ]; 

        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $customer_check = 0;
        if ($request->google)
            $customer_check = Customer::where('google', '=', $request->google)->count();
        else if ($request->facebook)
            $customer_check = Customer::where('facebook', '=', $request->facebook)->count();
        else if ($request->apple)
            $customer_check = Customer::where('apple', '=', $request->apple)->count();

        if($customer_check > 0) {
            if ($request->google)
                $customer = Customer::where('google', '=', $request->google)->firstOrFail();
            else if ($request->facebook)
                $customer = Customer::where('facebook', '=', $request->facebook)->firstOrFail();
            else
                $customer = Customer::where('apple', '=', $request->apple)->firstOrFail();
            
            $customer->device_type = $request->device_type;
            $customer->device_token = $request->token;
            $customer->remember_token = $this->getToken();
            $customer->save();

            $customer->ratings = DB::table('comments')->where('driver_id','=', $customer->id)->get();

            if (count($customer->ratings) > 0) {
                $total = 0;
                foreach ($customer->ratings as $rating) {
                    $total += $rating->score;
                }
                $customer->rating_score = $total / count($customer->ratings);    
            } else {
                $customer->rating_score = 0;
            }
            $customer->rating_count = count($customer->ratings); 
            
            $token = $this->getToken();

            // Customer::where('id' , '=', $customer->id)->update([
            //     "device_type" => $request->device_type,
            //     "device_token" => $request->device_token,
            //     "remember_token" => $token
            // ]);

            $body = $customer;

            return response()->json(['status'=>'1', 'info'=>$body]);
        } else {
            return response()->json(['status'=>'0', 'msg'=>'No customer exist']);
        }
    }

}