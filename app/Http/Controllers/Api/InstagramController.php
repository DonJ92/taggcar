<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Auth;
use Validator;
use Carbon\Carbon;
use DB;

class InstagramController extends Controller
{

    public function getProfile(Request $request) {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
            return json_encode(array('status' => 0, 'message' => $message));
        }

        $code = $request->code;

        $response = Http::asForm()->post('https://api.instagram.com/oauth/access_token', [            
            'client_id' => getenv("INSTAGRAM_CLIENT_ID"),
            'client_secret' => getenv("INSTAGRAM_CLIENT_SECRET"),
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => 'https://socialsizzle.heroku.com/auth/',        
        ]);
        
        $json = json_decode($response->body());

        if ($response->failed()) {
            return json_encode(array('status' => 0, 'message' => $json->error_message));
        }

        $token = $json->access_token;

        if (empty($token)) {
            return json_encode(array('status' => 0, 'message' => "Can't get access token"));
        } 

        $response = $client->request('GET', 'https://graph.instagram.com/me?fields=id,username&access_token=' . $token);

        if ($response->id) {
            return json_encode(array('status' => 1, 'data' => array('id' => $response->id, 'username' => $response->username)));
        } else {
            return json_encode(array('status' => 0, 'message' => "Can't get profile"));
        }
    }

}