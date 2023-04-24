<?php

namespace App\Http\Controllers;
use App\Models\Logs;
use App\Models\User;

use Illuminate\Http\Request;
use Carbon\Carbon;

class LogsController extends Controller
{
  public function searchuser(Request $req)
  {
    if ($req['group']) {
      // $getusers = User::where('group_id',$req['group'])->select('id')->get();
      if ($req['username']) {
        return Logs::with('user')->whereHas('User', function($q) use ($req)
        {
          $q->where('group_id', $req['group']);

        })->Where('user_id',  $req['username'])
        ->whereDate('created_at', 'like', '%' . $req['date'] . '%')
        ->Where('type', 'like', '%' . $req['type'] . '%')
        ->Where('id', 'like', '%' . $req['logid'] . '%')
  		 // ->Where('id', 'like', '%' . $req['logid'] . '%')
        // ->WhereIn('id', $getusers)
        ->latest()->paginate(10);
      }else {
        return Logs::with('user')->whereHas('User', function($q) use ($req)
        {
          $q->where('group_id', $req['group']);

        })->Where('user_id', 'like', '%' . $req['username'] . '%')
        ->whereDate('created_at', 'like', '%' . $req['date'] . '%')
        ->Where('type', 'like', '%' . $req['type'] . '%')
        ->Where('id', 'like', '%' . $req['logid'] . '%')
  		 // ->Where('id', 'like', '%' . $req['logid'] . '%')
        // ->WhereIn('id', $getusers)
        ->latest()->paginate(10);
      }

    }else {
      if ($req['username']) {
        return Logs::with('user')
  		  ->Where('user_id', $req['username'])
        ->whereDate('created_at', 'like', '%' . $req['date'] . '%')
        ->Where('type', 'like', '%' . $req['type'] . '%')
        ->Where('id', 'like', '%' . $req['logid'] . '%')
        ->latest()->paginate(10);

      }else {
        return Logs::with('user')
  		  ->Where('user_id', 'like', '%' . $req['username'] . '%')
        ->whereDate('created_at', 'like', '%' . $req['date'] . '%')
        ->Where('type', 'like', '%' . $req['type'] . '%')
        ->Where('id', 'like', '%' . $req['logid'] . '%')
        ->latest()->paginate(10);
      }

    }

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
      ->latest()->paginate(10);
    }
    return Logs::with('user')->whereDate('created_at', Carbon::today())->latest()->paginate(10);
    // return response()->json($data);
  }
}
