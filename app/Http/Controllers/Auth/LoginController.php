<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use Session;
use Illuminate\Support\Facades\Input;
use Validator;
use DB;

class LoginController extends Controller
{

  public function login() {
		return view('auth.login');
	}

	public function postLogin(Request $request) {
    //--- Validation Section
    $rules = [
              'email'   => 'required|email',
              'password' => 'required'
            ];

    $validator = Validator::make(Input::all(), $rules);
    
    if ($validator->fails()) {
      return redirect()->back()->with('err', '-1');
    }
    //--- Validation Section Ends

    // Attempt to log the user in
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
      // if successful, then redirect to their intended location
    	return redirect('dashboard');
    }
	    // if unsuccessful, then redirect back to the login with the form data
    return redirect()->back()->with('err', '-2'); 
  }

  public function logout()
  {
    Auth::guard('web')->logout();
    return redirect('/');
  }

  public function getAdmins() {
    $admins = User::all();
    $customers = $this->getInactiveUser();
    $Icount = count($customers);
    return view('customer.admins', compact('admins', 'Icount', 'customers'));
  }

  public function adminDetail($admin_id) {
    $admin = User::where('id', '=', $admin_id)->firstOrFail();
    $customers = $this->getInactiveUser();
    $Icount = count($customers);
    return view('customer.adminDetail', compact('admin', 'customers', 'Icount'));
  }

  public function adminDel($admin_id) {
    $res = DB::delete('delete from users where id in '.$admin_id.')');
    echo json_encode(array('result' => $res));
  }

  public function adminEdit(Request $request) {
    User::where('id', '=', $request->admin_id)->update([
      'name' => $request->e_name,
      'email' => $request->e_email
    ]);
    $admins = User::all();
    $customers = $this->getInactiveUser();
    $Icount = count($customers);
    return view('customer.admins', compact('admins', 'Icount', 'customers'));
  }

  public function ajaxUploadCertImg(Request $request){
    $id = $this->getParam("id", "0");
    $adminInfo = User::find($id);
    if(!isset($adminInfo['id'])){
        return json_encode(array('status'=>'0', 'msg'=>'Incorrect information.'));
    }
    $url = $this->generalFileUpload($request, 'file', date("Ymd"));
    $adminInfo['avatar_Url'] = $url;
    $adminInfo->save();
    return json_encode(array('status'=>'1', 'msg'=>'success.', 'url'=>$url));
  }


}