<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\betevent;
use App\Models\Event;
use App\Models\User;
use App\Models\expertbet;
use App\Models\control;
use Auth;

class insertbet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $finalamount;
    public $startingfight;
    public $id;
    public $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($finalamount,$startingfight,$id,$user)
    {
      $this->finalamount = $finalamount;
      $this->startingfight = $startingfight;
      $this->id = $id;
      $this->user = $user;
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
        $idd = $this->id;
        $user = $this->user;
        $check = Event::where('id',$idd)->first();
        if ($check->pick==20) {
          event(new betevent($amount,$starting,$idd,$user,'id'));
        }else {
          $getcontrol = control::first();
          $getallbets = expertbet::where('event_id',$idd )->where('turn',2)->get();
          $mm = $getallbets->where('bet','MM')->sum('amount');
          $mmsum = $getallbets->where('bet','MM')->sum('amount');
          $mw = $getallbets->where('bet','Mw')->sum('amount');
          $mwsum = $getallbets->where('bet','Mw')->sum('amount');
          $md = $getallbets->where('bet','MD')->sum('amount');
          $mdsum = $getallbets->where('bet','MD')->sum('amount');
          $wm = $getallbets->where('bet','wM')->sum('amount');
          $wmsum = $getallbets->where('bet','wM')->sum('amount');
          $ww = $getallbets->where('bet','ww')->sum('amount');
          $wwsum = $getallbets->where('bet','ww')->sum('amount');
          $wd = $getallbets->where('bet','wD')->sum('amount');
          $wdsum = $getallbets->where('bet','wD')->sum('amount');
          $dm = $getallbets->where('bet','DM')->sum('amount');
          $dmsum = $getallbets->where('bet','DM')->sum('amount');
          $dw = $getallbets->where('bet','Dw')->sum('amount');
          $dwsum = $getallbets->where('bet','Dw')->sum('amount');
          $dd = $getallbets->where('bet','DD')->sum('amount');
          $ddsum = $getallbets->where('bet','DD')->sum('amount');

          $rakecalc = $getcontrol->rakepick2/100;
          $totalamount1 = $getallbets->sum('amount');
          $totalamountcalc = $totalamount1*$rakecalc;
          $totalamountfinal = $totalamount1-$totalamountcalc;
          $mm1 = $mm * $rakecalc;
          $mm = $mm - $mm1;
          if ($mmsum) {
            $mm = $totalamountfinal / $mmsum;
            $mm = floor($mm * 100);
          }else {
            $mm = $totalamountfinal;
          }


          $mw1 = $mw * $rakecalc;
          $mw = $mw - $mw1;
          if ($mwsum) {
          $mw = $totalamountfinal / $mwsum;
          $mw = floor($mw * 100);
          }else {
            $mw = $totalamountfinal;
          }

          $md1 = $md * $rakecalc;
          $md = $md - $md1;
          if ($mdsum) {
          $md = $totalamountfinal / $mdsum;
          $md = floor($md * 100);
          }else {
            $md = $totalamountfinal;
          }

          $wm1 = $wm * $rakecalc;
          $wm = $wm - $wm1;
          if ($wmsum) {
            $wm = $totalamountfinal / $wmsum;
            $wm = floor($wm * 100);
          }else {
            $wm = $totalamountfinal;
          }

          $ww1 = $ww * $rakecalc;
          $ww = $ww - $ww1;
          if ($wwsum) {
            $ww = $totalamountfinal / $wwsum;
            $ww = floor($ww * 100);
          }else {
            $ww = $totalamountfinal;
          }

          $wd1 = $wd * $rakecalc;
          $wd = $wd - $wd1;
          if ($wdsum) {
            $wd = $totalamountfinal / $wdsum;
            $wd = floor($wd * 100);
          }else {
            $wd = $totalamountfinal;
          }

          $dm1 = $dm * $rakecalc;
          $dm = $dm - $dm1;
          if ($dmsum) {
          $dm = $totalamountfinal / $dmsum;
          $dm = floor($dm * 100);
          }else {
            $dm = $totalamountfinal;
          }

          $dw1 = $dw * $rakecalc;
          $dw = $dw - $dw1;
          if ($dwsum) {
            $dw = $totalamountfinal / $dwsum;
            $dw = floor($dw * 100);
          }else {
            $dw = $totalamountfinal;
          }

          $dd1 = $dd * $rakecalc;
          $dd = $dd - $dd1;
          if ($ddsum) {
            // code...
          $dd = $totalamountfinal / $ddsum;
          $dd = floor($dd * 100);
          }else {
            $dd = $totalamountfinal;
          }

          $alldatax = array();
          array_push($alldatax, ['mm' => $mm , 'mw'=>$mw,'md'=>$md,'wm'=>$wm,'ww'=>$ww,'wd'=>$wd,'dm'=>$dm,'dw'=>$dw,'dd'=>$dd,'final'=>$totalamountfinal,]);
          // $this->alldata=$alldatax;
          broadcast(new betevent($amount,$starting,$idd,$user,$alldatax))->toOthers();
        }
    }
}
