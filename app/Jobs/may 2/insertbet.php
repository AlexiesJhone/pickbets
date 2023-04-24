<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\betevent;

class insertbet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $finalamount;
    public $startingfight;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($finalamount,$startingfight)
    {
      $this->finalamount = $finalamount;
      $this->startingfight = $startingfight;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $amount = $this->finalamount;
        $starting = $this->startingfight;
        event(new betevent($amount,$starting));
    }
}
