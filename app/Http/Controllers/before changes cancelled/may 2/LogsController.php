<?php

namespace App\Http\Controllers;
use App\Models\Logs;

use Illuminate\Http\Request;
use Carbon\Carbon;

class LogsController extends Controller
{
  public function searchuser(Request $req)
  {

    return Logs::with('user')->Where('user_id', 'like', '%' . $req['username'] . '%')
    ->whereDate('created_at', 'like', '%' . $req['date'] . '%')
    ->Where('type', 'like', '%' . $req['type'] . '%')
    ->Where('id', 'like', '%' . $req['logid'] . '%')
    ->latest()->paginate(100);
    // ->where('type',$req['type'])->where('id',$req['logid'])
    // return response()->json($data);
  }
  public function logs(Request $req)
  {
    if ($req['username']||$req['date']||$req['type']||$req['logid']) {
      return Logs::with('user')->Where('user_id', 'like', '%' . $req['username'] . '%')
      ->whereDate('created_at', 'like', '%' . $req['date'] . '%')
      ->Where('type', 'like', '%' . $req['type'] . '%')
      ->Where('id', 'like', '%' . $req['logid'] . '%')
      ->latest()->paginate(100);
    }
    return Logs::with('user')->whereDate('created_at', Carbon::today())->latest()->paginate(100);
    // return response()->json($data);
  }
}
