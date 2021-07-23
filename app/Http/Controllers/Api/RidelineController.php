<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Validator;
use DB;

class RidelineController extends Controller
{
    public function index() {

    }
	public function getLocation(Request $request) {

        $keyword = $request->keyword;
        if ($keyword!="") {
            $query = " SELECT p.*, c.name as city_name, CONCAT(p.name, ', ', c.name) as location from cities as c LEFT JOIN places as p ON (c.id=p.city_id) WHERE c.name like '%".$keyword."%' or p.name like '%".$keyword."%';";
        }        
        $places = DB::select($query);
        // foreach($line_from as $line_f){
        //     array_push($lines, $line_f->line_from);
        // }
        // foreach($line_to as $line_t){
        //     array_push($lines, $line_t->line_to);
        // }
        // $lines = array_unique($lines);              
        return json_encode($places); 
    }
    
}