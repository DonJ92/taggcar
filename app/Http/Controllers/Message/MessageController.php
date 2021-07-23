<?php 


namespace App\Http\Controllers\Message;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Message;
use Carbon\Carbon;
use DB;

class MessageController extends Controller
{
	public function getMessages() {
		$messages = DB::select('SELECT m.id, m.content, m.msg_date, (select GROUP_CONCAT(name) from customers as c where FIND_IN_SET(c.id, m.sender)) as sender, (select GROUP_CONCAT(name) from customers as c where FIND_IN_SET(c.id, m.receiver)) as receiver FROM `messages` as m');
		$customers = $this->getInactiveUser();
		$Icount = count($customers);
		return view('message.messages', compact('messages', 'Icount', 'customers'));
	}

	public function messageDel($message_id) {
		$res = DB::delete("delete from messages where id in (".$message_id.")");
		echo json_encode(array('result' => $res));
	}

	public function messageDetail($message_id) {
		$customers = $this->getInactiveUser();
		$Icount = count($customers);
		$messages = DB::select('SELECT m.id, m.content, m.msg_date, (select GROUP_CONCAT(name) from customers as c where FIND_IN_SET(c.id, m.sender)) as sender, (select GROUP_CONCAT(avatar_url) from customers as c where FIND_IN_SET(c.id, m.sender)) as sender_url, (select GROUP_CONCAT(name) from customers as c where FIND_IN_SET(c.id, m.receiver)) as receiver, (select GROUP_CONCAT(avatar_url) from customers as c where FIND_IN_SET(c.id, m.receiver)) as receiver_url FROM `messages` as m where m.id='.$message_id);
		$message = $messages[0];
		return view('message.messageDetail', compact('customers', 'Icount', 'message'));
	}
}