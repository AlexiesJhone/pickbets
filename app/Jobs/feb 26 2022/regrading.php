<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\Logs;
use App\Models\bet;
use App\Models\control;
use App\Models\selection;
use App\Models\expertbet;
use App\Models\Event;
use App\Models\Results;
use App\Models\Prebet;
use App\Events\leaderboards;
use App\Events\resultevent;

class regrading implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $oldresult;
    public $newresult;
    public $eventid;
    public $fn;
    public $c1;
    public $c2;
    public $getoneprebetonly;
    public $name;
    public $array;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($oldresult,$newresult,$eventid,$fn,$c1,$c2,$getoneprebetonly,$name,$array)
    {
      $this->oldresult = $oldresult;
      $this->newresult = $newresult;
      $this->eventid = $eventid;
      $this->fn = $fn;
      $this->c2 = $c2;
      $this->c1 = $c1;
      $this->getoneprebetonly = $getoneprebetonly;
      $this->name = $name;
      $this->array = $array;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $fightnumber = $this->fn;
      $bagongresult = $this->newresult;
      $lumangresult = $this->oldresult;
      $event_id = $this->eventid;
      $startingfight = $this->getoneprebetonly;
      $named = $this->name;
      $cc1 = $this->name;
      $a = $this->array;
      $control = control::first();
      $lastnumber = $startingfight->startingfight + $control->pick ;
      $lastnumbernocancel = $startingfight->startingfight + $control->pick -1;
      $isangcancel = $startingfight->startingfight + $control->pick;
      // $start =
      $checkforcancel = Results::whereIn('event_id',$a)->where('result','Cancelled')->whereBetween('fightnumber', [$startingfight->startingfight, $lastnumber])->count();
      // return $checkforcancel;
      if ($checkforcancel==1 && $fightnumber===$isangcancel) {
        DB::table('selection as a')
      ->where('a.fightnumber', '=', $fightnumber)->whereIn('a.event_id', $a)->where('a.selection', '=', $lumangresult)
      ->join('expertbet as c', 'a.expertbet_id', '=', 'c.id')
      ->update(['c.wins' =>DB::raw('wins - 1')]);

        DB::table('selection as a')
      ->where('a.fightnumber', '=', $fightnumber)->whereIn('a.event_id', $a)->where('a.selection', '=', $bagongresult)
      ->join('expertbet as c', 'a.expertbet_id', '=', 'c.id')
      ->update(['c.wins' =>DB::raw('wins + 1')]);
    }
    // $checkforcancel2 = Results::whereIn('event_id',$a)->where('result','Cancelled')->whereBetween('fightnumber', [$startingfight->startingfight, $lastnumber])->count();
    elseif($checkforcancel>=2) {
      // return $isangcancel;
        DB::table('selection as a')
      ->where('a.fightnumber', '=', $fightnumber)->whereIn('a.event_id', $a)->where('a.selection', '=', $lumangresult)
      ->join('expertbet as c', 'a.expertbet_id', '=', 'c.id')
      ->update(['c.wins' =>DB::raw('wins - 1')]);

        DB::table('selection as a')
      ->where('a.fightnumber', '=', $fightnumber)->whereIn('a.event_id', $a)->where('a.selection', '=', $bagongresult)
      ->join('expertbet as c', 'a.expertbet_id', '=', 'c.id')
      ->update(['c.wins' =>DB::raw('wins + 1')]);
    }elseif ($fightnumber===$lastnumbernocancel&&$bagongresult==='Cancelled') {
        DB::table('selection as a')
      ->where('a.fightnumber', '=', $fightnumber)->whereIn('a.event_id', $a)->where('a.selection', '=', $lumangresult)
      ->join('expertbet as c', 'a.expertbet_id', '=', 'c.id')
      ->update(['c.wins' =>DB::raw('wins - 1')]);

        DB::table('selection as a')
      ->where('a.fightnumber', '=', $fightnumber)->whereIn('a.event_id', $a)
      ->join('expertbet as c', 'a.expertbet_id', '=', 'c.id')
      ->update(['c.lose' =>null,'c.winner'=>0]);
      Event::whereIn('id',$a)->where('startingfight',$startingfight->startingfight)
      ->update(['control' =>'Closed']);
    }
	elseif (!$checkforcancel&&$lastnumbernocancel>=$fightnumber) {
      DB::table('selection as a')
    ->where('a.fightnumber', '=', $fightnumber)->whereIn('a.event_id', $a)->where('a.selection', '=', $lumangresult)
    ->join('expertbet as c', 'a.expertbet_id', '=', 'c.id')
    ->update(['c.wins' =>DB::raw('wins - 1')]);

      DB::table('selection as a')
    ->where('a.fightnumber', '=', $fightnumber)->whereIn('a.event_id', $a)->where('a.selection', '=', $bagongresult)
    ->join('expertbet as c', 'a.expertbet_id', '=', 'c.id')
    ->update(['c.wins' =>DB::raw('wins + 1')]);
    }
    event(new resultevent($checkforcancel,$startingfight->startingfight,$lastnumber,'alex','alex',$bagongresult,$fightnumber,$lastnumbernocancel));


      // bet::whereHas('prebets', function($q) use ($req)
      // {
      //   $q->where('fightnumber',$req['fightnumber'])->where('event_id',$req['event_id'])->where('selection','=',$req['result'])->where('win',null);
      // })->increment('wins');
      // bet::whereHas('prebets', function($q) use ($req)
      // {
      //   $q->where('fightnumber',$req['fightnumber'])->where('event_id',$req['event_id'])->where('selection','!=',$req['result'])->where('win',1);
      // })->decrement('wins');



      $getevent=Event::whereIn('id',$a)->first();
      $control = control::first();
        $getbet = expertbet::where('id',$startingfight->expertbet_id)->whereIn('event_id',$a)->first();
		$checkforcancel = Results::whereIn('event_id',$a)->where('result','Cancelled')->whereBetween('fightnumber', [$startingfight->startingfight, $lastnumber])->count();
        if (!$checkforcancel) {
          $computed = $getbet->startingfight+$control->pick-1;
        }elseif ($checkforcancel==1) {
          $computed = $getbet->startingfight+$control->pick;
        }elseif ($checkforcancel==2) {
          $computed = $getbet->startingfight+$control->pick+1;
        }
        $computed1 = true;

        // ITO ANYTIME PWEDE MABAGO WINNER SA REGRADE
        // if ($computed1) {

          // ITO MABABAGO LANG WINNER PAG NASA LAST CURRENT MATCH NG FIRST PICK NG BET NIYA UNG CURRENT FIGHT NUMBER
        if ($getevent->currentfight >= $computed) {
          // DECLARATION OF THE WINNERS
          $data1=$getbet->startingfight-$control->pick +1;
          $data2=$data1+1;
          $checker = expertbet::where('startingfight',$getbet->startingfight)->whereIn('event_id',$a)->orderBy('wins','DESC')->first();
          if ($checker===null) {
            return $getbet->startingfight;
          }else {
            expertbet::where('startingfight',$getbet->startingfight)->whereIn('event_id',$a)->update(['winner'=>0]);
            $maxwin=expertbet::where('startingfight',$getbet->startingfight)->whereIn('event_id',$a)->orderBy('wins','DESC')->first();
            $maxwin2=expertbet::where('startingfight',$getbet->startingfight)->whereIn('event_id',$a)->orderBy('wins','DESC')->select('wins')->groupBy('wins')->get()->take($control->numberofwinners);
            if ($maxwin->wins===$control->pick) {
              foreach ($maxwin2 as $key) {
                if ($control->pick === $key->turn) {
                  expertbet::where('startingfight',$getbet->startingfight)->where('wins',$maxwin->wins)->where('event_id',$maxwin->event_id)->update([
                    'lose' => 0,
                    'winner' => 2
                  ]);
                }
                // else {
                //   expertbet::where('startingfight',$data2)->where('wins',$maxwin->wins)->where('event_id',$maxwin->event_id)->update([
                //     'lose' => DB::raw('turn-wins'),
                //     'winner' => 1
                //   ]);
                // }
            }
            }else {
              foreach ($maxwin2 as $key) {
              expertbet::where('startingfight',$getbet->startingfight)->where('wins',$key->wins)->whereIn('event_id',$a)->update([
                'lose' => DB::raw('turn-wins'),
                'winner' => 1
              ]);
            }
            }
            // expertbet::where('startingfight',$getbet->startingfight)->where('wins',$control->pick)->whereIn('event_id',$a)->where('wins',$control->pick)->update([
            //   'lose' => DB::raw('turn-wins'),
            //   'winner' => 2
            // ]);
            // expertbet::where('startingfight',$getbet->startingfight)->where('wins',$maxwin->wins)->whereIn('event_id',$a)->where('wins',$maxwin->wins)->update([
            //   'lose' => DB::raw('turn-wins'),
            //   'winner' => 1
            // ]);
            Event::whereIn('id',$a)->where('startingfight',$startingfight->startingfight)
            ->update(['control' =>'Finished']);
            expertbet::where('startingfight',$getbet->startingfight)->where('winner',0)->whereIn('event_id',$a)->update(['winner'=>3, 'lose'=> DB::raw('turn-wins')]);
          }
          event(new leaderboards($getevent));
          event(new resultevent($bagongresult,$fightnumber,$named,'Confirmgraded',$cc1,$cc1,$cc1,$bagongresult));
        }else {
          event(new leaderboards($getevent));
          event(new resultevent($bagongresult,$fightnumber,$named,'Confirmgraded',$cc1,$cc1,$cc1,$bagongresult));
        }

    }
}
