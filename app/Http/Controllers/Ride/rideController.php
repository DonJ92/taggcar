<?php 


namespace App\Http\Controllers\Ride;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\RideLine;
use App\PerPrice;
use Carbon\Carbon;
use DB;

class RideController extends Controller
{
	public function rideLists() {
		$lines = RideLine::all();
		$customers = $this->getInactiveUser();
		$Icount = count($customers);
		return view('ride.rideLists', compact('lines', 'Icount', 'customers'));
	}

	public function rideCreate() {
		$line_from = $this->getParam('line_from', '');
		$line_to = $this->getParam('line_to', '');
		$to_place = $this->getParam('to_place', '');
		$line_price = $this->getParam('line_price', '');

		$res = DB::table('lines')->insert([
			'line_from'=>$line_from,
			'line_to'=>$line_to,
			'to_place'=>$to_place,
			'line_price'=>$line_price
		]);
		$data = array();
		$lines = Line::all();
    $i = 0;
    $count = count($lines);
    $i = $count+1;
    $str = "";
		foreach($lines as $line) {
      $i = $i-1;
			$str.='<tr>
	              <td class="text-center">
	                <input type="checkbox" class="checkboxes" name="ID" onclick="clicked();" value="'.$line->id.'" /></td>
	              <td class="text-center">'.$line->line_from.'</td>
	              <td class="text-center">'.$line->line_to.'</td>
	              <td class="text-center">'.$line->to_place.'</td>
	              <td class="text-center">'.$line->line_price.'</td>
	              <td class="text-center">
	                <div class="btn-group-xs">
	                  <a href="'. url('ride/rideDetail') .'/'.$line->id.'" class="btn_detail">
	                      Detail
	                  </a>
	                  <input type="hidden" value="'. $line->id .'">
	                </div>
	              </td>
	            </tr>';
		}
		$data['table'] = $str;
		if($res) {
	    $data['status'] = "1";
	    $data['msg'] = "Saved successfully.";
		} else  {
			$data['status'] = "0";
	    $data['msg'] = "Save fail.";
		}

    return json_encode($data);
	}

	public function rideUpdate(Request $request) {
		$res = RideLine::where('id', '=', $request->e_id)->update([
			'line_from' => $request->e_line_from,
			'line_to' => $request->e_line_to,
			'to_place' => $request->e_to_place,
			'line_price' => $request->e_line_price,
		]);
		$lines = RideLine::all();
		$customers = $this->getInactiveUser();
		$Icount = count($customers);
		return view('ride.rideLists', compact('lines', 'customers', 'Icount'));
	}

	public function rideDel($line_id) {
		$res = DB::delete("delete from ridelines where id in (".$line_id.")");
		echo json_encode(array('result' => $res));
	}

	public function to_setting() {
		$customers = $this->getInactiveUser();
		$Icount = count($customers);
		$price = PerPrice::firstOrFail();
		return view('ride.rideSetting', compact('customers', 'Icount','price'));
	}

	public function setting() {
		$per_price = $this->getParam('per_price', '');
		$prices = PerPrice::all();
		$pCount = count($prices);
		if($pCount>0) {
			$res = PerPrice::where('id','=','1')->update([
				'per_price'=>$per_price
			]);
		} else {
			$res = PerPrice::insert([
				'per_price'=>$per_price
			]);
		}
		$data = array();
		if($res) {
	    $data['status'] = "1";
	    $data['msg'] = "Saved successfully.";
		} else  {
			$data['status'] = "0";
	    $data['msg'] = "Save fail.";
		}
		return json_encode($data);
	}
}