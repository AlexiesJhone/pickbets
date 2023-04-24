<?php

namespace App\Http\Controllers;

use App\Models\Results;
use App\Events\userupdate;
use App\Models\Event;
use App\Models\Prebet;
use App\Models\bet;
use App\Models\expertbet;
use App\Models\selection;
use App\Models\Logs;
use App\Models\User;
use App\Models\Potmoney;
use App\Models\Transactions;
use App\Models\pending;
use Auth;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;

class withdrawals extends Controller
{
  public function rejectwithdrawal(Request $req)
  {
    $checkuser = User::where('id',$req['user_id'])->first();
    $data = Event::where('status',1)->first();
    $checkpending = pending::where('user_id',$checkuser->id)->where('active',1)->where('confirmed',0)->first();
    if ($checkpending) {
      $newtransactioncashier = new Transactions();
      $newtransactioncashier->type = 'Reject_Withdrawal';
      $newtransactioncashier->startingbalance = $checkuser->cash;
      $newtransactioncashier->startingbalancecashier = Auth()->user()->cash;
      $newtransactioncashier->endingbalance = $checkuser->cash;
      $newtransactioncashier->endingbalancecashier = Auth()->user()->cash;
      $newtransactioncashier->barcode = $checkuser->id;
      $newtransactioncashier->remarks = $req['note'];
      $newtransactioncashier->event_id = $checkpending->event_id;
      $newtransactioncashier->amount = $checkpending->amount;
      $newtransactioncashier->user_id = $checkuser->id;
      $newtransactioncashier->cashier_id = Auth()->user()->id;
      $newtransactioncashier->save();
      $checkpending->confirmed = 2;
      $checkpending->active = 0;
      $checkpending->save();
      return $checkpending;
    }
  }
  public function confirmwithdrawuser(Request $req)
  {
    // withdraw users request
    $checkuser = User::where('id',$req['user_id'])->first();
    $checkpending = pending::where('user_id',$req['user_id'])->where('id',$req['id'])->first();
    $checker = $checkuser->cash - $checkpending->amount;
    // return $checker;
    if ($checker>=0) {
      $starting = $checkuser->cash;
      $ending = $checkuser->cash - $checkpending->amount;
      $checkuser->cash = $checkuser->cash - $checkpending->amount;
      $checkpending->confirmed = 1;
      $checkpending->active = 0;
      $addtocashier = User::where('id',Auth()->user()->id)->first();
      $checker = $addtocashier->cash - $checkpending->amount;
      $addtocashier->cash = $addtocashier->cash - $checkpending->amount;
      $newtransactioncashier = new Transactions();
      $newtransactioncashier->type = 'Withdrawal';
      $newtransactioncashier->startingbalance = $starting;
      $newtransactioncashier->startingbalancecashier = Auth()->user()->cash;
      $newtransactioncashier->endingbalance = $ending;
      $newtransactioncashier->endingbalancecashier = Auth()->user()->cash - $checkpending->amount;
      $newtransactioncashier->barcode = $checkuser->id;
      $newtransactioncashier->event_id = $checkpending->event_id;
      // $checkkungokay = Event::where('id',1)->first();
      // if (condition) {
      //   // code...
      // }
      $newtransactioncashier->amount = $checkpending->amount;
      $newtransactioncashier->user_id = $checkuser->id;
      $newtransactioncashier->cashier_id = Auth()->user()->id;
      if ($checker>=0) {
        $checkpending->save();
        $addtocashier->save();
        $checkuser->save();
        $newtransactioncashier->save();
      }else {
        return error;
      }
      broadcast(new userupdate($addtocashier->id));
      broadcast(new userupdate($checkpending->user_id));
      $createlogs = new Logs();
      $createlogs->type = 'Confirmed_Withdrawal';
      $createlogs->user_id = $req['user_id'];
      $createlogs->cashier_id = auth()->user()->id;
      $createlogs->message = auth()->user()->username.' Successfully withdraw '.$checkpending->amount.' from '.$checkuser->username.'.';
      $createlogs->save();
      return $checker;
    }else {
      return error;
    }
  }
  public function getplayerpending(Request $req)
  {
    return pending::with('user')->where('user_id',$req['id'])->where('confirmed',0)->where('active',1)->first();
  }
  public function cancelpending(Request $req)
  {
    pending::where('id',$req['id'])->where('active',1)->update(['active'=>0]);
  }
  public function depositconfirmed(Request $req)
  {
    $this->validate($req, [
      'id'=>'required|int',
      'amount'=>'required|int',
    ]);
    $data = Event::where('status',1)->first();
    // add deposit to player
    $deposittoplayer = User::findOrFail($req['id']);
    $starting = $deposittoplayer->cash;
    $ending = $req['amount'] + $deposittoplayer->cash;
    $deposittoplayer->cash = $deposittoplayer->cash + $req['amount'];
    // deduct deposit to cashier
    $deductcashier = User::findOrFail(auth()->user()->id);
    $checkifnegative = $deductcashier->cash - $req['amount'];
    $deductcashier->cash = $deductcashier->cash - $req['amount'];
    if ($checkifnegative>=0) {
      $deposittoplayer->save();
      $deductcashier->save();
    }else {
      return error;
    }
    // create transaction
    $newtransaction = new Transactions();
    $newtransaction->type = 'Deposit';
    $newtransaction->amount = $req['amount'];
    $newtransaction->barcode = $req['id'];
    $newtransaction->user_id = $req['id'];
    $newtransaction->cashier_id = auth()->user()->id;
    $newtransaction->event_id = $data->id;
    $newtransaction->startingbalance = $starting;
    $newtransaction->startingbalancecashier = auth()->user()->cash;
    $newtransaction->endingbalance = $ending;
    $newtransaction->endingbalancecashier = auth()->user()->cash - $req['amount'];
    $newtransaction->save();
    // create logs for this transactions
    $newlogs = new Logs();
    $newlogs->type = 'Deposit';
    $newlogs->user_id = $deposittoplayer->id;
    $newlogs->cashier_id = auth()->user()->id;
    $newlogs->message = auth()->user()->name.' Deposited '.$req['amount'].' to '. $deposittoplayer->name;
    $newlogs->save();
    broadcast(new userupdate($deposittoplayer->id));
  }
  public function getforpendings()
  {
    $data =  Event::whereHas('transactions', function($q)
    {
      $q->where('user_id','like', auth()->user()->id);

    })->orwhereHas('pending',function($s)
    {
      $s->where('user_id','like', auth()->user()->id);
    })->latest()->get();
      // return Event::with('transactions')->latest()->get();
      return $data;
  }
  public function deposit()
  {
    return view('deposit');
  }
  public function totalwithdraw()
  {
    $data = Event::where('status',1)->first();
    $data2 =  Event::where('event_name',$data->event_name)->select('id')->get();
    return Transactions::where('cashier_id',auth()->user()->id)->whereIn('event_id',$data2)->where('type','Withdrawal')->sum('amount');
  }
  public function gettotaldeposit()
  {
    $data = Event::where('status',1)->first();
    $data2 =  Event::where('event_name',$data->event_name)->select('id')->get();
    return Transactions::where('cashier_id',auth()->user()->id)->whereIn('event_id',$data2)->where('type','Deposit')->sum('amount');
  }
  public function gettotalunclaimed()
  {
    $data = Event::where('status',1)->first();
    $data2 =  Event::where('event_name',$data->event_name)->select('id')->get();
    return potmoney::whereIn('event_id',$data2)->sum('remaining');
  }
  public function getplayers()
  {
    return User::where('role',3)->where('group_id',auth()->user()->group_id)->get();
  }
  public function getpendings(Request $req)
  {
    return pending::where('event_id',$req['id'])->where('confirmed',0)->where('active',1)->latest()->get();
  }
  public function getpendingtransactions()
  {
    return pending::where('user_id',auth()->user()->id)->where('confirmed',0)->where('active',1)->latest()->get();
  }
  public function withdrawaluser(Request $req)
  {
    $this->validate($req, [
      'amount'=>'required|int',
      'passwords'=>'required',
      'details'=>'required',
    ]);

    $check = User::findOrFail(auth()->user()->id);
    $data = Event::where('status',1)->first();
    $checkpending = pending::where('user_id',auth()->user()->id)->where('active',1)->where('confirmed',0)->first();
    if (Hash::check($req['passwords'], $check->password) && !$checkpending) {
      $new = new Pending();
      $new->amount = $req['amount'];
      $new->details = $req['details'];
      $new->confirmed = 0;
      $new->user_id = auth()->user()->id;
      $new->event_id = $data->id;
      if ($check->cash >= $req['amount']) {
        $new->save();
        $createlogs = new Logs();
        $createlogs->type = 'Request_Withdrawal';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = auth()->user()->username.' requested to withdraw '.$req['amount'];
        $createlogs->save();
        return ['msg'=>'Withdrawal is on process please wait..','status'=>'success'];
      }else{
        $createlogs = new Logs();
        $createlogs->type = 'Request_Withdrawal';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = auth()->user()->username.' requested to withdraw '.$req['amount'].', but failed because cash not sufficient';
        $createlogs->save();
        return ['msg'=>'Cash not sufficient..','status'=>'error'];
      }
    }else {
      return ['msg'=>'You have already requested a pending withdrawal','status'=>'error'];
    }

  }
  public function getbarcodex(Request $req)
  {
    $this->validate($req, [
      'barcode'=>'required',
    ]);
    $getbet=expertbet::where('barcode',$req['barcode'])->first();
    $getpotmoney=Potmoney::where('startingfight',$getbet->startingfight)->where('event_id',$getbet->event_id)->first();

    if ($getpotmoney->claim===2) {
      return expertbet::where('barcode',$req['barcode'])->first();
    }else {
      // return error;
    }

    // return bet::where('barcode',$req['barcode'])->first();
  }
  public function barcodewin(Request $req)
  {
    $this->validate($req, [
      'barcode'=>'required',
    ]);

    $getbet=expertbet::where('barcode',$req['barcode'])->first();
    if ($getbet->winner===1||$getbet->winner===2) {
      return Event::where('id',$getbet->event_id)->first();
    }



  }
  public function withdrawal(Request $req)
  {
    $this->validate($req, [
      'barcode'=>'required',
    ]);

    $getbet=expertbet::where('barcode',$req['barcode'])->first();

    $user = User::findOrFail(auth()->user()->id);


    if ($getbet->claimed===null) {

      $getbet->claimed=1;
     $data = Event::where('id',$getbet->event_id)->first();
     if ($data->status==1) {
       // code...
     }else {
       return error;
     }

     // $getpotmoney=Potmoney::where('event_id',$data->id)->first();
      //$getpotmoney->remaining=$getpotmoney->remaining-$getpotmoney->payout;


      $makewithdrawal = new Transactions();
      $makewithdrawal->type='Withdrawal';
      $makewithdrawal->user_id=auth()->user()->id;
      $makewithdrawal->cashier_id=auth()->user()->id;
      $makewithdrawal->startingbalancecashier=auth()->user()->cash;
      $makewithdrawal->startingbalance=auth()->user()->cash;
      $makewithdrawal->endingbalance=auth()->user()->cash-$getbet->result;
      $makewithdrawal->endingbalancecashier=auth()->user()->cash-$getbet->result;
      $makewithdrawal->event_id=$data->id;
      $makewithdrawal->barcode=$req['barcode'];
      $makewithdrawal->amount=$getbet->result;
      $makewithdrawal->startingfight=$getbet->startingfight;
      $createlogs = new Logs();
      $createlogs->type = 'Withdrawal';
      $createlogs->user_id = auth()->user()->id;
      $createlogs->message = "Barcode : ".$req['barcode']."\nAmount : ".$getbet->result."\nEvent Name : ".$data->event_name."\nEvent Id : ".$data->id."\nFight Date : ".date('F j, Y, g:i a', strtotime($data->fightdate));
      $createlogs->save();


      $check = $user->cash - $makewithdrawal->amount;
      $user->cash = $user->cash - $makewithdrawal->amount;
      if ($check>=0) {
          //$getpotmoney->save();
        $makewithdrawal->save();
        $getbet->save();
        $user->save();
        broadcast(new userupdate(auth()->user()->id));
      }else {
        return error;
      }




    }else{
      return error;
    }

  }
  public function prebets(Request $req)
  {
    $this->validate($req, [
      'barcode'=>'required',
    ]);

    $getbet=expertbet::where('barcode',$req['barcode'])->first();
    $getprebets=selection::where('expertbet_id',$getbet->id)->where('event_id',$getbet->event_id)->get();
    return $getprebets;


  }
}
