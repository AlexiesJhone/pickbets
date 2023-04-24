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

class regradingpick2 implements ShouldQueue
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
      $lastnumber = $startingfight->startingfight + 1;
      $lastnumbernocancel = $startingfight->startingfight + $control->pick -1;
      $isangcancel = $startingfight->startingfight + $control->pick;
      $dalawangcancel = $startingfight->startingfight + $control->pick+1;
      $tatlongcancel = $startingfight->startingfight + $control->pick+2;

      $checkforcancel = Results::whereIn('event_id',$a)->where('result','Cancelled')->whereBetween('fightnumber', [$startingfight->startingfight, $lastnumber])->count();


      $checkforcancel=true;
      if ($checkforcancel) {
        DB::table('selection as a')
      ->join('expertbet as c', 'a.expertbet_id', '=', 'c.id')
      ->where('a.fightnumber', '=', $fightnumber)->whereIn('a.event_id', $a)->where('a.selection', '=', $lumangresult)->where('c.turn',2)
      ->update(['c.wins' =>DB::raw('wins - 1')]);

        DB::table('selection as a')
      ->where('a.fightnumber', '=', $fightnumber)->whereIn('a.event_id', $a)->where('a.selection', '=', $bagongresult)->where('c.turn',2)
      ->join('expertbet as c', 'a.expertbet_id', '=', 'c.id')
      ->update(['c.wins' =>DB::raw('wins + 1')]);

      $getevent=Event::whereIn('id',$a)->where('pick',2)->first();
      $control = control::first();
        $getbet = expertbet::where('id',$startingfight->id)->whereIn('event_id',$a)->where('turn',2)->first();
		      $checkforcancel = Results::whereIn('event_id',$a)->where('result','Cancelled')->whereBetween('fightnumber', [$startingfight->startingfight, $lastnumber])->count();
        $computed1 = true;
        if ($getevent->currentfight >= $lastnumber) {
          event(new resultevent('alex'.$startingfight->id.' '.$getbet,$isangcancel,$dalawangcancel,$tatlongcancel,'cancelcount ay '.$checkforcancel,'last number ay '.$lastnumber,'fught number ay'.$fightnumber,''));
          // DECLARATION OF THE WINNERS
          // $data1=$getbet->startingfight-1;
          // $data2=$data1+1;
          $checker = expertbet::where('startingfight',$getbet->startingfight)->whereIn('event_id',$a)->where('turn',2)->orderBy('wins','DESC')->first();
          if ($checker===null) {
            return $getbet->startingfight;
          }else {
            expertbet::where('startingfight',$getbet->startingfight)->whereIn('event_id',$a)->where('turn',2)->update(['winner'=>0]);
            // $maxwin=expertbet::where('startingfight',$getbet->startingfight)->whereIn('event_id',$a)->orderBy('wins','DESC')->first();

              // foreach ($maxwin2 as $key) {
              if ($checkforcancel==1) {
                expertbet::where('startingfight',$getbet->startingfight)->where('wins',1)->where('turn',2)->whereIn('event_id',$a)->update([
                  'lose' => DB::raw('turn-wins'),
                  'winner' => 1
                ]);
                $winners = expertbet::where('startingfight',$getbet->startingfight)->whereIn('event_id',$a)->where('turn',2)->get();
              // }
              $getallamount = $winners->sum('amount');
              $rakecalc = $control->rakepick2/100;
              $rakecalc2 = $getallamount * $rakecalc;
              $finalrake = $getallamount - $rakecalc2;
              $winnersamount = $winners->where('winner',1)->sum('amount');
              if ($winnersamount) {
                $dividendocalc = $finalrake/$winnersamount;
                $dividendo = floor($dividendocalc*100);
              }else {
                $dividendo = $finalrake;
              }

              if ($dividendo<130) {
                expertbet::where('startingfight',$getbet->startingfight)->whereIn('event_id',$a)->where('turn',2)->update([
                  'lose' => DB::raw('turn-wins'),
                  'winner' => 4
                ]);
              }else {
                // declaration of all Winners

                // declaration of all losers
                expertbet::where('startingfight',$getbet->startingfight)->where('winner',0)->whereIn('event_id',$a)->where('turn',2)->update([
                  'lose' => DB::raw('turn-wins'),
                  'winner' => 3
                ]);
              }
              }elseif ($checkforcancel==2) {
                expertbet::where('startingfight',$getbet->startingfight)->where('turn',2)->whereIn('event_id',$a)->update([
                  'lose' => DB::raw('turn-wins'),
                  'winner' => 4
                ]);
              }else{
                expertbet::where('startingfight',$getbet->startingfight)->where('wins',2)->where('turn',2)->whereIn('event_id',$a)->update([
                  'lose' => DB::raw('turn-wins'),
                  'winner' => 1
                ]);
                $winners = expertbet::where('startingfight',$getbet->startingfight)->whereIn('event_id',$a)->where('turn',2)->get();
              // }
              $getallamount = $winners->sum('amount');
              $rakecalc = $control->rakepick2/100;
              $rakecalc2 = $getallamount * $rakecalc;
              $finalrake = $getallamount - $rakecalc2;
              $winnersamount = $winners->where('winner',1)->sum('amount');
              if ($winnersamount) {
                $dividendocalc = $finalrake/$winnersamount;
                $dividendo = floor($dividendocalc*100);
              }else {
                $dividendo = $finalrake;
              }

              if ($dividendo<130) {
                expertbet::where('startingfight',$getbet->startingfight)->whereIn('event_id',$a)->where('turn',2)->update([
                  'lose' => DB::raw('turn-wins'),
                  'winner' => 4
                ]);
              }else {
                // declaration of all Winners

                // declaration of all losers
                expertbet::where('startingfight',$getbet->startingfight)->where('winner',0)->whereIn('event_id',$a)->where('turn',2)->update([
                  'lose' => DB::raw('turn-wins'),
                  'winner' => 3
                ]);
              }
              }
            Event::whereIn('id',$a)->where('startingfight',$startingfight->startingfight)->where('pick',2)->update(['control' =>'Finished']);
            // expertbet::where('startingfight',$getbet->startingfight)->where('winner',0)->whereIn('event_id',$a)->where('turn',2)->update(['winner'=>3, 'lose'=> DB::raw('turn-wins')]);
          }
          event(new leaderboards($getevent));
          event(new resultevent($bagongresult,$fightnumber,$named,'Confirmgraded',$cc1,$cc1,$cc1,$bagongresult));
        }else {
          event(new leaderboards($getevent));
          event(new resultevent($bagongresult,$fightnumber,$named,'Confirmgraded',$cc1,$cc1,$cc1,$bagongresult));
        }

    }
}
}
