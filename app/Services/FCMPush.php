<?php

namespace App\Services;
use App\Customer;
use DB;

class FCMPush
{
    public function __construct()
    {

    }

    function send($userId, $image, $title, $message, $type = 0, $ref_id = 0) {
        $customer = Customer::where('id', $userId)->get()->first();
        if ($customer == null)
            return false;        
        DB::table('notifications')->insert([
            'user_id' => $userId,
            'image' => $image,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'ref_id' => $ref_id,
            'created_at' => date('Y-m-d')
        ]);

        $serverKey = getenv("FCM_SERVER_KEY");

        $deviceToken = $customer->device_token;
        $deviceType = $customer->device_type;

        // Your Firebase Server API Key
        $headers = array('Authorization:key=' . $serverKey, 'Content-Type:application/json');

        $url = 'https://fcm.googleapis.com/fcm/send';
        
        if($deviceType == 'Android'){
            $fields = array(
                'to' => $deviceToken,
                'data' => [                    
                    'title' => $title,
                    'body' => $message,
                    'sound' => 1,
                ]
            );
        } else {
            $fields = array(
                'to' => $deviceToken,
                'data' => array(
                    'title' => $title,
                    'body' => $message,
                ),
                'notification' => array(
                    'title' =>$title , 
                    'body' => $message, 
                    'sound' => 'default', 
                    'badge' => '1'
                )
            );
        }
        
        // Open curl connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        
        $result = curl_exec($ch);

        if ($result === FALSE) {
            return false;
        }
        curl_close($ch);

        return true;
    }
}