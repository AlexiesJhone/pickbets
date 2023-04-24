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
use Illuminate\Support\Facades\DB;
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
    $get1=Event::where('id',$req['event_id'])->first();
    $get=Event::where('event_name',$get1->event_name)->get();
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
      $count2 = expertbet::where('startingfight',$req['start'])->where('event_id',$total->event_id)->select('user_id')->groupBy('user_id')->get();
      $totalplayers = count($count2);
      array_push($data,array('odds'=>$finalamount,'totalbets'=>$totalbets,'totalrake'=>$totalrake,'totalplayers'=>$totalplayers));
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
    $get1=Event::where('id',$req['event_id'])->first();
    $get=Event::where('event_name',$get1->event_name)->get();
    $array = array();
    foreach ($get as $key) {
      array_push($array, $key->id);
    }
      $control=control::first();
      $close = Potmoney::whereIn('event_id',$array)->where('startingfight',$req['startingfight'])->first();
      $getevent=Event::where('status',1)->first();
      $getevent->totalrake =$getevent->totalrake  - $close->rake;
      if ($control->addtojackpot) {
        $getevent->addtojackpot =$getevent->addtojackpot  - $close->addtojackpot;
        if ($close->deductedtojackpot) {
          $computed = $close->addtojackpot - $close->deductedtojackpot;
          $control->addtojackpot = $control->addtojackpot - $computed;
          $control->save();
          $close->deductedtojackpot = null;
          $close->save();
        }else {
          $control->addtojackpot = $control->addtojackpot - $close ->addtojackpot;
          $control->save();
          $close->deductedtojackpot = null;
          $close->save();
        }


      }else {
        $getevent->addtojackpot =$close->deductedtojackpot;
        $control->addtojackpot =$close->deductedtojackpot;
        $control->save();
      }
      $getevent->save();
      $checker = expertbet::where('startingfight',$req['startingfight'])->whereIn('event_id',$array)->orderBy('wins','DESC')->first();
      $getwinners = expertbet::where('startingfight',$checker->startingfight)->whereIn('event_id',$array)->where('winner',1)->orderBy('wins','DESC')->get();
      // $checker = bet::where('startingfight',$req['startingfight'])->where('potmoney_id',$close->id)->where('event_id',$getevent->id)->orderBy('wins','DESC')->first();
      // $getwinners = bet::where('startingfight',$checker->startingfight)->where('potmoney_id',$close->id)->where('event_id',$getevent->id)->where('wins',$checker->wins)->orderBy('wins','DESC')->get();
      foreach ($getwinners as $key) {
        $deductusercash = User::where('id',$key->user_id)->where('role',3)->first();
        if ($deductusercash) {
          $resetbet = expertbet::findOrFail($key->id);
          $resetbet->claimed=null;
          $deductusercash->cash = $deductusercash->cash - $resetbet->result;
          $resetbet->result=null;
          $deductusercash->save();
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
     $result = DB::transaction(function () use($req) {
    $get1=Event::where('id',$req['event_id'])->first();
    $get=Event::where('event_name',$get1->event_name)->get();
    $array = array();
    foreach ($get as $key) {
      array_push($array, $key->id);
    }
    $getevent=Event::where('status',1)->first();
    $checker = expertbet::where('startingfight',$req['startingfight'])->whereIn('event_id',$array)->orderBy('wins','DESC')->first();
    $control=control::first();
    $open = Potmoney::whereIn('event_id',$array)->where('startingfight',$req['startingfight'])->where('claim',1)->first();
    // $getwinners = expertbet::where('startingfight',$checker->startingfight)->whereIn('event_id',$array)->where('winner',1)->select('wins')->groupBy('wins')->orderBy('wins','DESC')->get();
    $getwinners = expertbet::where('startingfight',$checker->startingfight)->whereIn('event_id',$array)->where('winner',1)->get();


    // PAG JACKPOT ANG NANALO.
    if ($checker->wins===$control->pick) {
      $open->claim = 2;
      $officialrake = $control->rake/100;
      $officialpercentagejackpot = $control->percentage_jackpot/100;
      $open->rake =  $open->amount*$officialrake;
      $open->addtojackpot =  $open->amount*$officialpercentagejackpot;
      $open->save();
  	  $getevent->totalrake = $open->amount*$officialrake;
  	  $getevent->save();
      $control->addtojackpot = $open->amount*$officialpercentagejackpot + $control->addtojackpot;
      $control->save();
      $highest = $getwinners->max('wins');
      $lowest0 = $getwinners->where('wins',0)->first();
      $lowest0count = $getwinners->where('wins',0)->count();
      $lowest1 = $getwinners->where('wins',1)->first();
      $lowest1count = $getwinners->where('wins',1)->count();
      $lowest2 = $getwinners->where('wins',2)->first();
      $lowest2count = $getwinners->where('wins',2)->count();
      $lowest3 = $getwinners->where('wins',3)->first();
      $lowest3count = $getwinners->where('wins',3)->count();
      if ($highest==20) {
        // $total = $open->remaining = $open->amount*0.80;
        // $finaltotal = $total;
        $computepayoutremaining = $open->amount*0.80;
        $open->payout = $control->jackpot+$computepayoutremaining;
        $open->remaining =$computepayoutremaining;
        $open->save();
      }
      // if ($highest==19) {
      //   $total = $open->remaining = $open->amount*0.80;
      //   $finaltotal = $total-5000;
      //   $open->payout = $finaltotal;
      //   $open->remaining =$finaltotal;
      // }if ($highest==18) {
      //   $total = $open->remaining = $open->amount*0.80;
      //   $finaltotal = $total-2000;
      //   $open->payout = $finaltotal;
      //   $open->remaining =$finaltotal;
      // }if ($highest<18 && $highest>2) {
      //   $open->remaining = $open->amount*0.80;
      //   $open->payout = $open->amount*0.80;
      // }
      // $open->save();

      if ($lowest0) {
        $total_ng_bawas = 10000*$lowest0count;
        $finaltotal = $open->remaining-$total_ng_bawas;
        $open->payout = $finaltotal;
        $open->remaining = $finaltotal;
        $open->save();
      }if ($lowest1) {
        $total_ng_bawas = 3000*$lowest2count;
        $finaltotal = $open->remaining-5000;
        $open->payout = $finaltotal;
        $open->remaining = $finaltotal;
        $open->save();
      }if ($lowest2) {
        $total_ng_bawas = 2000*$lowest2count;
        $finaltotal = $open->remaining-$total_ng_bawas;
        $open->payout = $finaltotal;
        $open->remaining = $finaltotal;
        $open->save();
      }if ($lowest3) {
        $total_ng_bawas = 500*$lowest2count;
        $finaltotal = $open->remaining-$total_ng_bawas;
        $open->payout = $finaltotal;
        $open->remaining = $finaltotal;
        $open->save();
      }

      // HIGHEST SCORE

      $highest = $getwinners->max('wins');
      $counthighest = $getwinners->where('wins',$highest)->count();
      $gethighest = $getwinners->where('wins',$highest);
      // if ($highest===20) {
      //   $highestpercentage = $control->jackpot;
      // }elseif ($highest===19) {
      //   $highestpercentage = 5000;
      // }elseif($highest===18) {
      //   $highestpercentage = 2000;
      // }else {
        $highestpercentage = $open->remaining*0.40;
      // }
      $finalhighestpercentage1 = $control->jackpot/$counthighest;
      $finalhighestpercentage = floor($finalhighestpercentage1);
      foreach ($gethighest as $key) {
        $insertresult = expertbet::where('id',$key->id)->first();
        $checkuser = User::where('id',$insertresult->user_id)->first();
        if ($checkuser->role===3) {
          $startingbalance = $checkuser->cash;
          $endingbalance = $checkuser->cash + $finalhighestpercentage;
          $insertresult->claimed = 1;
          $checkuser->cash = $checkuser->cash + $finalhighestpercentage;
          $checkuser->save();
          broadcast(new userupdate($checkuser->id));
		  $createlogs = new Logs();
        $createlogs->type = 'Winners';
        $createlogs->user_id = $checkuser->id;
          $createlogs->message = 'Starting Balance : '.number_format($startingbalance,2) ."\nPayout : ".number_format($finalhighestpercentage,2)."\nEnding Balance : ".number_format($endingbalance,2)."\nStartingfight : ".$open->startingfight;
        $createlogs->save();
        }
        $insertresult->result = $finalhighestpercentage;
        $insertresult->save();

      }
      // END OF HIGHEST SCORE

      // 2ND TO THE HIGHEST

      $second = $getwinners->where('wins','!=',$highest)->max('wins');
      $countsecond = $getwinners->where('wins',$second)->count();
      $getsecond = $getwinners->where('wins',$second);
      // if ($second===19) {
      //   $secondpercentage = 5000;
      // }elseif ($second===18) {
      //   $secondpercentage = 2000;
      // }else {
        $secondpercentage = $open->remaining*0.30;
      // }

      $finalsecondpercentage = $secondpercentage/$countsecond;
      $minimum2ndtothehighest = 400;

      if ($finalsecondpercentage>$minimum2ndtothehighest) {
        $finalsecondpercentage1 = $finalsecondpercentage;
      $finalsecondpercentage = floor($finalsecondpercentage1);
      }else {
        $abono2 = $minimum2ndtothehighest - $finalsecondpercentage;
        $totalabono2 = $abono2*$countsecond;
        $control->addtojackpot = $control->addtojackpot-$totalabono2;
        $control->save();
        if ($open->deductedtojackpot) {
          $open->deductedtojackpot = $open->deductedtojackpot + $totalabono2;
          $open->save();
        }else {
          $open->deductedtojackpot = $totalabono2;
          $open->save();
        }
        $createlogs = new Logs();
        $createlogs->type = 'Abono';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = 'Total Abono for Top 2 : '.number_format($totalabono2,2)."\nFightdate : ".date("m.d.y",strtotime($getevent->fightdate))."\nStarting fight: ".$open->startingfight;
        $createlogs->save();
        $finalsecondpercentage = $minimum2ndtothehighest;
      }

      foreach ($getsecond as $key) {
        $insertresult = expertbet::where('id',$key->id)->first();
        $checkuser2 = User::where('id',$insertresult->user_id)->first();
        if ($checkuser2->role===3) {
          $startingbalance = $checkuser2->cash;
          $endingbalance = $checkuser2->cash + $finalsecondpercentage;
          $insertresult->claimed = 1;
          $checkuser2->cash = $checkuser2->cash + $finalsecondpercentage;
          $checkuser2->save();
          broadcast(new userupdate($checkuser2->id));
          $createlogs = new Logs();
          $createlogs->type = 'Winners';
          $createlogs->user_id = $checkuser2->id;
          $createlogs->message = 'Starting Balance : '.number_format($startingbalance,2) ."\nPayout : ".number_format($finalsecondpercentage,2)."\nEnding Balance : ".number_format($endingbalance,2)."\nStartingfight : ".$open->startingfight;
          $createlogs->save();
        }
        $insertresult->result = $finalsecondpercentage;
        $insertresult->save();
      }
      // END OF 2ND TO THE HIGHEST

      // 3RD TO THE HIGHEST

      $third = $getwinners->where('wins','!=',$highest)->where('wins','!=',$second)->max('wins');
      $countthird = $getwinners->where('wins',$third)->count();
      $getthird= $getwinners->where('wins',$third);
      // if ($third===19) {
      //   $thirdpercentage = 5000;
      // }elseif ($third===18) {
      //   $thirdpercentage = 2000;
      // }else {
        $thirdpercentage = $open->remaining*0.20;
      // }
      $finalthirdpercentage = $thirdpercentage/$countthird;
      $minimum3rdtothehighest = 300;
      if ($finalthirdpercentage>$minimum3rdtothehighest) {
        $finalthirdpercentage1 = $finalthirdpercentage;
        $finalthirdpercentage = floor($finalthirdpercentage1);
      }else {
        $abono3 = $minimum3rdtothehighest - $finalthirdpercentage;
        $totalabono3 = $abono3*$countthird;
        $control->addtojackpot = $control->addtojackpot-$totalabono3;
        $control->save();
        if ($open->deductedtojackpot) {
          $open->deductedtojackpot = $open->deductedtojackpot + $totalabono3;
          $open->save();
        }else {
          $open->deductedtojackpot = $totalabono3;
          $open->save();
        }
        $createlogs = new Logs();
        $createlogs->type = 'Abono';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = 'Total Abono for Top 3 : '.number_format($totalabono3,2)."\nFightdate : ".date("m.d.y",strtotime($getevent->fightdate))."\nStarting fight: ".$open->startingfight;
        $createlogs->save();
        $finalthirdpercentage = $minimum3rdtothehighest;
      }
      foreach ($getthird as $key) {
        $insertresult = expertbet::where('id',$key->id)->first();
        $checkuser3 = User::where('id',$insertresult->user_id)->first();
        if ($checkuser3->role===3) {
          $startingbalance = $checkuser3->cash;
          $endingbalance = $checkuser3->cash + $finalthirdpercentage;
          $insertresult->claimed = 1;
          $checkuser3->cash = $checkuser3->cash + $finalthirdpercentage;
          $checkuser3->save();
          broadcast(new userupdate($checkuser3->id));
          $createlogs = new Logs();
          $createlogs->type = 'Winners';
          $createlogs->user_id = $checkuser3->id;
          $createlogs->message = 'Starting Balance : '.number_format($startingbalance,2) ."\nPayout : ".number_format($finalthirdpercentage,2)."\nEnding Balance : ".number_format($endingbalance,2)."\nStartingfight : ".$open->startingfight;
          $createlogs->save();
        }
        $insertresult->result = $finalthirdpercentage;
        $insertresult->save();
      }
      // END OF 3RD TO THE HIGHEST

      // 4th TO THE HIGHEST

      $fourth = $getwinners->where('wins','!=',$highest)->where('wins','!=',$second)->where('wins','!=', $third)->max('wins');
      $countfourth = $getwinners->where('wins',$fourth)->count();
      $getfourth= $getwinners->where('wins',$fourth);
      // if ($fourth===19) {
      //   $fourthpercentage = 5000;
      // }elseif ($third===18) {
      //   $fourthpercentage = 2000;
      // }else {
        $fourthpercentage = $open->remaining*0.10;
      // }
      $finalfourthpercentage = $fourthpercentage/$countfourth;
      $minimum4thtothehighest = 200;
      if ($finalfourthpercentage>$minimum4thtothehighest) {
        $finalfourthpercentage1 = $finalfourthpercentage;
        $finalfourthpercentage = floor($finalfourthpercentage1);
      }else {
        $abono4 = $minimum4thtothehighest - $finalfourthpercentage;
        $totalabono4 = $abono4*$countthird;
        $control->addtojackpot = $control->addtojackpot-$totalabono4;
        $control->save();
        if ($open->deductedtojackpot) {
          $open->deductedtojackpot = $open->deductedtojackpot + $totalabono4;
          $open->save();
        }else {
          $open->deductedtojackpot = $totalabono4;
          $open->save();
        }
        $createlogs = new Logs();
        $createlogs->type = 'Abono';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = 'Total Abono for Top 4 : '.number_format($totalabono4,2)."\nFightdate : ".date("m.d.y",strtotime($getevent->fightdate))."\nStarting fight: ".$open->startingfight;
        $createlogs->save();
        $finalfourthpercentage = $minimum4thtothehighest;
      }
      foreach ($getfourth as $key) {
        $insertresult = expertbet::where('id',$key->id)->first();
        $checkuser4 = User::where('id',$insertresult->user_id)->first();
        if ($checkuser4->role===3) {
          $startingbalance = $checkuser4->cash;
          $endingbalance = $checkuser4->cash + $finalfourthpercentage;
          $insertresult->claimed = 1;
          $checkuser4->cash = $checkuser4->cash + $finalfourthpercentage;
          $checkuser4->save();
          broadcast(new userupdate($checkuser4->id));
          $createlogs = new Logs();
          $createlogs->type = 'Winners';
          $createlogs->user_id = $checkuser4->id;
          $createlogs->message = 'Starting Balance : '.number_format($startingbalance,2) ."\nPayout : ".number_format($finalfourthpercentage,2)."\nEnding Balance : ".number_format($endingbalance,2)."\nStartingfight : ".$open->startingfight;
          $createlogs->save();
        }
        $insertresult->result = $finalfourthpercentage;
        $insertresult->save();
      }
      // END OF 4th TO THE HIGHEST

      // LOWEST SCORE

      // $lowest = $getwinners->min('wins');
      // $countlowest = $getwinners->where('wins',$lowest)->count();
      // $getlowest= $getwinners->where('wins',$lowest);
      // if ($lowest==0) {
      //   $lowestpercentage = 10000;
      // }elseif ($lowest==1) {
      //   $lowestpercentage = 5000;
      // }elseif ($lowest==2) {
      //   $lowestpercentage = 2000;
      // }else {
      //   $lowestpercentage = $open->remaining*0.10;
      // }
      // $finallowestpercentage = $lowestpercentage/$countlowest;
      //
      // foreach ($getlowest as $key) {
      //   $insertresult = expertbet::where('id',$key->id)->first();
      //   $checkuser4 = User::where('id',$insertresult->user_id)->first();
      //   if ($checkuser4->role===3) {
      //     $insertresult->claimed = 1;
      //     $checkuser4->cash = $checkuser4->cash + $finallowestpercentage;
      //     $checkuser4->save();
      //     broadcast(new userupdate($checkuser4->id));
      //     $createlogs = new Logs();
      //     $createlogs->type = 'Winners';
      //     $createlogs->user_id = $checkuser4->id;
      //     $createlogs->message = $checkuser4->name.',Recieved '.$finallowestpercentage;
      //     $createlogs->save();
      //   }
      //   $insertresult->result = $finallowestpercentage;
      //   $insertresult->save();
      // }
      // END LOWEST SCORE
      // LOWEST SCORE 0
      $countlowest0 = $getwinners->where('wins',0)->count();
      $getlowest0= $getwinners->where('wins',0);
      if ($countlowest0) {
        $finallowestpercentage0 = 10000/$countlowest0;
      }

      foreach ($getlowest0 as $key) {
        $insertresult = expertbet::where('id',$key->id)->first();
        $checkuser5 = User::where('id',$insertresult->user_id)->first();
        if ($checkuser5->role===3) {
          $startingbalance = $checkuser5->cash;
          $endingbalance = $checkuser5->cash + $finallowestpercentage0;
          $insertresult->claimed = 1;
          $checkuser5->cash = $checkuser5->cash + $finallowestpercentage0;
          $checkuser5->save();
          broadcast(new userupdate($checkuser5->id));
          $createlogs = new Logs();
          $createlogs->type = 'Winners';
          $createlogs->user_id = $checkuser5->id;
          $createlogs->message = 'Starting Balance : '.number_format($startingbalance,2) ."\nConsolation Payout : ".number_format($finallowestpercentage0,2)."\nEnding Balance : ".number_format($endingbalance,2)."\nStartingfight : ".$open->startingfight;
          $createlogs->save();
        }
        $insertresult->result = $finallowestpercentage0;
        $insertresult->save();
      }
      // END LOWEST SCORE 0

      // LOWEST SCORE 1

      $countlowest1 = $getwinners->where('wins',1)->count();
      $getlowest1= $getwinners->where('wins',1);
      if ($countlowest1) {
        $finallowestpercentage1 = 5000/$countlowest1;
      }

      foreach ($getlowest1 as $key) {
        $insertresult = expertbet::where('id',$key->id)->first();
        $checkuser6 = User::where('id',$insertresult->user_id)->first();
        if ($checkuser6->role===3) {
          $startingbalance = $checkuser6->cash;
          $endingbalance = $checkuser6->cash + $finallowestpercentage1;
          $insertresult->claimed = 1;
          $checkuser6->cash = $checkuser6->cash + $finallowestpercentage1;
          $checkuser6->save();
          broadcast(new userupdate($checkuser6->id));
          $createlogs = new Logs();
          $createlogs->type = 'Winners';
          $createlogs->user_id = $checkuser6->id;
          $createlogs->message = 'Starting Balance : '.number_format($startingbalance,2) ."\nConsolation Payout : ".number_format($finallowestpercentage1,2)."\nEnding Balance : ".number_format($endingbalance,2)."\nStartingfight : ".$open->startingfight;
          $createlogs->save();
        }
        $insertresult->result = $finallowestpercentage1;
        $insertresult->save();
      }
      // END LOWEST SCORE 1

      // LOWEST SCORE 2

      $countlowest2 = $getwinners->where('wins',2)->count();
      $getlowest2= $getwinners->where('wins',2);
      if ($countlowest2) {
        $finallowestpercentage2 = 2000/$countlowest2;
      }

      foreach ($getlowest2 as $key) {
        $insertresult = expertbet::where('id',$key->id)->first();
        $checkuser7 = User::where('id',$insertresult->user_id)->first();
        if ($checkuser7->role===3) {
          $startingbalance = $checkuser7->cash;
          $endingbalance = $checkuser7->cash + $finallowestpercentage2;
          $insertresult->claimed = 1;
          $checkuser7->cash = $checkuser7->cash + $finallowestpercentage2;
          $checkuser7->save();
          broadcast(new userupdate($checkuser7->id));
          $createlogs = new Logs();
          $createlogs->type = 'Winners';
          $createlogs->user_id = $checkuser7->id;
          $createlogs->message = 'Starting Balance : '.number_format($startingbalance,2) ."\nConsolation Payout : ".number_format($finallowestpercentage2,2)."\nEnding Balance : ".number_format($endingbalance,2)."\nStartingfight : ".$open->startingfight;
          $createlogs->save();
        }
        $insertresult->result = $finallowestpercentage2;
        $insertresult->save();
      }
      // END LOWEST SCORE 2

      // LOWEST SCORE 3

      $countlowest3 = $getwinners->where('wins',3)->count();
      $getlowest3= $getwinners->where('wins',3);
      if ($countlowest3) {
        $finallowestpercentage3 = 2000/$countlowest2;
      }

      foreach ($getlowest3 as $key) {
        $insertresult = expertbet::where('id',$key->id)->first();
        $checkuser8 = User::where('id',$insertresult->user_id)->first();
        if ($checkuser8->role===3) {
          $startingbalance = $checkuser8->cash;
          $endingbalance = $checkuser8->cash + $finallowestpercentage3;
          $insertresult->claimed = 1;
          $checkuser8->cash = $checkuser8->cash + $finallowestpercentage3;
          $checkuser8->save();
          broadcast(new userupdate($checkuser8->id));
          $createlogs = new Logs();
          $createlogs->type = 'Winners';
          $createlogs->user_id = $checkuser8->id;
          $createlogs->message = 'Starting Balance : '.number_format($startingbalance,2) ."\nConsolation Payout : ".number_format($finallowestpercentage3,2)."\nEnding Balance : ".number_format($endingbalance,2)."\nStartingfight : ".$open->startingfight;
          $createlogs->save();
        }
        $insertresult->result = $finallowestpercentage3;
        $insertresult->save();
      }
      // END LOWEST SCORE 3

      broadcast(new resultevent('awd',$req['startingfight'],auth()->user()->name,'confirmclaimopened',$req['id'],auth()->user()->id,'id','id'))->toOthers();

      return 'success';
    }

    // PAG HINDI JACKPOT ANG NANALO.
    else {
      $open->claim = 2;
      $officialrake = $control->rake/100;
      $officialpercentagejackpot = $control->percentage_jackpot/100;
      $open->rake =  $open->amount*$officialrake ;
      $open->addtojackpot =  $open->amount*$officialpercentagejackpot;
      $pinagsamangrake = $officialrake+$officialpercentagejackpot;
      $officerake = $open->amount*$pinagsamangrake;
      $totalngbabaliksaplayers = $open->amount-$officerake;
      // return $totalngbabaliksaplayers;
      $open->remaining = $totalngbabaliksaplayers;
      $open->save();
  	  $getevent->totalrake = $open->amount*$officialrake;
  	  $getevent->save();
      $control->addtojackpot = $control->addtojackpot + $open->amount*$officialpercentagejackpot;
      $control->save();
      $highest = $getwinners->max('wins');
      $lowest0 = $getwinners->where('wins',0)->first();
      $lowest0count = $getwinners->where('wins',0)->count();
      $lowest1 = $getwinners->where('wins',1)->first();
      $lowest1count = $getwinners->where('wins',1)->count();
      $lowest2 = $getwinners->where('wins',2)->first();
      $lowest2count = $getwinners->where('wins',2)->count();

      $lowest3 = $getwinners->where('wins',3)->first();
      $lowest3count = $getwinners->where('wins',3)->count();

      // if ($highest==19) {
      //   $total = $open->remaining = $open->amount*0.80;
      //   $finaltotal = $total-5000;
      //   $open->payout = $finaltotal;
      //   $open->remaining =$finaltotal;
      // }if ($highest==18) {
      //   $total = $open->remaining = $open->amount*0.80;
      //   $finaltotal = $total-2000;
      //   $open->payout = $finaltotal;
      //   $open->remaining =$finaltotal;
      // }if ($highest<18 && $highest>2) {
      //   $open->remaining = $open->amount*0.80;
      //   $open->payout = $open->amount*0.80;
      // }
      // $open->save();

      if ($lowest0) {
        $total_ng_bawas = 10000*$lowest0count;
        $finaltotal = $open->remaining-$total_ng_bawas;
        $open->payout = $finaltotal;
        $open->remaining = $finaltotal;
        $open->save();
        // return $lowest0;
      }if ($lowest1) {
        $total_ng_bawas = 3000*$lowest1count;
        $finaltotal = $open->remaining-$total_ng_bawas;
        $open->payout = $finaltotal;
        $open->remaining = $finaltotal;
        $open->save();
        // return $finaltotal;
      }if ($lowest2) {
        $total_ng_bawas = 2000*$lowest2count;
        // return $total_ng_bawas;
        $finaltotal = $open->remaining-$total_ng_bawas;
        $open->payout = $finaltotal;
        $open->remaining = $finaltotal;
        $open->save();
        // return $finaltotal;
      }if ($lowest3) {
        $total_ng_bawas = 500*$lowest3count;
        $finaltotal = $open->remaining-$total_ng_bawas;
        $open->payout = $finaltotal;
        $open->remaining = $finaltotal;
        $open->save();
        // return $finaltotal;
      }if (!$lowest3&&!$lowest2&&!$lowest1&&!$lowest0) {
        // $finaltotal = $open->remaining;
        $open->payout = $open->payout = $open->amount*0.80;
        $open->remaining = $open->payout = $open->amount*0.80;
        $open->save();
        // return $finaltotal;
      }

      // HIGHEST SCORE

      $highest = $getwinners->max('wins');
      $counthighest = $getwinners->where('wins',$highest)->count();
      // return $counthighest;
      $gethighest = $getwinners->where('wins',$highest);
      // if ($highest===19) {
      //   $highestpercentage = 5000;
      // }elseif ($highest===18) {
      //   $highestpercentage = 2000;
      // }else {
        $highestpercentage = $open->remaining*0.40;
      // }
      $minimumhighest = 500;
      $finalhighestpercentage = $highestpercentage/$counthighest;
      if ($finalhighestpercentage>500) {
        $finalhighestpercentage1 = $highestpercentage/$counthighest;
        $finalhighestpercentage = floor($finalhighestpercentage1);
      }else {
        $abono = $minimumhighest - $finalhighestpercentage;
        $totalabono = $abono*$counthighest;
        $control->addtojackpot = $control->addtojackpot-$totalabono;
        $control->save();
        if ($open->deductedtojackpot) {
          $open->deductedtojackpot = $open->deductedtojackpot + $totalabono;
          $open->save();
        }else {
          $open->deductedtojackpot = $totalabono;
          $open->save();
        }
        $createlogs = new Logs();
        $createlogs->type = 'Abono';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = 'Total Abono for Top 1 : '.number_format($totalabono,2)."\nFightdate : ".date("m.d.y",strtotime($getevent->fightdate))."\nStarting fight: ".$open->startingfight;
        $createlogs->save();
        $finalhighestpercentage = $minimumhighest;
      }
      // return $abono;
      foreach ($gethighest as $key) {
        $insertresult = expertbet::where('id',$key->id)->first();
        $checkuser9 = User::where('id',$insertresult->user_id)->first();

        if ($checkuser9->role===3) {
          $startingbalance = $checkuser9->cash;
          $endingbalance = $checkuser9->cash + $finalhighestpercentage;
          $insertresult->claimed = 1;
          $checkuser9->cash = $checkuser9->cash + $finalhighestpercentage;
          $checkuser9->save();
          broadcast(new userupdate($checkuser9->id));
          $createlogs = new Logs();
          $createlogs->type = 'Winners';
          $createlogs->user_id = $checkuser9->id;
          $createlogs->message = 'Starting Balance : '.number_format($startingbalance,2) ."\nPayout : ".number_format($finalhighestpercentage,2)."\nEnding Balance : ".number_format($endingbalance,2)."\nStartingfight : ".$open->startingfight;
          $createlogs->save();
        }
        $insertresult->result = $finalhighestpercentage;
        $insertresult->save();

      }
      // END OF HIGHEST SCORE

      // 2ND TO THE HIGHEST

      $second = $getwinners->where('wins','!=',$highest)->max('wins');
      $countsecond = $getwinners->where('wins',$second)->count();
      $getsecond = $getwinners->where('wins',$second);
      // if ($second===19) {
      //   $secondpercentage = 5000;
      // }elseif ($second===18) {
      //   $secondpercentage = 2000;
      // }else {
        $secondpercentage = $open->remaining*0.30;
      // }
      if ($countsecond) {
        $finalsecondpercentage = $secondpercentage/$countsecond;
      }else {
        $finalsecondpercentage = $secondpercentage;
      }

      $minimum2ndtothehighest = 400;
      if ($finalsecondpercentage>$minimumhighest) {
        $finalsecondpercentage1 = $finalsecondpercentage;
      $finalsecondpercentage  = floor($finalsecondpercentage1);
      }else {
        $abono2 = $minimum2ndtothehighest - $finalsecondpercentage;
        $totalabono2 = $abono2*$countsecond;
        $control->addtojackpot = $control->addtojackpot-$totalabono2;
        $control->save();
        if ($open->deductedtojackpot) {
          $open->deductedtojackpot = $open->deductedtojackpot + $totalabono2;
          $open->save();
        }else {
          $open->deductedtojackpot = $totalabono2;
          $open->save();
        }
        $createlogs = new Logs();
        $createlogs->type = 'Abono';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = 'Total Abono for Top 2 : '.number_format($totalabono2,2)."\nFightdate : ".date("m.d.y",strtotime($getevent->fightdate))."\nStarting fight: ".$open->startingfight;
        $createlogs->save();
        $finalsecondpercentage = $minimum2ndtothehighest;
      }
      foreach ($getsecond as $key) {
        $insertresult = expertbet::where('id',$key->id)->first();
        $checkuser10 = User::where('id',$insertresult->user_id)->first();
        if ($checkuser10->role===3) {
          $startingbalance = $checkuser10->cash;
          $endingbalance = $checkuser10->cash + $finalsecondpercentage;
          $insertresult->claimed = 1;
          $checkuser10->cash = $checkuser10->cash + $finalsecondpercentage;
          $checkuser10->save();
          broadcast(new userupdate($checkuser10->id));
          $createlogs = new Logs();
          $createlogs->type = 'Winners';
          $createlogs->user_id = $checkuser10->id;
          $createlogs->message = 'Starting Balance : '.number_format($startingbalance,2) ."\nPayout : ".number_format($finalsecondpercentage,2)."\nEnding Balance : ".number_format($endingbalance,2)."\nStartingfight : ".$open->startingfight;
          $createlogs->save();
        }
        $insertresult->result = $finalsecondpercentage;
        $insertresult->save();
      }
      // END OF 2ND TO THE HIGHEST

      // 3RD TO THE HIGHEST

      $third = $getwinners->where('wins','!=',$highest)->where('wins','!=',$second)->max('wins');
      $countthird = $getwinners->where('wins',$third)->count();
      $getthird= $getwinners->where('wins',$third);
      // if ($third===19) {
      //   $thirdpercentage = 5000;
      // }elseif ($third===18) {
      //   $thirdpercentage = 2000;
      // }else {
        $thirdpercentage = $open->remaining*0.20;
      // }
      if ($countthird) {
        $finalthirdpercentage = $thirdpercentage/$countthird;
      }else {
        $finalthirdpercentage = $thirdpercentage;
      }


      $minimum3rdtothehighest = 300;
      if ($finalthirdpercentage>$minimum3rdtothehighest) {
        $finalthirdpercentage1 = $thirdpercentage/$countthird;
      $finalthirdpercentage = floor($finalthirdpercentage1);
      }else {
        $abono3 = $minimum3rdtothehighest - $finalthirdpercentage;
        $totalabono3 = $abono3*$counthighest;
        $control->addtojackpot = $control->addtojackpot-$totalabono3;
        $control->save();
        if ($open->deductedtojackpot) {
          $open->deductedtojackpot = $open->deductedtojackpot + $totalabono3;
          $open->save();
        }else {
          $open->deductedtojackpot = $totalabono3;
          $open->save();
        }
        $createlogs = new Logs();
        $createlogs->type = 'Abono';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = 'Total Abono for Top 3 : '.number_format($totalabono3,2) ."\nFightdate : ".date("m.d.y",strtotime($getevent->fightdate))."\nStarting fight: ".$open->startingfight;
        $createlogs->save();
        $finalthirdpercentage = $minimum3rdtothehighest;
      }

      foreach ($getthird as $key) {
        $insertresult = expertbet::where('id',$key->id)->first();
        $checkuser11 = User::where('id',$insertresult->user_id)->first();
        if ($checkuser11->role===3) {
          $startingbalance = $checkuser11->cash;
          $endingbalance = $checkuser11->cash + $finalthirdpercentage;
          $insertresult->claimed = 1;
          $checkuser11->cash = $checkuser11->cash + $finalthirdpercentage;
          $checkuser11->save();
          broadcast(new userupdate($checkuser11->id));
          $createlogs = new Logs();
          $createlogs->type = 'Winners';
          $createlogs->user_id = $checkuser11->id;
          $createlogs->message = 'Starting Balance : '.number_format($startingbalance,2) ."\nPayout : ".number_format($finalthirdpercentage,2)."\nEnding Balance : ".number_format($endingbalance,2)."\nStartingfight : ".$open->startingfight;
          $createlogs->save();
        }
        $insertresult->result = $finalthirdpercentage;
        $insertresult->save();
      }
      // END OF 3RD TO THE HIGHEST

      // END OF 4th TO THE HIGHEST

      $fourth = $getwinners->where('wins','!=',$highest)->where('wins','!=',$second)->where('wins','!=', $third)->max('wins');
      $countfourth = $getwinners->where('wins',$fourth)->count();
      $getfourth= $getwinners->where('wins',$fourth);
      // if ($fourth===19) {
      //   $fourthpercentage = 5000;
      // }elseif ($third===18) {
      //   $fourthpercentage = 2000;
      // }else {
        $fourthpercentage = $open->remaining*0.10;
      // }
      if ($countfourth) {
        $finalfourthpercentage = $fourthpercentage/$countfourth;
      }else {
        $finalfourthpercentage = $fourthpercentage;
      }
      $minimum4thtothehighest = 200;
      if ($finalfourthpercentage>$minimum4thtothehighest) {
        $finalfourthpercentage1 = $fourthpercentage/$countfourth;
      $finalfourthpercentage = floor($finalfourthpercentage1);
      }else {
        $abono4 = $minimum4thtothehighest - $finalfourthpercentage;
        $totalabono4 = $abono4*$countfourth;
        $control->addtojackpot = $control->addtojackpot-$totalabono4;
        $control->save();
        if ($open->deductedtojackpot) {
          $open->deductedtojackpot = $open->deductedtojackpot + $totalabono4;
          $open->save();
        }else {
          $open->deductedtojackpot = $totalabono4;
          $open->save();
        }
        $createlogs = new Logs();
        $createlogs->type = 'Abono';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = 'Total Abono for Top 4 : '.number_format($totalabono4,2)."\nFightdate : ".date("m.d.y",strtotime($getevent->fightdate))."\nStarting fight: ".$open->startingfight;
        $createlogs->save();
        $finalfourthpercentage = $minimum4thtothehighest;
      }
      foreach ($getfourth as $key) {
        $insertresult = expertbet::where('id',$key->id)->first();
        $checkuser12 = User::where('id',$insertresult->user_id)->first();
        if ($checkuser12->role===3) {
          $startingbalance = $checkuser12->cash;
          $endingbalance = $checkuser12->cash + $finalfourthpercentage;
          $insertresult->claimed = 1;
          $checkuser12->cash = $checkuser12->cash + $finalfourthpercentage;
          $checkuser12->save();
          broadcast(new userupdate($checkuser12->id));
          $createlogs = new Logs();
          $createlogs->type = 'Winners';
          $createlogs->user_id = $checkuser12->id;
          $createlogs->message = 'Starting Balance : '.number_format($startingbalance,2) ."\nPayout : ".number_format($finalfourthpercentage,2)."\nEnding Balance : ".number_format($endingbalance,2)."\nStartingfight : ".$open->startingfight;
          $createlogs->save();
        }
        $insertresult->result = $finalfourthpercentage;
        $insertresult->save();
      }
      // END OF 4th TO THE HIGHEST

      // LOWEST SCORE

      // $lowest = $getwinners->min('wins');
      // $countlowest = $getwinners->where('wins',$lowest)->count();
      // $getlowest= $getwinners->where('wins',$lowest);
      // if ($lowest==0) {
      //   $lowestpercentage = 10000;
      // }elseif ($lowest==1) {
      //   $lowestpercentage = 5000;
      // }elseif ($lowest==2) {
      //   $lowestpercentage = 2000;
      // }else {
      //   $lowestpercentage = $open->remaining*0.10;
      // }
      // $finallowestpercentage = $lowestpercentage/$countlowest;
      //
      // foreach ($getlowest as $key) {
      //   $insertresult = expertbet::where('id',$key->id)->first();
      //   $checkuser8 = User::where('id',$insertresult->user_id)->first();
      //   if ($checkuser8->role===3) {
      //     $insertresult->claimed = 1;
      //     $checkuser8->cash = $checkuser8->cash + $finallowestpercentage;
      //     $checkuser8->save();
      //     broadcast(new userupdate($checkuser8->id));
      //     $createlogs = new Logs();
      //     $createlogs->type = 'Winners';
      //     $createlogs->user_id = $checkuser8->id;
      //     $createlogs->message = $checkuser8->name.',Recieved '.$finallowestpercentage;
      //     $createlogs->save();
      //   }
      //   $insertresult->result = $finallowestpercentage;
      //   $insertresult->save();
      // }
      // END LOWEST SCORE

      // LOWEST SCORE 0

      $countlowest0 = $getwinners->where('wins',0)->count();
      $getlowest0= $getwinners->where('wins',0);
      if ($countlowest0) {
        $finallowestpercentage0 = 10000;
      }else {
        $finallowestpercentage0 = 10000;
      }

      foreach ($getlowest0 as $key) {
        $insertresult = expertbet::where('id',$key->id)->first();
        $checkuser13 = User::where('id',$insertresult->user_id)->first();
        if ($checkuser13->role===3) {
          $startingbalance = $checkuser13->cash;
          $endingbalance = $checkuser13->cash + $finallowestpercentage0;
          $insertresult->claimed = 1;
          $checkuser13->cash = $checkuser13->cash + $finallowestpercentage0;
          $checkuser13->save();
          broadcast(new userupdate($checkuser13->id));
          $createlogs = new Logs();
          $createlogs->type = 'Winners';
          $createlogs->user_id = $checkuser13->id;
          $createlogs->message = 'Starting Balance : '.number_format($startingbalance,2) ."\nConsolation Payout : ".number_format($finallowestpercentage0,2)."\nEnding Balance : ".number_format($endingbalance,2)."\nStartingfight : ".$open->startingfight;
          $createlogs->save();
        }
        $insertresult->result = $finallowestpercentage0;
        $insertresult->save();
      }
      // END LOWEST SCORE 0

      // LOWEST SCORE 1

      $countlowest1 = $getwinners->where('wins',1)->count();
      $getlowest1= $getwinners->where('wins',1);
      if ($countlowest1) {
        $finallowestpercentage1 = 3000;
      }else {
        $finallowestpercentage1 = 3000;
      }

      foreach ($getlowest1 as $key) {
        $insertresult = expertbet::where('id',$key->id)->first();
        $checkuser14 = User::where('id',$insertresult->user_id)->first();
        if ($checkuser14->role===3) {
          $startingbalance = $checkuser14->cash;
          $endingbalance = $checkuser14->cash + $finallowestpercentage1;
          $insertresult->claimed = 1;
          $checkuser14->cash = $checkuser14->cash + $finallowestpercentage1;
          $checkuser14->save();
          broadcast(new userupdate($checkuser14->id));
          $createlogs = new Logs();
          $createlogs->type = 'Winners';
          $createlogs->user_id = $checkuser14->id;
          $createlogs->message = 'Starting Balance : '.number_format($startingbalance,2) ."\nConsolation Payout : ".number_format($finallowestpercentage1,2)."\nEnding Balance : ".number_format($endingbalance,2)."\nStartingfight : ".$open->startingfight;
          $createlogs->save();
        }
        $insertresult->result = $finallowestpercentage1;
        $insertresult->save();
      }
      // END LOWEST SCORE 1

      // LOWEST SCORE 2

      $countlowest2 = $getwinners->where('wins',2)->count();
      $getlowest2= $getwinners->where('wins',2);
      if ($countlowest2) {
        $finallowestpercentage2 = 2000;
      }else {
        $finallowestpercentage2 = 2000;
      }

      foreach ($getlowest2 as $key) {
        $insertresult = expertbet::where('id',$key->id)->first();
        $checkuser15 = User::where('id',$insertresult->user_id)->first();
        if ($checkuser15->role===3) {
          $startingbalance = $checkuser15->cash;
          $endingbalance = $checkuser15->cash + $finallowestpercentage2;
          $insertresult->claimed = 1;
          $checkuser15->cash = $checkuser15->cash + $finallowestpercentage2;
          $checkuser15->save();
          broadcast(new userupdate($checkuser15->id));
          $createlogs = new Logs();
          $createlogs->type = 'Winners';
          $createlogs->user_id = $checkuser15->id;
          $createlogs->message = 'Starting Balance : '.number_format($startingbalance,2) ."\nConsolation Payout : ".number_format($finallowestpercentage2,2)."\nEnding Balance : ".number_format($endingbalance,2)."\nStartingfight : ".$open->startingfight;
          $createlogs->save();
        }
        $insertresult->result = $finallowestpercentage2;
        $insertresult->save();
      }
      // END LOWEST SCORE 2

      // LOWEST SCORE 3

      $countlowest3 = $getwinners->where('wins',3)->count();
      $getlowest3= $getwinners->where('wins',3);
      if ($countlowest3) {
        $finallowestpercentage3 = 500;
      }else {
        $finallowestpercentage3 = 500;
      }

      foreach ($getlowest3 as $key) {
        $insertresult = expertbet::where('id',$key->id)->first();
        $checkuser16 = User::where('id',$insertresult->user_id)->first();
        if ($checkuser16->role===3) {
          $startingbalance = $checkuser15->cash;
          $endingbalance = $checkuser15->cash + $finallowestpercentage3;
          $insertresult->claimed = 1;
          $checkuser16->cash = $checkuser16->cash + $finallowestpercentage3;
          $checkuser16->save();
          broadcast(new userupdate($checkuser16->id));
          $createlogs = new Logs();
          $createlogs->type = 'Winners';
          $createlogs->user_id = $checkuser16->id;
          $createlogs->message = 'Starting Balance : '.number_format($startingbalance,2) ."\nConsolation Payout : ".number_format($finallowestpercentage3,2)."\nEnding Balance : ".number_format($endingbalance,2)."\nStartingfight : ".$open->startingfight;
          $createlogs->save();
        }
        $insertresult->result = $finallowestpercentage3;
        $insertresult->save();
      }
      // END LOWEST SCORE 3

      broadcast(new resultevent('awd',$req['startingfight'],auth()->user()->name,'confirmclaimopened',$req['id'],auth()->user()->id,'id','id'))->toOthers();

      return 'success';
    }
  });
  return $result;
    }

  }
  // public function open(Request $req)
  // {
  //   // OPEN CLAIMING FOR SPECIFIC STARTING FIGHT NUMBER
  //
  //   $this->validate($req, [
  //     'id' => 'required|max:255',
  //     // 'amount' => 'required|max:255',
  //     'event_id'=> 'required',
  //     'startingfight'=> 'required'
  //   ]);
  //   $get=Event::where('status',1)->get();
  //   $array = array();
  //   foreach ($get as $key) {
  //     array_push($array, $key->id);
  //   }
  //   $getevent=Event::where('status',1)->first();
  //   $checker = expertbet::where('startingfight',$req['startingfight'])->whereIn('event_id',$array)->orderBy('wins','DESC')->first();
  //   $getwinners = expertbet::where('startingfight',$checker->startingfight)->whereIn('event_id',$array)->where('wins',$checker->wins)->orderBy('wins','DESC')->get();
  //   $countwinners = count($getwinners);
  //
  //   $control=control::first();
  //   $open = Potmoney::whereIn('event_id',$array)->where('startingfight',$req['startingfight'])->where('claim',1)->first();
  //   if ($checker->wins===$control->pick) {
  //     $open->claim = 2;
  //     $c1 = $control->jackpot+$getevent->addtojackpot;
  //     $c2 = $c1/$countwinners;
  //     $open->payout = $c2;
  //     $open->remaining = $c1;
  //     $open->rake = - $control->jackpot;
  //     $open->addtojackpot =null;
  //     $open->deductedtojackpot = $getevent->addtojackpot;
  //     $open->save();
  //     $getevent->totalrake = $getevent->totalrake - $getevent->jackpot - $getevent->addtojackpot;
  //     $getevent->addtojackpot =null;
  //     $getevent->save();
  //     foreach ($getwinners as $key) {
  //       $checkuser = User::where('id',$key->user_id)->first();
  //       if ($checkuser->role == 3) {
  //         $checkuser->cash = $checkuser->cash + $open->payout;
  //         $checkuser->save();
  //
  //         $betclaim = expertbet::findOrFail($key->id);
  //         $betclaim->claimed = 1;
  //         $betclaim->save();
  //
  //         $open->remaining = $open->remaining - $open->payout;
  //         $open->save();
  //         $createlogs = new Logs();
  //         $createlogs->type = 'Confirmed_Open_Claiming_Bets';
  //         $createlogs->user_id = $checkuser->id;
  //         $getuser1 = User::findOrFail($checkuser->id);
  //         $createlogs->message =$getuser1->username.' wins '.$open->payout.', from startingfight number : '.$open->startingfight.' from '.$getevent->event_name.' event, [New balance : '.substr($checkuser->cash, 0, -1).']';
  //         $createlogs->save();
  //         broadcast(new userupdate($checkuser->id));
  //       }
  //     }
  //     foreach ($getwinners as $key) {
  //       $inputresultwin = expertbet::findOrFail($key->id);
  //       $inputresultwin->result = $open->payout;
  //       $inputresultwin->save();
  //     }
  //   }else {
  //     $control=control::first();
  //     $open->claim = 2;
  //     $tojackpot = $control->percentage_jackpot/100;
  //     $tojackpot2 = $tojackpot*$open->amount;
  //     // return $control;
  //     $c1 = $control->rake/100;
  //     $c2 = $c1*$open->amount;
  //     $open->rake=$c2;
  //     $c3 = $open->amount-$c2;
  //     $c5 =  $open->amount-$tojackpot2-$c2;
  //     $c4 = $c5/$countwinners;
  //     $open->payout = $c4;
  //     $open->remaining = $c5;
  //     // $open->remaining = $c3;
  //     $open->addtojackpot = $tojackpot2;
  //     $open->save();
  //     $getevent->totalrake = $getevent->totalrake+$open->rake;
  //     $getevent->addtojackpot = $getevent->addtojackpot+$tojackpot2;
  //     $getevent->save();
  //     foreach ($getwinners as $key) {
  //       $checkuser = User::where('id',$key->user_id)->first();
  //       if ($checkuser->role == 3) {
  //         $checkuser->cash = $checkuser->cash + $open->payout;
  //         $checkuser->save();
  //         $open->remaining = $open->remaining - $open->payout;
  //         $open->save();
  //         $createlogs = new Logs();
  //         $createlogs->type = 'Confirmed_Open_Claiming_Bets';
  //         $createlogs->user_id = $checkuser->id;
  //         $getuser1 = User::findOrFail($checkuser->id);
  //         $createlogs->message =$getuser1->username.' wins '.substr($open->payout, 0, -1).', from startingfight number : '.$open->startingfight.' from '.$getevent->event_name.' event, [New balance : '.substr($checkuser->cash, 0, -1).']';
  //         $createlogs->save();
  //         broadcast(new userupdate($checkuser->id));
  //       }
  //     }
  //     foreach ($getwinners as $key) {
  //       $inputresultwin = expertbet::findOrFail($key->id);
  //       $checkuser1 = User::where('id',$inputresultwin->user_id)->first();
  //       $inputresultwin->result = $open->payout;
  //       if ($checkuser1->role===3) {
  //         $inputresultwin->claimed = 1;
  //       }
  //       $inputresultwin->save();
  //     }
  //   }
  //   // create logs
  //   $createlogs = new Logs();
  //   $createlogs->type = 'Confirmed_Open_Claiming_Bets';
  //   $createlogs->user_id = auth()->user()->id;
  //   $getuser1 = User::findOrFail($req['user_id']);
  //   $createlogs->message = auth()->user()->username.' Confirmed '.$getuser1->username.' for open claiming bet, for startingfight number '.$req['startingfight'].'.';
  //   $createlogs->save();
  //   broadcast(new resultevent('awd',$req['startingfight'],auth()->user()->name,'confirmclaimopened',$req['id'],auth()->user()->id,'id','id'))->toOthers();
  // }
