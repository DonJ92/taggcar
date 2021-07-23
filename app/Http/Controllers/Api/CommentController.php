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

class CommentController extends Controller
{
    public function index() {

    }

    public function add(Request $request) {
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|integer',
            'passenger_id' => 'required|integer',            
            'booking_id' => 'required|integer',
            'score' => 'required|int',
            'comment' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
            return json_encode(array('status' => 0, 'message' => $message));
        }

        try {

            DB::table('comments')->insert(array(
                'booking_id' => $request->booking_id,
                'driver_id' => $request->driver_id,
                'passenger_id' => $request->passenger_id,
                'score' => $request->score,
                'comment' => $request->comment,
            ));

            return json_encode(array('status' => 1));

        } catch (\Exception $ex) {
            return json_encode(array('status' => 0, 'message' => $ex->getMessage()));
        }             
    }

    public function getRatingsReceived(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
            return json_encode(array('status' => 0, 'message' => $message));
        }


        $ratings = DB::table('comments')
        				->select('comments.*', 'customers.name', 'customers.avatar_url')
				        ->join('customers', 'customers.id' , '=' , 'comments.passenger_id')
				        ->where('driver_id','=', $request->user_id)->get();

        foreach ($ratings as $rating) {
			$rating->created_at = date('d F Y', strtotime($rating->created_at));
		}

        return json_encode($ratings);
    }

    public function getRatingsLeft(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
            return json_encode(array('status' => 0, 'message' => $message));
        }


        $ratings = DB::table('comments')
        				->select('comments.*', 'customers.name', 'customers.avatar_url')
				        ->join('customers', 'customers.id' , '=' , 'comments.driver_id')
				        ->where('passenger_id','=', $request->user_id)->get();

		foreach ($ratings as $rating) {
			$rating->created_at = date('d F Y', strtotime($rating->created_at));
		}
		
        return json_encode($ratings);
    }

}