<?php 


namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Customer;
use Carbon\Carbon;
use Validator;
use DB;


class CustomerController extends Controller
{
	
	public function getDrivers() {
		$customers = $this->getInactiveUser();
		$Icount = count($customers);
		$Ydrivers = Customer::where('role', '=', 'D')->where('is_allow', '=', 'Y')->get();
		$Ndrivers = Customer::where('role', '=', 'D')->where('is_allow', '=', 'N')->get();
		return view('customer.driver', compact('Ydrivers', 'Ndrivers', 'Icount', 'customers'));
	}

	public function getPassengers() {
		$passengers = Customer::where('role', '=', 'P')->get();
		$customers = $this->getInactiveUser();
		$Icount = count($customers);
		return view('customer.passenger', compact('passengers', 'Icount', 'customers'));
	}

	public function driverDel($driver_id) {
		$res = DB::delete("delete from customers where id in (".$driver_id.")");
		echo json_encode(array('result' => $res));
	}

	public function passengerDel($passenger_id) {
		$res = DB::delete("delete from customers where id in (".$passenger_id.")");
		echo json_encode(array('result' => $res));
	}

	public function driverDetail($driver_id) {
		$driver = Customer::where('id', '=', $driver_id)->firstOrFail();
		$trips = DB::table('trips')
		->select('*')
		->leftjoin('customers','customers.id','=','trips.driver_id')
		->where('customers.id','=',$driver_id)
		->get();
		$customers = $this->getInactiveUser();
		$Icount = count($customers);
		return view('customer.driverDetail', compact('driver', 'Icount', 'customers', 'trips'));
	}

	public function passengerDetail($passenger_id) {
		$passenger = Customer::where('id', '=', $passenger_id)->firstOrFail();
		$bookings = DB::table('bookings')
		->select('bookings.b_departure', 'bookings.b_arrival','bookings.b_arrive_time','bookings.b_leave_time','bookings.b_price','trips.departure','trips.arrival','trips.price','customers.name', 'bookings.b_passengers','trips.passengers','customers.avatar_url')
		->leftjoin('customers', 'customers.id','=','bookings.passenger_id')
		->leftjoin('trips','trips.id','=', 'bookings.trip_id')
		->get();
		$customers = $this->getInactiveUser();
		$Icount = count($customers);
		return view('customer.passengerDetail', compact('passenger', 'Icount', 'customers','bookings'));
	}

	public function driverActive($driver_id) {
		$res = Customer::where('id', '=', $driver_id)->update([
			'is_allow'=>'Y'
		]);
		echo json_encode(array('result' => $res));
	}

	public function driverInactive($driver_id) {
		$res = Customer::where('id', '=', $driver_id)->update([
			'is_allow'=>'N'
		]);
		echo json_encode(array('result' => $res));
	}


}