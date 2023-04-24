<?php

namespace App\Http\Controllers;

use App\Models\Results;
use App\Events\resultevent;
use App\Events\leaderboards;
use App\Events\eventlistener;
use App\Jobs\grading;
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
      return 'alex';
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

    $createlogs = new Logs();
    $createlogs->type = 'Requested_Regrade';
    $createlogs->user_id = auth()->user()->id;
    $createlogs->message = auth()->user()->username.' requested regrade '.$req['newresult'].' for fight number '.$req['fightnumber'];
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
    $getoneprebetonly = selection::whereIn('event_id',$array)->where('fightnumber',$req['fightnumber'])->first();
    $get1=Event::where('id',$req['event_id'])->first();
    $get=Event::where('event_name',$get1->event_name)->get();
    $array = array();
    foreach ($get as $key) {
      array_push($array, $key->id);
    }
    if ($getoneprebetonly) {
      $this->dispatch(new regrading($oldresult,$newresult,$eventid,$fn,$c1,$c2,$getoneprebetonly->expertbet_id,$name,$array));
    }else {
      event(new leaderboards($get));
      event(new resultevent($newresult,$fn,$name,'Confirmgraded',$name,$name,$name,$newresult));
    }
    // ito ung original na prebet regrade
    // $getprebet = Prebet::where('fightnumber',$req['fightnumber'])->where('event_id',$getevent->id)->get();

    // Prebet::where('fightnumber','=',$req['fightnumber'])->where('event_id','=',$getevent->id)->where('selection','!=',$req['result'])->where('win','=',1)->update(['win' => null]);
    // Prebet::where('fightnumber','=',$req['fightnumber'])->where('event_id','=',$getevent->id)->where('selection','=',$req['result'])->where('win','=',null)->update(['win' => 1]);
    // Prebet::where('fightnumber','=',$req['fightnumber'])->where('event_id','=',$getevent->id)->where('selection','!=',$req['result'])->where('win','=',null)->update(['win' => null]);

    // create logs
    $createlogs = new Logs();
    $createlogs->type = 'Confirmed_Regrade';
    $createlogs->user_id = auth()->user()->id;
    $getuser1 = User::findOrFail($req['user_id']);
    $createlogs->message = auth()->user()->username.' Confirmed '.$getuser1->username.' for regrade, '.$req['newresult'].' for fight number '.$req['fightnumber'];
    $createlogs->save();



    // broadcast(new resultevent($req['result'],$req['fightnumber'],auth()->user()->name,'Confirmgraded',$req['id']))->toOthers();
    // foreach ($getprebet as $regrade) {
    //
    //   $regradex = bet::where('id',$regrade->bet_id)->where('event_id',$getevent->id)->first();
    //   $getprebetx = Prebet::where('id',$regrade->id)->first();
    //
    //   // PAG PAREHAS RESULT AT MAY NADAGDAG
    //   if ($getprebetx->selection === $req['result'] && $getprebetx->win === 1) {
    //
    //   }// PAG HND PAREHAS RESULT AT MAY NADAGDAG
    //   elseif ($getprebetx->selection != $req['result'] && $getprebetx->win === 1) {
    //     if ($regradex->wins===0) {
    //       // code...
    //     }else{
    //       $getprebetx->win = null;
    //       $getprebetx->save();
    //       $regradex->wins = $regradex->wins-1;
    //       $regradex->save();
    //     }
    //   }// PAG PAREHAS RESULT AT WALANG NADAGDAG
    //   elseif ($getprebetx->selection === $req['result'] && $getprebetx->win === null) {
    //     $getprebetx->win = 1;
    //     $getprebetx->save();
    //     $regradex->wins = $regradex->wins+1;
    //     $regradex->save();
    //   }// PAG HND PAREHAS RESULT AT WALANG NADAGDAG
    //   elseif ($getprebetx->selection != $req['result'] && $getprebetx->win === null) {
    //     $getprebetx->win = null;
    //     $getprebetx->save();
    //   }
    // }


    // broadcast(new leaderboards($regrade))->toOthers();
    // broadcast(new resultevent($req['result'],$req['fightnumber'],auth()->user()->name,'Confirmgraded',$req['id'],auth()->user()->id,$req['user_id']))->toOthers();
    // broadcast(new resultevent($req['result'],$req['fightnumber'],auth()->user()->name,'Confirmed',$req['id'],auth()->user()->id,$req['user_id']))->toOthers();
  }

  public function sample()
  {
    // CLEAR ALL TABLES JUST FOR TESTING AND DEBUGGING
    $getevent=Event::where('status',1)->first();
    $getevent->startingfight=0;
    $getevent->control="Close";
    $getevent->currentfight=0;
    $getevent->save();
    bet::truncate();
    Prebet::truncate();
    Potmoney::truncate();
    Results::truncate();
    Transactions::truncate();
    Logs::truncate();
  }
  public function confirmed(Request $req)
  {
    $this->validate($req, [
      'result' => 'required|max:255',
      'event_id' => 'required|max:255',
      'fightnumber'=> 'required',
    ]);
    $check=Results::where('fightnumber',$req['fightnumber'])->where('event_id',$req['event_id'])->first();
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
      $getoneprebetonly = selection::select('expertbet_id')->where('fightnumber',$req['fightnumber'])->whereIn('event_id',$array)->first();
      // $getoneprebetonly=DB::table('selection')->whereIn('event_id',$array)->where('fightnumber',$req['fightnumber'])->first();
      if ($getoneprebetonly) {
        $starting = $req['fightnumber'];
        $getactiveevent = Event::where('status',1)->first();
        $remove = Event::where('startingfight',$starting)->whereIn('id',$array)->first();
        $total = $getactiveevent->currentfight + 1;
        if ($remove) {
          // if ($remove->startingfight<=$total) {
            $remove->control = 'Closed';
            $remove->save();
            broadcast(new eventlistener($remove))->toOthers();
          // return $remove;
        }
        broadcast(new leaderboards($check))->toOthers();
        broadcast(new resultevent($req['result'],$req['fightnumber'],auth()->user()->name,'Confirmed_result',1,auth()->user()->id,$req['id'],auth()->user()->id));
        $getonebetonly = expertbet::where('id',$getoneprebetonly->expertbet_id)->first();

        $this->dispatch(new grading($result,$eventid,$fn,$c1,$c2,auth()->user()->name,$getonebetonly->startingfight,$array));
      }else{
        // DB::transaction(function () use($fightnumber,$results,$event_id,$confirm1,$confirm2,$data,$name){})
        $starting = $req['fightnumber'] ;
        $getactiveevent = Event::where('status',1)->first();
        $remove = Event::where('startingfight',$starting)->first();
        $total = $getactiveevent->currentfight + 1;
        if ($remove) {
          // if ($remove->startingfight<=$total) {
            $remove->control = 'Finished';
            $remove->save();
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
        $createlogs = new Logs();
        $createlogs->type = 'Confirmed_Grade';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = auth()->user()->username.' Confirmed '.$getuser1->username.', '.$req['result'].' for fight number '.$req['fightnumber'];
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
    // create logs

    $createlogs = new Logs();
    $createlogs->type = 'Requested_Grade';
    $createlogs->user_id = auth()->user()->id;
    $createlogs->message = auth()->user()->username.' Requested '.$req['result'].' for fight number '.$req['fightnumber'];
    $createlogs->save();

    broadcast(new resultevent($req['result'],$req['fightnumber'],auth()->user()->name,'Confirm',auth()->user()->id,$req['declarator_id'],$req['event_id'],auth()->user()->id))->toOthers();
  }

}
