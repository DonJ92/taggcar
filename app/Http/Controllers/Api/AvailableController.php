<?php
/**
 * Created by PhpStorm.
 * User: Lockie.J
 * Date: 10/21/2020
 * Time: 7:14 AM
 */
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use DB;

class AvailableController extends Controller{

    public function addAvailable(Request $request){
        $count = DB::table('availables')->where("ownerid" , $request->owner)->where(  "followerid" , $request->follower)->count();
        $count += DB::table('availables')->where("ownerid" , $request->follower)->where(  "followerid" , $request->owner)->count();
        if($count == 0){
            DB::table('availables')->insert(
                [
                    "ownerid" => $request->owner,
                    "followerid" => $request->follower,
                ]
            );

            return json_encode($request);
        }
    }

    public function getAvailables(Request $request){
        $list = DB::table('availables')->where("ownerid" , $request->id)->orWhere('followerid' , $request->id)->get();
        $res = [];
        foreach($list as $value){
            $user = null;
            if($value->ownerid == $request->id){
                $user = DB::table("customers")->where("id" , $value->followerid)->get()->first();
            } else{
                $user = DB::table("customers")->where("id" , $value->ownerid)->get()->first();
            }
            if ($user)
                array_push($res , $user);
        }

        $count_sum = 0;
        $res_unreads = [];
    
        foreach($res as $value){
            $count = DB::table('messages')->where("receiver" , $request->id)->where("sender" , $value->id)->where('status' , "!=" , 0)->count();
            array_push($res_unreads , $count);

            $count_sum += $count;
        }
        return json_encode(["res" => $res , "unreads"=>$res_unreads , "count_sum" => $count_sum]);
    }
}