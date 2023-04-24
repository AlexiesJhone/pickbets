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
use App\Models\Event;
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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($oldresult,$newresult,$eventid,$fn,$c1,$c2,$getoneprebetonly,$name)
    {
      $this->oldresult = $oldresult;
      $this->newresult = $newresult;
      $this->eventid = $eventid;
      $this->fn = $fn;
      $this->c2 = $c2;
      $this->c1 = $c1;
      $this->getoneprebetonly = $getoneprebetonly;
      $this->name = $name;
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

      DB::table('Prebet as a')
    ->where('a.fightnumber', '=', $fightnumber)->where('a.event_id', '=', $event_id)->where('a.selection', '=', $lumangresult)
    ->join('bet as c', 'a.bet_id', '=', 'c.id')
    ->update(['c.wins' =>DB::raw('wins - 1')]);

      DB::table('Prebet as a')
    ->where('a.fightnumber', '=', $fightnumber)->where('a.event_id', '=', $event_id)->where('a.selection', '=', $bagongresult)
    ->join('bet as c', 'a.bet_id', '=', 'c.id')
    ->update(['c.wins' =>DB::raw('wins + 1')]);

      // bet::whereHas('prebets', function($q) use ($req)
      // {
      //   $q->where('fightnumber',$req['fightnumber'])->where('event_id',$req['event_id'])->where('selection','=',$req['result'])->where('win',null);
      // })->increment('wins');
      // bet::whereHas('prebets', function($q) use ($req)
      // {
      //   $q->where('fightnumber',$req['fightnumber'])->where('event_id',$req['event_id'])->where('selection','!=',$req['result'])->where('win',1);
      // })->decrement('wins');



      $getevent=Event::where('status',1)->first();
        $getbet = bet::where('id',$startingfight)->where('event_id',$event_id)->first();
        $computed = $getbet->startingfight+$getevent->pick-1;
        $computed1 = true;

        // ITO ANYTIME PWEDE MABAGO WINNER SA REGRADE
        // if ($computed1) {

          // ITO MABABAGO LANG WINNER PAG NASA LAST CURRENT MATCH NG FIRST PICK NG BET NIYA UNG CURRENT FIGHT NUMBER
        if ($getevent->currentfight >= $computed) {
          // DECLARATION OF THE WINNERS
          $data1=$getbet->startingfight-$getevent->pick +1;
          $data2=$data1+1;
          $checker = bet::where('startingfight',$getbet->startingfight)->where('event_id',$getevent->id)->orderBy('wins','DESC')->first();
          if ($checker===null) {
            return $getbet->startingfight;
          }else {
            $maxwin=bet::where('startingfight',$getbet->startingfight)->where('event_id',$getevent->id)->orderBy('wins','DESC')->first();
            bet::where('startingfight',$getbet->startingfight)->where('event_id',$getevent->id)->update(['winner'=>0]);
            bet::where('startingfight',$getbet->startingfight)->where('wins',$getevent->pick)->where('event_id',$getevent->id)->where('wins',$getevent->pick)->update([
              'lose' => DB::raw('turn-wins'),
              'winner' => 2
            ]);
            bet::where('startingfight',$getbet->startingfight)->where('wins',$maxwin->wins)->where('event_id',$getevent->id)->where('wins',$maxwin->wins)->update([
              'lose' => DB::raw('turn-wins'),
              'winner' => 1
            ]);
            bet::where('startingfight',$getbet->startingfight)->where('winner',0)->where('event_id',$getevent->id)->update(['winner'=>3, 'lose'=> DB::raw('turn-wins')]);
          }
          event(new leaderboards($getevent));
          event(new resultevent($bagongresult,$fightnumber,$named,'Confirmgraded',$cc1,$cc1,$cc1,$bagongresult));
        }else {
          event(new leaderboards($getevent));
          event(new resultevent($bagongresult,$fightnumber,$named,'Confirmgraded',$cc1,$cc1,$cc1,$bagongresult));
        }

    }
}
