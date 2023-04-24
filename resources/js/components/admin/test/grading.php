<?php

namespace App\Jobs;

use App\Events\resultevent;
use App\Events\leaderboards;
use App\Models\Event;
use App\Models\Prebet;
use App\Models\bet;
use App\Models\Potmoney;
use App\Models\User;
use App\Models\Logs;
use Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Results;

class grading implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $result;
    public $eventid;
    public $fn;
    public $c1;
    public $c2;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($result,$eventid,$fn,$c1,$c2)
    {
        $this->result = $result;
        $this->eventid = $eventid;
        $this->fn = $fn;
        $this->c1 = $c1;
        $this->c2 = $c2;
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

      bet::whereHas('prebets', function($q) use ($fightnumber,$event_id,$results)
      {
        $q->where('fightnumber','=',$fightnumber)->where('event_id','=',$event_id)->where('selection','=',$results);
      })->increment('wins');
      // lagyan lahat ng turn
      // bet::whereHas('prebets', function($q) use ($fightnumber,$event_id)
      // {
      //   $q->where('fightnumber','=',$fightnumber)->where('event_id','=',$event_id);
      // })->increment('turn');

      // // if may nadagdag plus 1 win sa prebet
      Prebet::where('fightnumber','=',$fightnumber)->where('event_id','=',$event_id)->where('selection','=',$results)->update(['win' => 1]);

      $getuser2 = User::where('id',$confirm2)->first();
      $getuser1 = User::where('id',$confirm1)->first();
      event(new leaderboards($data));
      event(new resultevent($results,$fightnumber,$getuser2->name,'Confirmed',1,$getuser2->id,$results,$confirm1));

      // $createlogs = new Logs();
      // $createlogs->type = 'Confirmed_Grade';
      // $createlogs->user_id = $confirm1;
      // $createlogs->message = $getuser2->username.' Confirmed '.$getuser1->username.', '.$results.' for fight number '.$fightnumber;
      // $createlogs->save();


      // DECLARATION OF THE WINNERS

      $getevent=Event::where('status',1)->first();
      $data1=$fightnumber-$getevent->pick;
      $data2=$data1+1;

      $checker = bet::where('startingfight',$data2)->where('event_id',$getevent->id)->orderBy('wins','DESC')->first();
      $getevent=Event::where('status',1)->first();
      if ($checker===null) {
        return 'dito hnd existss';
      }else {
        $maxwin=bet::where('startingfight',$data2)->where('event_id',$getevent->id)->orderBy('wins','DESC')->first();

        bet::where('startingfight',$data2)->where('wins',$maxwin->wins)->where('event_id',$getevent->id)->update([
          'lose' => DB::raw('turn-wins'),
          'winner' => 2
        ]);

        bet::where('startingfight',$data2)->where('wins',$maxwin->wins)->where('event_id',$getevent->id)->update([
          'lose' => DB::raw('turn-wins'),
          'winner' => 1
        ]);

        Potmoney::where('startingfight',$maxwin->startingfight)->where('event_id',$getevent->id)->update(['claim'=>1]);

          // DECLARATION OF THE LOSERS
        bet::where('startingfight',$data2)->where('winner',0)->where('event_id',$getevent->id)->update([
          'lose' => DB::raw('turn-wins'),
          'winner' => 3
        ]);
        broadcast(new leaderboards($getevent))->toOthers();
      }
      broadcast(new resultevent($results,$fightnumber,$getuser2->name,'Confirmed',1,$confirm2,$confirm2))->toOthers();
      return 'success';

    }
  }
