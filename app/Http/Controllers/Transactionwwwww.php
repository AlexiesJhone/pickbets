<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Support\Arr;
use App\Models\Results;
use App\Events\userupdate;
use App\Models\Event;
use App\Models\Prebet;
use App\Models\bet;
use App\Models\Logs;
use App\Models\User;
use App\Models\control;
use App\Models\Potmoney;
use App\Models\expertbet;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class Transaction extends Controller
{
  public function search(Request $req)
  {
    // $this->validate($req, [
    //   'amount'=>'required|int',
    //   'passwords'=>'required',
    // ]);
    return $getuser = User::with('group')
    ->Where('username', 'like', '%' . $req['username'] . '%')
    ->Where('name', 'like', '%' . $req['name'] . '%')
    ->Where('pnumber', 'like', '%' . $req['phone'] . '%')
    ->Where('group_id', 'like', '%' . $req['group'] . '%')
    ->Where('id', 'like', '%' . $req['id'] . '%')
    ->paginate(10);
  }
  public function checkpassword(Request $req)
  {
    $this->validate($req, [
      'amount'=>'required|int',
      'passwords'=>'required',
    ]);
    $getuser = User::findOrFail(auth()->user()->id);
    if (Hash::check($req['passwords'], $getuser->password)) {
      return $req;
    }else {
      return error;
    }
  }
  public function transferrollback(Request $req)
  {
    return DB::transaction(function () use($req){
    $getuser = User::findOrFail($req['id']);
    $getuser->cash = $getuser->cash+$req['amount'];
    $getuser->save();
    $getactiveevent = Event::where('status',1)->first();
    $transferfundstransact = new Transactions();
    $transferfundstransact->type = 'Transfer Failed [Rollback]';
    $transferfundstransact->amount = $req['amount'];
    $transferfundstransact->user_id = $req['id'];
    $transferfundstransact->event_id = $getactiveevent->id;
    $transferfundstransact->startingbalance = $getuser->cash-$req['amount'];
    $transferfundstransact->barcode = $req['id'];
    $transferfundstransact->startingbalancecashier = 0;
    $transferfundstransact->endingbalancecashier = 0;
    $transferfundstransact->endingbalance = $getuser->cash;
    $transferfundstransact->save();
    return ['cash'=>  $req['amount']];
    });
  }
  public function testinglangnaman(Request $req)
  {
    $this->validate($req, [
      'amount'=>'required|int',
      'passwords'=>'required',
    ]);
        $getuser = User::findOrFail($req['id']);
        $total = $getuser->cash - $req['amount'];
      if (Hash::check($req['passwords'], $getuser->password)) {
        if ($total>=0) {
          return DB::transaction(function () use($req,$getuser,$total){
          $getuser->cash = $total;
          $getuser->save();
          $getactiveevent = Event::where('status',1)->first();
          $transferfundstransact = new Transactions();
          $transferfundstransact->type = 'Transfered to pitlive.ph';
          $transferfundstransact->amount = $req['amount'];
          $transferfundstransact->user_id = $req['id'];
          $transferfundstransact->event_id = $getactiveevent->id;
          $transferfundstransact->startingbalance = $getuser->cash+$req['amount'];
          $transferfundstransact->barcode = $req['id'];
          $transferfundstransact->startingbalancecashier = 0;
          $transferfundstransact->endingbalancecashier = 0;
          $transferfundstransact->endingbalance = $total;
          $transferfundstransact->save();
          broadcast(new userupdate($req['id']));
          return ['cash'=>  $req['amount']];
        });
        }else {
          return ['error'=>'Unsufficient Balance'];
        }
      }else {
        return ['error'=>'Password not match'];
      }
  }
  public function checkeventdetails(Request $req)
  {
      $event = Event::where('id',$req['event_id'])->first();
      $events = Event::where('event_name',$event->event_name)->get();
      $finalevents = array();
      foreach ($events  as $key) {
        array_push($finalevents,$key->id);
      }
      // $cashierorplayer = User::where('id',$req['id'])->first();
      $bets = expertbet::with('user')->where('user_id',$req['id'])->whereIn('event_id',$finalevents)->latest()->paginate(10);
      // if ($cashierorplayer->role==3) {
      //   $transactions = Transactions::with('user')->where('user_id',$req['id'])->whereIn('event_id',$finalevents)->latest()->get();
      // }elseif ($cashierorplayer->role==0||$cashierorplayer->role==4) {
      //   $transactions = Transactions::with('user')->where('cashier_id',$req['id'])->whereIn('event_id',$finalevents)->latest()->get();
      // }
      // $a = array();
      // $allbets = array();
      // $alltransactions = array();
      // $alltransactionscashier = array();
      // foreach ($bets as $key) {
      //   array_push($allbets,$key);
      // }
      // foreach ($transactions as $key) {
      //   $cashier = User::where('id',$key->cashier_id)->first();
      //   $group = User::with('group')->where('id',$key->user_id)->first();
      //   $user = User::where('id',$key->user_id)->first();
      //   array_push($alltransactions,array('created_at'=>$key->created_at,'type'=>$key->type,'amount'=>$key->amount,'cashier'=>$cashier->username,'group'=>$group->group->name,'user'=>$user->username,'barcode'=>$key->barcode,'startingbalancecashier'=>$key->startingbalancecashier,
      // 'endingbalancecashier'=>$key->endingbalancecashier,'startingbalance'=>$key->startingbalance,'endingbalance'=>$key->endingbalance,'userrole'=>$key->user->role));
      // }
      //
      // array_push($a,array('bets'=>$bets,'transactions'=>$alltransactions));
      return $bets;
  }
  public function checkeventdetails2(Request $req)
  {
      $event = Event::where('id',$req['event_id'])->first();
      $events = Event::where('event_name',$event->event_name)->get();
      $finalevents = array();
      foreach ($events  as $key) {
        array_push($finalevents,$key->id);
      }
      $cashierorplayer = User::where('id',$req['id'])->first();
      // $bets = expertbet::with('user')->where('user_id',$req['id'])->whereIn('event_id',$finalevents)->latest()->get();
      if ($cashierorplayer->role==3) {
        $transactions = Transactions::with('user')->where('user_id',$req['id'])->whereIn('event_id',$finalevents)->latest()->paginate(11);
      }elseif ($cashierorplayer->role==0||$cashierorplayer->role==4) {
        $transactions = Transactions::with('user')->where('cashier_id',$req['id'])->whereIn('event_id',$finalevents)->latest()->paginate(11);
      }
      $a = array();
      $alltransactions = array();
      $alltransactionscashier = array();
      foreach ($transactions as $key) {
        $cashier = User::where('id',$key->cashier_id)->first();
        $group = User::with('group')->where('id',$key->user_id)->first();
        $user = User::where('id',$key->user_id)->first();
        array_push($alltransactions,array('created_at'=>$key->created_at,'type'=>$key->type,'amount'=>$key->amount,'cashier'=>$cashier->username,'group'=>$group->group->name,'user'=>$user->username,'barcode'=>$key->barcode,'startingbalancecashier'=>$key->startingbalancecashier,
      'endingbalancecashier'=>$key->endingbalancecashier,'startingbalance'=>$key->startingbalance,'endingbalance'=>$key->endingbalance,'userrole'=>$key->user->role));
      }

      // array_push($a,array('bets'=>$bets,'transactions'=>$alltransactions));
      $dataxx = $this->paginate($alltransactions);
      return $dataxx;
  }
  public function geteventsdatailed(Request $req)
  {
      // $data = Event::whereHas('transactions', function($q) use ($req)
      // {
      //   $q->where('amount','>', 0)->where('user_id','=',$req['id']);
      //
      // })->latest()->get();
      // $a = array();
      // foreach ($data as $key) {
      //   array_push($a,array('id' => $key->id,'event_name' => $key->event_name,'fightdate' => $key->fightdate,'fights' => $key->fights,'status' => $key->status, ));
      // }
      return Event::groupBy('event_name')->latest()->get();
  }
  public function getuserdatailed(Request $req)
  {
      $getuser = User::with('group')->where('id',$req['id'])->first();
      $a = array();

      array_push($a,array('id' => $getuser->id,'username' => $getuser->username,'name' => $getuser->name,'groupname' => $getuser->group->name,'cash' => $getuser->cash, ));

      return $a;
  }
  public function gettransactions(Request $req)
  {

      $event = Event::where('event_name',$req['event_name'])->get();
      $array = array();
      foreach ($event as $key) {
        array_push($array,$key->id);
      }
      $getuser = User::where('id',$req['id'])->first();
      $gettransactions = Transactions::with('user')->whereIn('event_id',$array)->where('cashier_id',$req['id'])->latest()->get();
      $gettransactions2 = array();
      foreach ($gettransactions as $key) {
        $formated = Carbon::parse($key->created_at)->format('Y-m-d H:i');
        array_push($gettransactions2,array('amount'=>$key->amount,'barcode'=>$key->barcode,'amount'=>$key->amount,'cashier_id'=>$key->cashier_id,'created_at'=>$formated,'endingbalance'=>$key->endingbalance,'event_id'=>$key->event_id,'id'=>$key->id,'startingbalance'=>$key->startingbalance,'startingfight'=>$key->startingfight,
      'type'=>$key->type,'user_id'=>$key->user_id,'user'=>$key->user,'startingbalancecashier'=>$key->startingbalancecashier,'endingbalancecashier'=>$key->endingbalancecashier,));
      }
      $getbets = expertbet::whereIn('event_id',$array)->where('user_id',$req->id)->latest()->get();
      $a = array();
      $totalwithdraw =array();
      $totaldeposit =array();
      $totalbets =array();
      foreach ($gettransactions as $key){
        if ($key->type === 'Withdrawal') {
          array_push($totalwithdraw, $key->amount);
        }
        if ($key->type === 'Deposit') {
          array_push($totaldeposit, $key->amount);
        }
      }
      foreach ($getbets as $key) {
        array_push($totalbets, $key->amount);
      }

      $totalbet = array_sum($totalbets);
      $totalwithdrawal = array_sum($totalwithdraw);
      $totaldeposit = array_sum($totaldeposit);
      array_push($a, array('id'=>$getuser->id,'username'=>$getuser->username,'name'=>$getuser->name,'role'=>$getuser->role, 'cash'=>$getuser->cash,'transactions'=>$gettransactions2,'bets'=>$getbets,'totalw' => $totalwithdrawal,'totald' => $totaldeposit,'totalbets'=>$totalbet));
      // $aa = collect($a)
      $dataxx = $this->paginate($a);
      return $dataxx;
  }
  public function forcecashout(Request $req)
  {
      $event = Event::where('status', 1)->first();
      $getuser = User::findOrFail($req['id']);
      $newtransaction = new Transactions();
      $newtransaction->type = 'Cash Out';
      $newtransaction->amount = $getuser->cash;
      $newtransaction->barcode = auth()->user()->id;
      $newtransaction->user_id = $getuser->id;
      $newtransaction->cashier_id = $getuser->id;
      $newtransaction->event_id = $event->id;
      $newtransaction->startingbalance = $getuser->cash;
      $newtransaction->startingbalancecashier = $getuser->cash;
      $newtransaction->endingbalance = $getuser->cash-$getuser->cash;
      $newtransaction->endingbalancecashier = 0;
      $newtransaction->save();

      $getuser->cash = 0;
      $getuser->save();
      $createlogs = new Logs();
      $createlogs->type = 'Forced_Cash_Out';
      $createlogs->user_id = auth()->user()->id;
      $createlogs->cashier_id = $getuser->id;
      $createlogs->message = 'Admin forced cash out '.$newtransaction->amount.' of '.$getuser->name;
      $createlogs->save();
      broadcast(new userupdate($getuser->id));
  }
  public function getusersfrommonitoring(Request $req)
  {
    $getevent = Event::where('event_name',$req['event_name'])->get();
    $array = array();
    foreach ($getevent as $key) {
      array_push($array,$key->id);
    }
    $data = User::with('group')->where('role','!=',1)->where('role','!=',2)->where('role','!=',3)->whereHas('transactions', function($q) use ($req,$array)
    {
      $q->where('amount','>', 0)->whereIn('event_id',$array);

    })->latest()->get();
    $a = array();
    $datein = null;
    $dateout = null;
    foreach ($data as $key) {
      $datein = null;
      $dateout = null;
      $in = Transactions::whereIn('event_id',$array)->where('cashier_id',$key->id)->where('type','Cash In')->orderby('created_at','asc')->first();
      $out = Transactions::whereIn('event_id',$array)->where('cashier_id',$key->id)->where('type','Cash Out')->orderby('created_at','asc')->first();
      if($in && $in->type==='Cash In'){
        $createdAt = Carbon::parse($in->created_at);
        $createdAt->format('ll');
        $datein = Carbon::parse($key->created_at)->format('Y-m-d H:i');
      }
      if($out && $out->type==='Cash Out'){
        $createdAt = Carbon::parse($out->created_at);

        $createdAt->format('ll');
        $dateout = Carbon::parse($key->created_at)->format('Y-m-d H:i');
      }
      array_push($a, array('username'=>$key->username,'id'=>$key->id,'name'=>$key->name,'group'=>$key->group,'cash'=>$key->cash,'cashin'=>$datein,'cashout'=>$dateout,));
    }
    return $a;
  }
  public function getmycash()
  {
    return User::where('id',auth()->user()->id)->first();
  }
  public function topwithdrawals()
  {
    $event = Event::where('status', 1)->first();
    $toptellercashiers = User::with('group')->where('role','!=',1)->where('role','!=',2)->where('role','!=',3)->where('role','!=',0)->get();
    $gatheredamount = array();
    $a = array();
    $b = array();
    foreach ($toptellercashiers as $key) {
      unset($gatheredamount);
      $gatheredamount = array();
      $gettransactions = Transactions::where('cashier_id',$key->id)->where('event_id',$event->id)->where('type','Withdrawal')->get();
      foreach ($gettransactions as $keys) {
        array_push($gatheredamount, $keys->amount);
      }
      $total=array_sum($gatheredamount);
      if ($total>0) {
        array_push($a,array('total'=>$total,'name'=>$key->name,'username'=>$key->username,'group'=>$key->group,));
      }
    }
    return $a;
  }
  public function toploaders()
  {
    $event = Event::where('status', 1)->first();
    $toptellercashiers = User::with('group')->where('role','!=',1)->where('role','!=',2)->where('role','!=',3)->where('role','!=',0)->get();
    $gatheredamount = array();
    $a = array();
    $b = array();
    foreach ($toptellercashiers as $key) {
      unset($gatheredamount);
      $gatheredamount = array();
      $gettransactions = Transactions::where('cashier_id',$key->id)->where('event_id',$event->id)->where('type','Deposit')->get();
      foreach ($gettransactions as $keys) {
        array_push($gatheredamount, $keys->amount);
      }
      $total=array_sum($gatheredamount);
      if ($total>0) {
        array_push($a,array('total'=>$total,'name'=>$key->name,'username'=>$key->username,'group'=>$key->group,));
      }
    }
    return $a;
  }
  public function toptellercashier()
  {
    $toptellercashiers = User::with('group')->where('cash','>=',50000)->where('role','!=',1)->where('role','!=',2)->where('role','!=',3)->orderBy('cash', 'DESC')->get();
    return $toptellercashiers;
  }
  public function topplayerss()
  {
    $topplayers = User::with('group')->where('cash','>',0)->where('role',3)->orderBy('cash', 'DESC')->get();
    return $topplayers;
  }
  public function spotcheck()
  {
    $event = Event::where('status', 1)->first();
    $getallusers = User::with('transactions')->get();
    $getallbets = bet::with('user')->where('event_id',$event->id)->get();
    $getallpayout = bet::with('user')->where('event_id',$event->id)->where('winner','!=',0)->where('winner','!=',3)->where('claimed',1)->get();
    $alltransactions = Transactions::where('event_id',$event->id)->get();
    $a = array();
    $gatherallbetamount = array();
    $userinitialaccount = array();
    $playercash = array();
    $cashiercash = array();
    $totalwithdrawal = array();
    $totaldeposit = array();
    $totalcashin = array();
    $totalteller = array();
    $totalpayout = array();

    foreach ($getallpayout as $key) {
      if ($key->user->role===0) {
        array_push($totalpayout,$key->result);
      }
    }
    foreach ($getallbets as $key) {
      $rake = $event->rake/100;
      $computedrake = $key->amount * $rake;
      array_push($gatherallbetamount,$computedrake);
    }
    foreach ($alltransactions as $key) {
      if ($key->type==='Withdrawal') {
        array_push($totalwithdrawal,$key->amount);
      }
      if ($key->type==='Deposit') {
        array_push($totaldeposit,$key->amount);
      }
      if ($key->type==='Cash In') {
        array_push($totalcashin,$key->amount);
      }
    }
    foreach ($getallusers as $key) {
      array_push($userinitialaccount,$key->cash);
      if ($key->role==3) {
        array_push($playercash,$key->cash);
      }
      if ($key->role==4||$key->role==0) {
        array_push($cashiercash,$key->cash);
      }
    }
    $totalc = array_sum($totalcashin);
    $totald = array_sum($totaldeposit);
    $totalw = array_sum($totalwithdrawal);
    $totalinitial = array_sum($userinitialaccount);
    $totalplayercash = array_sum($playercash);
    $totalcashiercash = array_sum($cashiercash);
    $totalrake = array_sum($gatherallbetamount);
    $totalpayoutteller = array_sum($totalpayout);
    array_push($a,array('totalinitial'=>$totalinitial,'totalplayercash'=>$totalplayercash,'totalcashiercash'=>$totalcashiercash,'totalwithdraw'=>$totalw,'totaldeposit'=>$totald,'totalcashin'=>$totalc,'totalrake'=>$totalrake,'totalpayout'=>$totalpayoutteller));
    return $a;
  }
  public function cashout()
  {
    $event = Event::where('status', 1)->first();
    $logs =new Logs();
    $logs->type = 'Cash_Out';
    $logs->user_id = auth()->user()->id;
    $logs->cashier_id = auth()->user()->id;
    $logs->message = auth()->user()->username.' Cash out '.substr(auth()->user()->cash, 0, -1);
    $logs->save();
     $newtransactions = new Transactions();
     $newtransactions->type = 'Cash Out';
     $newtransactions->amount = auth()->user()->cash;
     $newtransactions->barcode = auth()->user()->id;
     $newtransactions->user_id = auth()->user()->id;
     $newtransactions->cashier_id = auth()->user()->id;
     $newtransactions->event_id = $event->id;
     $newtransactions->startingbalancecashier = auth()->user()->cash;
     $newtransactions->startingbalance = auth()->user()->cash;
     $newtransactions->endingbalance = 0.000;
     $newtransactions->endingbalancecashier = 0.000;
     $newtransactions->save();
     $removecash = User::findOrFail(auth()->user()->id);
     $removecash->cash = 0;
     $removecash->save();

  }
  public function cashin(Request $req)
  {
    $this->validate($req, [
      'amount'=>'required|int',
    ]);
    $event = Event::where('status', 1)->first();
    $logs =new Logs();
    $logs->type = 'Cash_In';
    $logs->user_id = auth()->user()->id;
    $logs->cashier_id = auth()->user()->id;
    $logs->event_id = $event->id;
    $logs->message = auth()->user()->username.' Cash In '.substr($req['amount'], 0, -1);
    $logs->save();
    $event = Event::where('status', 1)->first();
    $cashin = new Transactions();
    $cashin->type = "Cash In";
    $cashin->amount = $req['amount'];
    $cashin->user_id = auth()->user()->id;
    $cashin->cashier_id = auth()->user()->id;
    $cashin->barcode = auth()->user()->id;
    $cashin->event_id = $event->id;
    $cashin->startingbalance = auth()->user()->cash;
    $cashin->startingbalancecashier = auth()->user()->cash;
    $cashin->endingbalance = auth()->user()->cash+ $req['amount'];
    $cashin->endingbalancecashier = auth()->user()->cash+ $req['amount'];
     $addcash = User::findOrFail(auth()->user()->id);
     $addcash->cash = auth()->user()->cash+ $req['amount'];
     $addcash->save();
    $cashin->save();
  }
  public function transtotal(Request $req)
  {

    $event = Event::where('event_name', $req['event_name'])->get();
    $array = array();
    foreach ($event as $key) {
      array_push($array,$key->id);
    }
    $data = Transactions::whereIn('event_id',$array)->get();

    $a = array();
    $withdrawal = array();
    $deposit = array();
    foreach ($data as $key) {
      $getuser = User::with('group')->where('id',$key->user_id)->first();
      $getcashier = User::where('id',$key->cashier_id)->first();
      if ($key->type==='Withdrawal') {
        array_push($withdrawal,$key->amount);
      }elseif ($key->type==='Deposit') {
        array_push($deposit,$key->amount);
      }
      $totalw = array_sum($withdrawal);
      $totald = array_sum($deposit);
      array_push($a,array('totalw'=>$totalw,'totald'=>$totald,));
    }

    return $a;
  }
  public function transmodal(Request $req)
  {
    $data = Event::where('event_name',$req['event_name'])->get();
    $array = array();
    foreach ($data as $key) {
      array_push($array,$key->id);
    }
    // $data = Transactions::with('user')->paginate(10);
    $data = Transactions::with('user')->whereIn('event_id',$array)->get();

    $a = array();
    $withdrawal = array();
    $deposit = array();
    foreach ($data as $key) {
      $getuser = User::with('group')->where('id',$key->user_id)->first();
      $getcashier = User::where('id',$key->cashier_id)->first();
      if ($key->type==='Withdrawal') {
        array_push($withdrawal,$key->amount);
      }elseif ($key->type==='Deposit') {
        array_push($deposit,$key->amount);
      }
      $totalw = array_sum($withdrawal);
      $totald = array_sum($deposit);
      array_push($a,array('id'=>$key->id,'type'=>$key->type,'amount'=>$key->amount,'barcode'=>$key->barcode,'startingfight'=>$key->startingfight,'user_id'=>$key->user_id,'cashier_id'=>$key->cashier_id,'event_id'=>$key->event_id,'startingbalance'=>$key->startingbalance,
      'endingbalance'=>$key->endingbalance,'created_at'=>$key->created_at,'updated_at'=>$key->updated_at,'cashier'=>$getcashier,'user'=>$getuser,'totalw'=>$totalw,'totald'=>$totald,));
    }
     $dataxx = $this->paginate($a);
    return $dataxx;
  }
  public function geteventswithtransactions(Request $req)
  {
    // $data = Event::with('transactions')->whereHas('transactions', function($q) use ($req)
    // {
    //   $q->where('amount','>', 0);
    //
    // })->latest()->get();
    // $data = Event::select(DB::raw('DISTINCT(event_name)'),'status')->paginate(10);
    if ($req['event_name']) {
      $data = Event::where('event_name',$req['event_name'])->select('event_name','status','fightdate','fights')->groupBy('event_name')->latest()->paginate(10);
    }else {
      $data = Event::select('event_name','status','fightdate','fights')->groupBy('event_name')->latest()->paginate(10);
    }


    // $a = array();
    // foreach ($data as $key) {
    //   $getuser = User::where('id',$key->user_id)->first();
    //   $getcashier = User::where('id',$key->cashier_id)->first();
    //   array_push($a,array('id'=>$key->id,'status'=>$key->status,'event_name'=>$key->event_name,'type'=>$key->type,'amount'=>$key->amount,'barcode'=>$key->barcode,'startingfight'=>$key->startingfight,'user_id'=>$key->user_id,'cashier_id'=>$key->cashier_id,'event_id'=>$key->event_id,
    //   'startingbalance'=>$key->startingbalance,
    //   'endingbalance'=>$key->endingbalance,'created_at'=>$key->created_at,'updated_at'=>$key->updated_at,'cashier'=>$getcashier,'user'=>$getuser,'transactions'=>$key->transactions));
    // }
    //  $dataxx = $this->paginate($a);
    return $data;
  }
  public function geteventsformonitoring(Request $req)
  {
    // $data = Event::with('transactions')->whereHas('transactions', function($q) use ($req)
    // {
    //   $q->where('amount','>', 0);
    //
    // })->latest()->get();
    // $data = Event::select('event_name','id','created_at','status')->distinct('event_name')->paginate(10);
    // $data = Event::select(DB::raw('DISTINCT(event_name)'),'status')->paginate(10);
    if ($req['event_name']) {
      return Event::where('event_name',$req['event_name'])->select('event_name','status','fightdate')->groupBy('event_name')->latest()->paginate(10);
    }else {
      return Event::select('event_name','status','fightdate')->groupBy('event_name')->latest()->paginate(10);
    }


    // $a = array();
    // foreach ($data as $key) {
    //   $getuser = User::where('id',$key->user_id)->first();
    //   $getcashier = User::where('id',$key->cashier_id)->first();
    //   array_push($a,array('id'=>$key->id,'status'=>$key->status,'event_name'=>$key->event_name,'type'=>$key->type,'amount'=>$key->amount,'barcode'=>$key->barcode,'startingfight'=>$key->startingfight,'user_id'=>$key->user_id,'cashier_id'=>$key->cashier_id,'event_id'=>$key->event_id,
    //   'startingbalance'=>$key->startingbalance,
    //   'endingbalance'=>$key->endingbalance,'created_at'=>$key->created_at,'updated_at'=>$key->updated_at,'cashier'=>$getcashier,'user'=>$getuser,'transactions'=>$key->transactions));
    // }
    //  $dataxx = $this->paginate($a);
  }
  public function eventgetusersreport(Request $req)
  {
    // $data = User::with('bet')->whereHas('bet', function($q) use ($req)
    // {
    //   $q->where('event_id','like', $req['id']);
    //
    // })->latest()->get();
    // $a=array();
    // $b=array();
    // $c=array();
    // $d=array();
    // $e=array();
    // $f=array();
    // foreach ($data as $key) {
    //   unset($b);
    //   $b = array();
    //   unset($c);
    //   $c = array();
    //   unset($d);
    //   $d = array();
    //   unset($e);
    //   $e = array();
    //   unset($f);
    //   $f = array();
    //   $getactualbet = bet::where('event_id', $req['id'])->where('user_id',$key->id)->get();
    //   foreach ($getactualbet as $keys) {
    //     array_push($f,$keys);
    //     $get = Event::where('id',$keys->event_id)->first();
    //     $get2 = bet::where('id',$keys->id)->first();
    //     if ($get2->result && $get2->claimed==1) {
    //       array_push($d,$get2->result);
    //     }elseif ($get2->result && $get2->claimed===null) {
    //       array_push($e,$get2->result);
    //     }
    //     $rake = $get->rake/100;
    //     $rakeofbet = $get->amount * $rake;
    //     array_push($b,$keys->amount);
    //     array_push($c,$rakeofbet);
    //   }
    //   $totalbets = array_sum($b);
    //   $totalrake = array_sum($c);
    //   $totalpayoutpaid = array_sum($d);
    //   $totalunclaimed = array_sum($e);
    //   $totalcountbets = count($f);
    //   array_push($a, array('id' => $key->id,'username' => $key->username,'role' => $key->role,'totalbets'=>$totalbets,'totalrake'=>$totalrake,'totalpayoutpaid'=>$totalpayoutpaid,'totalunclaimed' => $totalunclaimed,'totalcountbets' => $totalcountbets,));
    // }
    // return $a;
    $getevent = Event::where('id',$req['id'])->first();
    $events= Event::where('event_name',$getevent->event_name)->get();
    $arrays = array();
    foreach ($events as $key) {
      array_push($arrays, $key->id);
    }
    $control = control::first();
    $rake = $control->rake/100;
    $results = DB::select('select * from users where id = ?', [1]);
    // return $results;
    $sql = '`username`,sum(c.amount) AS totalbets, sum(c.amount * ?) AS totalrake,sum(c.result) AS totalpayoutpaid';
    $get = DB::table('users as a')
     ->join('expertbet as c', 'a.id', '=', 'c.user_id')
     ->whereIn('event_id',$arrays)
     ->selectRaw($sql,[$rake])
      // >select('a.startingfight','c.bet','c.amount','c.wins','c.winner','c.result','c.lose')
     // ->paginate(3);
     ->groupBy('a.username')
      ->get()
      ->unique('username');
      $array = array();
      foreach ($get as $key) {
        $getuser = User::where('username',$key->username)->first();
        $unclaimed = expertbet::where('user_id',$getuser->id)->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('claimed',null)->sum('result');
        $claimed = expertbet::where('user_id',$getuser->id)->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('claimed',1)->sum('result');
        array_push($array,array('username'=>$key->username,'totalbets'=>$key->totalbets,'totalpayoutpaid'=>$claimed,'totalrake'=>$key->totalrake,'totalunclaimed'=>$unclaimed));
      }
      $myCollectionObj = collect($array);

       $dataxx = $this->paginate($myCollectionObj);
     return $dataxx;
  }
  public function paginate($items, $perPage = 10, $page = null, $options = [])
   {
       $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
       $items = $items instanceof Collection ? $items : Collection::make($items);
       return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
   }
  public function betdetailereport(Request $req)
  {
  //   $Event = bet::with('potmoney')->with('user')->where('event_id',$req['id'])->where('user_id',$req['user_id'])->latest()->get();
  //   $a = array();
  //   foreach ($Event as $key) {
  //     $getevent = Event::where('id',$req['id'])->first();
  //     $rake = $getevent->rake/100;
  //     array_push($a,array('amount' => $key->amount,'barcode' => $key->barcode,'bet' => $key->bet,'claimed' => $key->claimed,'created_at'=> $key->created_at,'event_id' => $key->event_id,'id' => $key->id,
  //   'lose' => $key->lose,'potmoney_id' => $key->potmoney_id,'result' => $key->result,'startingfight' => $key->startingfight,'turn' => $key->turn,'updated_at' => $key->updated_at,'user' => $key->user,'user_id' => $key->user_id,
  // 'winner' => $key->winner,'wins' => $key->wins,'income'=>$key->amount*$rake));
  //   }

  $control = control::first();
  $rake = $control->rake/100;
  $event = Event::where('id',$req['id'])->first();
  $events = Event::where('event_name',$event->event_name)->select('id')->get();
  $sql = '`username`,`role`,(c.id) AS id,(amount * ?) AS income,`barcode`,`amount`,`bet`,`startingfight`,`claimed`,`result`,`winner`,`wins`,(c.created_at) AS created';
  $getuser = User::where('username',$req['user_id'])->first();
  $rake = $control->rake/100;
  $actualrake = $control->rake;
  // $get = expertbet::with('user')->where('user_id',$getuser->id)->selectRaw($sql,[$rake])->paginate(3);
  $get=DB::table('expertbet as c')
   ->join('users as a', 'c.user_id', '=', 'a.id')
   ->where('c.user_id',$getuser->id)
   ->whereIn('c.event_id',$events)
   ->selectRaw($sql,[$rake])
    // ->select('c.startingfight','c.bet','c.amount','c.wins','c.winner','c.result','c.lose',DB::raw('(c.amount*:rake) as rakes'))
   ->paginate(10);


    return $get;
  }
  public function arenareportsmodaltotal(Request $req)
  {
    $event = Event::where('event_name',$req['event_name'])->get();
    $array = array();
    foreach ($event as $key) {
      array_push($array, $key->id);
    }
    // return $array;
    $betcount = expertbet::whereIn('event_id',$array)->count();
    $totalpayout = expertbet::whereIn('event_id',$array)->where('winner','!=',0)->where('winner','!=',3)->where('claimed',1)->sum('result');
    $totalpayoutunclaimed = expertbet::whereIn('event_id',$array)->where('winner','!=',0)->where('winner','!=',3)->where('claimed',null)->sum('result');
    $pot = Potmoney::whereIn('event_id',$array)->sum('rake');
    $numberofbetsamount = Potmoney::whereIn('event_id',$array)->sum('amount');
    // $numberofbetsamount = array();
    // $rake = array();
    $rakefinal = null;
    $payout = array();
    $payoutunclaimed = array();
      // $rake = $event->rake/100;
      // $finalrake = $key->amount * $rake;
    $a = array();
    // foreach ($bet as $key) {
    //   array_push($numberofbetsamount, $key->amount);
    //   // if (!$rakefinal) {
    //   //   $rakefinal = $finalrake;
    //   // }else {
    //   //   $rakefinal = $rakefinal+$finalrake;
    //   // }
    //   if ($key->result&&$key->winner!=0&&$key->winner!=3&&$key->claimed==1) {
    //     array_push($payout, $key->result);
    //   }else{
    //     array_push($payoutunclaimed, $key->result);
    //   }
    // }
    // $numberofbets = count($bet);
    // $totalbetsamountfinal = array_sum($numberofbetsamount);
    // $totalrake = array_sum($rake);
    // $totalpayout = array_sum($payout);
    // $totalpayoutunclaimed = array_sum($payoutunclaimed);
    array_push($a, array('numberofbets'=> $betcount,'totalbetsamountfinal'=> $numberofbetsamount,'rakefinal'=> $pot,'totalpayout'=> $totalpayout,'totalpayoutunclaimed'=> $totalpayoutunclaimed,));
    return $a;
  }
  public function arenareportsmodal(Request $req)
  {
    $get = Event::where('event_name',$req->event_name)->get();
    $array = array();
    foreach ($get as $key) {
      array_push($array,$key->id);
    }
    $pot = Potmoney::whereIn('event_id',$array)->latest()->get();
    $a = array();
    foreach ($pot as $key) {
      $bet = expertbet::where('event_id',$key->event_id)->where('winner','!=',0)->where('winner','!=',3)->get();
      $count = expertbet::where('event_id',$key->event_id)->count();
      $count2 = expertbet::where('event_id',$key->event_id)->select('user_id')->groupBy('user_id')->get();
      $totalplayers = count($count2);
      $winnerscount = count($bet);
      // $betscount = count($bet);
      array_push($a,array('totalplayers'=>$totalplayers,'amount' => $key->amount,'claim' => $key->claim,'created_at' => $key->created_at,'event_id' => $key->event_id,'id' => $key->id,'payout' => $key->payout,'rake' => $key->rake,'remaining' => $key->remaining,
    'startingfight' => $key->startingfight,'updated_at' => $key->updated_at,'winners' => $winnerscount,'bet' => $count,));
    }
    return $a;
  }
  public function totalarenareports(Request $req)
  {
    $control = control::first();
    $payout = expertbet::where('event_id',$req['event_id'])->where('winner','!=',3)->where('winner','!=',0)->sum('result');
    $totalamountbet = expertbet::where('event_id',$req['event_id'])->sum('amount');
    $totalunclaimed = expertbet::where('event_id',$req['event_id'])->where('claimed',null)->where('winner','!=',0)->where('winner','!=',3)->sum('amount');
    $rake = $control->rake/100;
    $totalincome = $totalamountbet * $rake;
    // $totalunclaimed = 1;
    $b = array();
    array_push($b,array('totalincome'=>$totalincome,'totalamount'=>$totalamountbet,'totalpayout'=>$payout,'totalunclaimed'=>$totalunclaimed,));
    return $b;
  }
  public function betsofarenareports(Request $req)
  {
    $control = control::first();
    $rake = $control->rake / 100;
    $sql = '`barcode`,`startingfight`,`name`,(a.id) AS id,`role`,`amount`,`winner`,`result`,`claimed`,`wins`,(a.updated_at) AS updated_at,(a.created_at) AS created_at,`username`,(a.amount * ?) AS income';
    if ($req['username']) {
      $get=DB::table('expertbet as a')
       ->join('users as c', 'a.user_id', '=', 'c.id')
       ->where('event_id',$req['event_id'])
       ->where('username',$req['username'])
       ->selectRaw($sql,[$rake])
       ->paginate(10);
    }else {
      $get=DB::table('expertbet as a')
       ->join('users as c', 'a.user_id', '=', 'c.id')
       ->where('event_id',$req['event_id'])
       ->selectRaw($sql,[$rake])
       ->paginate(10);
    }

    // $get=DB::table('expertbet as a')
    //  ->join('users as c', 'a.user_id', '=', 'c.id')
    //  ->where('event_id',$req['event_id'])
    //  ->selectRaw($sql,[$rake])
    //   // >select('a.startingfight','c.bet','c.amount','c.wins','c.winner','c.result','c.lose')
    //  ->paginate(10);
    // $bet = expertbet::with('user')->where('event_id',$req['event_id'])->latest()->paginate(3);
    // $a = array();
    // foreach ($bet as $key) {
    //   $getpot = Event::where('id',$key->event_id)->first();
    //   $rake = $getpot->rake /100;
    //   $income = $key->amount * $rake;
    //   array_push($a,array('amount'=>$key->amount,'barcode'=>$key->barcode,'amount'=>$key->amount,'bet'=>$key->bet,'claimed'=>$key->claimed,'created_at'=>$key->created_at,'event_id'=>$key->event_id,'id'=>$key->id,'lose'=>$key->lose,
    // 'potmoney_id'=>$key->potmoney_id,'result'=>$key->result,'startingfight'=>$key->startingfight,'turn'=>$key->turn,'updated_at'=>$key->updated_at,'user_id'=>$key->user_id,'winner'=>$key->winner,'wins'=>$key->wins,'income'=>$income,'user'=>$key->user));
    // }
    return $get;
  }
  public function searchbetsofarenareports(Request $req)
  {
    $control = control::first();
    $rake = $control->rake / 100;
    $sql = '`barcode`,`startingfight`,`name`,(a.id) AS id,`role`,`amount`,`winner`,`result`,`claimed`,(a.updated_at) AS updated_at,(a.created_at) AS created_at,`username`,(a.amount * ?) AS income';
    if ($req['username']) {
      $get=DB::table('expertbet as a')
       ->join('users as c', 'a.user_id', '=', 'c.id')
       ->where('event_id',$req['event_id'])
       ->where('username',$req['username'])
       ->selectRaw($sql,[$rake])
       ->paginate(10);
    }else {
      $get=DB::table('expertbet as a')
       ->join('users as c', 'a.user_id', '=', 'c.id')
       ->where('event_id',$req['event_id'])
       ->selectRaw($sql,[$rake])
       ->paginate(10);
    }

    return $get;
  }
  public function geteventsreports()
  {
    // $Event = Event::select()latest()->paginate();
    // $sorted = $Event->unique('event_name');
    $data = Event::select('event_name','status','fightdate','created_at','fights','fightopened','fightclosed','id','venue')->groupBy('event_name')->latest()->paginate(10);
    return $data;
  }
  public function searchgeteventsreports(Request $req)
  {
    // $Event = Event::select()latest()->paginate();
    // $sorted = $Event->unique('event_name');
    $data = Event::where('event_name',$req['event_name'])->select('event_name','status','fightdate','created_at','fights','fightopened','fightclosed','id','venue')->groupBy('event_name')->latest()->paginate(10);
    return $data;
  }
  public function getalleventsreports()
  {
    // $Event = Event::select()latest()->paginate();
    // $sorted = $Event->unique('event_name');
    $data = Event::select('event_name','id')->groupBy('event_name')->latest()->get();
    return $data;
  }
  public function searchusertransaction(Request $req)
  {
    $getevent = Event::where('id',$req['id'])->first();
    $events= Event::where('event_name',$getevent->event_name)->get();
    $arrays = array();
    foreach ($events as $key) {
      array_push($arrays, $key->id);
    }
    $control = control::first();
    $rake = $control->rake/100;
    $results = DB::select('select * from users where id = ?', [1]);
    // return $results;
    $sql = '`username`,sum(c.amount) AS totalbets, sum(c.amount * ?) AS totalrake,sum(c.result) AS totalpayoutpaid';
    $get = DB::table('users as a')
     ->join('expertbet as c', 'a.id', '=', 'c.user_id')
     ->whereIn('event_id',$arrays)
     ->where('username',$req['username'])
     ->selectRaw($sql,[$rake])
      // >select('a.startingfight','c.bet','c.amount','c.wins','c.winner','c.result','c.lose')
     // ->paginate(3);
     ->groupBy('a.username')
      ->get()
      ->unique('username');
      $array = array();
      foreach ($get as $key) {
        $getuser = User::where('username',$key->username)->first();
        $unclaimed = expertbet::where('user_id',$getuser->id)->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('claimed',null)->sum('result');
        $claimed = expertbet::where('user_id',$getuser->id)->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('claimed',1)->sum('result');
        array_push($array,array('username'=>$key->username,'totalbets'=>$key->totalbets,'totalpayoutpaid'=>$claimed,'totalrake'=>$key->totalrake,'totalunclaimed'=>$unclaimed));
      }
      $myCollectionObj = collect($array);

       $dataxx = $this->paginate($myCollectionObj);
     return $dataxx;
  }
  public function searchusers()
  {
    // $Event = Event::select()latest()->paginate();
    // $sorted = $Event->unique('event_name');
    $data = User::where('role',3)->orWhere('role',0)->select('username','id')->get();
    return $data;
  }
  public function thismonthsale()
  {
    $monthly = potmoney::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)->sum('rake');
    return $monthly;
  }
  public function getdailysalesdata()
  {
    $a = array();
    $b = array();

    $hour = expertbet::with('event')->latest()->take(5000)->get()->groupBy(function($q) {
            return Carbon::parse($q->created_at)->format('d');
            // return Carbon::parse($date->created_at)->format('M/j/y g:i a');
          });
          $control = control::first();
          foreach ($hour as $key) {
            unset($a); // $foo is gone
            $a = array();
            foreach ($key as $keys) {
              array_push($a, $keys->amount);
            }
            // $get = Event::where('id',$keys->event_id)->first();
            $rake = $control->rake/100;
            $sum = array_sum($a);
            $earnings = $sum * $rake;
              array_push($b,array('x'=> Carbon::parse($key[0]->created_at)->format('M/d'),'y'=>$earnings));
          }

    return $b;
  }
  public function getdailysales()
  {
    $a = array();
    $b = array();
    $hour = expertbet::latest()->take(5000)->get()->groupBy(function($date) {

            return Carbon::parse($date->created_at)->format('d');
          });
          foreach ($hour as $key) {
            array_push($a, $key[0]->created_at->timestamp);
          }
          // foreach ($hour as $key) {
          //   array_push($a, $key[0]->created_at->timestamp);
          // }
    return $a;
  }
  public function totalcurrentpendingbets()
  {
    $get=Event::where('status',1)->get();
    $array = array();
    foreach ($get as $key) {
      array_push($array, $key->id);
    }
    $getbets = expertbet::whereIn('event_id',$array)->count();
    // $new = number_format($getbets);
    // $count = count($getbets);
    return $getbets;
  }
  public function totalcurrentrake()
  {
    $get=Event::where('status',1)->get();
    $array = array();
    foreach ($get as $key) {
      array_push($array, $key->id);
    }
    $getbets = Potmoney::whereIn('event_id',$array)->sum('rake');
    // $rake = $get->rake/100;
    // return $getbets * $rake;
    return $getbets;
  }
  public function totalpendingbetsreport()
  {
    $get=Event::where('status',1)->get();
    $array = array();
    foreach ($get as $key) {
      array_push($array, $key->id);
    }
    // $get = Event::where('status',1)->first();
    $getbets = expertbet::whereIn('event_id',$array)->where('winner',0)->count();
    // $count = count($getbets);
    return $getbets;
  }
  public function perhourdata()
  {
    $a = array();
    $b = array();

    $hour = expertbet::with('event')->latest()->take(5000)->get()->groupBy(function($q) {
            return Carbon::parse($q->created_at)->format('M/j ga');
            // return Carbon::parse($date->created_at)->format('M/j/y g:i a');
          });

          $control = control::first();
          foreach ($hour as $key) {
            unset($a); // $foo is gone
            $a = array();
            foreach ($key as $keys) {
              array_push($a, $keys->amount);
            }
            // $get = Event::where('id',$keys->event_id)->first();
            $rake = $control->rake/100;
            $sum = array_sum($a);
            $earnings = $sum * $rake;
              array_push($b,array('x'=> Carbon::parse($key[0]->created_at)->format('ga M/d'),'y'=>$earnings));
          }
          // latest()->limit(10)->

    return $b;
  }
  public function perhourbets()
  {
    $a = array();
    $hour = expertbet::latest()->take(5000)->get()->groupBy(function($date) {

            return Carbon::parse($date->created_at)->format('M/j ga');
          });
          foreach ($hour as $key => $value) {
              array_push($a, $key);
          }
          // latest()->limit(10)->
    return $a;
  }
  public function transactioncashier()
  {
    return view('transactioncashier');
  }
  public function newgetcashiertrans(Request $req)
  {
    return Transactions::with('event')->with('user')->where('cashier_id',auth()->user()->id)->where('event_id',$req['id'])->latest()->get();
  }
  public function geteventoftransactions()
  {
    $data = Event::with('transactions')->whereHas('transactions', function($q)
    {
      $q->where('cashier_id','like', auth()->user()->id);

    })->latest()->get();
    return $data;
  }
  public function getcashiertrans()
  {
    return Transactions::with('event')->with('user')->where('cashier_id',auth()->user()->id)->latest()->get();
  }
  public function getallrake()
  {
  //   $get = potmoney::get()->pluck('rake');
  //   $a=array();
  //   foreach ($get as $key) {
  //     array_push($a,floatval($key));
  //   }
  // return $a;
        // $data = Event::whereHas('potmoney', function($q)
        // {
        //   $q->where('rake','>', 0);
        //
        // })->get();
        // $get=DB::table('potmoney as c')
        //  // ->where('a.status',1)
        //  // ->unique('a.event_name')
        //  ->join('events as a', 'a.id', '=', 'c.event_id')
        //  // ->where('c.rake','>',0)
        //  ->get()->unique('a.event_name');
         $count=DB::table('events as a')
          ->get()->unique('event_name')->count();
          $total = $count - 5;

          if ($total>=1) {
            $totals = $total;
          }else {
            $totals = 0;
          }
         $get=DB::table('events as a')
          ->get()->unique('event_name')->skip($totals)->take(10);
          // $translated = $get->unique('event_name');
         // return $get;
        // $get = Event::with('potmoney')->where('status',1)->get();
        // return $get->toArray();
          $a=array();
          foreach ($get as $key) {
            array_push($a, $key->event_name);
          }
          return $a;

  }
  public function getrakedata()
  {
    // $data = Event::with('potmoney')->whereHas('potmoney', function($q)
    // {
    //   $q->where('rake','>', 0);
    //
    // })->get();
    $count=DB::table('events as a')
     ->get()->unique('event_name')->count();
     $total = $count - 5;

     if ($total>=1) {
       $totals = $total;
     }else {
       $totals = 0;
     }
    $get=DB::table('events as a')
     // ->where('a.status',1)
     // ->unique('a.event_name')
     // ->join('potmoney as c', 'a.id', '=', 'c.event_id')
     // ->where('c.rake','>',0)
     ->get()->unique('event_name')->skip($totals)->take(10);
    $a=array();
    $translated = $get->unique('event_name');
    foreach ($get as $key) {
      array_push($a, $key->totalrake);
    }
    // $number = floatval(array_sum($a));

    return $a;

  }
  public function totalbets()
  {
    $count=DB::table('events as a')
     ->get()->unique('event_name')->count();
     $total = $count - 5;

     if ($total>=1) {
       $totals = $total;
     }else {
       $totals = 0;
     }
    $get=DB::table('events as a')
     ->get()->unique('event_name')->skip($totals)->take(10);
    $a=array();
    $translated = $get->unique('event_name');
    foreach ($get as $key) {
      array_push($a, $key->event_name);
    }
    return $a;

  }
  public function totalbetsdata()
  {
    //  $a=array();
    //  $alex=DB::table('events')
    // ->join('expertbet', 'expertbet.event_id', '=', 'events.id')
    // ->groupBy('events.event_name')
    // ->get(['events.event_name', DB::raw('count(expertbet.id) as count')]);
    // $a=array();
    // $b=array();
    // foreach ($alex as $key) {
    //   unset($a);
    //   array_push($b,$key->count);
    // }
    //
    // return $b;
    $count=DB::table('events as a')
     ->get()->unique('event_name')->count();
     $total = $count - 5;

     if ($total>=1) {
       $totals = $total;
     }else {
       $totals = 0;
     }
    $get=DB::table('events as a')
     ->get()->unique('event_name')->skip($totals)->take(10);
    $a=array();
    $translated = $get->unique('event_name');
    foreach ($get as $key) {
      $check = Event::where('event_name',$key->event_name)->get();
      $events = array();
      foreach ($check as $keys) {
        array_push($events,$keys->id);
      }
      $bet = expertbet::whereIn('event_id',$events)->count();
      array_push($a, $bet);
    }
    return $a;

  }
}
