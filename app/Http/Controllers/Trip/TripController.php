<?php 


namespace App\Http\Controllers\Trip;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use DB;
use App\Trip;
use App\Booking;

class TripController extends Controller
{
	public function getTrips() {
		$trips = DB::table('trips')->select('trips.id','trips.departure','trips.arrival','trips.price','trips.start_date','customers.name')->leftjoin('customers','customers.id','trips.driver_id')->get();
		$customers = $this->getInactiveUser();
		$Icount = count($customers);
		return view('trip.trips',compact('trips','customers','Icount'));
	}

	public function getBookings() {
		$bookings = DB::table('bookings')->select('bookings.id','bookings.b_departure','bookings.b_arrival','bookings.b_price','customers.name')->leftjoin('customers','customers.id','bookings.passenger_id')->get();
		$customers = $this->getInactiveUser();
		$Icount = count($customers);
		return view('trip.bookings', compact('bookings','customers','Icount'));
	}

	public function tripDetail($trip_id) {
		$customers = $this->getInactiveUser();
		$Icount = count($customers);
		$trip = DB::table('trips')->select('trips.id','trips.departure','trips.arrival','trips.price','trips.start_date','trips.path','trips.passengers','customers.name','customers.avatar_url','trips.leave_time','trips.arrive_time')->leftjoin('customers','customers.id','trips.driver_id')->where('trips.id','=', $trip_id)->first();
		return view('trip.tripDetail',compact('customers','Icount','trip'));
	}

	public function tripUpdate(Request $request) {
		$id = $request->e_trip_id;
		DB::table('trips')->where('id','=',$id)->update([
			'departure'=>$request->e_departure,
			'arrival'=>$request->e_arrival,
			'path'=>$request->e_path,
			'price'=>$request->e_price,
			'passengers'=>$request->e_passengers,
			'start_date'=>$request->e_start_date,
			'leave_time'=>$request->e_leave_time,
			'arrive_time'=>$request->e_arrive_time
		]);
		$trips = DB::table('trips')->select('trips.id','trips.departure','trips.arrival','trips.price','trips.start_date','customers.name')->leftjoin('customers','customers.id','trips.driver_id')->get();
		$customers = $this->getInactiveUser();
		$Icount = count($customers);
		return view('trip.trips', compact('customers','Icount','trips'));
	}

	public function bookingDetail($booking_id) {
		$booking = DB::table('bookings')->select('bookings.id','bookings.b_departure','bookings.b_arrival','bookings.b_price','bookings.b_arrive_time','bookings.b_leave_time','bookings.b_passengers','customers.name','customers.avatar_url','trips.departure','trips.arrival')->leftjoin('customers','customers.id','bookings.passenger_id')->leftjoin('trips', 'trips.id','=','bookings.trip_id')->where('bookings.id','=',$booking_id)->first();
		$customers = $this->getInactiveUser();
		$Icount = count($customers);
		return view('trip.bookingDetail', compact('booking','customers','Icount'));
	}

	public function bookingUpdate(Request $request) {
		DB::table('bookings')->where('id','=',$request->e_booking_id)->update([
			'b_departure'=>$request->e_departure,
			'b_arrival'=>$request->e_arrival,
			'b_price'=>$request->e_price,
			'b_passengers'=>$request->e_passengers,
			'b_arrive_time'=>$request->e_arrive_time,
			'b_leave_time'=>$request->e_leave_time
		]);
		$customers = $this->getInactiveUser();
		$Icount = count($customers);
		$bookings = DB::table('bookings')->select('bookings.id','bookings.b_departure','bookings.b_arrival','bookings.b_price','bookings.b_leave_time','bookings.b_arrive_time','customers.name')->leftjoin('customers','customers.id','bookings.passenger_id')->get();
		return view('trip.bookings', compact('bookings','customers','Icount'));

	}
}