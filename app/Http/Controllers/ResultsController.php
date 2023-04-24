<?php

namespace App\Http\Controllers;

use App\Models\Results;
use App\Events\resultevent;
use App\Events\leaderboards;
use App\Events\eventlistener;
use App\Jobs\grading;
use App\Jobs\gradingpick2;
use App\Jobs\gradingpick3;
use App\Jobs\gradingpick4;
use App\Jobs\gradingpick5;
use App\Jobs\gradingpick6;
use App\Jobs\gradingpick8;
use App\Jobs\gradingpick14;
use App\Jobs\regradingpick3;
use App\Jobs\regradingpick4;
use App\Jobs\regradingpick2;
use App\Jobs\regradingpick5;
use App\Jobs\regradingpick6;
use App\Jobs\regradingpick8;
use App\Jobs\regradingpick14;
use App\Jobs\regrading;
use App\Jobs\secondgrading;
use App\Models\Event;
use App\Models\Prebet;
use App\Models\selection;
use App\Models\bet;
use App\Models\expertbet;
use App\Models\Potmoney;
use App\Models\startingfights;
use App\Models\User;
use App\Models\Logs;
use Auth;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class ResultsController extends Controller
{
  public function index()
  {
    // GET RESULTS FOR ADMIN
    $getevent=Event::where('status',1)->first();
    return Results::where('event_id',$getevent->id)->latest()->get();
  }
  public function results(Request $req)
  {
    $getevent=Event::where('status',1)->where('id',$req['id'])->first();
    if ($getevent) {
      return Results::where('event_id',$getevent->id)->latest()->get();
    }else {
      // code...
      return '';
    }
  }
  public function mismatchresults(Request $req)
  {
    // GET RESULTS FOR ADMIN
    $getevent=Event::where('status',1)->first();
    broadcast(new resultevent($req['result'],$req['fightnumber'],auth()->user()->name,'mismatchresults',auth()->user()->id,$req['declarator_id'],$req['event_id'],$req['name']))->toOthers();
    $createlogs = new Logs();
    $createlogs->type = 'Mismatch_Results';
    $createlogs->user_id = auth()->user()->id;
    $createlogs->message = auth()->user()->username.' and '.$req['firstdeclarator'].', Mismatch results for fight number '.$req['fightnumber'];
    $createlogs->save();
    return $req;
  }
  public function getresultstotal()
  {
    // GET RESULTS FOR ADMIN
    $getevent=Event::where('status',1)->first();
    $meron = Results::where('event_id',$getevent->id)->where('result','Meron')->latest()->count();
    $wala = Results::where('event_id',$getevent->id)->where('result','Wala')->latest()->count();
    $draw = Results::where('event_id',$getevent->id)->where('result','Draw')->latest()->count();
    $cancelled = Results::where('event_id',$getevent->id)->where('result','Cancelled')->latest()->count();

    $totalofbets = array();
    array_push($totalofbets,array('meron'=>$meron,'wala'=>$wala,'cancelled'=>$cancelled,'draw'=>$draw));
    return $totalofbets;
  }
  public function confirmgrade(Request $req)
  {
    $this->validate($req, [
      'result' => 'required',
      'event_id' => 'required',
      'fightnumber'=> 'required',
      'id'=> 'required',
      'declarator_id'=> 'required',
      'newresult'=> 'required',
    ]);

    // create logs
    if ($req['newresult']==="Meron") {
      $appleresult = 'Red Apple';
    }elseif ($req['newresult']==="Wala") {
      $appleresult = 'White Apple';
    }else {
      $appleresult = $req['newresult'];
    }
    $createlogs = new Logs();
    $createlogs->type = 'Requested_Regrade';
    $createlogs->user_id = auth()->user()->id;
    $createlogs->message = auth()->user()->username.' requested regrade '.$appleresult.' for fight number '.$req['fightnumberregrade'];
    $createlogs->save();

    // broadcast(new resultevent($req['result'],$req['fightnumber'],auth()->user()->name,'Confirmgrade',$req['id'],auth()->user()->id))->toOthers();
    broadcast(new resultevent($req['result'],$req['fightnumberregrade'],auth()->user()->name,'Confirmgrade',auth()->user()->id,$req['declarator_id'],$req['event_id'],$req['newresult']))->toOthers();

  }
  public function regrade(Request $req)
  {
    // REGRADE RESULT FIGHT
    $this->validate($req, [
      'newresult' => 'required',
      'result' => 'required',
      'event_id' => 'required',
      'fightnumber'=> 'required',
    ]);
    // UPDATE RESULT
    $regrade=Results::where('fightnumber',$req['fightnumber'])->where('event_id',$req['event_id'])->first();
    $regrade->result=$req['newresult'];
    $regrade->save();
    broadcast(new resultevent($req['result'],$req['fightnumber'],auth()->user()->name,'confirmedloading',1,auth()->user()->id,$req['id'],$req['newresult']))->toOthers();

    $oldresult = $req['result'];
    $newresult = $req['newresult'];
    $eventid = $req['event_id'];
    $fn = $req['fightnumber'];
    $c1 = $req['id'];
    $c2 = auth()->user()->id;
    $name = auth()->user()->name;
    $get=Event::where('status',1)->get();
    $array = array();
    foreach ($get as $key) {
      array_push($array, $key->id);
    }
    // $getoneprebetonly = selection::whereIn('event_id',$array)->where('fightnumber',$req['fightnumber'])->first();
    $getoneprebetonly = expertbet::whereIn('event_id',$array)->where('turn',20)->whereHas('selection', function ($query) use ($req) {
        $query->where('fightnumber', $req['fightnumber']);
    })->first();
    // $getoneprebetonly2 = experbet::whereIn('event_id',$array)->whereHas('selection',)->first();
    $getoneprebetonly2 = expertbet::whereIn('event_id',$array)->where('turn',2)->whereHas('selection', function ($query) use ($req) {
        $query->where('fightnumber', $req['fightnumber']);
    })->first();
    $getoneprebetonly3 = expertbet::whereIn('event_id',$array)->where('turn',3)->whereHas('selection', function ($query) use ($req) {
        $query->where('fightnumber', $req['fightnumber']);
    })->first();
    $getoneprebetonly4 = expertbet::whereIn('event_id',$array)->where('turn',4)->whereHas('selection', function ($query) use ($req) {
        $query->where('fightnumber', $req['fightnumber']);
    })->first();
    $getoneprebetonly5 = expertbet::whereIn('event_id',$array)->where('turn',5)->whereHas('selection', function ($query) use ($req) {
        $query->where('fightnumber', $req['fightnumber']);
    })->first();
    $getoneprebetonly6 = expertbet::whereIn('event_id',$array)->where('turn',6)->whereHas('selection', function ($query) use ($req) {
        $query->where('fightnumber', $req['fightnumber']);
    })->first();
    $getoneprebetonly8 = expertbet::whereIn('event_id',$array)->where('turn',8)->whereHas('selection', function ($query) use ($req) {
        $query->where('fightnumber', $req['fightnumber']);
    })->first();
    $getoneprebetonly14 = expertbet::whereIn('event_id',$array)->where('turn',14)->whereHas('selection', function ($query) use ($req) {
        $query->where('fightnumber', $req['fightnumber']);
    })->first();
    // return $getoneprebetonly2;
    $get1=Event::where('id',$req['event_id'])->first();
    $get=Event::where('event_name',$get1->event_name)->get();
    $array = array();
    foreach ($get as $key) {
      array_push($array, $key->id);
    }
    if ($getoneprebetonly||$getoneprebetonly2||$getoneprebetonly3||$getoneprebetonly4||$getoneprebetonly5||$getoneprebetonly6||$getoneprebetonly8||$getoneprebetonly14) {
      if ($getoneprebetonly) {
        $this->dispatch(new regrading($oldresult,$newresult,$eventid,$fn,$c1,$c2,$getoneprebetonly,$name,$array));
      }
      if ($getoneprebetonly2) {
        $this->dispatch(new regradingpick2($oldresult,$newresult,$eventid,$fn,$c1,$c2,$getoneprebetonly2,$name,$array));
      }
      if ($getoneprebetonly3) {
        $this->dispatch(new regradingpick3($oldresult,$newresult,$eventid,$fn,$c1,$c2,$getoneprebetonly3,$name,$array));
      }
      if ($getoneprebetonly4) {
        $this->dispatch(new regradingpick4($oldresult,$newresult,$eventid,$fn,$c1,$c2,$getoneprebetonly4,$name,$array));
      }
      if ($getoneprebetonly5) {
        $this->dispatch(new regradingpick5($oldresult,$newresult,$eventid,$fn,$c1,$c2,$getoneprebetonly5,$name,$array));
      }
      if ($getoneprebetonly6) {
        $this->dispatch(new regradingpick6($oldresult,$newresult,$eventid,$fn,$c1,$c2,$getoneprebetonly6,$name,$array));
      }
      if ($getoneprebetonly8) {
        $this->dispatch(new regradingpick8($oldresult,$newresult,$eventid,$fn,$c1,$c2,$getoneprebetonly8,$name,$array));
      }
      if ($getoneprebetonly14) {
        $this->dispatch(new regradingpick14($oldresult,$newresult,$eventid,$fn,$c1,$c2,$getoneprebetonly14,$name,$array));
      }
    }else {
      event(new leaderboards($get));
      event(new resultevent($newresult,$fn,$name,'Confirmgraded',$name,$name,$name,$newresult));
    }
    // create logs
    if ($req['newresult']==="Meron") {
      $appleresult = 'Red Apple';
    }elseif ($req['newresult']==="Wala") {
      $appleresult = 'White Apple';
    }else {
      $appleresult = $req['newresult'];
    }
    $createlogs = new Logs();
    $createlogs->type = 'Confirmed_Regrade';
    $createlogs->user_id = auth()->user()->id;
    $getuser1 = User::findOrFail($req['user_id']);
    $createlogs->message = auth()->user()->username.' Confirmed '.$getuser1->username.' for regrade, '.$appleresult.' for fight number '.$req['fightnumber'];
    $createlogs->save();
  }

  public function sample()
  {
    // CLEAR ALL TABLES JUST FOR TESTING AND DEBUGGING
    $getevent=Event::where('status',1)->where('startingfight',141)->update([
      'control'=>"Open",
      'currentfight'=>0
    ]);
    $getbets=expertbet::where('startingfight',141)->update([
      'wins'=>0,
      'winner'=>0
    ]);
    // $getevent->startingfight=0;
    // $getevent->control="Close";
    // $getevent->currentfight=0;
    // $getevent->save();
    // bet::truncate();
    // Prebet::truncate();
    // Potmoney::truncate();
    Results::truncate();
    // Transactions::truncate();
    // Logs::truncate();
  }
  public function confirmed(Request $req)
  {
    $this->validate($req, [
      'result' => 'required|max:255',
      'event_id' => 'required|max:255',
      'fightnumber'=> 'required',
    ]);
    $check=Results::where('fightnumber',$req['fightnumber'])->where('event_id',$req['event_id'])->first();
    // return $check;
    // event(new resultevent($req['result'],$req['fightnumber'],auth()->user()->name,'confirmedloading',1,auth()->user()->id,$req['id']));
    if ($check) {
      return error;
    }else {

    // $data = new Results();
    // $data->result=$req['result'];
    // $data->fightnumber=$req['fightnumber'];
    // $data->event_id=$req['event_id'];
    // $data->confirm1 = $req['id'];
    // $data->confirm2 = auth()->user()->id;
    // $data->save();
    // broadcast(new leaderboards($check))->toOthers();

    $getuser1 = User::findOrFail($req['id']);

    // $data2 = Event::findOrFail($req['event_id']);
    // $data2->currentfight = $req['fightnumber'];
    // $data2->save();
      // code...
      $result = $req['result'];
      $eventid = $req['event_id'];
      $fn = $req['fightnumber'];
      $c1 = $req['id'];
      $c2 = auth()->user()->id;
      // $createlogs = new Logs();
      // $createlogs->type = 'Confirmed_Grade';
      // $createlogs->user_id = auth()->user()->id;
      // $createlogs->message = auth()->user()->username.' Confirmed '.$getuser1->username.', '.$req['result'].' for fight number '.$req['fightnumber'];
      // $createlogs->save();
      $get1=Event::where('id',$req['event_id'])->first();
      $get=Event::where('event_name',$get1->event_name)->get();
      $array = array();
      foreach ($get as $key) {
        array_push($array, $key->id);
      }
      $getoneprebetonly = selection::select('expertbet_id','event_id')->where('fightnumber',$req['fightnumber'])->whereIn('event_id',$array)->first();
      // $getoneprebetonly=DB::table('selection')->whereIn('event_id',$array)->where('fightnumber',$req['fightnumber'])->first();
      // return $getoneprebetonly;
      if ($getoneprebetonly) {
        $starting = $req['fightnumber'];
        $getactiveevent = Event::where('status',1)->first();
        $remove = Event::where('startingfight',$starting)->whereIn('id',$array)->first();

        $total = $getactiveevent->currentfight + 1;
        if ($remove) {
          // return $remove;
          // if ($remove->startingfight<=$total) {
            // $remove->control = 'Closed';
            // $remove->save();
            $remove = Event::where('startingfight',$starting)->whereIn('id',$array)->update([
              'control' => 'Closed'
            ]);
            $remove = Event::where('startingfight',$starting)->whereIn('id',$array)->first();
            broadcast(new eventlistener($remove))->toOthers();
          // return $remove;
        }
        broadcast(new leaderboards($check))->toOthers();
        broadcast(new resultevent($req['result'],$req['fightnumber'],auth()->user()->name,'Confirmed_result',1,auth()->user()->id,$req['id'],auth()->user()->id));
        $getonebetonly = expertbet::where('id',$getoneprebetonly->expertbet_id)->first();
        // return $getoneprebetonly->expertbet_id;
          $this->dispatch(new grading($result,$eventid,$fn,$c1,$c2,auth()->user()->name,$getonebetonly->startingfight,$array));
          if ($get1->pick2) {
            $this->dispatch(new gradingpick2($result,$eventid,$fn,$c1,$c2,auth()->user()->name,$getonebetonly->startingfight,$array));
          }
          if ($get1->pick3) {
            $this->dispatch(new gradingpick3($result,$eventid,$fn,$c1,$c2,auth()->user()->name,$getonebetonly->startingfight,$array));
          }
          if ($get1->pick4) {
            $this->dispatch(new gradingpick4($result,$eventid,$fn,$c1,$c2,auth()->user()->name,$getonebetonly->startingfight,$array));
          }
          if ($get1->pick5) {
            $this->dispatch(new gradingpick5($result,$eventid,$fn,$c1,$c2,auth()->user()->name,$getonebetonly->startingfight,$array));
          }
          if ($get1->pick6) {
            $this->dispatch(new gradingpick6($result,$eventid,$fn,$c1,$c2,auth()->user()->name,$getonebetonly->startingfight,$array));
          }
          if ($get1->pick8) {
            $this->dispatch(new gradingpick8($result,$eventid,$fn,$c1,$c2,auth()->user()->name,$getonebetonly->startingfight,$array));
          }
          if ($get1->pick14) {
            $this->dispatch(new gradingpick14($result,$eventid,$fn,$c1,$c2,auth()->user()->name,$getonebetonly->startingfight,$array));
          }
      }else{
        // DB::transaction(function () use($fightnumber,$results,$event_id,$confirm1,$confirm2,$data,$name){})
        $starting = $req['fightnumber'] ;
        $getactiveevent = Event::where('status',1)->first();
        $remove = Event::where('startingfight',$starting)->whereIn('id',$array)->update([
          'control'=> 'Finished',
        ]);
        $total = $getactiveevent->currentfight + 1;
        if ($remove) {
          // if ($remove->startingfight<=$total) {
            // $remove->control = 'Finished';
            // $remove->save();
            broadcast(new eventlistener($remove))->toOthers();
          // return $remove;
        }
        $data = new Results();
        $data->result=$req['result'];
        $data->fightnumber=$req['fightnumber'];
        $data->event_id=$req['event_id'];
        $data->confirm1 = $req['id'];
        $data->confirm2 = auth()->user()->id;
        $data->save();
        if ($req['result']==="Meron") {
          $appleresult = 'Red Apple';
        }elseif ($req['result']==="Wala") {
          $appleresult = 'White Apple';
        }else {
          $appleresult = $req['result'];
        }
        $createlogs = new Logs();
        $createlogs->type = 'Confirmed_Grade';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = auth()->user()->username.' Confirmed '.$getuser1->username.', '.$appleresult.' for fight number '.$req['fightnumber'];
        $createlogs->save();
        $data2 = Event::findOrFail($req['event_id']);
        $data2->currentfight = $req['fightnumber'];
        $data2->save();
        broadcast(new leaderboards($check))->toOthers();
        broadcast(new resultevent($req['result'],$req['fightnumber'],auth()->user()->name,'Confirmed',1,auth()->user()->id,$req['id'],auth()->user()->id));
      }

      // event(new resultevent($results,$fightnumber,$getuser2->name,'Confirmed',1,$getuser2->id,$results,$confirm1));
    // grading::dispatch($result,$eventid,$fn,$id);
    // grading::dispatch($result);
    // $getprebets2 = Prebet::where('fightnumber',$data->fightnumber)->where('event_id',$data2->id)->get();
    // grade a win bet
    // bet::whereHas('prebets', function($q) use ($req)
    // {
    //   $q->where('fightnumber','=',$req['fightnumber'])->where('event_id','=',$req['event_id'])->where('selection','=',$req['result']);
    // })->increment('wins');
    // lagyan lahat ng turn
    // bet::whereHas('prebets', function($q) use ($req)
    // {
    //   $q->where('fightnumber','=',$req['fightnumber'])->where('event_id','=',$req['event_id']);
    // })->increment('turn');
    // // if may nadagdag plus 1 win sa prebet
    // Prebet::where('fightnumber','=',$req['fightnumber'])->where('event_id','=',$req['event_id'])->where('selection','=',$req['result'])->update(['win' => 1]);
    // foreach ($getprebets2 as $datas) {
    //   $updatewins1=bet::findOrFail($datas->bet_id);
    //   $updatewins1->turn = $updatewins1->turn + 1;
    //   $updatewins1->save();
    // }
    // $getprebets = Prebet::where('fightnumber',$data->fightnumber)->where('selection',$data->result)->where('event_id',$data2->id)->get();
    // bet::where('event_id',$data2->id)->whereHas('prebets', function($q) use ($data,$req)
    // {
    //   $q->where('fightnumber',$data->fightnumber)->where('selection','=', $req['result'])->where('event_id',$req['event_id']);
    // })->increment('wins');
    // Prebets::where('fightnumber',$data->fightnumber)->where('selection',$data->result)->where('event_id',$data2->id)->update(['win' => 1]);
    // original
    // Prebet::where('fightnumber',$data->fightnumber)->where('event_id',$data2->id)->chunk(100, function($chunks) use($data,$req) {
    //   $actualevent = Event::findOrFail($req['event_id']);
    //   foreach ($chunks as $datas) {
    //     $updatewins1=bet::where('id',$datas->bet_id)->increment('turn');
        // $updatewins1->turn = $updatewins1->turn + 1; commented
        // $updatewins1->save(); commented
        // if ($datas->selection===$req['result']) {
        //   $updatewins = bet::findOrFail($datas->bet_id);
          // $getprebetx = Prebet::where('fightnumber',$data->fightnumber)->where('selection',$data->result)->where('event_id',$actualevent->id)->first(); commented
        // $getprebetxx = Prebet::where('fightnumber',$data->fightnumber)->where('selection',$data->result)->where('event_id',$actualevent->id)->update(['win' => 1]);
        //
        //   if ($updatewins->wins===null ) {
        //     $updatewins->wins=1;
        //     $updatewins->save();

            // $getprebetx->win=1; commented
            // $getprebetx->save(); commented


          // }else{
          //   $updatewins->wins=$updatewins->wins+1;
          //   $updatewins->save();

            // $getprebetx->win=1;commented
            // $getprebetx->save();commented


      //     }
      //
      //   }
      // }
      // });
    // end of original
    // foreach ($chunks as $datax) {
    //   foreach ($datax as $datas) {
    //   $updatewins1=bet::where('id',$datas->bet_id)->first();
    //   $updatewins1->turn = $updatewins1->turn + 1;
    //   $updatewins1->save();
    //   if ($datas->selection===$data->result) {
    //     $updatewins=bet::findOrFail($datas->bet_id);
    //     $getprebetx = Prebet::where('fightnumber',$data->fightnumber)->where('selection',$data->result)->where('event_id',$data2->id)->first();
    //     $getprebetxx = Prebet::where('fightnumber',$data->fightnumber)->where('selection',$data->result)->where('event_id',$data2->id)->update(['win' => 1]);
    //
    //     if ($updatewins->wins===null ) {
    //       $updatewins->wins=1;
    //       // $updatewins->turn=1;
    //       $updatewins->save();
    //
    //       $getprebetx->win=1;
    //       $getprebetx->save();
    //
    //
    //     }else{
    //       $updatewins->wins=$updatewins->wins+1;
    //       // $updatewins->turn=$updatewins->turn+1;
    //       $updatewins->save();
    //
    //       $getprebetx->win=1;
    //       $getprebetx->save();
    //
    //
    //     }
    //
    //   }
    //   }
    // }

    // // DECLARATION OF THE WINNERS
    //
    // $getevent=Event::where('status',1)->first();
    // $data1=$req['fightnumber']-$getevent->pick;
    // $data2=$data1+1;
    //
    // $checker = bet::where('startingfight',$data2)->where('event_id',$getevent->id)->orderBy('wins','DESC')->first();
    // $getevent=Event::where('status',1)->first();
    // if ($checker===null) {
    //   return 'dito hnd existss';
    // }else {
    //   $maxwin=bet::where('startingfight',$data2)->where('event_id',$getevent->id)->orderBy('wins','DESC')->first();
    //   // $getplayerwithmaxwin = bet::where('startingfight',$data2)->where('wins',$maxwin->wins)->where('event_id',$getevent->id)->get();
    //   bet::where('startingfight',$data2)->where('wins',$maxwin->wins)->where('event_id',$getevent->id)->update([
    //     'lose' => DB::raw('turn-wins'),
    //     'winner' => 2
    //   ]);
    //   bet::where('startingfight',$data2)->where('wins',$maxwin->wins)->where('event_id',$getevent->id)->update([
    //     'lose' => DB::raw('turn-wins'),
    //     'winner' => 1
    //   ]);
    //   Potmoney::where('startingfight',$data->startingfight)->where('event_id',$getevent->id)->update(['claim'=>1]);
    //   // foreach ($getplayerwithmaxwin as $data) {
    //   //   $datas= bet::findOrFail($data->id);
    //   //   if ($data->wins===$getevent->pick) {
    //   //     $datas->lose =$getevent->pick - $datas->wins;
    //   //     $datas->winner=2;
    //   //     $datas->save();
    //   //     Potmoney::where('startingfight',$data->startingfight)->where('event_id',$getevent->id)->update(['claim'=>1]);
    //   //   }else {
    //   //     $datas->lose =$getevent->pick - $datas->wins ;
    //   //     $datas->winner=1;
    //   //     $datas->save();
    //   //     $getpotmoney = Potmoney::where('startingfight',$data->startingfight)->where('event_id',$getevent->id)->update(['claim'=>1]);
    //   //   }
    //   // }
    //     // DECLARATION OF THE LOSERS
    //   bet::where('startingfight',$data2)->where('winner',0)->where('event_id',$getevent->id)->update([
    //     'lose' => DB::raw('turn-wins'),
    //     'winner' => 3
    //   ]);
    //   // foreach ($getplayerlosers as $key) {
    //   //   $datax= bet::findOrFail($key->id);
    //   //   $datax->lose =$getevent->pick - $datax->wins ;
    //   //   $datax->winner=3;
    //   //   $datax->save();
    //   // }
    //   broadcast(new leaderboards($getevent))->toOthers();
    // }
    // broadcast(new resultevent($req['result'],$req['fightnumber'],auth()->user()->name,'Confirmed',1,auth()->user()->id,$req['id']))->toOthers();
    // return 'success';
  }
  }

  public function insertresults(Request $req)
  {
    $this->validate($req, [
      'result' => 'required|max:255',
      'event_id' => 'required|max:255',
      'fightnumber'=> 'required',
      'fightnumber'=> 'required',
      'declarator_id'=> 'required',
    ]);

    if ($req['result']==="Meron") {
      $appleresult = 'Red Apple';
    }elseif ($req['result']==="Wala") {
      $appleresult = 'White Apple';
    }else {
      $appleresult = $req['result'];
    }
    // create logs
    $getrequesteddeclarator = User::where('id',$req['declarator_id'])->first();
    $createlogs = new Logs();
    $createlogs->type = 'Requested_Grade';
    $createlogs->user_id = auth()->user()->id;
    $createlogs->message = auth()->user()->username.' Requested '.$appleresult.' for fight number '.$req['fightnumber'].' to '.$getrequesteddeclarator->username.'.';
    $createlogs->save();

    broadcast(new resultevent($req['result'],$req['fightnumber'],auth()->user()->name,'Confirm',auth()->user()->id,$req['declarator_id'],$req['event_id'],auth()->user()->id))->toOthers();
  }

}
