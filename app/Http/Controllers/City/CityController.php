<?php 


namespace App\Http\Controllers\City;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\City;
use App\Place;
use Carbon\Carbon;
use DB;

class CityController extends Controller
{
	public function cityLists() {
		$cities = City::all();
		$customers = $this->getInactiveUser();
		$Icount = count($customers);
		return view('city.cities', compact('cities', 'Icount', 'customers'));
	}

	public function placeLists() {
		$places = DB::table('places')->select('places.id','places.pname','places.lat','places.city_id','places.lng','cities.name')->leftjoin('cities','cities.id','=', 'places.city_id')->get();
		file_put_contents('a.txt', print_r($places,1));
		$cities = City::all();
		$customers = $this->getInactiveUser();
		$Icount = count($customers);
		return view('city.places', compact('places', 'Icount', 'customers', 'cities'));
	}

	public function cityCreate() {
		$name = $this->getParam('name', '');
		$nearby = $this->getParam('nearby', '');
		$res = DB::table('cities')->insert([
			'name'=>$name,
			'nearby'=>$nearby,
		]);
		$data = array();
		$cities = City::all();
    $i = 0;
    $count = count($cities);
    $i = $count+1;
    $str = "";
		foreach($cities as $city) {
      $i = $i-1;
			$str.='<tr>
	              <td class="text-center">
	                <input type="checkbox" class="checkboxes" name="ID" onclick="clicked();" value="'.$city->id.'" /></td>
	              <td class="text-center">'.$city->name.'</td>
	              <td class="text-center">'.$city->nearby.'</td>
	              <td class="text-center">
	                <div class="btn-group-xs">
	                  <a href="'. url('city/cityDetail/') .'/'.$city->id.'" class="btn_detail">
	                      Detail
	                  </a>
	                  <input type="hidden" value="'. $city->id .'">
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

	public function placeCreate() {
		$name = $this->getParam('name', '');
		$city_id = $this->getParam('city_id', '');
		$lat = $this->getParam('lat', '');
		$lng = $this->getParam('lng', '');
		$res = DB::table('places')->insert([
			'pname'=>$name,
			'city_id'=>$city_id,
			'lat'=>$lat,
			'lng'=>$lng
		]);
		$data = array();
		$places = Place::all();
    $i = 0;
    $count = count($places);
    $i = $count+1;
    $str = "";
		foreach($places as $place) {
      $i = $i-1;
			$str.='<tr>
	              <td class="text-center">
	                <input type="checkbox" class="checkboxes" name="ID" onclick="clicked();" value="'.$place->id.'" /></td>
	              <td class="text-center">'.$place->pname.'</td>
	              <td class="text-center">'.$place->city_id.'</td>
	              <td class="text-center">'.$place->lat.'</td>
	              <td class="text-center">'.$place->lng.'</td>
	              <td class="text-center">
	                <div class="btn-group-xs">
	                  <a href="#" data-toggle="modal" data-target="#placeDetail" class="btn_detail btn_placeDetail">
	                      Detail
	                  </a>
	                  <input type="hidden" value="'. $place->id .'">
	                  <input type="hidden" value="'. $place->pname .'">
                    <input type="hidden" value="'. $place->city_id .'">
                    <input type="hidden" value="'. $place->lat .'">
                    <input type="hidden" value="'. $place->lng .'">
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

	public function cityUpdate(Request $request) {
		$nearby = implode(',', $request->e_nearby);
		$res = City::where('id', '=', $request->e_id)->update([
			'name' => $request->e_name,
			'nearby' => $nearby,
		]);
		$cities = City::all();
		$t_citites = DB::select('SELECT c.name, (select group_concat(name) from cities where FIND_IN_SET(c.id, c.nearby)) as nearby FROM cities as c');
		$customers = $this->getInactiveUser();
		$Icount = count($customers);
		return view('city.cities', compact('cities', 't_citites', 'customers', 'Icount'));
	}

	public function placeUpdate(Request $request) {
		$res = Place::where('id', '=', $request->e_id)->update([
			'pname' => $request->e_name,
			'city_id' => $request->e_city_id,
			'lat'=> $request->e_lat,
			'lng'=>$request->e_lng
		]);
		$cities = City::all();
		$places = DB::table('places')->select('*')->leftjoin('cities','cities.id','=', 'places.city_id')->get();
		$customers = $this->getInactiveUser();
		$Icount = count($customers);
		return view('city.places', compact('cities', 'places', 'customers', 'Icount'));
	}

	public function cityDel($city_id) {
		$res = DB::delete("delete from cities where id in (".$city_id.")");
		echo json_encode(array('result' => $res));
	}

	public function placeDel($place_id) {
		$res = DB::delete("delete from places where id in (".$place_id.")");
		echo json_encode(array('result' => $res));
	}

	
}