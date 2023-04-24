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
use App\Models\Group;
use App\Models\Potmoney;
use App\Models\expertbet;
use App\Models\past_expertbet;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exports\derbyexport;
use App\Exports\derbyexporttransaction;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

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
      if ($event->status==2) {
        // code...
        $bets = past_expertbet::with('user')->where('user_id',$req['id'])->whereIn('event_id',$finalevents)->latest()->paginate(10);
      }else {
        $bets = expertbet::with('user')->where('user_id',$req['id'])->whereIn('event_id',$finalevents)->latest()->paginate(10);
      }
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
  public function checkeventdetailssummary()
  {
      // $event = Event::where('status',1)->first();
      $events = Event::where('status',1)->get();
      $finalevents = array();
      foreach ($events  as $key) {
        array_push($finalevents,$key->id);
      }
      $bets = expertbet::with('user')->where('user_id',auth()->user()->id)->whereIn('event_id',$finalevents)

      ->latest()->paginate(10);
      return $bets;
  }
  public function getbetonly()
  {
      // $event = Event::where('status',1)->first();
      $events = Event::where('status',1)->get();
      $finalevents = array();
      foreach ($events  as $key) {
        array_push($finalevents,$key->id);
      }
      $bets = expertbet::with('user')->where('user_id',auth()->user()->id)->whereIn('event_id',$finalevents)

      ->latest()->get();
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
        $transactions = Transactions::with('user')->where('user_id',$req['id'])->whereIn('event_id',$finalevents)->latest()->paginate(10);
      }elseif ($cashierorplayer->role==9||$cashierorplayer->role==4) {
        $transactions = Transactions::with('user')->where('cashier_id',$req['id'])->whereIn('event_id',$finalevents)->latest()->paginate(10);
      }
      $a = array();
      $alltransactions = array();
      $alltransactionscashier = array();
      foreach ($transactions as $key) {
        $cashier = User::where('id',$key->cashier_id)->first();
        $group = User::with('group')->where('id',$key->user_id)->first();
        $user = User::where('id',$key->user_id)->first();
        array_push($alltransactions,array('id'=>$cashier->id,'created_at'=>$key->created_at,'type'=>$key->type,'amount'=>$key->amount,'cashier'=>$cashier->username,'group'=>$group->group->name,'user'=>$user->username,'barcode'=>$key->barcode,'startingbalancecashier'=>$key->startingbalancecashier,
      'endingbalancecashier'=>$key->endingbalancecashier,'startingbalance'=>$key->startingbalance,'endingbalance'=>$key->endingbalance,'userrole'=>$key->user->role));
      }

      // array_push($a,array('bets'=>$bets,'transactions'=>$alltransactions));
      $dataxx = $this->paginate($alltransactions);
      return $dataxx;
  }
  public function checkeventdetails2summary()
  {
      // $event = Event::where('id',$req['event_id'])->first();
      $events = Event::where('status',1)->get();
      $finalevents = array();
      foreach ($events  as $key) {
        array_push($finalevents,$key->id);
      }
      $cashierorplayer = User::where('id',auth()->user()->id)->first();
      // $bets = expertbet::with('user')->where('user_id',$req['id'])->whereIn('event_id',$finalevents)->latest()->get();
      if ($cashierorplayer->role==3) {
        $transactions = Transactions::with('user')->where('user_id',auth()->user()->id)->whereIn('event_id',$finalevents)->latest()->get();
      }elseif ($cashierorplayer->role==9||$cashierorplayer->role==4) {
        $transactions = Transactions::with('user')->where('cashier_id',auth()->user()->id)->whereIn('event_id',$finalevents)->whereNotIn('type', ['Withdrawal','Withdrawal_Cancelled'])->latest()->get();
        $totalcashin = Transactions::with('user')->where('cashier_id',auth()->user()->id)->whereIn('event_id',$finalevents)->where('type','Cash In')->sum('amount');
        $totalcashout = Transactions::with('user')->where('cashier_id',auth()->user()->id)->whereIn('event_id',$finalevents)->where('type','Cash Out')->sum('amount');
        $totalWithdrawal = Transactions::with('user')->where('cashier_id',auth()->user()->id)->whereIn('event_id',$finalevents)->where('type','Withdrawal')->sum('amount');
        $totalDeposit = Transactions::with('user')->where('cashier_id',auth()->user()->id)->whereIn('event_id',$finalevents)->where('type','Deposit')->sum('amount');
      }
      // return $transactions;
      $a = array();
      $alltransactions = array();
      $alltransactionscashier = array();
      // return $transactions = Transactions::with('user')->where('cashier_id',auth()->user()->id)->whereIn('event_id',$finalevents)->latest()->get();
      foreach ($transactions as $key) {
        $cashier = User::where('id',$key->cashier_id)->first();
        $group = User::with('group')->where('id',$key->user_id)->first();
        $user = User::where('id',$key->user_id)->first();
        array_push($alltransactions,array('id'=>$cashier->id,'created_at'=>$key->created_at,'type'=>$key->type,'amount'=>$key->amount,'cashier'=>$cashier->username,'group'=>$group->group->name,'user'=>$user->username,'barcode'=>$key->barcode,'startingbalancecashier'=>$key->startingbalancecashier,
      'endingbalancecashier'=>$key->endingbalancecashier,'startingbalance'=>$key->startingbalance,'endingbalance'=>$key->endingbalance,'userrole'=>$key->user->role,'totalcashin'=>$totalcashin,'totalcashout'=>$totalcashout,'totaldeposit'=>$totalDeposit,'totalwithdraw'=>$totalWithdrawal));
      }

      // array_push($a,array('bets'=>$bets,'transactions'=>$alltransactions));
      $dataxx = $this->paginate($alltransactions);
      return $alltransactions;
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
      return Event::groupBy('event_name')
      ->whereHas('expertbet', function($q) use ($req)
      {
        $q->where('user_id', $req['id']);

      })
      ->orWhereHas('transactions', function($q) use ($req)
      {
        $q->where('user_id', $req['id']);

      })
      ->latest()->get();
  }
  public function geteventsdatailedpage(Request $req)
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
      if ($req['event_id']) {
        $eventid = Event::where('id',$req['event_id'])->select('event_name')->first();
        $event = Event::where('event_name',$eventid->event_name)->get();

        $array = array();
        foreach ($event as $key) {
          array_push($array,$key->id);
        }
        // return $array;
        return Event::whereIn('id',$array)->groupBy('event_name')
        // ->whereHas('expertbet', function($q) use ($req)
        // {
        //   $q->where('user_id', $req['id']);
        //
        // })
        // ->orWhereHas('transactions', function($q) use ($req)
        // {
        //   $q->where('user_id', $req['id']);
        //
        // })
        ->latest()->paginate(10);
      }else {
        return Event::groupBy('event_name')->whereHas('expertbet', function($q) use ($req)
        {
          $q->where('user_id', $req['id']);

        })
        ->orWhereHas('transactions', function($q) use ($req)
        {
          $q->where('user_id', $req['id']);

        })
        ->orWhereHas('past_expertbet', function($q) use ($req)
        {
          $q->where('user_id', $req['id']);

        })
        ->latest()->paginate(10);
      }

  }
  public function getuserdatailed(Request $req)
  {
      $getuser = User::with('group')->where('id',$req['id'])->first();
      $a = array();

      array_push($a,array('id' => $getuser->id,'username' => $getuser->username,'name' => $getuser->name,'groupname' => $getuser->group->name,'cash' => $getuser->cash, ));

      return $a;
  }
  public function downloadmonitoring($id1,$id2)
  {
      $user = User::where('id',$id1)->first();
      return Excel::download(new derbyexport($id1,$id2), 'All_Bets_From_'.$user->username.'.xlsx');
  }
  public function downloadusertransaction($id1,$id2)
  {
    $user = User::where('id',$id1)->first();
      return Excel::download(new derbyexporttransaction($id1,$id2), 'All_Transaction_From_'.$user->username.'.xlsx');
  }
  public function gettransactions(Request $req)
  {

      $event = Event::where('event_name',$req['event_name'])->get();
      $event1 = Event::where('event_name',$req['event_name'])->first();
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
      if ($event1->status==2) {
        $getbets = past_expertbet::whereIn('event_id',$array)->where('user_id',$req->id)->latest()->get();
      }else {
        $getbets = expertbet::whereIn('event_id',$array)->where('user_id',$req->id)->latest()->get();
      }
      $a = array();
      $totalwithdraw =array();
      $totaldeposit =array();
      $totalbets =array();
      $totalwithdrawmobile =array();
      $totalcashin =array();
      $totalcashout =array();
      foreach ($gettransactions as $key){
        if ($key->type === 'Withdrawal') {
          array_push($totalwithdraw, $key->amount);
        }
        if ($key->type === 'Deposit') {
          array_push($totaldeposit, $key->amount);
        }
        if ($key->type === 'Withdrawal_Mobile') {
          array_push($totalwithdrawmobile, $key->amount);
        }
        if ($key->type === 'Cash In') {
          array_push($totalcashin, $key->amount);
        }
        if ($key->type === 'Cash Out') {
          array_push($totalcashout, $key->amount);
        }
      }
      foreach ($getbets as $key) {
        array_push($totalbets, $key->amount);
      }

      $totalbet = array_sum($totalbets);
      $totalwithdrawal = array_sum($totalwithdraw);
      $totaldeposit = array_sum($totaldeposit);
      $totalcashin = array_sum($totalcashin);
      $totalcashout = array_sum($totalcashout);
      $totalwithdrawalmobile = array_sum($totalwithdrawmobile);
      array_push($a, array('totalwithdrawmobile'=>$totalwithdrawalmobile,'cashin'=>$totalcashin,'cashout'=>$totalcashout,'id'=>$getuser->id,'username'=>$getuser->username,'name'=>$getuser->name,'role'=>$getuser->role, 'cash'=>$getuser->cash,'transactions'=>$gettransactions2,'bets'=>$getbets,'totalw' => $totalwithdrawal,'totald' => $totaldeposit,'totalbets'=>$totalbet));
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
      $createlogs->type = 'Forced_Cash-Out';
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
    $data = User::with('group')
    ->whereHas('transactions', function($q) use ($array)
    {
      $q->whereIn('event_id',$array);

    })
    ->orwhereHas('expertbet', function($q) use ($array)
    {
      $q->whereIn('event_id',$array);

    })
    ->orwhereHas('past_expertbet', function($q) use ($array)
    {
      $q->whereIn('event_id',$array);

    })
    ->whereIn('role',[9,4])
    // ->whereHas('transactions','expertbet')
    // {
    //   $q->where('amount','>', 0)->whereIn('event_id',$array);
    //
    // })
    ->latest()->get();
    $a = array();
    $datein = null;
    $dateout = null;
    foreach ($data as $key) {
      if ($key->role!=3) {
        // code...

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
      array_push($a, array('username'=>$key->username,'id'=>$key->id,'name'=>$key->name,'group'=>$key->group,'cash'=>$key->cash,'cashin'=>$datein,'cashout'=>$dateout,'active'=>$getevent[0]->status));
      }
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
    $event = Event::where('status', 1)->get();
    // $event = Event::where('event_name',$req['event_name'])->where('pick',20)->get();
    $control = control::first();
    $array = array();
    foreach ($event as $key) {
      array_push($array, $key->id);
    }
    $getallusers = User::with('transactions')->get();
    $getallbets = expertbet::with('user')->whereIn('event_id',$array)->get();
    $getallbetspick2 = expertbet::with('user')->whereIn('event_id',$array)->where('turn',2)->sum('amount');
    $getallbetspick20 = expertbet::with('user')->whereIn('event_id',$array)->where('turn',20)->sum('amount');
    $getallbetspick20amount = expertbet::with('user')->whereIn('event_id',$array)->where('turn',20)->sum('amount');
    $getallbetspick2amount = expertbet::with('user')->whereIn('event_id',$array)->where('turn',2)->where('winner','!=',4)->sum('amount');
    $getallpayout = expertbet::with('user')->whereIn('event_id',$array)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->where('claimed',1)->sum('result');
    // total payout ng pick 20
    $getallpayoutpick20 = expertbet::with('user')->whereIn('event_id',$array)->where('winner','!=',0)->where('winner','!=',3)->where('turn',20)->sum('result');
    // total payout ng pick 2
    $getallpayoutpick2 = expertbet::with('user')->whereIn('event_id',$array)->where('turn',2)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->sum('result');
    $getallpayoutpick2cancelled = expertbet::with('user')->whereIn('event_id',$array)->where('winner',4)->sum('result');
    $getallpayoutunclaimedpick2 = expertbet::with('user')->whereIn('event_id',$array)->where('winner',1)->where('turn',2)->where('claimed',null)->sum('result');
    $getallpayoutunclaimedpick2cancelled = expertbet::with('user')->whereIn('event_id',$array)->where('winner',4)->where('turn',2)->where('claimed',null)->sum('result');
    $getallpayoutunclaimedpick20 = expertbet::with('user')->whereIn('event_id',$array)->where('winner',1)->where('turn',20)->where('claimed',null)->sum('result');
    $getallcancelledpayoutclaimedpick20 = expertbet::with('user')->whereIn('event_id',$array)->where('winner',4)->where('turn',2)->where('claimed',1)->sum('result');
    $alltransactions = Transactions::whereIn('event_id',$array)->get();
    $fundspick2 = Potmoney::whereIn('event_id',$array)->where('pick',2)->sum('addtojackpot');
    $rakepick2 = Potmoney::whereIn('event_id',$array)->where('pick',2)->sum('rake');
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
    // total rake ng pick 20
    $pick20rake = array();
    // total rake ng pick 2
    $pick2rake = array();
    // total contingency funds
    $pick20funds = array();
    $pick2funds = array();
    $totalcancelledbetspick2 = array();

    // foreach ($getallpayout as $key) {
    //   if ($key->user->role===9) {
    //     array_push($totalpayout,$key->result);
    //   }
    // }
    foreach ($getallbets as $key) {
      if ($key->turn==20) {
        $rake = $control->rake/100;
        $contingencyfunds = $control->percentage_jackpot/100;
        $computedrake = $key->amount * $rake;
        $computedrake2 = $key->amount * $contingencyfunds;
        array_push($gatherallbetamount,$computedrake);
        array_push($pick20rake,$computedrake);
        array_push($pick20funds,$computedrake2);
      }else {
        if ($key->winner==4) {
          $computedrake = $key->amount;
          array_push($totalcancelledbetspick2,$computedrake);
        }else {
          $rake = $control->rakepick2/100;
          $computedrake = $key->amount * $rake;
          array_push($gatherallbetamount,$computedrake);
          array_push($pick2rake,$computedrake);
        }
      }
    }
    foreach ($alltransactions as $key) {
      if ($key->type==='Withdrawal_Mobile') {
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
      if ($key->role==4||$key->role==9) {
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
    // total rake ng pick 20
    $totalrakeofpick20 = array_sum($pick20rake);
    $totalrakeofpick2 = array_sum($pick2rake);
      // total contingency funds
    $totalfundsofpick20 = array_sum($pick20funds);
    $totalfundsofpick2 = array_sum($pick2funds);
    $totalngdalawangfunds = $totalfundsofpick20+$fundspick2;
    $totalcancelledpick2 = array_sum($totalcancelledbetspick2);
    $breakagecalc = $totalfundsofpick20+$totalrakeofpick20+$getallpayoutpick20;
    $breakageofpick20 = $getallbetspick20amount-$breakagecalc;
    $breakagecalc2 = $rakepick2 + $getallpayoutpick2;
    $breakageofpick2 = $getallbetspick2amount-$breakagecalc2-$fundspick2;
    $totalngbreakage = $breakageofpick2 + $breakageofpick20;
    // return $breakagecalc2;

    // $totalpayoutteller = array_sum($totalpayout);
    array_push($a,array('totalbreakage'=>$totalngbreakage, 'contingencyfunds'=>$totalngdalawangfunds,'breakageofpick2'=>$breakageofpick2,'breakageofpick20'=>$breakageofpick20,'totalunclaimedpick20'=>$getallpayoutunclaimedpick20,'totalunclaimedpick2'=>$getallpayoutunclaimedpick2,'totalbetspick2'=>$getallbetspick2amount,'totalbetspick20'=>$getallbetspick20,'totalinitial'=>$totalinitial,'totalplayercash'=>$totalplayercash,'totalcashiercash'=>$totalcashiercash,'totalwithdraw'=>$totalw,'totaldeposit'=>$totald,'totalcashin'=>$totalc,
    'totalrake'=>$totalrake,'totalpayout'=>$getallpayout,'totalcancelledpick2'=>$totalcancelledpick2,'cancelledpayoutpick2'=>$getallpayoutpick2cancelled,'uncalimedcancelledpick2'=>$getallpayoutunclaimedpick2cancelled,'totalcancelledpayoutclaimed'=>$getallcancelledpayoutclaimedpick20));
    return $a;
  }
  public function cashout()
  {
    $event = Event::where('status', 1)->first();
	if ($event) {
      // code...
    }else {
      return ['error'=>'You cannot cashout while there`s no active event!.'];
    }
    $logs =new Logs();
    $logs->type = 'Cash_Out';
    $logs->user_id = auth()->user()->id;
    $logs->cashier_id = auth()->user()->id;
    $logs->message = auth()->user()->username.' Cash out '.number_format(auth()->user()->cash, 2);
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
     $removecash->lock = null;
     $removecash->save();
     return view('summary');

  }
  public function cashouttemporary()
  {
    $events1= Event::where('status',1)->first();
    if ($events1) {
      $user = User::where('id',auth()->user()->id)->first();
      $user->lock = 1;
      $user->save();
    }else {
      return ['error'=>'You cannot cash out while there`s no active event'];
    }
  }
  public function restoresession(Request $req)
  {
    $this->validate($req, [
      'pin'=>'required'
    ]);
    $checkpin = User::where('pin',$req['pin'])->where('group_id',auth()->user()->group_id)->first();
    if ($checkpin) {
      $user = User::where('id',auth()->user()->id)->first();
      $user->lock = null;
      $user->save();
    }else {
      $logs = new Logs();
      $logs->type = 'Incorrect_Pin';
      $logs->user_id = auth()->user()->id;
      $logs->message = auth()->user()->username.' Invalid Pin.';
      $logs->save();
      return ['error'=>'Incorrect Pin'];
    }

  }
  public function cashin(Request $req)
  {
    $this->validate($req, [
      'amount'=>'required|int',
      'pin'=>'required'
    ]);
    $checkpin = User::where('pin',$req['pin'])->where('group_id',auth()->user()->group_id)->first();
    if (auth()->user()->lock) {
      // code...
      return ['error'=>'You Cannot Cash In.'];
    }else {
    }
    if ($checkpin) {
      // code...
    }else {
      $logs = new Logs();
      $logs->type = 'Incorrect_Pin';
      $logs->user_id = auth()->user()->id;
      $logs->message = auth()->user()->username.' Invalid Pin.';
      $logs->save();
      return ['error'=>'Incorrect Pin'];
    }
    $event = Event::where('status', 1)->first();
	if ($event) {
      // code...
    }else {
      return ['error'=>'There are no active event for now..'];
    }
    $logs =new Logs();
    $logs->type = 'Cash_In';
    $logs->user_id = auth()->user()->id;
    $logs->cashier_id = auth()->user()->id;
    $logs->event_id = $event->id;
    $logs->message = auth()->user()->username.' Cash In '.number_format($req['amount'], 2);
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
    $cashin = array();
    $cashout = array();
    foreach ($data as $key) {
      $getuser = User::with('group')->where('id',$key->user_id)->first();
      $getcashier = User::where('id',$key->cashier_id)->first();
      if ($key->type==='Withdrawal') {
        array_push($withdrawal,$key->amount);
      }elseif ($key->type==='Deposit') {
        array_push($deposit,$key->amount);
      }elseif ($key->type==='Cash In') {
        array_push($cashin,$key->amount);
      }elseif ($key->type==='Cash Out') {
        array_push($cashout,$key->amount);
      }
      $totalw = array_sum($withdrawal);
      $totald = array_sum($deposit);
      $totalcashin = array_sum($cashin);
      $totalcashout = array_sum($cashout);
      array_push($a,array('totalw'=>$totalw,'totald'=>$totald,'totalcashin'=>$totalcashin,'totalcashout'=>$totalcashout));
    }

    return $a;
  }
  public function transmodaldownloadall(Request $req)
  {
    $data = Event::where('event_name',$req['event_name'])->get();
    $array = array();
    foreach ($data as $key) {
      array_push($array,$key->id);
    }
    $get = DB::table('transactions as a')
    ->whereIn('a.event_id',$array)
    ->join('users as c', 'a.user_id', '=', 'c.id')
    ->join('users as d', 'a.cashier_id', '=', 'd.id')
    ->join('groups as e', 'd.group_id', '=', 'e.id')
    //->select('a.*','c.username AS name','d.username AS cashiername','e.name AS group')
		->select(DB::raw("a.*,c.username AS name,d.username AS cashiername,e.name AS groups,CASE WHEN (a.type = 'Deposit' OR a.type = 'Withdrawal_Mobile') THEN (c.username)
	WHEN (a.type = 'Withdrawal' OR a.type = 'Withdrawal_Cancelled') THEN (a.barcode)
	ELSE d.username END AS reciever"))
    ->get();
    return $get;
  }
  public function transmodal(Request $req)
  {
    $data = Event::where('event_name',$req['event_name'])->get();
    $array = array();
    foreach ($data as $key) {
      array_push($array,$key->id);
    }
    // $data = Transactions::with('user')->paginate(10);
    $data = Transactions::whereIn('event_id',$array)->paginate();
    $get = DB::table('transactions as a')
    ->whereIn('a.event_id',$array)
    ->join('users as c', 'a.user_id', '=', 'c.id')
    ->join('users as d', 'a.cashier_id', '=', 'd.id')
    ->join('groups as e', 'd.group_id', '=', 'e.id')
    //->select('a.*','c.username AS name','d.username AS cashiername','e.name AS group')
	->select(DB::raw("a.*,c.username AS name,d.username AS cashiername,e.name AS groups,CASE WHEN (a.type = 'Deposit' OR a.type = 'Withdrawal_Mobile') THEN (c.username)
	WHEN (a.type = 'Withdrawal' OR a.type = 'Withdrawal_Cancelled') THEN (a.barcode)
	ELSE d.username END AS reciever"))
    ->paginate(10);

    return $get;
    $a = array();
    $withdrawal = array();
    $deposit = array();
    foreach ($data as $key) {
      // foreach ($key->data as $key) {
      //   // code...
      // }
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
      $data = Event::where('event_name',$req['event_name'])->select('id','event_name','status','fightdate','fights','created_at')->groupBy('event_name')->latest()->paginate(10);
    }else {
      $data = Event::select('id','event_name','status','fightdate','fights','created_at')->groupBy('event_name')->latest()->paginate(10);
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
  public function totalsummary()
  {
    $events1= Event::where('status',1)->first();
    $events= Event::where('status',1)->get();
    $arrays = array();
    foreach ($events as $key) {
      array_push($arrays, $key->id);
    }
    $a = array();
    if (auth()->user()->role==9) {
      $getcashin = Transactions::where('user_id',auth()->user()->id)->whereIn('event_id',$arrays)->where('type','Cash In')->sum('amount');
      $getcashout = Transactions::where('user_id',auth()->user()->id)->whereIn('event_id',$arrays)->where('type','Cash Out')->latest()->first();
      $totalbetsamount = expertbet::whereIn('event_id',$arrays)->where('user_id',auth()->user()->id)->sum('amount');
      $totalbetsamountpick20 = expertbet::whereIn('event_id',$arrays)->where('user_id',auth()->user()->id)->where('turn',20)->sum('amount');
      $totalbetsamountpick20count = expertbet::whereIn('event_id',$arrays)->where('user_id',auth()->user()->id)->where('turn',20)->count();
      $totalbetsamountpick2 = expertbet::whereIn('event_id',$arrays)->where('user_id',auth()->user()->id)->where('turn',2)->where('winner','!=',4)->sum('amount');
      $totalbetsamountpick2cancelled = expertbet::whereIn('event_id',$arrays)->where('user_id',auth()->user()->id)->where('turn',2)->where('winner',4)->sum('amount');
      $totalbetsamountpick2count = expertbet::whereIn('event_id',$arrays)->where('user_id',auth()->user()->id)->where('turn',2)->count();
      $getallpayoutpick20 = expertbet::with('user')->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('turn',20)->where('user_id',auth()->user()->id)->sum('result');
      $getallpayoutpick2 = expertbet::with('user')->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('turn',2)->where('user_id',auth()->user()->id)->sum('result');
      $control = control::first();
      $funds = $control->percentage_jackpot/100;
      $rake = $control->rake/100;
      $rakepick2 = $control->rakepick2/100;
      $totalcontingencyfunds = $totalbetsamountpick20 * $funds;
      $totalrakepick20 = $totalbetsamountpick20 * $rake;
      $totalrakepick2 = $totalbetsamountpick2 * $rakepick2;

      $breakagecalc = $totalcontingencyfunds+$totalrakepick20+$getallpayoutpick20;
      $breakageofpick20 = $totalbetsamountpick20-$breakagecalc;

      $breakagecalc2 = $totalrakepick2 + $getallpayoutpick2;
      $breakageofpick2 = $totalbetsamountpick2-$breakagecalc2;

      $totalbreakage = $breakageofpick20 + $breakageofpick2;
      $totalcountbets = $totalbetsamountpick20count + $totalbetsamountpick2count;
      // if ($getcashin) {
      //   $amount = $getcashin->amount;
      // }else {
      //   $amount = 0;
      // }
      if ($getcashout) {
        $cashout = $getcashout->amount;
      }else {
        $cashout = 0;
      }


      array_push($a, array('CashIn' => $getcashin,'totalamountbets'=>$totalbetsamount,'totalamountbetspick2cancelled'=>$totalbetsamountpick2cancelled,'totalamountbetspick2'=>$totalbetsamountpick2,'contingency'=>$totalcontingencyfunds,'moh'=>$cashout,'breakage'=>$totalbreakage,'Event'=>$events1->event_name,'date'=>$events1->fightdate,'arena'=>$events1->venue
      ,'pick20count'=>$totalbetsamountpick20,'pick2count'=>$totalbetsamountpick2,'totalcount'=>$totalcountbets));
      return $a;
    }else {
      $getalluser = User::where('group_id',auth()->user()->group_id)->select('id')->get();
      $getalltellers = User::where('group_id',auth()->user()->group_id)->where('role',9)->select('id')->get();
      $getcashin = Transactions::where('user_id',auth()->user()->id)->whereIn('event_id',$arrays)->where('type','Cash In')->sum('amount');
      $totalpayout = Transactions::where('user_id',auth()->user()->id)->whereIn('event_id',$arrays)->where('type','Withdrawal')->sum('amount');
      $totalpayoutmobile = Transactions::where('cashier_id',auth()->user()->id)->whereIn('event_id',$arrays)->where('type','Withdrawal_Mobile')->sum('amount');
      $totalpayoutcancelled = Transactions::where('user_id',auth()->user()->id)->whereIn('event_id',$arrays)->where('type','Withdrawal_Cancelled')->sum('amount');

      $cancelledunclaimed = expertbet::whereIn('event_id',$arrays)->whereIn('user_id',$getalltellers)->where('turn',2)->where('winner',4)->where('claimed',null)->sum('amount');
      $unclaimedpayout = expertbet::whereIn('event_id',$arrays)->whereIn('user_id',$getalltellers)->where('winner',1)->where('claimed',null)->sum('result');


      $getcashout = Transactions::where('user_id',auth()->user()->id)->whereIn('event_id',$arrays)->where('type','Cash Out')->latest()->first();
      $totalbetsamount = expertbet::whereIn('event_id',$arrays)->whereIn('user_id',$getalluser)->sum('amount');
      $totalbetsamountpick20 = expertbet::whereIn('event_id',$arrays)->whereIn('user_id',$getalluser)->where('turn',20)->sum('amount');
      $totalbetsamountpick2 = expertbet::whereIn('event_id',$arrays)->whereIn('user_id',$getalluser)->where('turn',2)->sum('amount');
      $getallpayoutpick20 = expertbet::with('user')->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('turn',20)->whereIn('user_id',$getalluser)->sum('result');
      $getallpayoutpick2 = expertbet::with('user')->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('turn',2)->whereIn('user_id',$getalluser)->sum('result');
      $control = control::first();
      $funds = $control->percentage_jackpot/100;
      $rake = $control->rake/100;
      $rakepick2 = $control->rakepick2/100;
      $totalcontingencyfunds = $totalbetsamountpick20 * $funds;
      $totalrakepick20 = $totalbetsamountpick20 * $rake;
      $totalrakepick2 = $totalbetsamountpick2 * $rakepick2;

      $breakagecalc = $totalcontingencyfunds+$totalrakepick20+$getallpayoutpick20;
      $breakageofpick20 = $totalbetsamountpick20-$breakagecalc;

      $breakagecalc2 = $totalrakepick2 + $getallpayoutpick2;
      $breakageofpick2 = $totalbetsamountpick2-$breakagecalc2;

      $totalbreakage = $breakageofpick20 + $breakageofpick2;
      // if ($getcashin) {
      //   $amount = $getcashin->amount;
      // }else {
      //   $amount = 0;
      // }
      if ($getcashout) {
        $cashout = $getcashout->amount;
      }else {
        $cashout = 0;
      }

      array_push($a, array('payoutunclaimed'=>$unclaimedpayout,'cancelledunclaimed'=>$cancelledunclaimed,'totalpayout'=>$totalpayout,'totalpayoutmobile'=>$totalpayoutmobile,'totalpayoutcancelled'=>$totalpayoutcancelled,'CashIn' => $getcashin,'moh'=>$cashout,'Event'=>$events1->event_name,'date'=>$events1->fightdate,'arena'=>$events1->venue,));
      return $a;
    }

  }
public function eventgetgroupreport(Request $req)
  {
    $getevent = Event::where('id',$req['id'])->first();
    // return $getevent;
    $events= Event::where('event_name',$getevent->event_name)->get();
    $arrays = array();
    foreach ($events as $key) {
      array_push($arrays, $key->id);
    }
    // return $arrays;
    // $user = group::
    // whereHas('users', function($q) use($arrays)
    // {
    //   $q->whereHas('expertbet', function($query) use($arrays) {
    //         $query->whereIn('event_id', $arrays);
    //    });
    // })
    // ->with(['users' => function ($query) use ($arrays) {
    //   // $query->select('totalbets');
    //   $query->whereHas('expertbet', function($query) use($arrays) {
    //         $query->whereIn('event_id', $arrays);
    //    });
    //   $query->withCount(['expertbet AS totalbets' => function ($q) use ($arrays) {
    //     $q->select('totalbets');
    //               return $q->select(DB::raw('SUM(amount)'))->whereIn('event_id', $arrays);
    //            }]);
    //       }])
    // ->paginate(10);
    if ($req['searchgroup']) {
      if ($getevent->status==2) {

        $get = DB::table('groups as a')
         ->join('users as b', 'a.id', '=', 'b.group_id')
         ->join('past_expertbet as c', 'b.id', '=', 'c.user_id')
         // ->join('expertbet as d', 'b.id', '=', 'd.user_id')
         ->whereIn('c.event_id',$arrays)->where('c.winner','!=',4)->where('a.name',$req['searchgroup'])
         // ->where('d.winner','!=',4)->where('d.turn',2)->whereIn('d.event_id',$arrays)
         // FORMAT(SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END)+SUM(CASE WHEN (c.winner!=4 AND c.turn=20)), 'c', 'fil-ph') AS totalbets
         // FORMAT(CONVERT(INT,SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END)+SUM(CASE WHEN (c.winner!=4 AND c.turn=20))), 'c', 'fil-ph') AS totalbets
         // FORMAT(SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END), 'c', 'fil-ph') AS totalpick2,
         ->select(DB::raw("(a.id) as id,a.name,
         CAST(SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END)+SUM(CASE WHEN (c.winner!=4 AND c.turn=20) THEN c.amount ELSE 0 END) AS DECIMAL(12,2)) as totalbets,
         CAST(SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END) AS DECIMAL(12,2)) AS totalpick2,
         CAST(SUM(CASE WHEN (c.winner!=4 AND c.turn=20) THEN c.amount ELSE 0 END) AS DECIMAL(12,2)) AS totalpick20,
         CAST(SUM(CASE WHEN c.turn=20 THEN (c.amount*0.10) ELSE 0 END) AS DECIMAL(12,2)) AS totalrakepick20,
         CAST(SUM(CASE WHEN c.turn=2 THEN (c.amount*0.05) ELSE 0 END) AS DECIMAL(12,2)) AS totalrakepick2,
         CAST(SUM(CASE WHEN c.turn=2 THEN (c.amount*0.05) ELSE 0 END)+SUM(CASE WHEN c.turn=20 THEN (c.amount*0.10) ELSE 0 END) AS DECIMAL(12,2)) AS totalrake,
         CAST(SUM(CASE WHEN c.claimed=1  THEN (c.result) ELSE 0 END) AS DECIMAL(12,2)) AS payoutpaid,
         CAST(SUM(CASE WHEN c.claimed IS NULL THEN (c.result) ELSE 0 END) AS DECIMAL(12,2)) AS payoutunclaimed")
         // ,DB::raw("(CASE WHEN c.turn = 20 THEN SUM(c.amount) ELSE 0  END) AS totalpick20"),
         )->groupBy('a.name')
         ->paginate(10);
        return $get;
      }else {
        $get = DB::table('groups as a')
         ->join('users as b', 'a.id', '=', 'b.group_id')
         ->join('expertbet as c', 'b.id', '=', 'c.user_id')
         // ->join('expertbet as d', 'b.id', '=', 'd.user_id')
         ->whereIn('c.event_id',$arrays)->where('c.winner','!=',4)->where('a.name',$req['searchgroup'])
         // ->where('d.winner','!=',4)->where('d.turn',2)->whereIn('d.event_id',$arrays)
         // FORMAT(SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END)+SUM(CASE WHEN (c.winner!=4 AND c.turn=20)), 'c', 'fil-ph') AS totalbets
         // FORMAT(CONVERT(INT,SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END)+SUM(CASE WHEN (c.winner!=4 AND c.turn=20))), 'c', 'fil-ph') AS totalbets
         // FORMAT(SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END), 'c', 'fil-ph') AS totalpick2,
         ->select(DB::raw("(a.id) as id,a.name,
         CAST(SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END)+SUM(CASE WHEN (c.winner!=4 AND c.turn=20) THEN c.amount ELSE 0 END) AS DECIMAL(12,2)) as totalbets,
         CAST(SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END) AS DECIMAL(12,2)) AS totalpick2,
         CAST(SUM(CASE WHEN (c.winner!=4 AND c.turn=20) THEN c.amount ELSE 0 END) AS DECIMAL(12,2)) AS totalpick20,
         CAST(SUM(CASE WHEN c.turn=20 THEN (c.amount*0.10) ELSE 0 END) AS DECIMAL(12,2)) AS totalrakepick20,
         CAST(SUM(CASE WHEN c.turn=2 THEN (c.amount*0.05) ELSE 0 END) AS DECIMAL(12,2)) AS totalrakepick2,
         CAST(SUM(CASE WHEN c.turn=2 THEN (c.amount*0.05) ELSE 0 END)+SUM(CASE WHEN c.turn=20 THEN (c.amount*0.10) ELSE 0 END) AS DECIMAL(12,2)) AS totalrake,
         CAST(SUM(CASE WHEN c.claimed=1  THEN (c.result) ELSE 0 END) AS DECIMAL(12,2)) AS payoutpaid,
         CAST(SUM(CASE WHEN c.claimed IS NULL THEN (c.result) ELSE 0 END) AS DECIMAL(12,2)) AS payoutunclaimed")
         // ,DB::raw("(CASE WHEN c.turn = 20 THEN SUM(c.amount) ELSE 0  END) AS totalpick20"),
         )->groupBy('a.name')
         ->paginate(10);
        return $get;

      }

      // FORMAT(SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END)+SUM(CASE WHEN (c.winner!=4 AND c.turn=20) THEN c.amount ELSE 0 END), 'c', 'en-PH') as totalbets,
      // FORMAT(SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END), 'c', 'fil-ph') AS totalpick2,
      // FORMAT(SUM(CASE WHEN (c.winner!=4 AND c.turn=20) THEN c.amount ELSE 0 END), 'c', 'fil-ph') AS totalpick20,
      // FORMAT(SUM(CASE WHEN c.turn=20 THEN (c.amount*0.10) ELSE 0 END), 'c', 'fil-ph') AS totalrakepick20,
      // FORMAT(SUM(CASE WHEN c.turn=2 THEN (c.amount*0.05) ELSE 0 END), 'c', 'fil-ph') AS totalrakepick2,
      // FORMAT(SUM(CASE WHEN c.turn=2 THEN (c.amount*0.05) ELSE 0 END)+SUM(CASE WHEN c.turn=20 THEN (c.amount*0.10) ELSE 0 END), 'c', 'fil-ph') AS totalrake,
      // FORMAT(SUM(CASE WHEN c.claimed=1  THEN (c.result) ELSE 0 END), 'c', 'fil-ph') AS payoutpaid,
      // FORMAT(SUM(CASE WHEN c.claimed IS NULL THEN (c.result) ELSE 0 END), 'c', 'fil-ph') AS payoutunclaimed")
    }else {
      if ($getevent->status==2) {
        // return 'alex';
        $get = DB::table('groups as a')
        ->join('users as b', 'a.id', '=', 'b.group_id')
        ->join('past_expertbet as c', 'b.id', '=', 'c.user_id')
        // ->join('expertbet as d', 'b.id', '=', 'd.user_id')
        ->whereIn('c.event_id',$arrays)->where('c.winner','!=',4)
        // ->where('d.winner','!=',4)->where('d.turn',2)->whereIn('d.event_id',$arrays)
        ->select(DB::raw("(a.id) as id,a.name,
        CAST(SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END)+SUM(CASE WHEN (c.winner!=4 AND c.turn=20) THEN c.amount ELSE 0 END) AS DECIMAL(12,2)) as totalbets,
        CAST(SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END) AS DECIMAL(12,2)) AS totalpick2,
        CAST(SUM(CASE WHEN (c.winner!=4 AND c.turn=20) THEN c.amount ELSE 0 END) AS DECIMAL(12,2)) AS totalpick20,
        CAST(SUM(CASE WHEN c.turn=20 THEN (c.amount*0.10) ELSE 0 END) AS DECIMAL(12,2)) AS totalrakepick20,
        CAST(SUM(CASE WHEN c.turn=2 THEN (c.amount*0.05) ELSE 0 END) AS DECIMAL(12,2)) AS totalrakepick2,
        CAST(SUM(CASE WHEN c.turn=2 THEN (c.amount*0.05) ELSE 0 END)+SUM(CASE WHEN c.turn=20 THEN (c.amount*0.10) ELSE 0 END) AS DECIMAL(12,2)) AS totalrake,
        CAST(SUM(CASE WHEN c.claimed=1  THEN (c.result) ELSE 0 END) AS DECIMAL(12,2)) AS payoutpaid,
        CAST(SUM(CASE WHEN c.claimed IS NULL THEN (c.result) ELSE 0 END) AS DECIMAL(12,2)) AS payoutunclaimed")
        // ,DB::raw("(CASE WHEN c.turn = 20 THEN SUM(c.amount) ELSE 0  END) AS totalpick20"),
        )->groupBy('a.name')
        ->paginate(10);
        return $get;
      }else {
        // return $getevent->active;
        $get = DB::table('groups as a')
        ->join('users as b', 'a.id', '=', 'b.group_id')
        ->join('expertbet as c', 'b.id', '=', 'c.user_id')
        // ->join('expertbet as d', 'b.id', '=', 'd.user_id')
        ->whereIn('c.event_id',$arrays)->where('c.winner','!=',4)
        // ->where('d.winner','!=',4)->where('d.turn',2)->whereIn('d.event_id',$arrays)
        ->select(DB::raw("(a.id) as id,a.name,
        CAST(SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END)+SUM(CASE WHEN (c.winner!=4 AND c.turn=20) THEN c.amount ELSE 0 END) AS DECIMAL(12,2)) as totalbets,
        CAST(SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END) AS DECIMAL(12,2)) AS totalpick2,
        CAST(SUM(CASE WHEN (c.winner!=4 AND c.turn=20) THEN c.amount ELSE 0 END) AS DECIMAL(12,2)) AS totalpick20,
        CAST(SUM(CASE WHEN c.turn=20 THEN (c.amount*0.10) ELSE 0 END) AS DECIMAL(12,2)) AS totalrakepick20,
        CAST(SUM(CASE WHEN c.turn=2 THEN (c.amount*0.05) ELSE 0 END) AS DECIMAL(12,2)) AS totalrakepick2,
        CAST(SUM(CASE WHEN c.turn=2 THEN (c.amount*0.05) ELSE 0 END)+SUM(CASE WHEN c.turn=20 THEN (c.amount*0.10) ELSE 0 END) AS DECIMAL(12,2)) AS totalrake,
        CAST(SUM(CASE WHEN c.claimed=1  THEN (c.result) ELSE 0 END) AS DECIMAL(12,2)) AS payoutpaid,
        CAST(SUM(CASE WHEN c.claimed IS NULL THEN (c.result) ELSE 0 END) AS DECIMAL(12,2)) AS payoutunclaimed")
        // ,DB::raw("(CASE WHEN c.turn = 20 THEN SUM(c.amount) ELSE 0  END) AS totalpick20"),
        )->groupBy('a.name')
        ->paginate(10);
        return $get;
      }
    }
  }
  public function eventgetusersgroupreport(Request $req)
  {
    $getgroup = Group::where('id',$req['group_id'])->first();
    $getevent = Event::where('id',$req['id'])->first();
    $events= Event::where('event_name',$getevent->event_name)->get();
    $arrays = array();
    foreach ($events as $key) {
      array_push($arrays, $key->id);
    }
    if ($getevent->status==2) {
      $data = array();
      $user = User::where('group_id',$getgroup->id)->where('role',9)
      ->whereHas('past_expertbet', function($q) use($arrays)
      {
              $q->whereIn('event_id', $arrays);
      })
      ->withSum(["past_expertbet AS totalbets"=> function ($q) use ($arrays) {
                 $q->select(DB::raw("CAST(SUM(amount) AS DECIMAL(12,2)) as totalbets"))->whereIn('event_id', $arrays)->where('winner','!=',4);
         }],"amount")
      ->withSum(["past_expertbet AS totalpick20"=> function ($q) use ($arrays) {
                 $q->select(DB::raw("CAST(SUM(amount) AS DECIMAL(12,2)) as totalpick20"))->whereIn('event_id', $arrays)->where('turn',20)->where('winner','!=',4);
         }],"amount")
      ->withSum(["past_expertbet AS totalpick2"=> function ($q) use ($arrays) {
                 $q->select(DB::raw("CAST(SUM(amount) AS DECIMAL(12,2)) as totalpick20"))->whereIn('event_id', $arrays)->where('turn',2)->where('winner','!=',4);
         }],"amount")
      ->withSum(["past_expertbet AS totalrakepick20"=> function ($q) use ($arrays) {
                 $q->select(DB::raw("CAST(SUM(amount*0.10) AS DECIMAL(12,2)) as totalrakepick20"))->whereIn('event_id', $arrays)->where('turn',20)->where('winner','!=',4);
         }],"amount")
      ->withSum(["past_expertbet AS totalrakepick2"=> function ($q) use ($arrays) {
                 $q->select(DB::raw("CAST(SUM(CASE WHEN turn=2 THEN (amount*0.10) ELSE 0 END) AS DECIMAL(12,2)) as totalrakepick2"))->whereIn('event_id', $arrays)->where('turn',2)->where('winner','!=',4);
         }],"amount")
      ->withSum(["past_expertbet AS totalrake"=> function ($q) use ($arrays) {
                 $q->select(DB::raw("CAST(SUM(CASE WHEN turn=2 THEN (amount*0.05) ELSE 0 END)+SUM(CASE WHEN turn=20 THEN (amount*0.10) ELSE 0 END) AS DECIMAL(12,2)) AS totalrake"))->whereIn('event_id', $arrays)->where('winner','!=',4);
         }],"amount")
      ->withSum(["past_expertbet AS payoutpaid"=> function ($q) use ($arrays) {
                 $q->select(DB::raw("CAST(SUM(CASE WHEN claimed=1  THEN (result) ELSE 0 END) AS DECIMAL(12,2)) AS payoutpaid"))->whereIn('event_id', $arrays)->where('winner','!=',4);
         }],"amount")
      ->withSum(["past_expertbet AS payoutunclaimed"=> function ($q) use ($arrays) {
                 $q->select(DB::raw("CAST(SUM(CASE WHEN claimed IS NULL THEN (result) ELSE 0 END) AS DECIMAL(12,2)) AS payoutunclaimed"))->whereIn('event_id', $arrays)->where('winner','!=',4);
         }],"amount")
      ->get();
      $user2 = User::where('group_id',$getgroup->id)->where('role',4)
      ->whereHas('transactions', function($q) use($arrays)
      {
              $q->whereIn('event_id', $arrays);
      })
      ->withSum(["transactions AS payoutpaid"=> function ($q) use ($arrays) {
                 $q->select(DB::raw("CAST(SUM(amount) AS DECIMAL(12,2)) AS payoutpaid"))->whereIn('event_id', $arrays)->where('type','Withdrawal');
         }],"amount")
      ->get();
      $mobile = DB::table('groups as a')
       ->join('users as b', 'a.id', '=', 'b.group_id')
       ->join('past_expertbet as c', 'b.id', '=', 'c.user_id')
       // ->join('expertbet as d', 'b.id', '=', 'd.user_id')
       ->whereIn('c.event_id',$arrays)->where('c.winner','!=',4)->where('b.role',3)->where('b.group_id',$getgroup->id)
       // ->where('d.winner','!=',4)->where('d.turn',2)->whereIn('d.event_id',$arrays)
       ->select(DB::raw("(a.id) as id,a.name,b.role,
       CAST(SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END)+SUM(CASE WHEN (c.winner!=4 AND c.turn=20) THEN c.amount ELSE 0 END) AS DECIMAL(12,2)) as totalbets,
       CAST(SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END) AS DECIMAL(12,2)) AS totalpick2,
       CAST(SUM(CASE WHEN (c.winner!=4 AND c.turn=20) THEN c.amount ELSE 0 END) AS DECIMAL(12,2)) AS totalpick20,
       CAST(SUM(CASE WHEN c.turn=20 THEN (c.amount*0.10) ELSE 0 END) AS DECIMAL(12,2)) AS totalrakepick20,
       CAST(SUM(CASE WHEN c.turn=2  THEN (c.amount*0.05) ELSE 0 END) AS DECIMAL(12,2)) AS totalrakepick2,
       CAST(SUM(CASE WHEN c.turn=2 THEN (c.amount*0.05) ELSE 0 END)+SUM(CASE WHEN c.turn=20 THEN (c.amount*0.10) ELSE 0 END) AS DECIMAL(12,2)) AS totalrake,
       CAST(SUM(CASE WHEN c.claimed=1  THEN (c.result) ELSE 0 END) AS DECIMAL(12,2)) AS payoutpaid,
       CAST(SUM(CASE WHEN c.claimed IS NULL THEN (c.result) ELSE 0 END) AS DECIMAL(12,2)) AS payoutunclaimed")
       // ,DB::raw("(CASE WHEN c.turn = 20 THEN SUM(c.amount) ELSE 0  END) AS totalpick20"),
       )
       ->get(10);
      foreach ($user2 as $key) {
        array_push($data, $key);
      }
      foreach ($user as $key) {
        array_push($data, $key);
      }
      foreach ($mobile as $key) {
        //array_push($data, $key);
  	   array_push($data, array('role' => $key->role,'id' => $key->id,'name' => 'Mobile Players','payoutpaid' => $key->payoutpaid,'payoutunclaimed'=>$key->payoutunclaimed,'totalbets'=>$key->totalbets,'totalpick2'=>$key->totalpick2,'totalpick20' => $key->totalpick20,'totalrake' => $key->totalrake,'totalrakepick2' => $key->totalrakepick2,
        'totalrakepick20' => $key->totalrakepick20));
      }
      // $get = DB::table('groups as a')
      //  ->join('users as b', 'a.id', '=', 'b.group_id')
      //  ->join('expertbet as c', 'b.id', '=', 'c.user_id')
      //  // ->join('expertbet as d', 'b.id', '=', 'd.user_id')
      //  ->whereIn('c.event_id',$arrays)->where('c.winner','!=',4)
      //  // ->where('d.winner','!=',4)->where('d.turn',2)->whereIn('d.event_id',$arrays)
      //  ->select(DB::raw("(a.id) as id,a.name,FORMAT(SUM(c.amount), 'N', 'en-us') as totalbets,FORMAT(SUM(CASE WHEN c.turn=2 THEN c.amount ELSE 0 END), 'N', 'en-us') AS totalpick2,FORMAT(SUM(CASE WHEN c.turn=20 THEN c.amount ELSE 0 END), 'N', 'en-us') AS totalpick20,FORMAT(SUM(CASE WHEN c.turn=20 THEN (c.amount*0.10) ELSE 0 END), 'N', 'en-us') AS totalrakepick20,FORMAT(SUM(CASE WHEN c.turn=2 THEN (c.amount*0.05) ELSE 0 END), 'N', 'en-us') AS totalrakepick2
      //  ,FORMAT(SUM(CASE WHEN c.turn=2 THEN (c.amount*0.05) ELSE 0 END)+SUM(CASE WHEN c.turn=20 THEN (c.amount*0.10) ELSE 0 END), 'N', 'en-us') AS totalrake,FORMAT(SUM(CASE WHEN c.claimed=1  THEN (c.result) ELSE 0 END), 'N', 'en-us') AS payoutpaid,FORMAT(SUM(CASE WHEN c.claimed IS NULL THEN (c.result) ELSE 0 END), 'N', 'en-us') AS payoutunclaimed")
      //  // ,DB::raw("(CASE WHEN c.turn = 20 THEN SUM(c.amount) ELSE 0  END) AS totalpick20"),
      //  )->groupBy('a.name')
      //  ->paginate(10);
      return $data;
    }else {
      $data = array();
      $user = User::where('group_id',$getgroup->id)->where('role',9)
      ->whereHas('expertbet', function($q) use($arrays)
      {
              $q->whereIn('event_id', $arrays);
      })
      ->withSum(["expertbet AS totalbets"=> function ($q) use ($arrays) {
                 $q->select(DB::raw("CAST(SUM(amount) AS DECIMAL(12,2)) as totalbets"))->whereIn('event_id', $arrays)->where('winner','!=',4);
         }],"amount")
      ->withSum(["expertbet AS totalpick20"=> function ($q) use ($arrays) {
                 $q->select(DB::raw("CAST(SUM(amount) AS DECIMAL(12,2)) as totalpick20"))->whereIn('event_id', $arrays)->where('turn',20)->where('winner','!=',4);;
         }],"amount")
      ->withSum(["expertbet AS totalpick2"=> function ($q) use ($arrays) {
                 $q->select(DB::raw("CAST(SUM(amount) AS DECIMAL(12,2)) as totalpick20"))->whereIn('event_id', $arrays)->where('turn',2)->where('winner','!=',4);;
         }],"amount")
      ->withSum(["expertbet AS totalrakepick20"=> function ($q) use ($arrays) {
                 $q->select(DB::raw("CAST(SUM(amount*0.10) AS DECIMAL(12,2)) as totalrakepick20"))->whereIn('event_id', $arrays)->where('turn',20)->where('winner','!=',4);;
         }],"amount")
      ->withSum(["expertbet AS totalrakepick2"=> function ($q) use ($arrays) {
                 $q->select(DB::raw("CAST(SUM(amount*0.05) AS DECIMAL(12,2)) as totalrakepick2"))->whereIn('event_id', $arrays)->where('turn',2)->where('winner','!=',4);;
         }],"amount")
      ->withSum(["expertbet AS totalrake"=> function ($q) use ($arrays) {
                 $q->select(DB::raw("CAST(SUM(CASE WHEN turn=2 THEN (amount*0.05) ELSE 0 END)+SUM(CASE WHEN turn=20 THEN (amount*0.10) ELSE 0 END) AS DECIMAL(12,2)) AS totalrake"))->whereIn('event_id', $arrays)->where('winner','!=',4);
         }],"amount")
      ->withSum(["expertbet AS payoutpaid"=> function ($q) use ($arrays) {
                 $q->select(DB::raw("CAST(SUM(CASE WHEN claimed=1  THEN (result) ELSE 0 END) AS DECIMAL(12,2)) AS payoutpaid"))->whereIn('event_id', $arrays)->where('winner','!=',4);
         }],"amount")
      ->withSum(["expertbet AS payoutunclaimed"=> function ($q) use ($arrays) {
                 $q->select(DB::raw("CAST(SUM(CASE WHEN claimed IS NULL THEN (result) ELSE 0 END) AS DECIMAL(12,2)) AS payoutunclaimed"))->whereIn('event_id', $arrays)->where('winner','!=',4);
         }],"amount")
      ->get();
      $user2 = User::where('group_id',$getgroup->id)->where('role',4)
      ->whereHas('transactions', function($q) use($arrays)
      {
              $q->whereIn('event_id', $arrays);
      })
      ->withSum(["transactions AS payoutpaid"=> function ($q) use ($arrays) {
                 $q->select(DB::raw("CAST(SUM(amount) AS DECIMAL(12,2)) AS payoutpaid"))->whereIn('event_id', $arrays)->where('type','Withdrawal');
         }],"amount")
      ->get();
      $mobile = DB::table('groups as a')
       ->join('users as b', 'a.id', '=', 'b.group_id')
       ->join('expertbet as c', 'b.id', '=', 'c.user_id')
       // ->join('expertbet as d', 'b.id', '=', 'd.user_id')
       ->whereIn('c.event_id',$arrays)->where('c.winner','!=',4)->where('b.role',3)->where('b.group_id',$getgroup->id)
       // ->where('d.winner','!=',4)->where('d.turn',2)->whereIn('d.event_id',$arrays)
       ->select(DB::raw("(a.id) as id,a.name,b.role,
       CAST(SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END)+SUM(CASE WHEN (c.winner!=4 AND c.turn=20) THEN c.amount ELSE 0 END) AS DECIMAL(12,2)) as totalbets,
       CAST(SUM(CASE WHEN (c.winner!=4 AND c.turn=2) THEN c.amount ELSE 0 END) AS DECIMAL(12,2)) AS totalpick2,
       CAST(SUM(CASE WHEN (c.winner!=4 AND c.turn=20) THEN c.amount ELSE 0 END) AS DECIMAL(12,2)) AS totalpick20,
       CAST(SUM(CASE WHEN c.turn=20 THEN (c.amount*0.10) ELSE 0 END) AS DECIMAL(12,2)) AS totalrakepick20,
       CAST(SUM(CASE WHEN c.turn=2 THEN (c.amount*0.05) ELSE 0 END) AS DECIMAL(12,2)) AS totalrakepick2,
       CAST(SUM(CASE WHEN c.turn=2 THEN (c.amount*0.05) ELSE 0 END)+SUM(CASE WHEN c.turn=20 THEN (c.amount*0.10) ELSE 0 END) AS DECIMAL(12,2)) AS totalrake,
       CAST(SUM(CASE WHEN c.claimed=1  THEN (c.result) ELSE 0 END) AS DECIMAL(12,2)) AS payoutpaid,
       CAST(SUM(CASE WHEN c.claimed IS NULL THEN (c.result) ELSE 0 END) AS DECIMAL(12,2)) AS payoutunclaimed")
       // ,DB::raw("(CASE WHEN c.turn = 20 THEN SUM(c.amount) ELSE 0  END) AS totalpick20"),
       )
       ->get(10);
      foreach ($user2 as $key) {
        array_push($data, $key);
      }
      foreach ($user as $key) {
        array_push($data, $key);
      }
      foreach ($mobile as $key) {
        //array_push($data, $key);
  	   array_push($data, array('role' => $key->role,'id' => $key->id,'name' => 'Mobile Players','payoutpaid' => $key->payoutpaid,'payoutunclaimed'=>$key->payoutunclaimed,'totalbets'=>$key->totalbets,'totalpick2'=>$key->totalpick2,'totalpick20' => $key->totalpick20,'totalrake' => $key->totalrake,'totalrakepick2' => $key->totalrakepick2,
        'totalrakepick20' => $key->totalrakepick20));
      }
      // $get = DB::table('groups as a')
      //  ->join('users as b', 'a.id', '=', 'b.group_id')
      //  ->join('expertbet as c', 'b.id', '=', 'c.user_id')
      //  // ->join('expertbet as d', 'b.id', '=', 'd.user_id')
      //  ->whereIn('c.event_id',$arrays)->where('c.winner','!=',4)
      //  // ->where('d.winner','!=',4)->where('d.turn',2)->whereIn('d.event_id',$arrays)
      //  ->select(DB::raw("(a.id) as id,a.name,FORMAT(SUM(c.amount), 'N', 'en-us') as totalbets,FORMAT(SUM(CASE WHEN c.turn=2 THEN c.amount ELSE 0 END), 'N', 'en-us') AS totalpick2,FORMAT(SUM(CASE WHEN c.turn=20 THEN c.amount ELSE 0 END), 'N', 'en-us') AS totalpick20,FORMAT(SUM(CASE WHEN c.turn=20 THEN (c.amount*0.10) ELSE 0 END), 'N', 'en-us') AS totalrakepick20,FORMAT(SUM(CASE WHEN c.turn=2 THEN (c.amount*0.05) ELSE 0 END), 'N', 'en-us') AS totalrakepick2
      //  ,FORMAT(SUM(CASE WHEN c.turn=2 THEN (c.amount*0.05) ELSE 0 END)+SUM(CASE WHEN c.turn=20 THEN (c.amount*0.10) ELSE 0 END), 'N', 'en-us') AS totalrake,FORMAT(SUM(CASE WHEN c.claimed=1  THEN (c.result) ELSE 0 END), 'N', 'en-us') AS payoutpaid,FORMAT(SUM(CASE WHEN c.claimed IS NULL THEN (c.result) ELSE 0 END), 'N', 'en-us') AS payoutunclaimed")
      //  // ,DB::raw("(CASE WHEN c.turn = 20 THEN SUM(c.amount) ELSE 0  END) AS totalpick20"),
      //  )->groupBy('a.name')
      //  ->paginate(10);
      return $data;
    }

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
    $rakepick2 = $control->rakepick2/100;
    $results = DB::select('select * from users where id = ?', [1]);
    // return $results;
    // $sql = '*, sum(c.amount * ?) AS totalrake,sum(c.amount * ?) AS totalrake2,sum(c.result) AS totalpayoutpaid';
    // $get = DB::table('users as a')
    //  ->join('expertbet as c', 'a.id', '=', 'c.user_id')
    //  ->whereIn('event_id',$arrays)
    //  ->selectRaw($sql,[$rake,$rakepick2])
    //   // >select('a.startingfight','c.bet','c.amount','c.wins','c.winner','c.result','c.lose')
    //  // ->paginate(3);
    //  ->groupBy('a.username')
    //   ->get()
    //   ->unique('username');
    //
    //
    //   $array = array();
    //   foreach ($get as $key) {
    //     $getuser = User::where('username',$key->username)
    //     ->withSum(['expertbet as expertbet_sum_result' => function ($query) use ($arrays) {
    //     $query->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->where('claimed',1);
    //   }],'result')
    //     ->withSum(['expertbet as expertbet_sum_result2' => function ($query) use ($arrays) {
    //     $query->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->where('claimed',null);
    //   }],'result')
    //     ->withSum(['expertbet as pick2' => function ($query) use ($arrays) {
    //     $query->whereIn('event_id',$arrays)->where('turn',2)->where('winner','!=',4);
    //   }],'amount')
    //     ->withSum(['expertbet as pick20' => function ($query) use ($arrays) {
    //     $query->whereIn('event_id',$arrays)->where('turn',20)->where('winner','!=',4);
    //   }],'amount')
    //   ->first();
    //   $calcpick2 = $control->rakepick2/100;
    //   $calcpick20 = $control->rake/100;
    //   $rakepick2 = $getuser->pick2*$calcpick2;
    //   $rakepick20 = $getuser->pick20*$calcpick20;
    //   $finalrake = $rakepick2+$rakepick20;
    //   // return $getuser;
    //     // return $getuser;
    //     // $unclaimed = expertbet::where('user_id',$getuser->id)->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('claimed',null)->sum('result');
    //     // $claimed = expertbet::where('user_id',$getuser->id)->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('claimed',1)->sum('result');
    //     array_push($array,array('username'=>$key->username,'totalbets'=>$key->totalbets,'totalpayoutpaid'=>$getuser->expertbet_sum_result,'totalrake'=>$finalrake,'totalunclaimed'=>$getuser->expertbet_sum_result2,'role'=>$getuser->role));
    //   }
    //   $myCollectionObj = collect($array);
    //
    //    $dataxx = $this->paginate($myCollectionObj);
    //  return $dataxx;
    if ($getevent->status==2) {
      $user = User::whereHas('past_expertbet', function($q) use($arrays)
      {
        $q->whereIn('event_id', $arrays);

      })
      ->withSum(['past_expertbet as totalunclaimed' => function ($query) use ($arrays) {
      $query->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->where('claimed',null);
    }],'result')
      ->withSum(['past_expertbet as totalpayoutpaid' => function ($query) use ($arrays) {
      $query->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->where('claimed',1);
    }],'result')
      ->withSum(['past_expertbet as totalbets' => function ($query) use ($arrays) {
      $query->whereIn('event_id',$arrays);
    }],'amount')
    ->withSum(['past_expertbet as totalpick20' => function ($query) use ($arrays) {
    $query->whereIn('event_id',$arrays)->where('turn',20)->where('winner','!=',4);
  }],'amount')
    ->withSum(['past_expertbet as totalpick2' => function ($query) use ($arrays) {
    $query->whereIn('event_id',$arrays)->where('turn',2)->where('winner','!=',4);;
  }],'amount')
    ->paginate(10);


 // >sum(DB::raw('sales.price * merchants.commission'));

      return $user;
    }else {
      $user = User::whereHas('expertbet', function($q) use($arrays)
      {
        $q->whereIn('event_id', $arrays);

      })
      ->withSum(['expertbet as totalunclaimed' => function ($query) use ($arrays) {
      $query->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->where('claimed',null);
    }],'result')
      ->withSum(['expertbet as totalpayoutpaid' => function ($query) use ($arrays) {
      $query->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->where('claimed',1);
    }],'result')
      ->withSum(['expertbet as totalbets' => function ($query) use ($arrays) {
      $query->whereIn('event_id',$arrays);
    }],'amount')
    ->withSum(['expertbet as totalpick20' => function ($query) use ($arrays) {
    $query->whereIn('event_id',$arrays)->where('turn',20)->where('winner','!=',4);
  }],'amount')
    ->withSum(['expertbet as totalpick2' => function ($query) use ($arrays) {
    $query->whereIn('event_id',$arrays)->where('turn',2)->where('winner','!=',4);;
  }],'amount')
    ->paginate(10);


 // >sum(DB::raw('sales.price * merchants.commission'));

      return $user;
    }

  }
  public function eventgetusersreportexcel(Request $req)
  {

    $getevent = Event::where('id',$req['id'])->first();
    $events= Event::where('event_name',$getevent->event_name)->get();
    $arrays = array();
    foreach ($events as $key) {
      array_push($arrays, $key->id);
    }
    $control = control::first();
    $rake = $control->rake/100;
    $rakepick2 = $control->rakepick2/100;
    $results = DB::select('select * from users where id = ?', [1]);
    if ($getevent->status==2) {
      $user = User::whereHas('past_expertbet', function($q) use($arrays)
      {
        $q->whereIn('event_id', $arrays);

      })
      ->withSum(['past_expertbet as totalunclaimed' => function ($query) use ($arrays) {
      $query->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->where('claimed',null);
    }],'result')
      ->withSum(['past_expertbet as totalpayoutpaid' => function ($query) use ($arrays) {
      $query->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->where('claimed',1);
    }],'result')
      ->withSum(['past_expertbet as totalbets' => function ($query) use ($arrays) {
      $query->whereIn('event_id',$arrays);
    }],'amount')
    ->withSum(['past_expertbet as totalpick20' => function ($query) use ($arrays) {
    $query->whereIn('event_id',$arrays)->where('turn',20)->where('winner','!=',4);
  }],'amount')
    ->withSum(['past_expertbet as totalpick2' => function ($query) use ($arrays) {
    $query->whereIn('event_id',$arrays)->where('turn',2)->where('winner','!=',4);
  }],'amount')
    ->paginate(10);
    }else {
      $user = User::whereHas('expertbet', function($q) use($arrays)
      {
        $q->whereIn('event_id', $arrays);

      })
      ->withSum(['expertbet as totalunclaimed' => function ($query) use ($arrays) {
      $query->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->where('claimed',null);
    }],'result')
      ->withSum(['expertbet as totalpayoutpaid' => function ($query) use ($arrays) {
      $query->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->where('claimed',1);
    }],'result')
      ->withSum(['expertbet as totalbets' => function ($query) use ($arrays) {
      $query->whereIn('event_id',$arrays);
    }],'amount')
    ->withSum(['expertbet as totalpick20' => function ($query) use ($arrays) {
    $query->whereIn('event_id',$arrays)->where('turn',20)->where('winner','!=',4);
  }],'amount')
    ->withSum(['expertbet as totalpick2' => function ($query) use ($arrays) {
    $query->whereIn('event_id',$arrays)->where('turn',2)->where('winner','!=',4);
  }],'amount')
    ->paginate(10);
    }


   $userx = array();
   foreach ($user as $key) {
     $rakeforpick2 = $control->rakepick2/100;
     $rakeforpick20 = $control->rake/100;
     $rakepick2 = $key->totalpick2 * $rakeforpick2;
     $rakepick20 = $key->totalpick20 * $rakeforpick20;
     $income = $rakepick2+$rakepick20;
     if ($key->role==3) {
       $role = 'Mobile';
     }
     if ($key->role==9) {
       $role = 'Teller';
     }

     array_push($userx,array('username' => $key->username,'role'=>$role,'totalbets' => $key->totalbets,'income' => $income,'totalunclaimed' => $key->totalunclaimed,'totalpayoutpaid' => $key->totalpayoutpaid,));
   }

     return $userx;
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
  if ($event->status==2) {
    $get=DB::table('past_expertbet as c')
     ->join('users as a', 'c.user_id', '=', 'a.id')
     ->where('c.user_id',$getuser->id)
     ->whereIn('c.event_id',$events)
     ->select(DB::raw('DATE_FORMAT(c.created_at, "%d-%b-%Y") as created_at,(c.created_at) AS created,username,role,(c.id) AS id,barcode,amount,bet,startingfight,claimed,result,winner,wins,turn,(CASE WHEN c.turn = 2 THEN FORMAT(c.amount*0.05,2) ELSE FORMAT(c.amount*0.10,2) END) AS income'))
     // ->selectRaw($sql,[$rake],DB::raw('DATE_FORMAT(c.created_at, "%d-%b-%Y") as created_at'))
      // ->select('c.startingfight','c.bet','c.amount','c.wins','c.winner','c.result','c.lose',DB::raw('(c.amount*:rake) as rakes'))
     ->paginate(10);


      return $get;
  }else {
    $get=DB::table('expertbet as c')
     ->join('users as a', 'c.user_id', '=', 'a.id')
     ->where('c.user_id',$getuser->id)
     ->whereIn('c.event_id',$events)
     ->select(DB::raw('DATE_FORMAT(c.created_at, "%d-%b-%Y") as created_at,(c.created_at) AS created,username,role,(c.id) AS id,barcode,amount,bet,startingfight,claimed,result,winner,wins,turn,(CASE WHEN c.turn = 2 THEN FORMAT(c.amount*0.05,2) ELSE FORMAT(c.amount*0.10,2) END) AS income'))
     // ->selectRaw($sql,[$rake],DB::raw('DATE_FORMAT(c.created_at, "%d-%b-%Y") as created_at'))
      // ->select('c.startingfight','c.bet','c.amount','c.wins','c.winner','c.result','c.lose',DB::raw('(c.amount*:rake) as rakes'))
     ->paginate(10);


      return $get;
  }

  }
  public function arenareportsmodaltotal(Request $req)
  {
    $event = Event::where('event_name',$req['event_name'])->where('pick',20)->get();
    $testevent = Event::where('event_name',$req['event_name'])->where('pick',20)->first();
    // return $testevent;
    $control = control::first();
    $array = array();
    foreach ($event as $key) {
      array_push($array, $key->id);
    }
    // return $array;
    if ($testevent->status==2) {
      $betcount = past_expertbet::whereIn('event_id',$array)->where('turn',20)->count();
      $totalpayout = past_expertbet::whereIn('event_id',$array)->where('turn',20)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->sum('result');
      $totalpayoutunclaimed = past_expertbet::whereIn('event_id',$array)->where('turn',20)->where('winner','!=',0)->where('winner','!=',3)->where('claimed',null)->sum('result');
      $totalpayoutss = past_expertbet::whereIn('event_id',$array)->where('turn',20)->where('winner','!=',0)->where('winner','!=',3)->where('claimed',1)->sum('result');
      $numberofbetsamount = past_expertbet::with('user')->whereIn('event_id',$array)->where('turn',20)->sum('amount');
      // code...
    }else {
      $betcount = expertbet::whereIn('event_id',$array)->where('turn',20)->count();
      $totalpayout = expertbet::whereIn('event_id',$array)->where('turn',20)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->sum('result');
      $totalpayoutunclaimed = expertbet::whereIn('event_id',$array)->where('turn',20)->where('winner','!=',0)->where('winner','!=',3)->where('claimed',null)->sum('result');
      $totalpayoutss = expertbet::whereIn('event_id',$array)->where('turn',20)->where('winner','!=',0)->where('winner','!=',3)->where('claimed',1)->sum('result');
      $numberofbetsamount = expertbet::with('user')->whereIn('event_id',$array)->where('turn',20)->sum('amount');
    }
    $pot = Potmoney::whereIn('event_id',$array)->where('pick',20)->sum('rake');
    $totaldeductedabono = Potmoney::where('event_id',$req['id'])->where('pick',20)->sum('deductedtojackpot');
    //$numberofbetsamount = Potmoney::whereIn('event_id',$array)->where('pick',20)->sum('amount');
	//$getallbetspick20amount = expertbet::with('user')->whereIn('event_id',$array)->where('turn',20)->sum('amount');
    // $totaldeductedabono2 = Potmoney::where('event_id',$req['event_id'])->where('pick',20)->first();
    $rake = $control->rake/100;
    $funds = $control->percentage_jackpot/100;
    $totalrake = $numberofbetsamount * $rake;
    $totalcleanfunds = $numberofbetsamount * $funds;
    $currentfunds = $totalcleanfunds - $totaldeductedabono;
    $breakagecalc = $totalrake  + $totalcleanfunds + $totalpayout;
    $breakage =$numberofbetsamount - $breakagecalc;
    // $numberofbetsamount = array();
    // $rake = array();
    $rakefinal = $pot+$totalpayoutunclaimed+$breakage;
    $payout = array();
    $payoutunclaimed = array();
    $totalpayout2 = $this->numberPrecision($totalpayout, 2);
      // $rake = $event->rake/100;
      // $finalrake = $key->amount * $rake;
    $a = array();
    array_push($a, array('numberofbets'=> $betcount,'totalbetsamountfinal'=> $numberofbetsamount,'rakefinal'=> $rakefinal ,'totalpayout'=> $totalpayout2,'totalpayoutunclaimed'=> $totalpayoutunclaimed,'officerake'=>$totalrake,'totalcontingencyfunds'=>$totalcleanfunds,'currentcontingencyfunds'=>$currentfunds,'totalpayoutpaid'=>$totalpayoutss,'breakage'=>$breakage,'pick'=>20));
    return $a;
  }
  public function arenareportsmodaltotal2(Request $req)
  {
    $event = Event::where('event_name',$req['event_name'])->where('pick',2)->get();
    $event1 = Event::where('event_name',$req['event_name'])->where('pick',2)->first();
    $control = control::first();
    $array = array();
    foreach ($event as $key) {
      array_push($array, $key->id);
    }
    // return $array;
    if ($event1->status==2) {
      $betcount = past_expertbet::whereIn('event_id',$array)->where('turn',2)->count();
      $totalpayout = past_expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->sum('result');
      $totalpayoutunclaimed = past_expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->where('claimed',null)->sum('result');
      $totalpayoutunclaimedcancelled = past_expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner',4)->where('claimed',null)->sum('result');
      $totalpayoutclaimedcancelled = past_expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner',4)->where('claimed',1)->sum('result');
      $totalpayoutss = past_expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner','!=',0)->where('winner','!=',3)->where('claimed',1)->where('winner','!=',4)->sum('result');
      $numberofbetsamount = past_expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner','!=',4)->sum('amount');
      $numberofbetsamountfinal = past_expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner','!=',4)->where('winner','!=',5)->sum('amount');
      $numberofbetsamountfinal5 = past_expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner',5)->sum('amount');
      $numberofbetsamountcancelled = past_expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner',4)->sum('amount');
    }else {
      $betcount = expertbet::whereIn('event_id',$array)->where('turn',2)->count();
      $totalpayout = expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->sum('result');
      $totalpayoutunclaimed = expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->where('claimed',null)->sum('result');
      $totalpayoutunclaimedcancelled = expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner',4)->where('claimed',null)->sum('result');
      $totalpayoutclaimedcancelled = expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner',4)->where('claimed',1)->sum('result');
      $totalpayoutss = expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner','!=',0)->where('winner','!=',3)->where('claimed',1)->where('winner','!=',4)->sum('result');
      $numberofbetsamount = expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner','!=',4)->sum('amount');
      $numberofbetsamountfinal = expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner','!=',4)->where('winner','!=',5)->sum('amount');
      $numberofbetsamountfinal5 = expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner',5)->sum('amount');
      $numberofbetsamountcancelled = expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner',4)->sum('amount');
    }
    // $numberofbetsamount = expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner','!=',4)->sum('amount');
    // original
    $pot = Potmoney::whereIn('event_id',$array)->where('pick',2)->sum('rake');
    $pot3 = Potmoney::whereIn('event_id',$array)->where('pick',2)->sum('addtojackpot');
    $numberofbetsamount2 = Potmoney::whereIn('event_id',$array)->where('pick',2)->sum('amount');
    $totaldeductedabono = Potmoney::where('event_id',$req['event_id'])->where('pick',2)->sum('deductedtojackpot');
    $rake = $control->rakepick2/100;

    // $pot = $numberofbetsamountfinal*$rake;

    // $funds = $control->percentage_jackpot/100;
    // $walangnanalo = $numberofbetsamountfinal5;
    $walangnanalo = $pot3;
    // $totalrake = $numberofbetsamountfinal * $rake;
    $totalrake = $pot;
    // $totalcleanfunds = $numberofbetsamount * $funds;
    // $currentfunds = $totalcleanfunds - $totaldeductedabono;
    $breakagecalc = $totalrake + $totalpayout ;
    $breakage =$numberofbetsamount - $breakagecalc-$pot3 ;
    // $numberofbetsamount = array();
    // $rake = array();
    // return $totalrake;
    $rakefinal = $pot+$totalpayoutunclaimed+$breakage;
    // return $numberofbetsamount ;
    $payout = array();
    $payoutunclaimed = array();
    $totalpayout2 = $this->numberPrecision($totalpayout, 2);
      // $rake = $event->rake/100;
      // $finalrake = $key->amount * $rake;
    $a = array();
    array_push($a, array('funds'=>$pot3,'totalcontingencyfunds'=>$walangnanalo,'claimedcancelled'=>$totalpayoutclaimedcancelled,'totalcancelledunclaimed'=>$totalpayoutunclaimedcancelled,'cancelledbetsamount'=>$numberofbetsamountcancelled,'cancelledbetsamountpayout'=>$numberofbetsamountcancelled,'numberofbets'=> $betcount,'totalbetsamountfinal'=> $numberofbetsamount,'rakefinal'=> $rakefinal,'totalpayout'=> $totalpayout2,'totalpayoutunclaimed'=> $totalpayoutunclaimed,'officerake'=>$totalrake,'totalpayoutpaid'=>$totalpayoutss,
    'breakage'=>$breakage,
    'pick'=>2));
    return $a;
  }
  public function arenareportsmodaltotal3(Request $req)
  {
    $event = Event::where('event_name',$req['event_name'])->get();
    $event1 = Event::where('event_name',$req['event_name'])->first();
    $control = control::first();
    $array = array();
    foreach ($event as $key) {
      array_push($array, $key->id);
    }
    // return $array;
    if ($event1->status==2) {
      $betcount = past_expertbet::whereIn('event_id',$array)->count();
      $totalpayoutunclaimedpick20 = past_expertbet::whereIn('event_id',$array)->where('winner','!=',0)->where('winner','!=',3)->where('turn',20)->where('winner','!=',4)->sum('result');
      $totalpayoutunclaimedpick2 = past_expertbet::whereIn('event_id',$array)->where('winner','!=',0)->where('winner','!=',3)->where('turn',2)->where('winner','!=',4)->where('claimed',null)->sum('result');
      $totalpayout = past_expertbet::whereIn('event_id',$array)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->sum('result');
      $totalpayoutpick2 = past_expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->sum('result');
      $totalpayoutunclaimed = past_expertbet::whereIn('event_id',$array)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->where('claimed',null)->sum('result');
      $totalpayoutss = past_expertbet::whereIn('event_id',$array)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->where('claimed',1)->sum('result');
      $numberofbetsamount20 = past_expertbet::whereIn('event_id',$array)->where('turn',20)->sum('amount');
      $numberofbetsamountpick2 = past_expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->sum('amount');
      $numberofbetsamount2 = past_expertbet::whereIn('event_id',$array)->where('winner','!=',4)->where('turn',2)->sum('amount');
      $numberofbetsamount2final = past_expertbet::whereIn('event_id',$array)->where('winner','!=',4)->where('winner','!=',5)->where('turn',2)->sum('amount');
      $numberofbetsamount2final5 = past_expertbet::whereIn('event_id',$array)->where('winner', 5)->where('turn',2)->sum('amount');
      $numberofbetsamountcancelled = past_expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner',4)->sum('amount');
      $totalpayoutunclaimedcancelled = past_expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner',4)->where('claimed',null)->sum('result');
      $totalpayoutclaimedcancelled = past_expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner',4)->where('claimed',1)->sum('result');
    }else {
      $betcount = expertbet::whereIn('event_id',$array)->count();
      $totalpayoutunclaimedpick20 = expertbet::whereIn('event_id',$array)->where('winner','!=',0)->where('winner','!=',3)->where('turn',20)->where('winner','!=',4)->sum('result');
      $totalpayoutunclaimedpick2 = expertbet::whereIn('event_id',$array)->where('winner','!=',0)->where('winner','!=',3)->where('turn',2)->where('winner','!=',4)->where('claimed',null)->sum('result');
      $totalpayout = expertbet::whereIn('event_id',$array)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->sum('result');
      $totalpayoutpick2 = expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->sum('result');
      $totalpayoutunclaimed = expertbet::whereIn('event_id',$array)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->where('claimed',null)->sum('result');
      $totalpayoutss = expertbet::whereIn('event_id',$array)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->where('claimed',1)->sum('result');
      $numberofbetsamount20 = expertbet::whereIn('event_id',$array)->where('turn',20)->sum('amount');
      $numberofbetsamountpick2 = expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->sum('amount');
      $numberofbetsamount2 = expertbet::whereIn('event_id',$array)->where('winner','!=',4)->where('turn',2)->sum('amount');
      $numberofbetsamount2final = expertbet::whereIn('event_id',$array)->where('winner','!=',4)->where('winner','!=',5)->where('turn',2)->sum('amount');
      $numberofbetsamount2final5 = expertbet::whereIn('event_id',$array)->where('winner', 5)->where('turn',2)->sum('amount');
      $numberofbetsamountcancelled = expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner',4)->sum('amount');
      $totalpayoutunclaimedcancelled = expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner',4)->where('claimed',null)->sum('result');
      $totalpayoutclaimedcancelled = expertbet::whereIn('event_id',$array)->where('turn',2)->where('winner',4)->where('claimed',1)->sum('result');
    }
    $fundspick2 = Potmoney::whereIn('event_id',$array)->where('pick',2)->sum('addtojackpot');
    $pot = Potmoney::whereIn('event_id',$array)->sum('rake');
    $pot20 = Potmoney::whereIn('event_id',$array)->where('pick',20)->sum('rake');
    $pot2 = Potmoney::whereIn('event_id',$array)->where('pick',2)->sum('rake');
    $numberofbetsamount = Potmoney::whereIn('event_id',$array)->sum('amount');
    $totaldeductedabono = Potmoney::where('event_id',$req['event_id'])->sum('deductedtojackpot');
    $totalngdalawa = $numberofbetsamount20+$numberofbetsamount2;
    $rake = $control->rake/100;
    $rake2 = $control->rakepick2/100;
    $funds = $control->percentage_jackpot/100;
    // $pot2 = $numberofbetsamount2final*$rake2;
    $totalrake = $pot20+$pot2;
    $totalrake2 = $pot2;
    $totalrake20 = $pot20;
    $fundspick20 = $numberofbetsamount20 * $funds;
    $fundspick2 = $fundspick2;
    $totalcleanfunds = $fundspick20 + $fundspick2;
    $currentfunds = $totalcleanfunds - $totaldeductedabono;
    $breakagecalc = $totalrake20  + $totalcleanfunds + $totalpayoutunclaimedpick20;

    $breakagecalc2 = $totalrake2 + $totalpayoutpick2;
    $breakage2 =$numberofbetsamount2 - $breakagecalc2;

    $breakage20 = $numberofbetsamount20 - $breakagecalc;
    $breakage = $breakage2+$breakage20;
    // return $totalrake2;
    // $numberofbetsamount = array();
    // $rake = array();
    $rakefinal = $totalrake+$totalpayoutunclaimed+$breakage;
    $payout = array();
    $payoutunclaimed = array();
    $totalpayout2 = $this->numberPrecision($totalpayout, 2);
      // $rake = $event->rake/100;
      // $finalrake = $key->amount * $rake;
    $a = array();
    // totalpayoutclaimedcancelled
    array_push($a, array('claimedcancelled'=>$totalpayoutclaimedcancelled,'totalcancelledunclaimed'=>$totalpayoutunclaimedcancelled,'cancelledbetsamount'=>$numberofbetsamountcancelled,'cancelledbetsamountpayout'=>$numberofbetsamountcancelled,'numberofbets'=> $betcount,'totalbetsamountfinal'=> $totalngdalawa,'rakefinal'=> $rakefinal,'totalpayout'=> $totalpayout2,'totalpayoutunclaimed'=> $totalpayoutunclaimed,'officerake'=>$totalrake,'totalcontingencyfunds'=>$totalcleanfunds,'currentcontingencyfunds'=>$currentfunds,
    'totalpayoutpaid'=>$totalpayoutss,'breakage'=>$breakage,'pick'=>3));
    return $a;
  }
  public function arenareportsmodal(Request $req)
  {
    $get = Event::where('event_name',$req->event_name)->where('pick',20)->get();
    $get1 = Event::where('event_name',$req->event_name)->where('pick',20)->first();
    $get3 = Event::where('event_name',$req->event_name)->get();
    $array = array();
    $array2 = array();
    foreach ($get as $key) {
      array_push($array,$key->id);
    }
    foreach ($get3 as $key) {
      array_push($array2,$key->id);
    }
    $pot = Potmoney::whereIn('event_id',$array)->where('pick',20)->orderBy('startingfight','desc')->get();
    $results = Results::whereIn('event_id',$array2)->select('event_id','fightnumber','result')->get();
    $a = array();
    foreach ($pot as $key) {
      $result = null;
      $last = $key->startingfight+22;
      $resultx = $results->whereBetween('fightnumber', [$key->startingfight, $last]);

      foreach ($resultx as $keys) {
        if ($result) {

          if ($keys->result === "Meron") {
            $result = $result.'M';
          }elseif ($keys->result === "Wala") {
            $result = $result.'w';
          }elseif ($keys->result === "Cancelled") {
            $result = $result.'C';
          }
          else {
            $result = $result.'D';
          }
        }
        else {
          if ($keys->result === "Meron") {
            $result = 'M';
          }elseif ($keys->result === "Wala") {
            $result = 'w';
          }elseif ($keys->result === "Cancelled") {
            $result = 'C';
          }
          else {
            $result = 'D';
          }
        }
      }
      if ($get1->status==2) {
        $bet = past_expertbet::where('event_id',$key->event_id)->where('turn',20)->where('winner','!=',0)->where('winner','!=',3)->get();
        $count = past_expertbet::where('event_id',$key->event_id)->where('turn',20)->count();
        $count2 = past_expertbet::where('event_id',$key->event_id)->where('turn',20)->select('user_id')->groupBy('user_id')->get();
      }else {
        $bet = expertbet::where('event_id',$key->event_id)->where('turn',20)->where('winner','!=',0)->where('winner','!=',3)->get();
        $count = expertbet::where('event_id',$key->event_id)->where('turn',20)->count();
        $count2 = expertbet::where('event_id',$key->event_id)->where('turn',20)->select('user_id')->groupBy('user_id')->get();
      }
      $totalplayers = count($count2);
      $winnerscount = count($bet);
      $formatdate = Carbon::parse($key->created_at)->format('M/d/Y - H:i:s A');
      if ($key->claim==0) {
        $status = 'Pending';
      }
      elseif ($key->claim==1||$key->claim==2) {
        $status = 'Finished';
      }
      // $betscount = count($bet);
      array_push($a,array('results'=>$result,'totalplayers'=>$totalplayers,'amount' => $key->amount,'claim' => $key->claim,'created_at' => $key->created_at,'event_id' => $key->event_id,'id' => $key->id,'payout' => $key->payout,'rake' => $key->rake,'remaining' => $key->remaining,
    'startingfight' => $key->startingfight,'updated_at' => $key->updated_at,'winners' => $winnerscount,'bet' => $count,'created_at_format' => $formatdate,'status'=>$status,));
    }
    // $dataxx = $this->paginate($a);
    // return $dataxx;
    return $a;
  }
  public function arenareportsmodal2(Request $req)
  {
    $get = Event::where('event_name',$req->event_name)->where('pick',2)->get();
    $get3 = Event::where('event_name',$req->event_name)->get();
    $get1 = Event::where('event_name',$req->event_name)->where('pick',2)->first();
    $array = array();
    $array2 = array();
    foreach ($get as $key) {
      array_push($array,$key->id);
    }
    foreach ($get3 as $key) {
      array_push($array2,$key->id);
    }
    $pot = Potmoney::whereIn('event_id',$array)->where('pick',2)->latest()->orderBy('startingfight','desc')->get();
    $results = Results::whereIn('event_id',$array2)->select('event_id','fightnumber','result')->get();
    // return $results;
    $a = array();
    if ($get1->status==2) {
      foreach ($pot as $key) {
        $result = null;
        $second = $key->startingfight+1;
        $resultx = $results->WhereIn('fightnumber',[$key->startingfight,$second]);
        foreach ($resultx as $keys) {
          if ($result) {

            if ($keys->result === "Meron") {
              $result = $result.'M';
            }elseif ($keys->result === "Wala") {
              $result = $result.'w';
            }elseif ($keys->result === "Cancelled") {
              $result = $result.'C';
            }
            else {
              $result = $result.'D';
            }
          }else {
            if ($keys->result === "Meron") {
              $result = 'M';
            }elseif ($keys->result === "Wala") {
              $result = 'w';
            }elseif ($keys->result === "Cancelled") {
              $result = 'C';
            }
            else {
              $result = 'D';
            }
          }
        }

        $bet = past_expertbet::where('event_id',$key->event_id)->where('turn',2)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',5)->get();
        $count = past_expertbet::where('event_id',$key->event_id)->where('turn',2)->count();
        $count2 = past_expertbet::where('event_id',$key->event_id)->where('turn',2)->select('user_id')->groupBy('user_id')->get();
        $totalplayers = count($count2);
        $winnerscount = count($bet);
        $formatdate = Carbon::parse($key->created_at)->format('M/d/Y - H:i:s A');
        if ($key->claim==0) {
          $status = 'Pending';
        }
        elseif ($key->claim==1||$key->claim==2) {
          $status = 'Finished';
        }
        // $betscount = count($bet);
        array_push($a,array('results'=>$result,'totalplayers'=>$totalplayers,'amount' => $key->amount,'claim' => $key->claim,'created_at' => $key->created_at,'event_id' => $key->event_id,'id' => $key->id,'payout' => $key->payout,'rake' => $key->rake,'remaining' => $key->remaining,
        'startingfight' => $key->startingfight,'updated_at' => $key->updated_at,'winners' => $winnerscount,'bet' => $count,'created_at_format' => $formatdate,'status'=>$status,));
      }
    }else {
      foreach ($pot as $key) {
        $result = null;
        $second = $key->startingfight+1;
        $resultx = $results->WhereIn('fightnumber',[$key->startingfight,$second]);
        foreach ($resultx as $keys) {
          if ($result) {

            if ($keys->result === "Meron") {
              $result = $result.'M';
            }elseif ($keys->result === "Wala") {
              $result = $result.'w';
            }elseif ($keys->result === "Cancelled") {
              $result = $result.'C';
            }
            else {
              $result = $result.'D';
            }
          }else {
            if ($keys->result === "Meron") {
              $result = 'M';
            }elseif ($keys->result === "Wala") {
              $result = 'w';
            }elseif ($keys->result === "Cancelled") {
              $result = 'C';
            }
            else {
              $result = 'D';
            }
          }
        }
        $bet = expertbet::where('event_id',$key->event_id)->where('turn',2)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',5)->get();
        $count = expertbet::where('event_id',$key->event_id)->where('turn',2)->count();
        $count2 = expertbet::where('event_id',$key->event_id)->where('turn',2)->select('user_id')->groupBy('user_id')->get();
        $totalplayers = count($count2);
        $winnerscount = count($bet);
        $formatdate = Carbon::parse($key->created_at)->format('M/d/Y - H:i:s A');
        if ($key->claim==0) {
          $status = 'Pending';
        }
        elseif ($key->claim==1||$key->claim==2) {
          $status = 'Finished';
        }
        // $betscount = count($bet);
        array_push($a,array('results'=>$result,'totalplayers'=>$totalplayers,'amount' => $key->amount,'claim' => $key->claim,'created_at' => $key->created_at,'event_id' => $key->event_id,'id' => $key->id,'payout' => $key->payout,'rake' => $key->rake,'remaining' => $key->remaining,
        'startingfight' => $key->startingfight,'updated_at' => $key->updated_at,'winners' => $winnerscount,'bet' => $count,'created_at_format' => $formatdate,'status'=>$status,));
      }
    }
    // $dataxx = $this->paginate($a);
    return $a;
  }
  public function arenareportsmodal3(Request $req)
  {
    $get = Event::where('event_name',$req->event_name)->get();
    $get1 = Event::where('event_name',$req->event_name)->first();
    $array = array();
    foreach ($get as $key) {
      array_push($array,$key->id);
    }
    $pot = Potmoney::whereIn('event_id',$array)->latest()->orderBy('startingfight','desc')->get();
    $results = Results::whereIn('event_id',$array)->select('event_id','fightnumber','result')->get();
    $a = array();
    foreach ($pot as $key) {
      $result = null;
      if ($key->pick==20) {
        $last = $key->startingfight+22;
        $resultx = $results->whereBetween('fightnumber', [$key->startingfight, $last]);

        foreach ($resultx as $keys) {
          if ($result) {

            if ($keys->result === "Meron") {
              $result = $result.'M';
            }elseif ($keys->result === "Wala") {
              $result = $result.'w';
            }elseif ($keys->result === "Cancelled") {
              $result = $result.'C';
            }
            else {
              $result = $result.'D';
            }
          }
          else {
            if ($keys->result === "Meron") {
              $result = 'M';
            }elseif ($keys->result === "Wala") {
              $result = 'w';
            }elseif ($keys->result === "Cancelled") {
              $result = 'C';
            }
            else {
              $result = 'D';
            }
          }
        }
      }else {
        $second = $key->startingfight+1;
        $resultx = $results->WhereIn('fightnumber',[$key->startingfight,$second]);
        foreach ($resultx as $keys) {
          if ($result) {

            if ($keys->result === "Meron") {
              $result = $result.'M';
            }elseif ($keys->result === "Wala") {
              $result = $result.'w';
            }elseif ($keys->result === "Cancelled") {
              $result = $result.'C';
            }
            else {
              $result = $result.'D';
            }
          }else {
            if ($keys->result === "Meron") {
              $result = 'M';
            }elseif ($keys->result === "Wala") {
              $result = 'w';
            }elseif ($keys->result === "Cancelled") {
              $result = 'C';
            }
            else {
              $result = 'D';
            }
          }
        }
      }



      if ($get1->status==2) {
        $bet = past_expertbet::where('event_id',$key->event_id)->where('winner','!=',0)->where('winner','!=',3)->get();
        $count = past_expertbet::where('event_id',$key->event_id)->count();
        $count2 = past_expertbet::where('event_id',$key->event_id)->select('user_id')->groupBy('user_id')->get();
      }else {
        $bet = expertbet::where('event_id',$key->event_id)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',5)->get();
        $count = expertbet::where('event_id',$key->event_id)->count();
        $count2 = expertbet::where('event_id',$key->event_id)->select('user_id')->groupBy('user_id')->get();
      }
      $totalplayers = count($count2);
      $winnerscount = count($bet);
      $formatdate = Carbon::parse($key->created_at)->format('M/d/Y - H:i:s A');
      if ($key->claim==0) {
        $status = 'Pending';
      }
      elseif ($key->claim==1||$key->claim==2) {
        $status = 'Finished';
      }
      // $betscount = count($bet);
      array_push($a,array('results'=>$result,'totalplayers'=>$totalplayers,'amount' => $key->amount,'claim' => $key->claim,'created_at' => $key->created_at,'event_id' => $key->event_id,'id' => $key->id,'payout' => $key->payout,'rake' => $key->rake,'remaining' => $key->remaining,
    'startingfight' => $key->startingfight,'updated_at' => $key->updated_at,'winners' => $winnerscount,'bet' => $count,'created_at_format' => $formatdate,'status'=>$status,));
    }
    // $dataxx = $this->paginate($a);
    // return $dataxx;
    return $a;
  }
  public function totalarenareports(Request $req)
  {
    $control = control::first();
	$get = Event::where('id',$req->event_id)->first();
	if($get->status==1)
	{
	$payout = expertbet::where('event_id',$req['event_id'])->where('winner','!=',3)->where('winner','!=',0)->sum('result');
    $totalpayoutss = expertbet::where('event_id',$req['event_id'])->where('winner','!=',0)->where('winner','!=',3)->where('claimed',1)->sum('result');
	$totalamountbet20 = expertbet::where('event_id',$req['event_id'])->sum('amount');
	$totalamountbet2 = expertbet::where('event_id',$req['event_id'])->where('turn',20)->sum('amount');
    $totalamountbet = potmoney::where('event_id',$req['event_id'])->sum('amount');
    $totalunclaimed = expertbet::where('event_id',$req['event_id'])->where('claimed',null)->where('winner','!=',0)->where('winner','!=',3)->sum('result');
	}else{
	$payout = past_expertbet::where('event_id',$req['event_id'])->where('winner','!=',3)->where('winner','!=',0)->sum('result');
    $totalpayoutss = past_expertbet::where('event_id',$req['event_id'])->where('winner','!=',0)->where('winner','!=',3)->where('claimed',1)->sum('result');
	$totalamountbet20 = past_expertbet::where('event_id',$req['event_id'])->sum('amount');
	$totalamountbet2 = past_expertbet::where('event_id',$req['event_id'])->where('turn',20)->sum('amount');
    $totalamountbet = potmoney::where('event_id',$req['event_id'])->sum('amount');
    $totalunclaimed = past_expertbet::where('event_id',$req['event_id'])->where('claimed',null)->where('winner','!=',0)->where('winner','!=',3)->sum('result');
	}
    
    
    $totalamountbet3 = potmoney::where('event_id',$req['event_id'])->where('pick',2)->sum('amount');
    $totaldeductedabono = potmoney::where('event_id',$req['event_id'])->sum('deductedtojackpot');
    
    $rake = $control->rake/100;
    $rakepick2 = $control->rakepick2/100;
    $funds = $control->percentage_jackpot/100;
    $totalrake = $totalamountbet20 * $rake;
    $totalrakepick2 = $totalamountbet * $rakepick2;
    $totalrakepick2lang = $totalamountbet3 * $rakepick2;
    $totalrakepick20lang = $totalamountbet2 * $rake;
    $overallrake = $totalrakepick2lang+$totalrakepick20lang;
    $totalcleanfunds = $totalamountbet20 * $funds;
    $totalincome1 = $totalamountbet - $totalrake;
    $totalincome2 = $totalincome1 - $totalcleanfunds;
    $netfees20 = $totalamountbet2-$totalrakepick20lang-$totalcleanfunds;
    $netfees2 = $totalamountbet3-$totalrakepick2lang;
    $payout2 = $this->numberPrecision($payout, 0);
    $newtotalunclaimed = $this->numberPrecision($totalunclaimed, 2) - $this->numberPrecision($totalunclaimed, 0);
    $breakagecalc = $totalrake  + $totalcleanfunds + $payout;
    $breakage = $breakagecalc - $totalamountbet ;
    $breakage2 = $this->numberPrecision($breakage, 2) + $newtotalunclaimed;
    $totalincome = $totalrake +  $this->numberPrecision($totalunclaimed, 0) + $breakage2;
    $totalincome2 = $this->numberPrecision($totalincome, 2);

    $newtotalunclaimed2 = $this->numberPrecision($totalunclaimed, 0);
    $currentfunds = $totalcleanfunds - $totaldeductedabono;
    // $totalunclaimed = 1;
    $b = array();
    array_push($b,array('netfees2'=>$netfees2,'netfees20'=>$netfees20,'totalincome'=>$totalincome2,'totalamount'=>$totalamountbet20,'totalpayout'=>$payout2,'totalunclaimed'=>$newtotalunclaimed2,'totalrake'=> $totalrake,'cleanfunds'=>$totalcleanfunds,'breakage'=>$breakage2,'current_contingency'=>$currentfunds,'totalpayouts'=>$totalpayoutss,'rakepick2'=>$totalrakepick2,'overall'=>$overallrake ));
    return $b;
  }
  function numberPrecision($number, $decimals = 0)
  {
      $negation = ($number < 0) ? (-1) : 1;
      $coefficient = 10 ** $decimals;
      return $negation * floor((string)(abs($number) * $coefficient)) / $coefficient;
  }
  public function betsofarenareports(Request $req)
  {
    $control = control::first();
    $checkpick = Event::where('id',$req->event_id)->first();
    if ($checkpick->pick==20) {
      $rake = $control->rake / 100;
    }else {
      $rake = $control->rakepick2 / 100;
    }
    if ($req['statuss']=='Winner') {
      $status = [1];
    }elseif ($req['statuss']=='Pending') {
      $status = [0];
    }elseif ($req['statuss']=='Loss') {
      $status = [3,5];
    }

    $sql = '`barcode`,`startingfight`,`name`,(a.id) AS id,`role`,`amount`,`winner`,`result`,`claimed`,`wins`,(a.updated_at) AS updated_at,(a.created_at) AS created_at,`username`,(a.amount * ?) AS income';
    if ($checkpick->status==2) {
      if ($req['username']&&$req['statuss']) {
        $get=DB::table('past_expertbet as a')
         ->join('users as c', 'a.user_id', '=', 'c.id')
         ->where('event_id',$req['event_id'])
         ->where('username',$req['username'])
         ->whereIn('winner',$status)
         ->selectRaw($sql,[$rake])
         ->paginate(10);
      }elseif ($req['username']) {
        $get=DB::table('past_expertbet as a')
         ->join('users as c', 'a.user_id', '=', 'c.id')
         ->where('event_id',$req['event_id'])
         ->where('username',$req['username'])
         ->selectRaw($sql,[$rake])
         ->paginate(10);
      }elseif ($req['statuss']) {
        $get=DB::table('past_expertbet as a')
         ->join('users as c', 'a.user_id', '=', 'c.id')
         ->where('event_id',$req['event_id'])
         ->whereIn('winner',$status)
         ->selectRaw($sql,[$rake])
         ->paginate(10);
      }
      else {
        $get=DB::table('past_expertbet as a')
         ->join('users as c', 'a.user_id', '=', 'c.id')
         ->where('event_id',$req['event_id'])
         ->selectRaw($sql,[$rake])
         ->paginate(10);
      }
    }else {
    if ($req['username']&&$req['statuss']) {
      $get=DB::table('expertbet as a')
       ->join('users as c', 'a.user_id', '=', 'c.id')
       ->where('event_id',$req['event_id'])
       ->where('username',$req['username'])
       ->whereIn('winner',$status)
       ->selectRaw($sql,[$rake])
       ->paginate(10);
    }elseif ($req['username']) {
      $get=DB::table('expertbet as a')
       ->join('users as c', 'a.user_id', '=', 'c.id')
       ->where('event_id',$req['event_id'])
       ->where('username',$req['username'])
       ->selectRaw($sql,[$rake])
       ->paginate(10);
    }elseif ($req['statuss']) {
      $get=DB::table('expertbet as a')
       ->join('users as c', 'a.user_id', '=', 'c.id')
       ->where('event_id',$req['event_id'])
       ->whereIn('winner',$status)
       ->selectRaw($sql,[$rake])
       ->paginate(10);
    }
    else {
      $get=DB::table('expertbet as a')
       ->join('users as c', 'a.user_id', '=', 'c.id')
       ->where('event_id',$req['event_id'])
       ->selectRaw($sql,[$rake])
       ->paginate(10);
    }
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
  public function downloadall(Request $req)
  {
    $geteventpick = Event::where('id',$req['event_id'])->first();

    $control = control::first();
    if ($geteventpick->pick==20) {
      $rake = $control->rake / 100;
    }
    if ($geteventpick->pick==2) {
      $rake = $control->rakepick2 / 100;
    }
    $sql = '`barcode`,`startingfight`,`bet`,`name`,(a.id) AS id,`role`,`amount`,`winner`,`result`,`claimed`,`wins`,(a.updated_at) AS updated_at,(a.created_at) AS created_at,`username`,(a.amount * ?) AS income';
      if ($geteventpick->status==2) {
        $get=DB::table('past_expertbet as a')
        ->join('users as c', 'a.user_id', '=', 'c.id')
        ->where('event_id',$req['event_id'])
        ->selectRaw($sql,[$rake])
        ->get();
      }else {
        $get=DB::table('expertbet as a')
        ->join('users as c', 'a.user_id', '=', 'c.id')
        ->where('event_id',$req['event_id'])
        ->selectRaw($sql,[$rake])
        ->get();
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
    $sql = '`username`,sum(c.amount) AS totalbets, sum(c.amount * ?) AS totalrake,sum(c.result) AS totalpayoutpaid,`role`';
    if ($getevent->status==2) {
      // $get = DB::table('users as a')
      //  ->join('past_expertbet as c', 'a.id', '=', 'c.user_id')
      //  ->whereIn('event_id',$arrays)
      //  ->where('username',$req['username'])
      //  ->selectRaw($sql,[$rake])
      //   // >select('a.startingfight','c.bet','c.amount','c.wins','c.winner','c.result','c.lose')
      //  // ->paginate(3);
      //  ->groupBy('a.username')
      //   ->get()
      //   ->unique('username');
      //   $array = array();
      //   foreach ($get as $key) {
      //     $getuser = User::where('username',$key->username)->first();
      //     $unclaimed = past_expertbet::where('user_id',$getuser->id)->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('claimed',null)->sum('result');
      //     $claimed = past_expertbet::where('user_id',$getuser->id)->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('claimed',1)->sum('result');
      //     array_push($array,array('username'=>$key->username,'totalbets'=>$key->totalbets,'totalpayoutpaid'=>$claimed,'totalrake'=>$key->totalrake,'totalunclaimed'=>$unclaimed));
      //   }
      //   $myCollectionObj = collect($array);
      //
      //    $dataxx = $this->paginate($myCollectionObj);
      //  return $dataxx;
       $user = User::whereHas('past_expertbet', function($q) use($arrays)
       {
         $q->whereIn('event_id', $arrays);

       })
       ->withSum(['past_expertbet as totalunclaimed' => function ($query) use ($arrays) {
       $query->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->where('claimed',null);
     }],'result')
       ->withSum(['past_expertbet as totalpayoutpaid' => function ($query) use ($arrays) {
       $query->whereIn('event_id',$arrays)->where('winner','!=',0)->where('winner','!=',3)->where('winner','!=',4)->where('claimed',1);
     }],'result')
       ->withSum(['past_expertbet as totalbets' => function ($query) use ($arrays) {
       $query->whereIn('event_id',$arrays);
     }],'amount')
     ->withSum(['past_expertbet as totalpick20' => function ($query) use ($arrays) {
     $query->whereIn('event_id',$arrays)->where('turn',20)->where('winner','!=',4);
   }],'amount')
     ->withSum(['past_expertbet as totalpick2' => function ($query) use ($arrays) {
     $query->whereIn('event_id',$arrays)->where('turn',2)->where('winner','!=',4);;
   }],'amount')
     ->paginate(10);


  // >sum(DB::raw('sales.price * merchants.commission'));

       return $user;
    }else {
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
          array_push($array,array('username'=>$key->username,'role'=>$key->role,'totalbets'=>$key->totalbets,'totalpayoutpaid'=>$claimed,'totalrake'=>$key->totalrake,'totalunclaimed'=>$unclaimed));
        }
        $myCollectionObj = collect($array);

         $dataxx = $this->paginate($myCollectionObj);
       return $dataxx;
    }

  }
  public function searchusers()
  {
    // $Event = Event::select()latest()->paginate();
    // $sorted = $Event->unique('event_name');
    $data = User::where('role',3)->orWhere('role',9)->select('username','id')->get();
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

    $hour = past_expertbet::with('event')->latest()->take(300)->get()->groupBy(function($q) {
            return Carbon::parse($q->created_at)->format('d');
            // return Carbon::parse($date->created_at)->format('M/j/y g:i a');
          });
    $hour2 = expertbet::with('event')->latest()->take(300)->get()->groupBy(function($q) {
            return Carbon::parse($q->created_at)->format('d');
            // return Carbon::parse($date->created_at)->format('M/j/y g:i a');
          });
          $control = control::first();
          foreach ($hour2 as $key) {
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
    $hour = expertbet::latest()->take(300)->get()->groupBy(function($date) {

            return Carbon::parse($date->created_at)->format('d');
          });
    $hour2 = past_expertbet::latest()->take(300)->get()->groupBy(function($date) {

            return Carbon::parse($date->created_at)->format('d');
          });
          foreach ($hour as $key) {
            array_push($a, $key[0]->created_at->timestamp);
          }
          foreach ($hour2 as $key) {
            array_push($a, $key[0]->created_at->timestamp);
          }
          // foreach ($hour as $key) {
          //   array_push($a, $key[0]->created_at->timestamp);
          // }
            // $data = collect($a)->sortBy('created_at');
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
    // $totalbets = array();
    $hour = expertbet::with('event')->latest()->take(300)->get()->groupBy(function($q) {
            return Carbon::parse($q->created_at)->format('M/j ga');
            // return Carbon::parse($date->created_at)->format('M/j/y g:i a');
          });
    $hour2 = past_expertbet::with('event')->latest()->take(300)->get()->groupBy(function($q) {
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
          foreach ($hour2 as $key) {
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
          // $data = collect($b)->sortBy('x');
    return  $b;
  }
  public function perhourbets()
  {
    $a = array();
    $hour = expertbet::latest()->take(300)->get()->groupBy(function($date) {

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
    $getevent = Event::where('id',$req['id'])->first();
    $events= Event::where('event_name',$getevent->event_name)->get();
    $arrays = array();
    foreach ($events as $key) {
      array_push($arrays, $key->id);
    }
    return Transactions::with('event')->with('user')->where('cashier_id',auth()->user()->id)->whereIn('event_id',$arrays)->latest()->get();
  }
  public function geteventoftransactions()
  {
    // awd
    // $data = Event::with('transactions')->whereHas('transactions', function($q)
    // {
    //   $q->where('cashier_id','like', auth()->user()->id);
    //
    // })->latest()->get();
    // return $data;
    $data = Event::select('event_name','status','fightdate','created_at','fights','fightopened','fightclosed','id','venue')->groupBy('event_name')->latest()->paginate(10);
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
      if ($key->status==2) {
        $bet = past_expertbet::whereIn('event_id',$events)->count();
      }else {
        $bet = expertbet::whereIn('event_id',$events)->count();
      }
      array_push($a, $bet);
    }
    return $a;

  }
}
