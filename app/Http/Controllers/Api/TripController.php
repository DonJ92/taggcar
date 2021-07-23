<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Auth;
use Carbon\Carbon;
use Validator;
use DB;
use App\Booking;

class TripController extends Controller
{
    public function index() {

    }
    public function trip_detail(Request $request) {
        $from_cityId = $request->from_cityId;
        $from_lng = $request->from_lng;
        $from_lat = $request->from_lat;
        $from_placeId= $request->from_placeId;
        $to_cityId = $request->to_cityId;
        $to_lng = $request->to_lng;
        $to_lat = $request->to_lat;
        $to_placeId= $request->to_placeId;
        $path_detail= $request->path_detail;
        
        $from_index=-1; $to_index=-1;
        $city_list  = array(); $place_list = array();

        $list = explode('-', $path_detail);
        foreach ($list as $index=>$p) {
            $path = explode('_', $p);
            if ($path[0] == $from_cityId && $from_index<0) {
                $from_index = $index;
            }
            if ($path[0] == $to_cityId) {
                $to_index = $index;
            }
            $sql = " SELECT * from cities where id=".$path[0];
            $city = DB::select($sql);
            array_push($city_list, $city[0]);
            
            $sql = " SELECT * from places where id=".$path[1];
            $place = DB::select($sql);
            array_push($place_list, $place[0]);
        }

        if (count($place_list)>1) {
            //calculate the distance
            $from = $place_list[$from_index];
            $to   = $place_list[$to_index];

            $lng = abs($from->lng - $from_lng);
            $lat = abs($from->lat - $from_lat);
            $from_distance = ($lng * $lng + $lat * $lat) / 2;

            $lng = abs($to->lng - $to_lng);
            $lat = abs($to->lat - $to_lat);
            $to_distance = ($lng * $lng + $lat * $lat) / 2;
        }
        $result = array('from_index'=>$from_index, 'to_index'=>$to_index, 'city_list'=> $city_list, 'place_list'=>$place_list, 'from_distance'=>$from_distance, 'to_distance'=>$to_distance);
        
        return json_encode($result);      
    }
    public function search(Request $request) {

        $departure = $request->departure;
        $destination = $request->destination;
        $departure_id = (int)($request->departure_id);
        $destination_id = (int)($request->destination_id);
        $date = $request->date;
        $passengers = (int)($request->passengers);
        $max = $request->max;

        $query = "SELECT trips.*, customers.name,customers.age, customers.avatar_url, rating.score FROM trips 
                LEFT JOIN customers ON customers.id = trips.driver_id
                LEFT JOIN (SELECT driver_id, AVG(score) as score FROM comments GROUP BY driver_id) as rating ON customers.id = rating.driver_id 
                WHERE 1=1";
  
        if($departure != ""){
            $query = $query." AND departure like '%".$departure."%'";
        }
        if($destination != ""){
            $query = $query." AND arrival like '%".$destination."%'";
        }
        if($date != ""){
            $query = $query." AND start_date = '".$date."'";
        }
        
        if($passengers > 0){
            $query = $query." AND passengers >= ".$passengers;
        }

        //echo $query;
        $trips = DB::select($query);

        return json_encode($trips); 
    }

    public function addtrip(Request $request) {
        $departure = $request->departure;
        $arrival = $request->arrival;
        $start_date = $request->start_date;
        $leave_time = $request->leave_time;
        $passengers = $request->passengers;
        $price = $request->price;
        $comment = $request->comment; 
        $driver_id = $request->driver_id;  
        $path_detail = $request->path_detail;
        $from_lat = $request->departure_latlng['lat'];
        $from_lng = $request->departure_latlng['lng'];
        $to_lat = $request->arrival_latlng['lat'];
        $to_lng = $request->arrival_latlng['lng'];
        $arrive_time = date('h:i:00', (strtotime($leave_time) +  2*3600));
        
        $fields["departure"]= $departure;
        $fields["arrival"]= $arrival;
        $fields["start_date"]= $start_date;
        $fields["leave_time"]= $leave_time;
        $fields["passengers"]= $passengers;
        $fields["price"]= $price;
        $fields["comment"]= $comment;
        $fields["arrive_time"] = $arrive_time;
        $fields["path_detail"] = $path_detail;
        $fields["driver_id"] = $driver_id;
        $fields['from_lat'] = $from_lat;
        $fields['from_lng'] = $from_lng;
        $fields['to_lat'] = $to_lat;
        $fields['to_lng'] = $to_lng;

        DB::table('trips')->insert($fields);

        echo json_encode(['status'=>'1', 'msg'=>'trip updated successfully']);
    }

    public function removetrip(Request $request){

        $id = $request->id;        
        $query = "delete from trips where id=".$id;
        DB::delete($query);

        return json_encode('trip deleted successfully');
    }
    public function updatetrip(Request $request){

        $id = $request->id;

        $departure = $request->departure;
        $arrival = $request->arrival;
        $start_date = $request->start_date;
        $leave_time = $request->leave_time;
        $passengers = $request->passengers;
        $price = $request->price;
        $comment = $request->comment; 
        $driver_id = $request->driver_id;  
        $path_detail = $request->path_detail;
        $from_lat = $request->departure_latlng->lat;
        $from_lng = $request->departure_latlng->lng;
        $to_lat = $request->arrival_latlng->lat;
        $to_lng = $request->arrival_latlng->lng;
        $arrive_time = date('h:i:00', (strtotime($leave_time) +  2*3600));

        $fields["departure"]= $departure;
        $fields["arrival"]= $arrival;
        $fields["start_date"]= $start_date;
        $fields["leave_time"]= $leave_time;
        $fields["passengers"]= $passengers;
        $fields["price"]= $price;
        $fields["comment"]= $comment;
        $fields["arrive_time"] = $arrive_time;
        $fields["path_detail"] = $path_detail;
        $fields['from_lat'] = $from_lat;
        $fields['from_lng'] = $from_lng;
        $fields['to_lat'] = $to_lat;
        $fields['to_lng'] = $to_lng;
        // $data = json_decode($request->data);        
        // $fields = array();
        // foreach ($data as $key=>$value) {
        //     $fields[$key] = $value;
        // }
        DB::table('trips')->where('id', $id)->update($fields);

        return json_encode('trip updated successfully');
    }
    public function listtrip(Request $request){
        $customer_id = $request->customer_id;

        $offerTrip = DB::table('trips')
            ->select('trips.*', 'customers.name', 'customers.avatar_url')
            ->join('customers', 'customers.id' , '=' , 'trips.driver_id')
            ->where('driver_id','=', $customer_id)->get();

        $bookTrip = DB::table('bookings')
            ->select('trips.*', 'customers.name', 'customers.avatar_url')
            ->join('trips', 'bookings.trip_id' , '=' , 'trips.id')
            ->join('customers', 'customers.id' , '=' , 'trips.driver_id')
            ->where('bookings.passenger_id','=', $customer_id)
            ->where('bookings.state','=', Config::get('constants.booking.charged'))
            ->get();

        $trips = array_merge($offerTrip->toArray(), $bookTrip->toArray());
        foreach ($trips as $trip) {

            $booking = Booking::where('trip_id', $trip->id)->where('state', Config::get('constants.booking.charged'))->first();
            if ($booking != null)
                $trip->booking = $booking->toArray();
        }

        return json_encode($trips);
    }

    public function deletearide(Request $request){
        $id = $request->id;

        DB::table('trips')->where("id" , $id)->delete();
        return 1;
    }

}