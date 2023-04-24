<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Logs;
use App\Models\Results;
use App\Models\Event;
use App\Models\User;
use App\Models\control;
use App\Models\expertbet;
use App\Models\Potmoney;
use Auth;
use App\Models\startingfights;
use App\Events\eventlistener;
use App\Events\resultevent;
use App\Events\startingfightsevents;
use Illuminate\Support\Facades\DB;

class startingfightcontroller extends Controller
{
  public function openstartingfightconfirm(Request $req)
  {
    $this->validate($req, [
      'id'=>'required',
      'declarator'=>'required',
    ]);
    $getstart = startingfights::where('id',$req['id'])->first();
    broadcast(new startingfightsevents($getstart->id,auth()->user()->id,$req['declarator'],auth()->user()->username,$getstart->startingfight))->toOthers();
    $getuser = User::where('id', $req['declarator'])->first();
    $createlogs = new Logs();
    $createlogs->type = 'Request_Open_Startingfight';
    $createlogs->user_id = auth()->user()->id;
    $createlogs->message = auth()->user()->username.' request to open starting fight number : '.$getstart->startingfight.' to '.$getuser->username;
    $createlogs->save();
  }
  public function openallfights(Request $req)
  {
    $this->validate($req, [
      // 'startingfight'=>'required',
      'event_id'=>'required',
    ]);
    $getnameevent = Event::where('id',$req['event_id'])->where('status',1)->latest()->first();
    $getnameevent2 = Event::where('event_name',$getnameevent->event_name)->where('status',1)->where('control','!=','Finished')->min('startingfight');

    $getactiveevent = Event::where('event_name',$getnameevent->event_name)->where('status',1)->get('id');

    $getresults = Results::whereIn('event_id',$getactiveevent)->latest()->first('fightnumber');
    if ($getresults) {
      $fn = $getresults->fightnumber + 1;
    }else {
      $fn = $getnameevent2 - 1;
    }
    // return $getnameevent2;
    $getactiveevent2 = Event::where('event_name',$getnameevent->event_name)->where('startingfight','>',$fn)->where('status',1)->update([
      'control'=>'Open'
    ]);
    broadcast(new eventlistener($getnameevent2))->toOthers();
    broadcast(new resultevent('Last','endevent',auth()->user()->name,'eventupdate','id',auth()->user()->name,'id','id'))->toOthers();
    $createlogs = new Logs();
    $createlogs->type = 'Open_All_Startingfight';
    $createlogs->user_id = auth()->user()->id;
    $createlogs->message = auth()->user()->username." Open all starting fights .\nEvent ID : ".$getnameevent->id."\nEvent Name : ".$getnameevent->event_name;
    $createlogs->save();
    return $getresults;


  }
  public function insertstartingfight(Request $req)
  {
    $this->validate($req, [
      'startingfight'=>'required',
      'event_id'=>'required',
    ]);
    $control = control::first();
    $getnameevent = Event::where('id',$req['event_id'])->where('status',1)->latest()->first();
    $getactiveevent = Event::where('id',$req['event_id'])->where('event_name',$getnameevent)->where('startingfight',$req['startingfight'])->where('status',1)->where('pick',$control->pick)->latest()->first();
    if ($getactiveevent) {
      return error;
    }else {
      $getevent = Event::where('event_name',$getnameevent->event_name)->where('id',$req['event_id'])->where('status',1)->latest()->first();
      // return $getevent->fights;
      $computed = $getevent->startingfight + $control->pick +2;
      $lastfight = $req['startingfight'] + $control->pick +2;

      if ($req->startingfight > $computed && $lastfight <= $getevent->fights) {
        $currentfight = $getevent->currentfight;
        $newcheck = Event::where('event_name',$getnameevent->event_name)->where('id',$req['event_id'])->where('status',1)->latest()->first();
        $newcheck2 = Event::where('event_name',$getnameevent->event_name)->update([
          'pick20'=>1
        ]);
        $newstartingfight = new Event();
        $newstartingfight->fights = $newcheck->fights;
        $newstartingfight->payout = $newcheck->payout;
        $newstartingfight->jackpot = $newcheck->jackpot;
        $newstartingfight->event_name = $newcheck->event_name;
        $newstartingfight->status = 1;
        $newstartingfight->pick = $control->pick;
        $newstartingfight->pick20 = 1;
        if ($getnameevent->pick2) {
          $newstartingfight->pick2 = 1;
          $newstartingfight->start2 = $newcheck->start2;
          $newstartingfight->end2 = $newcheck->end2;
        }
        if ($getnameevent->pick3) {
          $newstartingfight->pick3 = 1;
          $newstartingfight->start3 = $newcheck->start3;
          $newstartingfight->end3 = $newcheck->end3;
        }
        if ($getnameevent->pick4) {
          $newstartingfight->pick4 = 1;
          $newstartingfight->start4 = $newcheck->start4;
          $newstartingfight->end4 = $newcheck->end4;
        }
        if ($getnameevent->pick5) {
          $newstartingfight->pick5 = 1;
          $newstartingfight->start5 = $newcheck->start5;
          $newstartingfight->end5 = $newcheck->end5;
        }
        if ($getnameevent->pick6) {
          $newstartingfight->pick6 = 1;
          $newstartingfight->start6 = $newcheck->start6;
          $newstartingfight->end6 = $newcheck->end6;
        }
        $newstartingfight->control = 'Closed';
        $newstartingfight->startingfight = $req['startingfight'];
        $newstartingfight->fightopened = $newcheck->fightopened;
        $newstartingfight->venue = $newcheck->venue;
        $newstartingfight->fightdate = $newcheck->fightdate;
        $newstartingfight->currentfight = $newcheck->currentfight;
        $newstartingfight->save();

		    $createpotmoneypick20 = new Potmoney();
        $createpotmoneypick20->amount=0;
        $createpotmoneypick20->event_id=$newstartingfight->id;
        $createpotmoneypick20->pick=$newstartingfight->pick;
        $createpotmoneypick20->startingfight=$newstartingfight->startingfight;
        $createpotmoneypick20->save();

        // // Pick 2 insert starting
        // if ($getevent->pick2) {
        // $newevent = new Event();
        // $newevent->event_name = $newcheck->event_name;
        // $newevent->fights = $newcheck->fights;
        // $newevent->currentfight = $newcheck->currentfight;
        // $newevent->startingfight = $req['startingfight'];
        // $newevent->control = 'Closed';
        // $newevent->pick = 2;
        // $newevent->pick2 = 1;
        // if ($getnameevent->pick3) {
        //   $newevent->pick3 = 1;
        // }
        // if ($getnameevent->pick4) {
        //   $newevent->pick4 = 1;
        // }
        // if ($getnameevent->pick5) {
        //   $newevent->pick5 = 1;
        // }
        // if ($getnameevent->pick6) {
        //   $newevent->pick6 = 1;
        // }
        // $newevent->status = 1;
        // $newevent->venue = $newcheck->venue;
        // $newevent->fightdate = $newcheck->fightdate;
        // $newevent->save();
        //
		    // $createpotmoneypick2 = new Potmoney();
        // $createpotmoneypick2->amount=0;
        // $createpotmoneypick2->event_id=$newevent->id;
        // $createpotmoneypick2->pick=$newevent->pick;
        // $createpotmoneypick2->startingfight=$newevent->startingfight;
        // $createpotmoneypick2->save();
        //
        // $lastfight = $req['startingfight']+$control->pick-2;
        // for ($i=$req['startingfight']; $i < $lastfight ; $i++) {
        //   $newevent = new Event();
        //   $newevent->event_name = $newcheck->event_name;
        //   $newevent->fights = $newcheck->fights;
        //   $newevent->currentfight = $newcheck->currentfight;
        //   $newevent->startingfight = $i+2;
        //   $i = $i+2-1;
        //   $newevent->control = 'Closed';
        //   $newevent->pick = 2;
        //   $newevent->pick2 = 1;
        //   if ($getevent->pick3) {
        //   $newevent->pick3 = 1;
        //   }
        //   if ($getevent->pick4) {
        //   $newevent->pick4 = 1;
        //   }
        //   if ($getevent->pick5) {
        //   $newevent->pick5 = 1;
        //   }
        //   if ($getevent->pick6) {
        //   $newevent->pick6 = 1;
        //   }
        //   $newevent->status = 1;
        //   $newevent->venue = $newcheck->venue;
        //   $newevent->fightdate = $newcheck->fightdate;
        //   $newevent->save();
        //
		    // $createpotmoneypick2 = new Potmoney();
        // $createpotmoneypick2->amount=0;
        // $createpotmoneypick2->event_id=$newevent->id;
        // $createpotmoneypick2->pick=$newevent->pick;
        // $createpotmoneypick2->startingfight=$newevent->startingfight;
        // $createpotmoneypick2->save();
        // }
        // }
        // // end of Pick 2 insert starting
        //
        // // Pick 3 insert starting
        // if ($getevent->pick3) {
        // $newevent = new Event();
        // $newevent->event_name = $newcheck->event_name;
        // $newevent->fights = $newcheck->fights;
        // $newevent->currentfight = $newcheck->currentfight;
        // $newevent->startingfight = $req['startingfight'];
        // $newevent->control = 'Closed';
        // $newevent->pick = 3;
        // $newevent->pick3 = 1;
        // if ($getevent->pick2) {
        // $newevent->pick2 = 1;
        // }
        // if ($getevent->pick4) {
        // $newevent->pick4 = 1;
        // }
        // if ($getevent->pick5) {
        // $newevent->pick5 = 1;
        // }
        // if ($getevent->pick6) {
        // $newevent->pick6 = 1;
        // }
        // $newevent->status = 1;
        // $newevent->venue = $newcheck->venue;
        // $newevent->fightdate = $newcheck->fightdate;
        // $newevent->save();
        //
		    // $createpotmoneypick2 = new Potmoney();
        // $createpotmoneypick2->amount=0;
        // $createpotmoneypick2->event_id=$newevent->id;
        // $createpotmoneypick2->pick=$newevent->pick;
        // $createpotmoneypick2->startingfight=$newevent->startingfight;
        // $createpotmoneypick2->save();
        //
        // $lastfight = $req['startingfight']+$control->pick-5;
        // for ($i=$req['startingfight']; $i < $lastfight ; $i++) {
        //   $newevent = new Event();
        //   $newevent->event_name = $newcheck->event_name;
        //   $newevent->fights = $newcheck->fights;
        //   $newevent->currentfight = $newcheck->currentfight;
        //   $newevent->startingfight = $i+3;
        //   $i = $i+3-1;
        //   $newevent->control = 'Closed';
        //   $newevent->pick = 3;
        //   if ($getevent->pick2) {
        //   $newevent->pick2 = 1;
        //   }
        //   if ($getevent->pick4) {
        //   $newevent->pick4 = 1;
        //   }
        //   if ($getevent->pick5) {
        //   $newevent->pick5 = 1;
        //   }
        //   if ($getevent->pick6) {
        //   $newevent->pick6 = 1;
        //   }
        //   $newevent->pick3 = 1;
        //   $newevent->status = 1;
        //   $newevent->venue = $newcheck->venue;
        //   $newevent->fightdate = $newcheck->fightdate;
        //   $newevent->save();
        //
		    // $createpotmoneypick2 = new Potmoney();
        // $createpotmoneypick2->amount=0;
        // $createpotmoneypick2->event_id=$newevent->id;
        // $createpotmoneypick2->pick=$newevent->pick;
        // $createpotmoneypick2->startingfight=$newevent->startingfight;
        // $createpotmoneypick2->save();
        // }
        // }
        // // end of Pick 3 insert starting
        //
        // // Pick 4 insert starting
        // if ($getevent->pick4) {
        // $newevent = new Event();
        // $newevent->event_name = $newcheck->event_name;
        // $newevent->fights = $newcheck->fights;
        // $newevent->currentfight = $newcheck->currentfight;
        // $newevent->startingfight = $req['startingfight'];
        // $newevent->control = 'Closed';
        // $newevent->pick = 4;
        // $newevent->pick4 = 1;
        // if ($getevent->pick2) {
        // $newevent->pick2 = 1;
        // }
        // if ($getevent->pick3) {
        // $newevent->pick3 = 1;
        // }
        // if ($getevent->pick5) {
        // $newevent->pick5 = 1;
        // }
        // if ($getevent->pick6) {
        // $newevent->pick6 = 1;
        // }
        // $newevent->status = 1;
        // $newevent->venue = $newcheck->venue;
        // $newevent->fightdate = $newcheck->fightdate;
        // $newevent->save();
        //
		    // $createpotmoneypick2 = new Potmoney();
        // $createpotmoneypick2->amount=0;
        // $createpotmoneypick2->event_id=$newevent->id;
        // $createpotmoneypick2->pick=$newevent->pick;
        // $createpotmoneypick2->startingfight=$newevent->startingfight;
        // $createpotmoneypick2->save();
        //
        // $lastfight = $req['startingfight']+$control->pick-8;
        // for ($i=$req['startingfight']; $i < $lastfight ; $i++) {
        //   $newevent = new Event();
        //   $newevent->event_name = $newcheck->event_name;
        //   $newevent->fights = $newcheck->fights;
        //   $newevent->currentfight = $newcheck->currentfight;
        //   $newevent->startingfight = $i+4;
        //   $i = $i+4-1;
        //   $newevent->control = 'Closed';
        //   $newevent->pick = 4;
        //   if ($getevent->pick2) {
        //   $newevent->pick2 = 1;
        //   }
        //   if ($getevent->pick3) {
        //   $newevent->pick3 = 1;
        //   }
        //   if ($getevent->pick5) {
        //   $newevent->pick5 = 1;
        //   }
        //   if ($getevent->pick6) {
        //   $newevent->pick6 = 1;
        //   }
        //   $newevent->pick4 = 1;
        //   $newevent->status = 1;
        //   $newevent->venue = $newcheck->venue;
        //   $newevent->fightdate = $newcheck->fightdate;
        //   $newevent->save();
        //
		    // $createpotmoneypick2 = new Potmoney();
        // $createpotmoneypick2->amount=0;
        // $createpotmoneypick2->event_id=$newevent->id;
        // $createpotmoneypick2->pick=$newevent->pick;
        // $createpotmoneypick2->startingfight=$newevent->startingfight;
        // $createpotmoneypick2->save();
        // }
        // }
        // // end of Pick 4 insert starting
        // // Pick 5 insert starting
        // if ($getevent->pick5) {
        // $newevent = new Event();
        // $newevent->event_name = $newcheck->event_name;
        // $newevent->fights = $newcheck->fights;
        // $newevent->currentfight = $newcheck->currentfight;
        // $newevent->startingfight = $req['startingfight'];
        // $newevent->control = 'Closed';
        // $newevent->pick = 5;
        // $newevent->pick5 = 1;
        // if ($getevent->pick2) {
        // $newevent->pick2 = 1;
        // }
        // if ($getevent->pick3) {
        // $newevent->pick3 = 1;
        // }
        // if ($getevent->pick4) {
        // $newevent->pick4 = 1;
        // }
        // if ($getevent->pick6) {
        // $newevent->pick6 = 1;
        // }
        // $newevent->status = 1;
        // $newevent->venue = $newcheck->venue;
        // $newevent->fightdate = $newcheck->fightdate;
        // $newevent->save();
        //
		    // $createpotmoneypick2 = new Potmoney();
        // $createpotmoneypick2->amount=0;
        // $createpotmoneypick2->event_id=$newevent->id;
        // $createpotmoneypick2->pick=$newevent->pick;
        // $createpotmoneypick2->startingfight=$newevent->startingfight;
        // $createpotmoneypick2->save();
        //
        // $lastfight = $req['startingfight']+$control->pick-8;
        // for ($i=$req['startingfight']; $i < $lastfight ; $i++) {
        //   $newevent = new Event();
        //   $newevent->event_name = $newcheck->event_name;
        //   $newevent->fights = $newcheck->fights;
        //   $newevent->currentfight = $newcheck->currentfight;
        //   $newevent->startingfight = $i+5;
        //   $i = $i+5-1;
        //   $newevent->control = 'Closed';
        //   $newevent->pick = 5;
        //   if ($getevent->pick2) {
        //   $newevent->pick2 = 1;
        //   }
        //   if ($getevent->pick3) {
        //   $newevent->pick3 = 1;
        //   }
        //   if ($getevent->pick4) {
        //   $newevent->pick4 = 1;
        //   }
        //   if ($getevent->pick6) {
        //   $newevent->pick6 = 1;
        //   }
        //   $newevent->pick5 = 1;
        //   $newevent->status = 1;
        //   $newevent->venue = $newcheck->venue;
        //   $newevent->fightdate = $newcheck->fightdate;
        //   $newevent->save();
        //
		    // $createpotmoneypick2 = new Potmoney();
        // $createpotmoneypick2->amount=0;
        // $createpotmoneypick2->event_id=$newevent->id;
        // $createpotmoneypick2->pick=$newevent->pick;
        // $createpotmoneypick2->startingfight=$newevent->startingfight;
        // $createpotmoneypick2->save();
        // }
        // }
        // // end of Pick 5 insert starting
        // // Pick 6 insert starting
        // if ($getevent->pick6) {
        // $newevent = new Event();
        // $newevent->event_name = $newcheck->event_name;
        // $newevent->fights = $newcheck->fights;
        // $newevent->currentfight = $newcheck->currentfight;
        // $newevent->startingfight = $req['startingfight'];
        // $newevent->control = 'Closed';
        // $newevent->pick = 6;
        // $newevent->pick6 = 1;
        // if ($getevent->pick2) {
        // $newevent->pick2 = 1;
        // }
        // if ($getevent->pick3) {
        // $newevent->pick3 = 1;
        // }
        // if ($getevent->pick4) {
        // $newevent->pick4 = 1;
        // }
        // if ($getevent->pick5) {
        // $newevent->pick5 = 1;
        // }
        // $newevent->status = 1;
        // $newevent->venue = $newcheck->venue;
        // $newevent->fightdate = $newcheck->fightdate;
        // $newevent->save();
        //
		    // $createpotmoneypick2 = new Potmoney();
        // $createpotmoneypick2->amount=0;
        // $createpotmoneypick2->event_id=$newevent->id;
        // $createpotmoneypick2->pick=$newevent->pick;
        // $createpotmoneypick2->startingfight=$newevent->startingfight;
        // $createpotmoneypick2->save();
        //
        // $lastfight = $req['startingfight']+$control->pick-8;
        // for ($i=$req['startingfight']; $i < $lastfight ; $i++) {
        //   $newevent = new Event();
        //   $newevent->event_name = $newcheck->event_name;
        //   $newevent->fights = $newcheck->fights;
        //   $newevent->currentfight = $newcheck->currentfight;
        //   $newevent->startingfight = $i+6;
        //   $i = $i+6-1;
        //   $newevent->control = 'Closed';
        //   $newevent->pick = 6;
        //   if ($getevent->pick2) {
        //   $newevent->pick2 = 1;
        //   }
        //   if ($getevent->pick3) {
        //   $newevent->pick3 = 1;
        //   }
        //   if ($getevent->pick4) {
        //   $newevent->pick4 = 1;
        //   }
        //   if ($getevent->pick5) {
        //   $newevent->pick5 = 1;
        //   }
        //   $newevent->pick6 = 1;
        //   $newevent->status = 1;
        //   $newevent->venue = $newcheck->venue;
        //   $newevent->fightdate = $newcheck->fightdate;
        //   $newevent->save();
        //
		    // $createpotmoneypick2 = new Potmoney();
        // $createpotmoneypick2->amount=0;
        // $createpotmoneypick2->event_id=$newevent->id;
        // $createpotmoneypick2->pick=$newevent->pick;
        // $createpotmoneypick2->startingfight=$newevent->startingfight;
        // $createpotmoneypick2->save();
        // }
        // }
        // // end of Pick 6 insert starting

        broadcast(new resultevent('Last','endevent',auth()->user()->name,'eventupdate','id',auth()->user()->name,'id','id'))->toOthers();
        broadcast(new eventlistener($newstartingfight))->toOthers();
        $createlogs = new Logs();
        $createlogs->type = 'Create_Startingfight';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = auth()->user()->username.' created starting fight number : '.$newstartingfight->startingfight.'.';
        $createlogs->save();

      }else {
        return ['error'=>'You cannot create startingfight number '.$req->startingfight];
      }
    }

    // broadcast(new startingfightsevents($newstartingfight->id,auth()->user()->id,$req['declarator'],auth()->user()->username,$getstart->startingfight))->toOthers();
  }
  public function startingfights(Request $req)
  {
    // $getactiveevent = Event::where('status',1)->first();
    $getactiveevent = Event::where('id',$req['event_id'])->first();

    return Event::where('event_name',$getactiveevent->event_name)->where('status',1)->orderBy('startingfight','desc')->get();
  }
  public function getwinningbets(Request $req)
  {
    // $getactiveevent = Event::where('status',1)->first();
    // $getactiveevent = Event::where('id',$req['event_id'])->first();

    return expertbet::where('event_id',$req->id)->where('winner',1)->where('user_id',auth()->user()->id)->select('wins','result')->orderBy('startingfight','desc')->get();
  }
  public function startingfightshome(Request $req)
  {
    // $getactiveevent = Event::where('status',1)->first();
    $getactiveevent = Event::withSum('expertbet','amount')->where('id',$req['event_id'])->first();
    $array = array();
    $check = Event::
    // with(['expertbet' => function ($query) {
    // $query->where('user_id', auth()->user()->id)->where('winner',1)->latest();
    // }])
    withSum('expertbet','amount')->where('status',1)->orderBy('control','desc')->orderBy('startingfight')
    ->withSum(['expertbet' => function($query) {
        $query->where('user_id',auth()->user()->id)->where('winner',1);
    }],'result')
    ->latest()->get();
    $order = [ 'Last Call', 'Open', 'Closed', 'Finished'];
    $collectmuna = collect($check);
    $sorted = $collectmuna->sortBy(function($model) use ($order) {
        return array_search($model->control, $order);
    });
    $control = control::first();
    // return $sorted;
    foreach ($sorted as $key) {
      if ($key->pick==20) {
        $percentagerake = $control->rake/100;
        $percentagefunds = $control->percentage_jackpot/100;
        $percentage1 = $key->expertbet_sum_amount*$percentagerake;
        $percentage2 = $key->expertbet_sum_amount*$percentagefunds;
        $finalpercentage = $key->expertbet_sum_amount - $percentage1 - $percentage2;
        array_push($array,array('id'=>$key->id,'control'=>$key->control,'created_at'=>$key->created_at,'currentfight'=>$key->currentfight,'event_name'=>$key->event_name,
        'fightdate'=>$key->fightdate,'fightopened'=>$key->fightopened,'fights'=>$key->fights,'pick'=>$key->pick,'potmoney_sum_amount'=>$finalpercentage,'startingfight'=>$key->startingfight,
      'status'=>$key->status,'venue'=>$key->venue,'winnings'=>$key->expertbet_sum_result));
      }else {
        $percentage = $control->rakepick2/100;
        $percentage2 = $key->expertbet_sum_amount*$percentage;

        $percentage3 = $key->expertbet_sum_amount - $percentage2;
        array_push($array,array('id'=>$key->id,'control'=>$key->control,'created_at'=>$key->created_at,'currentfight'=>$key->currentfight,'event_name'=>$key->event_name,
        'fightdate'=>$key->fightdate,'fightopened'=>$key->fightopened,'fights'=>$key->fights,'pick'=>$key->pick,'potmoney_sum_amount'=>$percentage3,'startingfight'=>$key->startingfight,
      'status'=>$key->status,'venue'=>$key->venue,'winnings'=>$key->expertbet_sum_result));
      }

    }
    return $array;
  }
  public function removestarting(Request $req)
  {
    $this->validate($req, [
      // 'startingfight'=>'required',
      // 'event_id'=>'required',
      'id'=>'required',
    ]);
    $getactiveevent = Event::where('status',1)->first();
    $remove = startingfights::findOrFail($req['id']);
    $remove->status = 2;
    $remove->save();
    DB::table('expertbet as a')
    ->where('a.startingfight', '=', $remove->startingfight)->where('a.event_id', '=', $getactiveevent->id)
    ->join('users as c', 'a.user_id', '=', 'c.id')
    ->join('events as b', 'a.event_id', '=', 'b.id')
    ->update(['c.cash' =>DB::raw('cash + b.amount'),'a.winner'=>4]);
    // bet::where('startingfight',$remove->startingfight)->where('event_id',$getactiveevent->id)

      broadcast(new eventlistener($remove))->toOthers();
      broadcast(new resultevent('Last','endevent',auth()->user()->name,'eventupdate','id',auth()->user()->name,'id','id'))->toOthers();
      $createlogs = new Logs();
      $createlogs->type = 'Remove_Startingfight';
      $createlogs->user_id = auth()->user()->id;
      $createlogs->message = auth()->user()->username.' Removed startingfight :#'.$remove->startingfight.'.';
      $createlogs->save();
  }
  public function openstartingfight(Request $req)
  {
    $this->validate($req, [
      // 'startingfight'=>'required',
      // 'event_id'=>'required',
      'id'=>'required',
    ]);

    $remove = Event::findOrFail($req['id']);
    $currentfight = $remove->currentfight;
    if ($remove->startingfight<=$currentfight) {
      $remove->control = 'Closed';
      $remove->save();
      return ['error'=>'You cannot open startingfight number '.$remove->startingfight];
    }else {
      $remove->control = 'Open';
      $remove->save();
        broadcast(new eventlistener($remove))->toOthers();
        broadcast(new resultevent('Last','endevent',auth()->user()->name,'eventupdate','id',auth()->user()->name,'id','id'))->toOthers();
        $createlogs = new Logs();
        $createlogs->type = 'Open_Startingfight';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = auth()->user()->username.' Open startingfight :#'.$remove->startingfight.'.';
        $createlogs->save();
    }

  }
  public function closestartingfight(Request $req)
  {
    $this->validate($req, [
      // 'startingfight'=>'required',
      // 'event_id'=>'required',
      'id'=>'required',
    ]);
    // $getactiveevent = Event::where('id',$req['id'])->first();
    $remove = Event::findOrFail($req['id']);
    $getevents = Event::where('event_name',$remove->event_name)->where('startingfight',$remove->startingfight)->update([
      'control'=>'Closed'
    ]);
    // $remove->control = 'Closed';
    // $remove->save();
      broadcast(new eventlistener($remove))->toOthers();
      broadcast(new resultevent('Last','endevent',auth()->user()->name,'eventupdate','id',auth()->user()->name,'id','id'))->toOthers();
      $createlogs = new Logs();
      $createlogs->type = 'Close_Startingfight';
      $createlogs->user_id = auth()->user()->id;
      $createlogs->message = auth()->user()->username.' Closed startingfight :#'.$remove->startingfight.'.';
      $createlogs->save();
  }
  public function lastcallstartingfight(Request $req)
  {
    $this->validate($req, [
      // 'startingfight'=>'required',
      // 'event_id'=>'required',
      'id'=>'required',
    ]);
    $remove = Event::where('id',$req['id'])->first();
    // $remove = startingfights::findOrFail($req['id']);
    $remove->control = 'Last Call';
    $remove->save();
    // $starting = startingfights::where('id',$req['id'])->first();
    $try = 1;
      broadcast(new eventlistener($remove))->toOthers();
      broadcast(new resultevent('Last','endevent',auth()->user()->name,'eventupdate','id',auth()->user()->name,'id','id'))->toOthers();
      $createlogs = new Logs();
      $createlogs->type = 'Last_Call_Startingfight';
      $createlogs->user_id = auth()->user()->id;
      $createlogs->message = auth()->user()->username.' inserted Last call for startingfight :#'.$remove->startingfight.'.';
      $createlogs->save();
  }
  public function removelastcallstarting(Request $req)
  {
    $this->validate($req, [
      // 'startingfight'=>'required',
      // 'event_id'=>'required',
      'id'=>'required',
    ]);
    $remove = Event::where('id',$req['id'])->first();
    // $remove = startingfights::findOrFail($req['id']);
    $remove->control = 'Open';
    $remove->save();
      broadcast(new eventlistener($remove))->toOthers();
      broadcast(new resultevent('Last','endevent',auth()->user()->name,'eventupdate','id',auth()->user()->name,'id','id'))->toOthers();
      $createlogs = new Logs();
      $createlogs->type = 'Remove_Last_Call_Startingfight';
      $createlogs->user_id = auth()->user()->id;
      $createlogs->message = auth()->user()->username.' Removed last call for startingfight :#'.$remove->startingfight.'.';
      $createlogs->save();
  }
}
