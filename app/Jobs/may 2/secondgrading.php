<?php

namespace App\Jobs;

use App\Models\Prebet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class secondgrading implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $results;
    public $event_id;
    public $fightnumber;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($results,$event_id,$fightnumber)
    {
      $this->results = $results;
      $this->event_id = $event_id;
      $this->fightnumber = $fightnumber;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $fightnumberx = $this->fightnumber;
      $resultx = $this->results;
      $event_idx = $this->event_id;

      Prebet::where('fightnumber','=',$fightnumberx)->where('event_id','=',$event_idx)->where('selection','=',$resultx)->update(['win' => 1]);
    }
}
