<?php

namespace App\Jobs;

use App\Events\resultevent;
use App\Events\leaderboards;
use App\Events\eventlistener;
use App\Models\Event;
use App\Models\Prebet;
use App\Models\bet;
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

class grading implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $result;
    public $eventid;
    public $fn;
    public $c1;
    public $c2;
    public $name;
    public $startingfight;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($result,$eventid,$fn,$c1,$c2,$name,$startingfight)
    {
        $this->result = $result;
        $this->eventid = $eventid;
        $this->fn = $fn;
        $this->c1 = $c1;
        $this->c2 = $c2;
        $this->name = $name;
        $this->startingfight = $startingfight;
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



      $confirm1 = $this->c1;
      $confirm2 = $this->c2;
      $data = $this->result;
      $name = $this->name;

      DB::transaction(function () use($fightnumber,$results,$event_id,$confirm1,$confirm2,$data,$name){
        DB::table('Prebet as a')
        ->where('a.fightnumber', '=', $fightnumber)->where('a.event_id', '=', $event_id)->where('a.selection', '=', $results)
        ->join('bet as c', 'a.bet_id', '=', 'c.id')
        ->update(['c.wins' =>DB::raw('wins + 1')]);


        $data2 = Event::findOrFail($event_id);
        $data2->currentfight = $fightnumber;
        $data2->save();

        $data = new Results();
        $data->result=$results;
        $data->fightnumber=$fightnumber;
        $data->event_id=$event_id;
        $data->confirm1 = $confirm1;
        $data->confirm2 =$confirm2;
        $data->save();

        $getuser1 = User::findOrFail($confirm1);
        $createlogs = new Logs();
        $createlogs->type = 'Confirmed_Grade';
        $createlogs->user_id = $confirm2;
        $createlogs->message = $name.' Confirmed '.$getuser1->username.', '.$results.' for fight number '.$fightnumber;
        $createlogs->save();

        event(new resultevent($results,$fightnumber,$name,'Confirmed',1,$confirm2,$results,$confirm1,$confirm1));

        // DECLARATION OF THE WINNERS

        $getevent=Event::where('status',1)->first();
        $data1=$fightnumber-$getevent->pick;
        $data2=$data1+1;

        $checker = bet::where('startingfight',$data2)->where('event_id',$getevent->id)->orderBy('wins','DESC')->first();
        $getevent=Event::where('status',1)->first();
        if ($checker===null) {
          event(new leaderboards($data));
          // event(new resultevent('Last','endevent',auth()->user()->name,'eventupdate','id',auth()->user()->name,'id','id'));
          return 'success';
        }else {

          $maxwin=bet::where('startingfight',$data2)->where('event_id',$getevent->id)->orderBy('wins','DESC')->first();

          if ($maxwin->wins===$getevent->pick) {
            bet::where('startingfight',$data2)->where('wins',$maxwin->wins)->where('event_id',$getevent->id)->update([
              'lose' => 0,
              'winner' => 2
            ]);
          }else {
            bet::where('startingfight',$data2)->where('wins',$maxwin->wins)->where('event_id',$getevent->id)->update([
              'lose' => DB::raw('turn-wins'),
              'winner' => 1
            ]);
          }



          Potmoney::where('startingfight',$maxwin->startingfight)->where('event_id',$getevent->id)->update(['claim'=>1]);
          startingfights::where('startingfight',$maxwin->startingfight)->where('event_id',$getevent->id)->update(['control'=>'Finished']);

            // DECLARATION OF THE LOSERS
          bet::where('startingfight',$data2)->where('winner',0)->where('event_id',$getevent->id)->update([
            'lose' => DB::raw('turn-wins'),
            'winner' => 3
          ]);
          event(new leaderboards($data));
          event(new resultevent('Last','endevent',$confirm2,'eventupdate','id',$confirm2,'id','id'));
          event(new eventlistener($data));
        }
        return 'success';
      });


      // bet::where('startingfight',$this->startingfight)->where('event_id',$event_id)->whereHas('prebets', function($q) use ($fightnumber,$event_id,$results)
      // {
      //   $q->where('fightnumber','=',$fightnumber)->where('event_id','=',$event_id)->where('selection','=',$results);
      // })->update(['wins' =>DB::raw('wins + 1')]);
      // ->increment('wins');




    }
  }
