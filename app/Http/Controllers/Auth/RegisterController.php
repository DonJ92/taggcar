<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Support\Facades\Input;
use Validator;

class RegisterController extends Controller
{
	public function register() {
		return view('auth.register');
	}

	

	public function postRegister(Request $request) {
		$rules = [
      'email'   => 'required|email|unique:users',
      'password' => 'required|confirmed'
    ];
    $validator = Validator::make(Input::all(), $rules);
    
    if ($validator->fails()) {
      return redirect()->back()->with('err', '-1');
    }
    $user = new User;
    $input = $request->all();   
    $input['password'] = bcrypt($request['password']);
    $token = md5(time().$request->name.$request->email);
    $input['remember_token'] = $token;
    $user->fill($input)->save();
    
  	return redirect()->back()->with('err', '1');
	}
}