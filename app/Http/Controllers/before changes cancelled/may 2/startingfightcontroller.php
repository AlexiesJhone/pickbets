<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Logs;
use App\Models\Event;
use App\Models\User;
use App\Models\control;
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
  public function insertstartingfight(Request $req)
  {
    $this->validate($req, [
      'startingfight'=>'required',
      'event_id'=>'required',
    ]);
    $getactiveevent = Event::where('startingfight',$req['startingfight'])->where('status',1)->latest()->first();
    if ($getactiveevent) {
      return error;
    }else {
      $control = control::first();
      $getevent = Event::where('status',1)->latest()->first();
      $computed = $getevent->startingfight + $control->pick +2;
      $lastfight = $req['startingfight'] + $control->pick +2;

      if ($req->startingfight > $computed && $lastfight <= $getevent->fights) {
        $currentfight = $getevent->currentfight;
        $newcheck = Event::where('status',1)->latest()->first();
        $newstartingfight = new Event();
        $newstartingfight->fights = $newcheck->fights;
        $newstartingfight->event_name = $newcheck->event_name;
        $newstartingfight->status = 1;
        $newstartingfight->control = 'Closed';
        $newstartingfight->startingfight = $req['startingfight'];
        $newstartingfight->fightopened = $newcheck->fightopened;
        $newstartingfight->venue = $newcheck->venue;
        $newstartingfight->fightdate = $newcheck->fightdate;
        $newstartingfight->currentfight = $newcheck->currentfight;
        $newstartingfight->save();
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

    return Event::where('event_name',$getactiveevent->event_name)->where('status',1)->latest()->get();
  }
  public function startingfightshome(Request $req)
  {
    // $getactiveevent = Event::where('status',1)->first();
    $getactiveevent = Event::where('id',$req['event_id'])->first();

    return Event::where('status',1)->latest()->get();
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
    $remove->control = 'Closed';
    $remove->save();
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
