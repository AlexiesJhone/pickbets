<?php

namespace App\Http\Controllers;

use App\Models\Results;
use App\Events\resultevent;
use App\Models\Event;
use App\Models\Prebet;
use App\Models\bet;
use App\Models\expertbet;
use App\Models\Potmoney;
use App\Models\User;
use App\Models\Logs;
use App\Models\control;
use App\Events\userupdate;
use Auth;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class PotmoneyController extends Controller
{
  public function getpotmoney()
  {
    // GET WINNINGS

    $get=Event::where('status',1)->get();
    $array = array();
    foreach ($get as $key) {
      array_push($array, $key->id);
    }
    return Potmoney::whereIn('event_id',$array)->latest()->get();
  }
  public function getliveodds(Request $req)
  {
    // GET WINNINGS
    $get=Event::where('status',1)->get();
    $array = array();
    foreach ($get as $key) {
      array_push($array, $key->id);
    }
    $getevent=Event::where('status',1)->whereIn('id',$array)->first();
      $control=control::first();
    $total = Potmoney::whereIn('event_id',$array)->where('startingfight',$req['start'])->first();
    if ($total) {
      $rake = $control->rake/100;
      $rake2 = $control->percentage_jackpot/100;
      $amount1 = $total->amount * $rake;
      $amount2 = $total->amount * $rake2;
      $finalamount = $total->amount - $amount1 - $amount2;

      return $finalamount;
    }else {
      return '0';
    }
  }
  public function getliveoddsadmin(Request $req)
  {
    // GET WINNINGS

    $data = array();
    $get=Event::where('status',1)->get();
    $array = array();
    foreach ($get as $key) {
      array_push($array, $key->id);
    }
    $control=control::first();
    $total = Potmoney::whereIn('event_id',$array)->where('startingfight',$req['start'])->first();
    if ($total) {
      $rake = $control->rake/100;
      $rake2 = $control->percentage_jackpot/100;
      $amount1 = $total->amount * $rake;
      $amount2 = $total->amount * $rake2;
      $finalamount = $total->amount - $amount1 - $amount2;
      $totalrake = $amount1 + $amount2;

      $totalbets = expertbet::where('startingfight',$req['start'])->where('event_id',$total->event_id)->count();
      array_push($data,array('odds'=>$finalamount,'totalbets'=>$totalbets,'totalrake'=>$totalrake));
      return $data;
    }else {
      return '0';
    }
  }
  public function close(Request $req)
  {
    // CLOSE CLAIMING
    $this->validate($req, [
      'id' => 'required|max:255',
      // 'amount' => 'required|max:255',
      'event_id'=> 'required',
      'startingfight'=> 'required'
    ]);
    $get=Event::where('status',1)->get();
    $array = array();
    foreach ($get as $key) {
      array_push($array, $key->id);
    }
      $control=control::first();
      $close = Potmoney::whereIn('event_id',$array)->where('startingfight',$req['startingfight'])->first();
      $getevent=Event::where('status',1)->first();
      $getevent->totalrake =$getevent->totalrake  - $close->rake;
      if ($getevent->addtojackpot) {
        $getevent->addtojackpot =$getevent->addtojackpot  - $close->addtojackpot;
      }else {
        $getevent->addtojackpot =$close->deductedtojackpot;
      }
      $getevent->save();
      $checker = expertbet::where('startingfight',$req['startingfight'])->whereIn('event_id',$array)->orderBy('wins','DESC')->first();
      $getwinners = expertbet::where('startingfight',$checker->startingfight)->whereIn('event_id',$array)->where('wins',$checker->wins)->orderBy('wins','DESC')->get();
      // $checker = bet::where('startingfight',$req['startingfight'])->where('potmoney_id',$close->id)->where('event_id',$getevent->id)->orderBy('wins','DESC')->first();
      // $getwinners = bet::where('startingfight',$checker->startingfight)->where('potmoney_id',$close->id)->where('event_id',$getevent->id)->where('wins',$checker->wins)->orderBy('wins','DESC')->get();
      foreach ($getwinners as $key) {
        $deductusercash = User::where('id',$key->user_id)->where('role',3)->first();
        if ($deductusercash) {
          $deductusercash->cash = $deductusercash->cash - $close->payout;
          $deductusercash->save();
          $resetbet = expertbet::findOrFail($key->id);
          $resetbet->claimed=null;
          $resetbet->result=null;
          $resetbet->save();
          // return $close;
          broadcast(new userupdate($deductusercash->id));
        }else {
          $resetbet = expertbet::findOrFail($key->id);
          $resetbet->result=null;
          $resetbet->save();
        }
      }
      $close->claim = 1;
      $close->rake = 0;
      $close->remaining = 0;
      $close->payout = 0;
      // $close->addtojackpot = null;
      $close->save();

      // create logs
      $createlogs = new Logs();
      $createlogs->type = 'Confirmed_Close_Claiming_Bets';
      $createlogs->user_id = auth()->user()->id;
      $getuser1 = User::findOrFail($req['user_id']);
      $createlogs->message = auth()->user()->username.' Confirmed '.$getuser1->username.' for close claiming bets, for startingfight number '.$req['startingfight'];
      $createlogs->save();
      broadcast(new resultevent('awd',$req['startingfight'],auth()->user()->name,'confirmclaimedclose',$req['id'],auth()->user()->id,'id','id'))->toOthers();
      return $getwinners;
  }
  public function confirmclaim(Request $req)
  {
    // CLOSE CONFRIM CLAIMING
    $this->validate($req, [
      'id' => 'required|max:255',
      // 'amount' => 'required|max:255',
      'event_id'=> 'required',
      'startingfight'=> 'required'
    ]);

    $createlogs = new Logs();
    $createlogs->type = 'Request_Close_Claiming_bets';
    $createlogs->user_id = auth()->user()->id;
    $createlogs->message = auth()->user()->name.' Requested close claiming bets for startingfight '.$req['startingfight'].'.';
    $createlogs->save();

    broadcast(new resultevent($req['control'],$req['startingfight'],auth()->user()->name,'confirmclaimclose',$req['id'],auth()->user()->id,'id','id'))->toOthers();

  }
  public function confirmclaim2(Request $req)
  {
    // OPEN CONFIRM CLAIMING
    $this->validate($req, [
      'id' => 'required|max:255',
      // 'amount' => 'required|max:255',
      'event_id'=> 'required',
      'startingfight'=> 'required'
    ]);

    $createlogs = new Logs();
    $createlogs->type = 'Request_Open_Claiming_bets';
    $createlogs->user_id = auth()->user()->id;
    $createlogs->message = auth()->user()->name.' Requested open claimimg bets for startingfight '.$req['startingfight'].'.';
    $createlogs->save();


    broadcast(new resultevent($req['control'],$req['startingfight'],auth()->user()->name,'confirmclaimopen',$req['id'],auth()->user()->id,'id','id'))->toOthers();

  }
  public function open(Request $req)
  {
    // OPEN CLAIMING FOR SPECIFIC STARTING FIGHT NUMBER

    $this->validate($req, [
      'id' => 'required|max:255',
      // 'amount' => 'required|max:255',
      'event_id'=> 'required',
      'startingfight'=> 'required'
    ]);
    $get=Event::where('status',1)->get();
    $array = array();
    foreach ($get as $key) {
      array_push($array, $key->id);
    }
    $getevent=Event::where('status',1)->first();
    $checker = expertbet::where('startingfight',$req['startingfight'])->whereIn('event_id',$array)->orderBy('wins','DESC')->first();
    $getwinners = expertbet::where('startingfight',$checker->startingfight)->whereIn('event_id',$array)->where('wins',$checker->wins)->orderBy('wins','DESC')->get();
    $countwinners = count($getwinners);

    $control=control::first();
    $open = Potmoney::whereIn('event_id',$array)->where('startingfight',$req['startingfight'])->where('claim',1)->first();
    if ($checker->wins===$control->pick) {
      $open->claim = 2;
      $c1 = $control->jackpot+$getevent->addtojackpot;
      $c2 = $c1/$countwinners;
      $open->payout = $c2;
      $open->remaining = $c1;
      $open->rake = - $control->jackpot;
      $open->addtojackpot =null;
      $open->deductedtojackpot = $getevent->addtojackpot;
      $open->save();
      $getevent->totalrake = $getevent->totalrake - $getevent->jackpot - $getevent->addtojackpot;
      $getevent->addtojackpot =null;
      $getevent->save();
      foreach ($getwinners as $key) {
        $checkuser = User::where('id',$key->user_id)->first();
        if ($checkuser->role == 3) {
          $checkuser->cash = $checkuser->cash + $open->payout;
          $checkuser->save();

          $betclaim = expertbet::findOrFail($key->id);
          $betclaim->claimed = 1;
          $betclaim->save();

          $open->remaining = $open->remaining - $open->payout;
          $open->save();
          $createlogs = new Logs();
          $createlogs->type = 'Confirmed_Open_Claiming_Bets';
          $createlogs->user_id = $checkuser->id;
          $getuser1 = User::findOrFail($checkuser->id);
          $createlogs->message =$getuser1->username.' wins '.$open->payout.', from startingfight number : '.$open->startingfight.' from '.$getevent->event_name.' event, [New balance : '.substr($checkuser->cash, 0, -1).']';
          $createlogs->save();
          broadcast(new userupdate($checkuser->id));
        }
      }
      foreach ($getwinners as $key) {
        $inputresultwin = expertbet::findOrFail($key->id);
        $inputresultwin->result = $open->payout;
        $inputresultwin->save();
      }
    }else {
      $control=control::first();
      $open->claim = 2;
      $tojackpot = $control->percentage_jackpot/100;
      $tojackpot2 = $tojackpot*$open->amount;
      // return $control;
      $c1 = $control->rake/100;
      $c2 = $c1*$open->amount;
      $open->rake=$c2;
      $c3 = $open->amount-$c2;
      $c5 =  $open->amount-$tojackpot2-$c2;
      $c4 = $c5/$countwinners;
      $open->payout = $c4;
      $open->remaining = $c5;
      // $open->remaining = $c3;
      $open->addtojackpot = $tojackpot2;
      $open->save();
      $getevent->totalrake = $getevent->totalrake+$open->rake;
      $getevent->addtojackpot = $getevent->addtojackpot+$tojackpot2;
      $getevent->save();
      foreach ($getwinners as $key) {
        $checkuser = User::where('id',$key->user_id)->first();
        if ($checkuser->role == 3) {
          $checkuser->cash = $checkuser->cash + $open->payout;
          $checkuser->save();
          $open->remaining = $open->remaining - $open->payout;
          $open->save();
          $createlogs = new Logs();
          $createlogs->type = 'Confirmed_Open_Claiming_Bets';
          $createlogs->user_id = $checkuser->id;
          $getuser1 = User::findOrFail($checkuser->id);
          $createlogs->message =$getuser1->username.' wins '.substr($open->payout, 0, -1).', from startingfight number : '.$open->startingfight.' from '.$getevent->event_name.' event, [New balance : '.substr($checkuser->cash, 0, -1).']';
          $createlogs->save();
          broadcast(new userupdate($checkuser->id));
        }
      }
      foreach ($getwinners as $key) {
        $inputresultwin = expertbet::findOrFail($key->id);
        $checkuser1 = User::where('id',$inputresultwin->user_id)->first();
        $inputresultwin->result = $open->payout;
        if ($checkuser1->role===3) {
          $inputresultwin->claimed = 1;
        }
        $inputresultwin->save();
      }
    }
    // create logs
    $createlogs = new Logs();
    $createlogs->type = 'Confirmed_Open_Claiming_Bets';
    $createlogs->user_id = auth()->user()->id;
    $getuser1 = User::findOrFail($req['user_id']);
    $createlogs->message = auth()->user()->username.' Confirmed '.$getuser1->username.' for open claiming bet, for startingfight number '.$req['startingfight'].'.';
    $createlogs->save();
    broadcast(new resultevent('awd',$req['startingfight'],auth()->user()->name,'confirmclaimopened',$req['id'],auth()->user()->id,'id','id'))->toOthers();
  }
}
