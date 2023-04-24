<?php

namespace App\Jobs;

use App\Events\resultevent;
use App\Events\leaderboards;
use App\Events\eventlistener;
use App\Models\Event;
use App\Models\Prebet;
use App\Models\selection;
use App\Models\expertbet;
use App\Models\bet;
use App\Models\control;
use App\Models\Potmoney;
use App\Jobs\secondgrading;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\startingfights;
use App\Models\Results;
use Auth;
use App\Models\Logs;
use App\Models\User;

class gradingpick2 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $result;
    public $eventid;
    public $fn;
    public $c1;
    public $c2;
    public $name;
    public $startingfight;
    public $array;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($result,$eventid,$fn,$c1,$c2,$name,$startingfight,$array)
    {
        $this->result = $result;
        $this->eventid = $eventid;
        $this->fn = $fn;
        $this->c1 = $c1;
        $this->c2 = $c2;
        $this->name = $name;
        $this->startingfight = $startingfight;
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
      $results = $this->result;
      $event_id = $this->eventid;
      $a = $this->array;

      $Starting = $this->startingfight;
      $confirm1 = $this->c1;
      $confirm2 = $this->c2;
      $data = $this->result;
      $name = $this->name;

      DB::transaction(function () use($fightnumber,$results,$event_id,$confirm1,$confirm2,$data,$name,$a,$Starting){

        DB::table('selection as a')
        ->where('a.fightnumber', '=', $fightnumber)->whereIn('a.event_id', $a)->where('a.selection', '=', $results)->where('winner',0)->where('turn',2)
        ->join('expertbet as c', 'a.expertbet_id', '=', 'c.id')
        ->update(['c.wins' =>DB::raw('wins + 1')]);

        // DECLARATION OF THE WINNERS FOR PICK 2
        $checkfightnumber = $fightnumber-1;
        $CHECKPOTMONEY = Potmoney::whereIn('event_id',$a)->where('startingfight',$checkfightnumber)->where('pick',2)->first();
        if (isset($CHECKPOTMONEY)) {
          $lastfight = $CHECKPOTMONEY->startingfight+1;
        if ($CHECKPOTMONEY) {
          $checkforcancel = Results::whereIn('event_id',$a)->where('result','Cancelled')->whereBetween('fightnumber', [$CHECKPOTMONEY->startingfight, $lastfight])->count();
          if ($checkforcancel==1) {
            $control=control::first();
            $maxwin=expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->orderBy('wins','DESC')->where('turn',2)->first();
            // $maxwin2=expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->orderBy('wins','DESC')->where('turn',2)->select('wins')->groupBy('wins')->first();
              // foreach ($maxwin2 as $key) {
              expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->where('wins',1)->whereIn('event_id',$a)->where('turn',2)->update([
                'lose' => DB::raw('turn-wins'),
                'winner' => 1
              ]);

              $winners = expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->where('turn',2)->get();
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
              expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->where('turn',2)->update([
                'lose' => DB::raw('turn-wins'),
                'winner' => 4
              ]);
            }else {
              // declaration of all Winners

              // declaration of all losers
              expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->where('winner',0)->whereIn('event_id',$a)->where('turn',2)->update([
                'lose' => DB::raw('turn-wins'),
                'winner' => 3
              ]);
            }

            Potmoney::where('startingfight',$CHECKPOTMONEY->startingfight)->where('event_id',$maxwin->event_id)->where('pick',2)->update(['claim'=>1]);
            Event::where('startingfight',$CHECKPOTMONEY->startingfight)->where('id',$CHECKPOTMONEY->event_id)->update(['control'=>'Finished']);
            event(new leaderboards($data));
            event(new resultevent('Last','endevent',$confirm2,'eventupdate','id',$confirm2,'id','id'));
            event(new eventlistener($data));
            return 'success';
          }
          elseif ($checkforcancel==2) {
            $check=expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->where('winner',0)->whereIn('event_id',$a)->where('turn',2)->update([
              'lose' => DB::raw('turn-wins'),
              'winner' => 4
            ]);
            Event::where('startingfight',$CHECKPOTMONEY->startingfight)->where('id',$CHECKPOTMONEY->event_id)->update(['control'=>'Finished']);
            Potmoney::where('startingfight',$CHECKPOTMONEY->startingfight)->where('event_id',$CHECKPOTMONEY->event_id)->where('pick',2)->update(['claim'=>1]);
            event(new leaderboards($data));
            event(new resultevent('Last','endevent',$confirm2,'eventupdate','id',$confirm2,'id','id'));
            event(new eventlistener($data));
            return 'success';
          }
          else {
            $control=control::first();
            $maxwin=expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->orderBy('wins','DESC')->where('turn',2)->first();
            // $maxwin2=expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->orderBy('wins','DESC')->where('turn',2)->select('wins')->groupBy('wins')->first();
              // foreach ($maxwin2 as $key) {
              // declaration of all Winners
              expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->where('wins',2)->whereIn('event_id',$a)->where('turn',2)->update([
               'lose' => DB::raw('turn-wins'),
               'winner' => 1
             ]);

              $winners = expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->where('turn',2)->get();

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
            event(new resultevent('Last','endevent','Dividendo '.$dividendo,'eventupdate','id',$confirm2,'id','id'));
            if ($dividendo<130) {
              expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->where('turn',2)->update([
               'lose' => DB::raw('turn-wins'),
               'winner' => 4
             ]);
            }else {

              // declaration of all losers
              expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->where('winner',0)->whereIn('event_id',$a)->where('turn',2)->update([
                'lose' => DB::raw('turn-wins'),
                'winner' => 3
              ]);
            }

            Potmoney::where('startingfight',$CHECKPOTMONEY->startingfight)->where('event_id',$CHECKPOTMONEY->event_id)->where('pick',2)->update(['claim'=>1]);
            Event::where('startingfight',$CHECKPOTMONEY->startingfight)->where('id',$CHECKPOTMONEY->event_id)->update(['control'=>'Finished']);
            event(new leaderboards($data));
            event(new resultevent('Last','endevent',$confirm2,'eventupdate','id',$confirm2,'id','id'));
            event(new eventlistener($data));
            return 'success';
          }

        }else {
          Event::where('startingfight',$fightnumber)->whereIn('id',$a)->where('pick',2)->update(['control'=>'Finished']);
          event(new leaderboards($data));
          event(new resultevent('Last','fightnumber '.$fightnumber,$confirm2,'eventupdate','id',$confirm2,'id','id'));
          //event(new resultevent('Last','endevent',$confirm2,'alexpogilang','id',$confirm2,'id','id'));
          event(new eventlistener($data));
          return 'success';
        }
      }

      });
    }
  }
