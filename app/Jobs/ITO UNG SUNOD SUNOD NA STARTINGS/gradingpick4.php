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

class gradingpick4 implements ShouldQueue
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

        // DB::table('selection as a')
        // ->where('a.fightnumber', '=', $fightnumber)->whereIn('a.event_id', $a)->where('a.selection', '=', $results)->where('winner',0)->where('turn',3)
        // ->join('expertbet as c', 'a.expertbet_id', '=', 'c.id')
        // ->update(['c.wins' =>DB::raw('wins + 1')]);

        // DECLARATION OF THE WINNERS FOR PICK 2
        $checkfightnumber = $fightnumber-3;
        $CHECKPOTMONEY = Potmoney::whereIn('event_id',$a)->where('startingfight',$checkfightnumber)->where('pick',4)->first();
        if (!$CHECKPOTMONEY) {
          return 'wala';
        }
        $checkevent = Event::where('id',$CHECKPOTMONEY->event_id)->where('pick',4)->first();
        if (isset($CHECKPOTMONEY)) {
          $lastfight = $CHECKPOTMONEY->startingfight+3;
        if ($CHECKPOTMONEY) {
          $checkforcancel = Results::whereIn('event_id',$a)->where('result','Cancelled')->whereBetween('fightnumber', [$CHECKPOTMONEY->startingfight, $lastfight])->count();
          if ($checkforcancel==1) {
            $control=control::first();
            $maxwin=expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->orderBy('wins','DESC')->where('turn',4)->first();
            // $maxwin2=expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->orderBy('wins','DESC')->where('turn',2)->select('wins')->groupBy('wins')->first();
              // foreach ($maxwin2 as $key) {
            $checkifmeron = expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->where('wins',3)->whereIn('event_id',$a)->where('turn',4)->update([
                'lose' => DB::raw('turn-wins'),
                'winner' => 1
              ]);

              $winners = expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->where('turn',4)->get();
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
            $minimumpay = $checkevent->payout+1;
            if ($dividendo<$minimumpay) {
              expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->where('turn',4)->update([
                'lose' => DB::raw('turn-wins'),
                'winner' => 4
              ]);
            }else {
              // declaration of all Winners
              if ($checkifmeron) {
                expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->where('winner',0)->whereIn('event_id',$a)->where('turn',4)->update([
                  'lose' => DB::raw('turn-wins'),
                  'winner' => 3
                ]);
              }else {
                expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->where('winner',0)->whereIn('event_id',$a)->where('turn',4)->update([
                  'lose' => DB::raw('turn-wins'),
                  'winner' => 5
                ]);
              }
              // declaration of all losers

            }

            Potmoney::where('startingfight',$CHECKPOTMONEY->startingfight)->where('event_id',$CHECKPOTMONEY->event_id)->where('pick',4)->update(['claim'=>1]);
            Event::where('startingfight',$CHECKPOTMONEY->startingfight)->where('id',$CHECKPOTMONEY->event_id)->update(['control'=>'Finished']);
            $nextstart = $CHECKPOTMONEY->startingfight + 1;
            $checknextbets = expertbet::where('startingfight',$nextstart)->whereIn('event_id',$a)->where('turn',4)->count();
            if (!$checknextbets) {
              Potmoney::whereIn('event_id',$a)->where('startingfight',$nextstart)->where('pick',4)->update(['claim'=>1]);
              Event::where('startingfight',$nextstart)->WhereIn('id',$a)->where('pick',4)->update(['control'=>'Finished']);
            }
            $nextstart2 = $CHECKPOTMONEY->startingfight + 2;
            $checknextbets2 = expertbet::where('startingfight',$nextstart2)->whereIn('event_id',$a)->where('turn',4)->count();
            if (!$checknextbets2) {
              Potmoney::whereIn('event_id',$a)->where('startingfight',$nextstart2)->where('pick',4)->update(['claim'=>1]);
              Event::where('startingfight',$nextstart2)->WhereIn('id',$a)->where('pick',4)->update(['control'=>'Finished']);
            }
            $nextstart3 = $CHECKPOTMONEY->startingfight + 3;
            $checknextbets3 = expertbet::where('startingfight',$nextstart3)->whereIn('event_id',$a)->where('turn',4)->count();
            if (!$checknextbets3) {
              Potmoney::whereIn('event_id',$a)->where('startingfight',$nextstart3)->where('pick',4)->update(['claim'=>1]);
              Event::where('startingfight',$nextstart3)->WhereIn('id',$a)->where('pick',4)->update(['control'=>'Finished']);
            }

            event(new leaderboards($data));
            event(new resultevent('Last','endevent',$confirm2,'eventupdate','id',$confirm2,'id','id'));
            event(new eventlistener($data));
            return 'success';
          }
          elseif ($checkforcancel==2) {
            $control=control::first();
            $maxwin=expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->orderBy('wins','DESC')->where('turn',4)->first();
            // $maxwin2=expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->orderBy('wins','DESC')->where('turn',2)->select('wins')->groupBy('wins')->first();
              // foreach ($maxwin2 as $key) {
            $checkifmeron = expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->where('wins',2)->whereIn('event_id',$a)->where('turn',4)->update([
                'lose' => DB::raw('turn-wins'),
                'winner' => 1
              ]);

              $winners = expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->where('turn',4)->get();
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
            $minimumpay = $checkevent->payout+1;
            if ($dividendo<$minimumpay) {
              expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->where('turn',4)->update([
                'lose' => DB::raw('turn-wins'),
                'winner' => 4
              ]);
            }else {
              // declaration of all Winners
              if ($checkifmeron) {
                expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->where('winner',0)->whereIn('event_id',$a)->where('turn',4)->update([
                  'lose' => DB::raw('turn-wins'),
                  'winner' => 3
                ]);
              }else {
                expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->where('winner',0)->whereIn('event_id',$a)->where('turn',4)->update([
                  'lose' => DB::raw('turn-wins'),
                  'winner' => 5
                ]);
              }
              // declaration of all losers

            }

            Potmoney::where('startingfight',$CHECKPOTMONEY->startingfight)->where('event_id',$CHECKPOTMONEY->event_id)->where('pick',4)->update(['claim'=>1]);
            Event::where('startingfight',$CHECKPOTMONEY->startingfight)->where('id',$CHECKPOTMONEY->event_id)->update(['control'=>'Finished']);
            $nextstart = $CHECKPOTMONEY->startingfight + 1;
            $checknextbets = expertbet::where('startingfight',$nextstart)->whereIn('event_id',$a)->where('turn',4)->count();
            if (!$checknextbets) {
              Potmoney::whereIn('event_id',$a)->where('startingfight',$nextstart)->where('pick',4)->update(['claim'=>1]);
              Event::where('startingfight',$nextstart)->WhereIn('id',$a)->where('pick',4)->update(['control'=>'Finished']);
            }
            $nextstart2 = $CHECKPOTMONEY->startingfight + 2;
            $checknextbets2 = expertbet::where('startingfight',$nextstart2)->whereIn('event_id',$a)->where('turn',4)->count();
            if (!$checknextbets2) {
              Potmoney::whereIn('event_id',$a)->where('startingfight',$nextstart2)->where('pick',4)->update(['claim'=>1]);
              Event::where('startingfight',$nextstart2)->WhereIn('id',$a)->where('pick',4)->update(['control'=>'Finished']);
            }
            $nextstart3 = $CHECKPOTMONEY->startingfight + 3;
            $checknextbets3 = expertbet::where('startingfight',$nextstart3)->whereIn('event_id',$a)->where('turn',4)->count();
            if (!$checknextbets3) {
              Potmoney::whereIn('event_id',$a)->where('startingfight',$nextstart3)->where('pick',4)->update(['claim'=>1]);
              Event::where('startingfight',$nextstart3)->WhereIn('id',$a)->where('pick',4)->update(['control'=>'Finished']);
            }

            event(new leaderboards($data));
            event(new resultevent('Last','endevent',$confirm2,'eventupdate','id',$confirm2,'id','id'));
            event(new eventlistener($data));
            return 'success';
          }
          elseif ($checkforcancel==3) {
            $control=control::first();
            $maxwin=expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->orderBy('wins','DESC')->where('turn',4)->first();
            // $maxwin2=expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->orderBy('wins','DESC')->where('turn',2)->select('wins')->groupBy('wins')->first();
              // foreach ($maxwin2 as $key) {
            $checkifmeron = expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->where('wins',1)->whereIn('event_id',$a)->where('turn',4)->update([
                'lose' => DB::raw('turn-wins'),
                'winner' => 1
              ]);

              $winners = expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->where('turn',4)->get();
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
            $minimumpay = $checkevent->payout+1;
            if ($dividendo<$minimumpay) {
              expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->where('turn',4)->update([
                'lose' => DB::raw('turn-wins'),
                'winner' => 4
              ]);
            }else {
              // declaration of all Winners
              if ($checkifmeron) {
                expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->where('winner',0)->whereIn('event_id',$a)->where('turn',4)->update([
                  'lose' => DB::raw('turn-wins'),
                  'winner' => 3
                ]);
              }else {
                expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->where('winner',0)->whereIn('event_id',$a)->where('turn',4)->update([
                  'lose' => DB::raw('turn-wins'),
                  'winner' => 5
                ]);
              }
              // declaration of all losers

            }

            Potmoney::where('startingfight',$CHECKPOTMONEY->startingfight)->where('event_id',$CHECKPOTMONEY->event_id)->where('pick',4)->update(['claim'=>1]);
            Event::where('startingfight',$CHECKPOTMONEY->startingfight)->where('id',$CHECKPOTMONEY->event_id)->update(['control'=>'Finished']);
            $nextstart = $CHECKPOTMONEY->startingfight + 1;
            $checknextbets = expertbet::where('startingfight',$nextstart)->whereIn('event_id',$a)->where('turn',4)->count();
            if (!$checknextbets) {
              Potmoney::whereIn('event_id',$a)->where('startingfight',$nextstart)->where('pick',4)->update(['claim'=>1]);
              Event::where('startingfight',$nextstart)->WhereIn('id',$a)->where('pick',4)->update(['control'=>'Finished']);
            }
            $nextstart2 = $CHECKPOTMONEY->startingfight + 2;
            $checknextbets2 = expertbet::where('startingfight',$nextstart2)->whereIn('event_id',$a)->where('turn',4)->count();
            if (!$checknextbets2) {
              Potmoney::whereIn('event_id',$a)->where('startingfight',$nextstart2)->where('pick',4)->update(['claim'=>1]);
              Event::where('startingfight',$nextstart2)->WhereIn('id',$a)->where('pick',4)->update(['control'=>'Finished']);
            }
            $nextstart3 = $CHECKPOTMONEY->startingfight + 3;
            $checknextbets3 = expertbet::where('startingfight',$nextstart3)->whereIn('event_id',$a)->where('turn',4)->count();
            if (!$checknextbets3) {
              Potmoney::whereIn('event_id',$a)->where('startingfight',$nextstart3)->where('pick',4)->update(['claim'=>1]);
              Event::where('startingfight',$nextstart3)->WhereIn('id',$a)->where('pick',4)->update(['control'=>'Finished']);
            }
            event(new leaderboards($data));
            event(new resultevent('Last','endevent',$confirm2,'eventupdate','id',$confirm2,'id','id'));
            event(new eventlistener($data));
            return 'success';
          }
          elseif ($checkforcancel==4) {
            $check=expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->where('winner',0)->whereIn('event_id',$a)->where('turn',4)->update([
              'lose' => DB::raw('turn-wins'),
              'winner' => 4
            ]);
            Event::where('startingfight',$CHECKPOTMONEY->startingfight)->where('id',$CHECKPOTMONEY->event_id)->update(['control'=>'Finished']);
            Potmoney::where('startingfight',$CHECKPOTMONEY->startingfight)->where('event_id',$CHECKPOTMONEY->event_id)->where('pick',4)->update(['claim'=>1]);
            $nextstart = $CHECKPOTMONEY->startingfight + 1;
            $checknextbets = expertbet::where('startingfight',$nextstart)->whereIn('event_id',$a)->where('turn',4)->count();
            if (!$checknextbets) {
              Potmoney::whereIn('event_id',$a)->where('startingfight',$nextstart)->where('pick',4)->update(['claim'=>1]);
              Event::where('startingfight',$nextstart)->WhereIn('id',$a)->where('pick',4)->update(['control'=>'Finished']);
            }
            $nextstart2 = $CHECKPOTMONEY->startingfight + 2;
            $checknextbets2 = expertbet::where('startingfight',$nextstart2)->whereIn('event_id',$a)->where('turn',4)->count();
            if (!$checknextbets2) {
              Potmoney::whereIn('event_id',$a)->where('startingfight',$nextstart2)->where('pick',4)->update(['claim'=>1]);
              Event::where('startingfight',$nextstart2)->WhereIn('id',$a)->where('pick',4)->update(['control'=>'Finished']);
            }
            $nextstart3 = $CHECKPOTMONEY->startingfight + 3;
            $checknextbets3 = expertbet::where('startingfight',$nextstart3)->whereIn('event_id',$a)->where('turn',4)->count();
            if (!$checknextbets3) {
              Potmoney::whereIn('event_id',$a)->where('startingfight',$nextstart3)->where('pick',4)->update(['claim'=>1]);
              Event::where('startingfight',$nextstart3)->WhereIn('id',$a)->where('pick',4)->update(['control'=>'Finished']);
            }

            event(new leaderboards($data));
            event(new resultevent('Last','endevent',$confirm2,'eventupdate','id',$confirm2,'id','id'));
            event(new eventlistener($data));
            return 'success';
          }
          else {
            $control=control::first();
            $maxwin=expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->orderBy('wins','DESC')->where('turn',4)->first();
            // $maxwin2=expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->orderBy('wins','DESC')->where('turn',2)->select('wins')->groupBy('wins')->first();
              // foreach ($maxwin2 as $key) {
              // declaration of all Winners
              $checkifmeron = expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->where('wins',4)->whereIn('event_id',$a)->where('turn',4)->update([
               'lose' => DB::raw('turn-wins'),
               'winner' => 1
             ]);

              $winners = expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->where('turn',4)->get();

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
            $minimumpay = $checkevent->payout+1;
            if ($dividendo<$minimumpay) {
              expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->whereIn('event_id',$a)->where('turn',4)->update([
               'lose' => DB::raw('turn-wins'),
               'winner' => 4
             ]);
            }else {
              if ($checkifmeron) {
                expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->where('winner',0)->whereIn('event_id',$a)->where('turn',4)->update([
                  'lose' => DB::raw('turn-wins'),
                  'winner' => 3
                ]);
              }else {
                expertbet::where('startingfight',$CHECKPOTMONEY->startingfight)->where('winner',0)->whereIn('event_id',$a)->where('turn',4)->update([
                  'lose' => DB::raw('turn-wins'),
                  'winner' => 5
                ]);
              }

              // declaration of all losers

            }

            Potmoney::where('startingfight',$CHECKPOTMONEY->startingfight)->where('event_id',$CHECKPOTMONEY->event_id)->where('pick',4)->update(['claim'=>1]);
            Event::where('startingfight',$CHECKPOTMONEY->startingfight)->where('id',$CHECKPOTMONEY->event_id)->update(['control'=>'Finished']);

            $nextstart = $CHECKPOTMONEY->startingfight + 1;
            $checknextbets = expertbet::where('startingfight',$nextstart)->whereIn('event_id',$a)->where('turn',4)->count();
            if (!$checknextbets) {
              Potmoney::whereIn('event_id',$a)->where('startingfight',$nextstart)->where('pick',4)->update(['claim'=>1]);
              Event::where('startingfight',$nextstart)->WhereIn('id',$a)->where('pick',4)->update(['control'=>'Finished']);
            }
            $nextstart2 = $CHECKPOTMONEY->startingfight + 2;
            $checknextbets2 = expertbet::where('startingfight',$nextstart2)->whereIn('event_id',$a)->where('turn',4)->count();
            if (!$checknextbets2) {
              Potmoney::whereIn('event_id',$a)->where('startingfight',$nextstart2)->where('pick',4)->update(['claim'=>1]);
              Event::where('startingfight',$nextstart2)->WhereIn('id',$a)->where('pick',4)->update(['control'=>'Finished']);
            }
            $nextstart3 = $CHECKPOTMONEY->startingfight + 3;
            $checknextbets3 = expertbet::where('startingfight',$nextstart3)->whereIn('event_id',$a)->where('turn',4)->count();
            if (!$checknextbets3) {
              Potmoney::whereIn('event_id',$a)->where('startingfight',$nextstart3)->where('pick',4)->update(['claim'=>1]);
              Event::where('startingfight',$nextstart3)->WhereIn('id',$a)->where('pick',4)->update(['control'=>'Finished']);
            }

            event(new leaderboards($data));
            event(new resultevent('Last','endevent',$confirm2,'eventupdate','id',$confirm2,'id','id'));
            event(new eventlistener($data));
            return 'success';
          }

        }else {
          Event::where('startingfight',$fightnumber)->whereIn('id',$a)->where('pick',4)->update(['control'=>'Finished']);
          $nextstart = $CHECKPOTMONEY->startingfight + 1;
          $checknextbets = expertbet::where('startingfight',$nextstart)->whereIn('event_id',$a)->where('turn',4)->count();
          if (!$checknextbets) {
            Potmoney::whereIn('event_id',$a)->where('startingfight',$nextstart)->where('pick',4)->update(['claim'=>1]);
            Event::where('startingfight',$nextstart)->WhereIn('id',$a)->where('pick',4)->update(['control'=>'Finished']);
          }
          $nextstart2 = $CHECKPOTMONEY->startingfight + 2;
          $checknextbets2 = expertbet::where('startingfight',$nextstart2)->whereIn('event_id',$a)->where('turn',4)->count();
          if (!$checknextbets2) {
            Potmoney::whereIn('event_id',$a)->where('startingfight',$nextstart2)->where('pick',4)->update(['claim'=>1]);
            Event::where('startingfight',$nextstart2)->WhereIn('id',$a)->where('pick',4)->update(['control'=>'Finished']);
          }
          $nextstart3 = $CHECKPOTMONEY->startingfight + 3;
          $checknextbets3 = expertbet::where('startingfight',$nextstart3)->whereIn('event_id',$a)->where('turn',4)->count();
          if (!$checknextbets3) {
            Potmoney::whereIn('event_id',$a)->where('startingfight',$nextstart3)->where('pick',4)->update(['claim'=>1]);
            Event::where('startingfight',$nextstart3)->WhereIn('id',$a)->where('pick',4)->update(['control'=>'Finished']);
          }
          event(new leaderboards($data));
          event(new resultevent('Last','fightnumber '.$fightnumber,$confirm2,'eventupdate','id',$confirm2,'id','id'));
          event(new resultevent('Last','endevent',$confirm2,'alexpogilang','id',$confirm2,'id','id'));
          event(new eventlistener($data));
          return 'success';
        }
      }

      });
    }
  }
