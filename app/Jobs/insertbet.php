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
use Illuminate\Support\Facades\DB;
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
          if ($check->pick==2) {
          $getallbets = expertbet::where('event_id',$idd )->where('turn',2)->get();
          $mm = $getallbets->where('bet','RR')->sum('amount');
          $mmsum = $getallbets->where('bet','RR')->sum('amount');
          $mw = $getallbets->where('bet','Rw')->sum('amount');
          $mwsum = $getallbets->where('bet','Rw')->sum('amount');
          $md = $getallbets->where('bet','MD')->sum('amount');
          $mdsum = $getallbets->where('bet','MD')->sum('amount');
          $wm = $getallbets->where('bet','wR')->sum('amount');
          $wmsum = $getallbets->where('bet','wR')->sum('amount');
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
        }elseif ($check->pick==4) {
          $getallbets = expertbet::where('event_id',$idd)->where('turn',4)->get();
          $MMMM = $getallbets->where('bet','RRRR')->sum('amount');
          $MMMw = $getallbets->where('bet','RRRw')->sum('amount');
          $MMMD = $getallbets->where('bet','MMMD')->sum('amount');
          $MMwM = $getallbets->where('bet','RRwR')->sum('amount');
          $MMww = $getallbets->where('bet','RRww')->sum('amount');
          $MMwD = $getallbets->where('bet','MMwD')->sum('amount');
          $MMDM = $getallbets->where('bet','MMDM')->sum('amount');
          $MMDw = $getallbets->where('bet','MMDw')->sum('amount');
          $MMDD = $getallbets->where('bet','MMDD')->sum('amount');
          $MwMM = $getallbets->where('bet','RwRR')->sum('amount');
          $MwMw = $getallbets->where('bet','RwRw')->sum('amount');
          $MwMD = $getallbets->where('bet','MwMD')->sum('amount');
          $MwwM = $getallbets->where('bet','RwwR')->sum('amount');
          $Mwww = $getallbets->where('bet','Rwww')->sum('amount');
          $MwwD = $getallbets->where('bet','MwwD')->sum('amount');
          $MwDM = $getallbets->where('bet','MwDM')->sum('amount');
          $MwDw = $getallbets->where('bet','MwDw')->sum('amount');
          $MwDD = $getallbets->where('bet','MwDD')->sum('amount');
          $MDMM = $getallbets->where('bet','MDMM')->sum('amount');
          $MDMw = $getallbets->where('bet','MDMw')->sum('amount');
          $MDMD = $getallbets->where('bet','MDMD')->sum('amount');
          $MDwM = $getallbets->where('bet','MDwM')->sum('amount');
          $MDww = $getallbets->where('bet','MDww')->sum('amount');
          $MDwD = $getallbets->where('bet','MDwD')->sum('amount');
          $MDDM = $getallbets->where('bet','MDDM')->sum('amount');
          $MDDw = $getallbets->where('bet','MDDw')->sum('amount');
          $MDDD = $getallbets->where('bet','MDDD')->sum('amount');
          $wMMM = $getallbets->where('bet','wRRR')->sum('amount');
          $wMMw = $getallbets->where('bet','wRRw')->sum('amount');
          $wMMD = $getallbets->where('bet','wMMD')->sum('amount');
          $wMwM = $getallbets->where('bet','wRwR')->sum('amount');
          $wMww = $getallbets->where('bet','wRww')->sum('amount');
          $wMwD = $getallbets->where('bet','wMwD')->sum('amount');
          $wMDM = $getallbets->where('bet','wMDM')->sum('amount');
          $wMDw = $getallbets->where('bet','wMDw')->sum('amount');
          $wMDD = $getallbets->where('bet','wMDD')->sum('amount');
          $wwMM = $getallbets->where('bet','wwRR')->sum('amount');
          $wwMw = $getallbets->where('bet','wwRw')->sum('amount');
          $wwMD = $getallbets->where('bet','wwMD')->sum('amount');
          $wwwM = $getallbets->where('bet','wwwR')->sum('amount');
          $wwww = $getallbets->where('bet','wwww')->sum('amount');
          $wwwD = $getallbets->where('bet','wwwD')->sum('amount');
          $wwDM = $getallbets->where('bet','wwDM')->sum('amount');
          $wwDw = $getallbets->where('bet','wwDw')->sum('amount');
          $wwDD = $getallbets->where('bet','wwDD')->sum('amount');
          $wDMM = $getallbets->where('bet','wDMM')->sum('amount');
          $wDMw = $getallbets->where('bet','wDMw')->sum('amount');
          $wDMD = $getallbets->where('bet','wDMD')->sum('amount');
          $wDwM = $getallbets->where('bet','wDwM')->sum('amount');
          $wDww = $getallbets->where('bet','wDww')->sum('amount');
          $wDwD = $getallbets->where('bet','wDwD')->sum('amount');
          $wDDM = $getallbets->where('bet','wDDM')->sum('amount');
          $wDDw = $getallbets->where('bet','wDDw')->sum('amount');
          $wDDD = $getallbets->where('bet','wDDD')->sum('amount');
          $DMMM = $getallbets->where('bet','DMMM')->sum('amount');
          $DMMw = $getallbets->where('bet','DMMw')->sum('amount');
          $DMMD = $getallbets->where('bet','DMMD')->sum('amount');
          $DMwM = $getallbets->where('bet','DMwM')->sum('amount');
          $DMww = $getallbets->where('bet','DMww')->sum('amount');
          $DMwD = $getallbets->where('bet','DMwD')->sum('amount');
          $DMDM = $getallbets->where('bet','DMDM')->sum('amount');
          $DMDw = $getallbets->where('bet','DMDw')->sum('amount');
          $DMDD = $getallbets->where('bet','DMDD')->sum('amount');
          $DwMM = $getallbets->where('bet','DwMM')->sum('amount');
          $DwMw = $getallbets->where('bet','DwMw')->sum('amount');
          $DwMD = $getallbets->where('bet','DwMD')->sum('amount');
          $DwwM = $getallbets->where('bet','DwwM')->sum('amount');
          $Dwww = $getallbets->where('bet','Dwww')->sum('amount');
          $DwwD = $getallbets->where('bet','DwwD')->sum('amount');
          $DwDM = $getallbets->where('bet','DwDM')->sum('amount');
          $DwDw = $getallbets->where('bet','DwDw')->sum('amount');
          $DwDD = $getallbets->where('bet','DwDD')->sum('amount');
          $DDMM = $getallbets->where('bet','DDMM')->sum('amount');
          $DDMw = $getallbets->where('bet','DDMw')->sum('amount');
          $DDMD = $getallbets->where('bet','DDMD')->sum('amount');
          $DDwM = $getallbets->where('bet','DDwM')->sum('amount');
          $DDww = $getallbets->where('bet','DDww')->sum('amount');
          $DDwD = $getallbets->where('bet','DDwD')->sum('amount');
          $DDDM = $getallbets->where('bet','DDDM')->sum('amount');
          $DDDw = $getallbets->where('bet','DDDw')->sum('amount');
          $DDDD = $getallbets->where('bet','DDDD')->sum('amount');

          $rakecalc = $getcontrol->rakepick2/100;
          $totalamount1 = $getallbets->sum('amount');
          $totalamountcalc = $totalamount1*$rakecalc;
          $totalamountfinal = $totalamount1-$totalamountcalc;

          $MMMMsum = $MMMM;
          $MMMM1 = $MMMM * $rakecalc;
          $MMMM = $MMMM - $MMMM1;
          if ($MMMM) {
            $MMMM = $totalamountfinal / $MMMMsum;
            $MMMM = floor($MMMM * 100);
          }else {
            $MMMM = $totalamountfinal;
          }
          $MMMwsum = $MMMw;
          $MMMw1 = $MMMw * $rakecalc;
          $MMMw = $MMMw - $MMMw1;
          if ($MMMw) {
            $MMMw = $totalamountfinal / $MMMwsum;
            $MMMw = floor($MMMw * 100);
          }else {
            $MMMw = $totalamountfinal;
          }
          $MMMDsum = $MMMD;
          $MMMD1 = $MMMD * $rakecalc;
          $MMMD = $MMMD - $MMMD1;
          if ($MMMD) {
            $MMMD = $totalamountfinal / $MMMDsum;
            $MMMD = floor($MMMD * 100);
          }else {
            $MMMD = $totalamountfinal;
          }
          $MMwMsum = $MMwM;
          $MMwM1 = $MMwM * $rakecalc;
          $MMwM = $MMwM - $MMwM1;
          if ($MMwM) {
            $MMwM = $totalamountfinal / $MMwMsum;
            $MMwM = floor($MMwM * 100);
          }else {
            $MMwM = $totalamountfinal;
          }
          $MMwwsum = $MMww;
          $MMww1 = $MMww * $rakecalc;
          $MMww = $MMww - $MMww1;
          if ($MMww) {
            $MMww = $totalamountfinal / $MMwwsum;
            $MMww = floor($MMww * 100);
          }else {
            $MMww = $totalamountfinal;
          }
          $MMwDsum = $MMwD;
          $MMwD1 = $MMwD * $rakecalc;
          $MMwD = $MMwD - $MMwD1;
          if ($MMwD) {
            $MMwD = $totalamountfinal / $MMwDsum;
            $MMwD = floor($MMwD * 100);
          }else {
            $MMwD = $totalamountfinal;
          }
          $MMDMsum = $MMDM;
          $MMDM1 = $MMDM * $rakecalc;
          $MMDM = $MMDM - $MMDM1;
          if ($MMDM) {
            $MMDM = $totalamountfinal / $MMDMsum;
            $MMDM = floor($MMDM * 100);
          }else {
            $MMDM = $totalamountfinal;
          }
          $MMDwsum = $MMDw;
          $MMDw1 = $MMDw * $rakecalc;
          $MMDw = $MMDw - $MMDw1;
          if ($MMDw) {
            $MMDw = $totalamountfinal / $MMDwsum;
            $MMDw = floor($MMDw * 100);
          }else {
            $MMDw = $totalamountfinal;
          }
          $MMDDsum = $MMDD;
          $MMDD1 = $MMDD * $rakecalc;
          $MMDD = $MMDD - $MMDD1;
          if ($MMDD) {
            $MMDD = $totalamountfinal / $MMDDsum;
            $MMDD = floor($MMDD * 100);
          }else {
            $MMDD = $totalamountfinal;
          }
          $MwMMsum = $MwMM;
          $MwMM1 = $MwMM * $rakecalc;
          $MwMM = $MwMM - $MwMM1;
          if ($MwMM) {
            $MwMM = $totalamountfinal / $MwMMsum;
            $MwMM = floor($MwMM * 100);
          }else {
            $MwMM = $totalamountfinal;
          }
          $MwMwsum = $MwMw;
          $MwMw1 = $MwMw * $rakecalc;
          $MwMw = $MwMw - $MwMw1;
          if ($MwMw) {
            $MwMw = $totalamountfinal / $MwMwsum;
            $MwMw = floor($MwMw * 100);
          }else {
            $MwMw = $totalamountfinal;
          }
          $MwMDsum = $MwMD;
          $MwMD1 = $MwMD * $rakecalc;
          $MwMD = $MwMD - $MwMD1;
          if ($MwMD) {
            $MwMD = $totalamountfinal / $MwMDsum;
            $MwMD = floor($MwMD * 100);
          }else {
            $MwMD = $totalamountfinal;
          }
          $MwwMsum = $MwwM;
          $MwwM1 = $MwwM * $rakecalc;
          $MwwM = $MwwM - $MwwM1;
          if ($MwwM) {
            $MwwM = $totalamountfinal / $MwwMsum;
            $MwwM = floor($MwwM * 100);
          }else {
            $MwwM = $totalamountfinal;
          }
          $Mwwwsum = $Mwww;
          $Mwww1 = $Mwww * $rakecalc;
          $Mwww = $Mwww - $Mwww1;
          if ($Mwww) {
            $Mwww = $totalamountfinal / $Mwwwsum;
            $Mwww = floor($Mwww * 100);
          }else {
            $Mwww = $totalamountfinal;
          }
          $MwwDsum = $MwwD;
          $MwwD1 = $MwwD * $rakecalc;
          $MwwD = $MwwD - $MwwD1;
          if ($MwwD) {
            $MwwD = $totalamountfinal / $MwwDsum;
            $MwwD = floor($MwwD * 100);
          }else {
            $MwwD = $totalamountfinal;
          }
          $MwDMsum = $MwDM;
          $MwDM1 = $MwDM * $rakecalc;
          $MwDM = $MwDM - $MwDM1;
          if ($MwDM) {
            $MwDM = $totalamountfinal / $MwDMsum;
            $MwDM = floor($MwDM * 100);
          }else {
            $MwDM = $totalamountfinal;
          }
          $MwDwsum = $MwDw;
          $MwDw1 = $MwDw * $rakecalc;
          $MwDw = $MwDw - $MwDw1;
          if ($MwDw) {
            $MwDw = $totalamountfinal / $MwDwsum;
            $MwDw = floor($MwDw * 100);
          }else {
            $MwDw = $totalamountfinal;
          }
          $MwDDsum = $MwDD;
          $MwDD1 = $MwDD * $rakecalc;
          $MwDD = $MwDD - $MwDD1;
          if ($MwDD) {
            $MwDD = $totalamountfinal / $MwDDsum;
            $MwDD = floor($MwDD * 100);
          }else {
            $MwDD = $totalamountfinal;
          }
          $MDMMsum = $MDMM;
          $MDMM1 = $MDMM * $rakecalc;
          $MDMM = $MDMM - $MDMM1;
          if ($MDMM) {
            $MDMM = $totalamountfinal / $MDMMsum;
            $MDMM = floor($MDMM * 100);
          }else {
            $MDMM = $totalamountfinal;
          }
          $MDMwsum = $MDMw;
          $MDMw1 = $MDMw * $rakecalc;
          $MDMw = $MDMw - $MDMw1;
          if ($MDMw) {
            $MDMw = $totalamountfinal / $MDMwsum;
            $MDMw = floor($MDMw * 100);
          }else {
            $MDMw = $totalamountfinal;
          }
          $MDMDsum = $MDMD;
          $MDMD1 = $MDMD * $rakecalc;
          $MDMD = $MDMD - $MDMD1;
          if ($MDMD) {
            $MDMD = $totalamountfinal / $MDMDsum;
            $MDMD = floor($MDMD * 100);
          }else {
            $MDMD = $totalamountfinal;
          }
          $MDwMsum = $MDwM;
          $MDwM1 = $MDwM * $rakecalc;
          $MDwM = $MDwM - $MDwM1;
          if ($MDwM) {
            $MDwM = $totalamountfinal / $MDwMsum;
            $MDwM = floor($MDwM * 100);
          }else {
            $MDwM = $totalamountfinal;
          }
          $MDwwsum = $MDww;
          $MDww1 = $MDww * $rakecalc;
          $MDww = $MDww - $MDww1;
          if ($MDww) {
            $MDww = $totalamountfinal / $MDwwsum;
            $MDww = floor($MDww * 100);
          }else {
            $MDww = $totalamountfinal;
          }
          $MDwDsum = $MDwD;
          $MDwD1 = $MDwD * $rakecalc;
          $MDwD = $MDwD - $MDwD1;
          if ($MDwD) {
            $MDwD = $totalamountfinal / $MDwDsum;
            $MDwD = floor($MDwD * 100);
          }else {
            $MDwD = $totalamountfinal;
          }
          $MDDMsum = $MDDM;
          $MDDM1 = $MDDM * $rakecalc;
          $MDDM = $MDDM - $MDDM1;
          if ($MDDM) {
            $MDDM = $totalamountfinal / $MDDMsum;
            $MDDM = floor($MDDM * 100);
          }else {
            $MDDM = $totalamountfinal;
          }
          $MDDwsum = $MDDw;
          $MDDw1 = $MDDw * $rakecalc;
          $MDDw = $MDDw - $MDDw1;
          if ($MDDw) {
            $MDDw = $totalamountfinal / $MDDwsum;
            $MDDw = floor($MDDw * 100);
          }else {
            $MDDw = $totalamountfinal;
          }
          $MDDDsum = $MDDD;
          $MDDD1 = $MDDD * $rakecalc;
          $MDDD = $MDDD - $MDDD1;
          if ($MDDD) {
            $MDDD = $totalamountfinal / $MDDDsum;
            $MDDD = floor($MDDD * 100);
          }else {
            $MDDD = $totalamountfinal;
          }

          $wMMMsum = $wMMM;
          $wMMM1 = $wMMM * $rakecalc;
          $wMMM = $wMMM - $wMMM1;
          if ($wMMM) {
            $wMMM = $totalamountfinal / $wMMMsum;
            $wMMM = floor($wMMM * 100);
          }else {
            $wMMM = $totalamountfinal;
          }
          $wMMwsum = $wMMw;
          $wMMw1 = $wMMw * $rakecalc;
          $wMMw = $wMMw - $wMMw1;
          if ($wMMw) {
            $wMMw = $totalamountfinal / $wMMwsum;
            $wMMw = floor($wMMw * 100);
          }else {
            $wMMw = $totalamountfinal;
          }
          $wMMDsum = $wMMD;
          $wMMD1 = $wMMD * $rakecalc;
          $wMMD = $wMMD - $wMMD1;
          if ($wMMD) {
            $wMMD = $totalamountfinal / $wMMDsum;
            $wMMD = floor($wMMD * 100);
          }else {
            $wMMD = $totalamountfinal;
          }
          $wMwMsum = $wMwM;
          $wMwM1 = $wMwM * $rakecalc;
          $wMwM = $wMwM - $wMwM1;
          if ($wMwM) {
            $wMwM = $totalamountfinal / $wMwMsum;
            $wMwM = floor($wMwM * 100);
          }else {
            $wMwM = $totalamountfinal;
          }
          $wMwwsum = $wMww;
          $wMww1 = $wMww * $rakecalc;
          $wMww = $wMww - $wMww1;
          if ($wMww) {
            $wMww = $totalamountfinal / $wMwwsum;
            $wMww = floor($wMww * 100);
          }else {
            $wMww = $totalamountfinal;
          }
          $wMwDsum = $wMwD;
          $wMwD1 = $wMwD * $rakecalc;
          $wMwD = $wMwD - $wMwD1;
          if ($wMwD) {
            $wMwD = $totalamountfinal / $wMwDsum;
            $wMwD = floor($wMwD * 100);
          }else {
            $wMwD = $totalamountfinal;
          }
          $wMDMsum = $wMDM;
          $wMDM1 = $wMDM * $rakecalc;
          $wMDM = $wMDM - $wMDM1;
          if ($wMDM) {
            $wMDM = $totalamountfinal / $wMDMsum;
            $wMDM = floor($wMDM * 100);
          }else {
            $wMDM = $totalamountfinal;
          }
          $wMDwsum = $wMDw;
          $wMDw1 = $wMDw * $rakecalc;
          $wMDw = $wMDw - $wMDw1;
          if ($wMDw) {
            $wMDw = $totalamountfinal / $wMDwsum;
            $wMDw = floor($wMDw * 100);
          }else {
            $wMDw = $totalamountfinal;
          }
          $wMDDsum = $wMDD;
          $wMDD1 = $wMDD * $rakecalc;
          $wMDD = $wMDD - $wMDD1;
          if ($wMDD) {
            $wMDD = $totalamountfinal / $wMDDsum;
            $wMDD = floor($wMDD * 100);
          }else {
            $wMDD = $totalamountfinal;
          }
          $wwMMsum = $wwMM;
          $wwMM1 = $wwMM * $rakecalc;
          $wwMM = $wwMM - $wwMM1;
          if ($wwMM) {
            $wwMM = $totalamountfinal / $wwMMsum;
            $wwMM = floor($wwMM * 100);
          }else {
            $wwMM = $totalamountfinal;
          }
          $wwMwsum = $wwMw;
          $wwMw1 = $wwMw * $rakecalc;
          $wwMw = $wwMw - $wwMw1;
          if ($wwMw) {
            $wwMw = $totalamountfinal / $wwMwsum;
            $wwMw = floor($wwMw * 100);
          }else {
            $wwMw = $totalamountfinal;
          }
          $wwMDsum = $wwMD;
          $wwMD1 = $wwMD * $rakecalc;
          $wwMD = $wwMD - $wwMD1;
          if ($wwMD) {
            $wwMD = $totalamountfinal / $wwMDsum;
            $wwMD = floor($wwMD * 100);
          }else {
            $wwMD = $totalamountfinal;
          }
          $wwwMsum = $wwwM;
          $wwwM1 = $wwwM * $rakecalc;
          $wwwM = $wwwM - $wwwM1;
          if ($wwwM) {
            $wwwM = $totalamountfinal / $wwwMsum;
            $wwwM = floor($wwwM * 100);
          }else {
            $wwwM = $totalamountfinal;
          }
          $wwwwsum = $wwww;
          $wwww1 = $wwww * $rakecalc;
          $wwww = $wwww - $wwww1;
          if ($wwww) {
            $wwww = $totalamountfinal / $wwwwsum;
            $wwww = floor($wwww * 100);
          }else {
            $wwww = $totalamountfinal;
          }
          $wwwDsum = $wwwD;
          $wwwD1 = $wwwD * $rakecalc;
          $wwwD = $wwwD - $wwwD1;
          if ($wwwD) {
            $wwwD = $totalamountfinal / $wwwDsum;
            $wwwD = floor($wwwD * 100);
          }else {
            $wwwD = $totalamountfinal;
          }
          $wwDMsum = $wwDM;
          $wwDM1 = $wwDM * $rakecalc;
          $wwDM = $wwDM - $wwDM1;
          if ($wwDM) {
            $wwDM = $totalamountfinal / $wwDMsum;
            $wwDM = floor($wwDM * 100);
          }else {
            $wwDM = $totalamountfinal;
          }
          $wwDwsum = $wwDw;
          $wwDw1 = $wwDw * $rakecalc;
          $wwDw = $wwDw - $wwDw1;
          if ($wwDw) {
            $wwDw = $totalamountfinal / $wwDwsum;
            $wwDw = floor($wwDw * 100);
          }else {
            $wwDw = $totalamountfinal;
          }
          $wwDDsum = $wwDD;
          $wwDD1 = $wwDD * $rakecalc;
          $wwDD = $wwDD - $wwDD1;
          if ($wwDD) {
            $wwDD = $totalamountfinal / $wwDDsum;
            $wwDD = floor($wwDD * 100);
          }else {
            $wwDD = $totalamountfinal;
          }
          $wDMMsum = $wDMM;
          $wDMM1 = $wDMM * $rakecalc;
          $wDMM = $wDMM - $wDMM1;
          if ($wDMM) {
            $wDMM = $totalamountfinal / $wDMMsum;
            $wDMM = floor($wDMM * 100);
          }else {
            $wDMM = $totalamountfinal;
          }
          $wDMwsum = $wDMw;
          $wDMw1 = $wDMw * $rakecalc;
          $wDMw = $wDMw - $wDMw1;
          if ($wDMw) {
            $wDMw = $totalamountfinal / $wDMwsum;
            $wDMw = floor($wDMw * 100);
          }else {
            $wDMw = $totalamountfinal;
          }
          $wDMDsum = $wDMD;
          $wDMD1 = $wDMD * $rakecalc;
          $wDMD = $wDMD - $wDMD1;
          if ($wDMD) {
            $wDMD = $totalamountfinal / $wDMDsum;
            $wDMD = floor($wDMD * 100);
          }else {
            $wDMD = $totalamountfinal;
          }
          $wDwMsum = $wDwM;
          $wDwM1 = $wDwM * $rakecalc;
          $wDwM = $wDwM - $wDwM1;
          if ($wDwM) {
            $wDwM = $totalamountfinal / $wDwMsum;
            $wDwM = floor($wDwM * 100);
          }else {
            $wDwM = $totalamountfinal;
          }
          $wDwwsum = $wDww;
          $wDww1 = $wDww * $rakecalc;
          $wDww = $wDww - $wDww1;
          if ($wDww) {
            $wDww = $totalamountfinal / $wDwwsum;
            $wDww = floor($wDww * 100);
          }else {
            $wDww = $totalamountfinal;
          }
          $wDwDsum = $wDwD;
          $wDwD1 = $wDwD * $rakecalc;
          $wDwD = $wDwD - $wDwD1;
          if ($wDwD) {
            $wDwD = $totalamountfinal / $wDwDsum;
            $wDwD = floor($wDwD * 100);
          }else {
            $wDwD = $totalamountfinal;
          }
          $wDDMsum = $wDDM;
          $wDDM1 = $wDDM * $rakecalc;
          $wDDM = $wDDM - $wDDM1;
          if ($wDDM) {
            $wDDM = $totalamountfinal / $wDDMsum;
            $wDDM = floor($wDDM * 100);
          }else {
            $wDDM = $totalamountfinal;
          }
          $wDDwsum = $wDDw;
          $wDDw1 = $wDDw * $rakecalc;
          $wDDw = $wDDw - $wDDw1;
          if ($wDDw) {
            $wDDw = $totalamountfinal / $wDDwsum;
            $wDDw = floor($wDDw * 100);
          }else {
            $wDDw = $totalamountfinal;
          }
          $wDDDsum = $wDDD;
          $wDDD1 = $wDDD * $rakecalc;
          $wDDD = $wDDD - $wDDD1;
          if ($wDDD) {
            $wDDD = $totalamountfinal / $wDDDsum;
            $wDDD = floor($wDDD * 100);
          }else {
            $wDDD = $totalamountfinal;
          }
          $DMMMsum = $DMMM;
          $DMMM1 = $DMMM * $rakecalc;
          $DMMM = $DMMM - $DMMM1;
          if ($DMMM) {
            $DMMM = $totalamountfinal / $DMMMsum;
            $DMMM = floor($DMMM * 100);
          }else {
            $DMMM = $totalamountfinal;
          }
          $DMMwsum = $DMMw;
          $DMMw1 = $DMMw * $rakecalc;
          $DMMw = $DMMw - $DMMw1;
          if ($DMMw) {
            $DMMw = $totalamountfinal / $DMMwsum;
            $DMMw = floor($DMMw * 100);
          }else {
            $DMMw = $totalamountfinal;
          }
          $DMMDsum = $DMMD;
          $DMMD1 = $DMMD * $rakecalc;
          $DMMD = $DMMD - $DMMD1;
          if ($DMMD) {
            $DMMD = $totalamountfinal / $DMMDsum;
            $DMMD = floor($DMMD * 100);
          }else {
            $DMMD = $totalamountfinal;
          }
          $DMwMsum = $DMwM;
          $DMwM1 = $DMwM * $rakecalc;
          $DMwM = $DMwM - $DMwM1;
          if ($DMwM) {
            $DMwM = $totalamountfinal / $DMwMsum;
            $DMwM = floor($DMwM * 100);
          }else {
            $DMwM = $totalamountfinal;
          }
          $DMwwsum = $DMww;
          $DMww1 = $DMww * $rakecalc;
          $DMww = $DMww - $DMww1;
          if ($DMww) {
            $DMww = $totalamountfinal / $DMwwsum;
            $DMww = floor($DMww * 100);
          }else {
            $DMww = $totalamountfinal;
          }
          $DMwDsum = $DMwD;
          $DMwD1 = $DMwD * $rakecalc;
          $DMwD = $DMwD - $DMwD1;
          if ($DMwD) {
            $DMwD = $totalamountfinal / $DMwDsum;
            $DMwD = floor($DMwD * 100);
          }else {
            $DMwD = $totalamountfinal;
          }
          $DMDMsum = $DMDM;
          $DMDM1 = $DMDM * $rakecalc;
          $DMDM = $DMDM - $DMDM1;
          if ($DMDM) {
            $DMDM = $totalamountfinal / $DMDMsum;
            $DMDM = floor($DMDM * 100);
          }else {
            $DMDM = $totalamountfinal;
          }
          $DMDwsum = $DMDw;
          $DMDw1 = $DMDw * $rakecalc;
          $DMDw = $DMDw - $DMDw1;
          if ($DMDw) {
            $DMDw = $totalamountfinal / $DMDwsum;
            $DMDw = floor($DMDw * 100);
          }else {
            $DMDw = $totalamountfinal;
          }
          $DMDDsum = $DMDD;
          $DMDD1 = $DMDD * $rakecalc;
          $DMDD = $DMDD - $DMDD1;
          if ($DMDD) {
            $DMDD = $totalamountfinal / $DMDDsum;
            $DMDD = floor($DMDD * 100);
          }else {
            $DMDD = $totalamountfinal;
          }
          $DwMMsum = $DwMM;
          $DwMM1 = $DwMM * $rakecalc;
          $DwMM = $DwMM - $DwMM1;
          if ($DwMM) {
            $DwMM = $totalamountfinal / $DwMMsum;
            $DwMM = floor($DwMM * 100);
          }else {
            $DwMM = $totalamountfinal;
          }
          $DwMwsum = $DwMw;
          $DwMw1 = $DwMw * $rakecalc;
          $DwMw = $DwMw - $DwMw1;
          if ($DwMw) {
            $DwMw = $totalamountfinal / $DwMwsum;
            $DwMw = floor($DwMw * 100);
          }else {
            $DwMw = $totalamountfinal;
          }
          $DwMDsum = $DwMD;
          $DwMD1 = $DwMD * $rakecalc;
          $DwMD = $DwMD - $DwMD1;
          if ($DwMD) {
            $DwMD = $totalamountfinal / $DwMDsum;
            $DwMD = floor($DwMD * 100);
          }else {
            $DwMD = $totalamountfinal;
          }
          $DwwMsum = $DwwM;
          $DwwM1 = $DwwM * $rakecalc;
          $DwwM = $DwwM - $DwwM1;
          if ($DwwM) {
            $DwwM = $totalamountfinal / $DwwMsum;
            $DwwM = floor($DwwM * 100);
          }else {
            $DwwM = $totalamountfinal;
          }
          $Dwwwsum = $Dwww;
          $Dwww1 = $Dwww * $rakecalc;
          $Dwww = $Dwww - $Dwww1;
          if ($Dwww) {
            $Dwww = $totalamountfinal / $Dwwwsum;
            $Dwww = floor($Dwww * 100);
          }else {
            $Dwww = $totalamountfinal;
          }
          $DwwDsum = $DwwD;
          $DwwD1 = $DwwD * $rakecalc;
          $DwwD = $DwwD - $DwwD1;
          if ($DwwD) {
            $DwwD = $totalamountfinal / $DwwDsum;
            $DwwD = floor($DwwD * 100);
          }else {
            $DwwD = $totalamountfinal;
          }
          $DwDMsum = $DwDM;
          $DwDM1 = $DwDM * $rakecalc;
          $DwDM = $DwDM - $DwDM1;
          if ($DwDM) {
            $DwDM = $totalamountfinal / $DwDMsum;
            $DwDM = floor($DwDM * 100);
          }else {
            $DwDM = $totalamountfinal;
          }
          $DwDwsum = $DwDw;
          $DwDw1 = $DwDw * $rakecalc;
          $DwDw = $DwDw - $DwDw1;
          if ($DwDw) {
            $DwDw = $totalamountfinal / $DwDwsum;
            $DwDw = floor($DwDw * 100);
          }else {
            $DwDw = $totalamountfinal;
          }
          $DwDDsum = $DwDD;
          $DwDD1 = $DwDD * $rakecalc;
          $DwDD = $DwDD - $DwDD1;
          if ($DwDD) {
            $DwDD = $totalamountfinal / $DwDDsum;
            $DwDD = floor($DwDD * 100);
          }else {
            $DwDD = $totalamountfinal;
          }
          $DDMMsum = $DDMM;
          $DDMM1 = $DDMM * $rakecalc;
          $DDMM = $DDMM - $DDMM1;
          if ($DDMM) {
            $DDMM = $totalamountfinal / $DDMMsum;
            $DDMM = floor($DDMM * 100);
          }else {
            $DDMM = $totalamountfinal;
          }
          $DDMwsum = $DDMw;
          $DDMw1 = $DDMw * $rakecalc;
          $DDMw = $DDMw - $DDMw1;
          if ($DDMw) {
            $DDMw = $totalamountfinal / $DDMwsum;
            $DDMw = floor($DDMw * 100);
          }else {
            $DDMw = $totalamountfinal;
          }
          $DDMDsum = $DDMD;
          $DDMD1 = $DDMD * $rakecalc;
          $DDMD = $DDMD - $DDMD1;
          if ($DDMD) {
            $DDMD = $totalamountfinal / $DDMDsum;
            $DDMD = floor($DDMD * 100);
          }else {
            $DDMD = $totalamountfinal;
          }
          $DDwMsum = $DDwM;
          $DDwM1 = $DDwM * $rakecalc;
          $DDwM = $DDwM - $DDwM1;
          if ($DDwM) {
            $DDwM = $totalamountfinal / $DDwMsum;
            $DDwM = floor($DDwM * 100);
          }else {
            $DDwM = $totalamountfinal;
          }
          $DDwwsum = $DDww;
          $DDww1 = $DDww * $rakecalc;
          $DDww = $DDww - $DDww1;
          if ($DDww) {
            $DDww = $totalamountfinal / $DDwwsum;
            $DDww = floor($DDww * 100);
          }else {
            $DDww = $totalamountfinal;
          }
          $DDwDsum = $DDwD;
          $DDwD1 = $DDwD * $rakecalc;
          $DDwD = $DDwD - $DDwD1;
          if ($DDwD) {
            $DDwD = $totalamountfinal / $DDwDsum;
            $DDwD = floor($DDwD * 100);
          }else {
            $DDwD = $totalamountfinal;
          }
          $DDDMsum = $DDDM;
          $DDDM1 = $DDDM * $rakecalc;
          $DDDM = $DDDM - $DDDM1;
          if ($DDDM) {
            $DDDM = $totalamountfinal / $DDDMsum;
            $DDDM = floor($DDDM * 100);
          }else {
            $DDDM = $totalamountfinal;
          }
          $DDDwsum = $DDDw;
          $DDDw1 = $DDDw * $rakecalc;
          $DDDw = $DDDw - $DDDw1;
          if ($DDDw) {
            $DDDw = $totalamountfinal / $DDDwsum;
            $DDDw = floor($DDDw * 100);
          }else {
            $DDDw = $totalamountfinal;
          }
          $DDDDsum = $DDDD;
          $DDDD1 = $DDDD * $rakecalc;
          $DDDD = $DDDD - $DDDD1;
          if ($DDDD) {
            $DDDD = $totalamountfinal / $DDDDsum;
            $DDDD = floor($DDDD * 100);
          }else {
            $DDDD = $totalamountfinal;
          }
          $alldatax = array();
          array_push($alldatax, [
            'MMMM' => $MMMM ,'MMMw'=>$MMMw,'MMMD'=>$MMMD,'MMwM'=>$MMwM,'MMww'=>$MMww,'MMwD'=>$MMwD,'MMDM'=>$MMDM,'MMDw'=>$MMDw,'MMDD'=>$MMDD,
            'MwMM'=>$MwMM,'MwMw'=>$MwMw,'MwMD'=>$MwMD,'MwwM'=>$MwwM,'Mwww'=>$Mwww,'MwwD'=>$MwwD,'MwDM'=>$MwDM,'MwDw'=>$MwDw,'MwDD'=>$MwDD,
            'MDMM'=>$MDMM,'MDMw'=>$MDMw,'MDMD'=>$MDMD,'MDwM'=>$MDwM,'MDww'=>$MDww,'MDwD'=>$MDwD,'MDDM'=>$MDDM,'MDDw'=>$MDDw,'MDDD'=>$MDDD,

            'wMMM' => $wMMM ,'wMMw'=>$wMMw,'wMMD'=>$wMMD,'wMwM'=>$wMwM,'wMww'=>$wMww,'wMwD'=>$wMwD,'wMDM'=>$wMDM,'wMDw'=>$wMDw,'wMDD'=>$wMDD,
            'wwMM'=>$wwMM,'wwMw'=>$wwMw,'wwMD'=>$wwMD,'wwwM'=>$wwwM,'wwww'=>$wwww,'wwwD'=>$wwwD,'wwDM'=>$wwDM,'wwDw'=>$wwDw,'wwDD'=>$wwDD,
            'wDMM'=>$wDMM,'wDMw'=>$wDMw,'wDMD'=>$wDMD,'wDwM'=>$wDwM,'wDww'=>$wDww,'wDwD'=>$wDwD,'wDDM'=>$wDDM,'wDDw'=>$wDDw,'wDDD'=>$wDDD,

            'DMMM' => $DMMM ,'DMMw'=>$DMMw,'DMMD'=>$DMMD,'DMwM'=>$DMwM,'DMww'=>$DMww,'DMwD'=>$DMwD,'DMDM'=>$DMDM,'DMDw'=>$DMDw,'DMDD'=>$DMDD,
            'DwMM'=>$DwMM,'DwMw'=>$DwMw,'DwMD'=>$DwMD,'DwwM'=>$DwwM,'Dwww'=>$Dwww,'DwwD'=>$DwwD,'DwDM'=>$DwDM,'DwDw'=>$DwDw,'DwDD'=>$DwDD,
            'DDMM'=>$DDMM,'DDMw'=>$DDMw,'DDMD'=>$DDMD,'DDwM'=>$DDwM,'DDww'=>$DDww,'DDwD'=>$DDwD,'DDDM'=>$DDDM,'DDDw'=>$DDDw,'DDDD'=>$DDDD,
          ]);

          broadcast(new betevent($amount,$starting,$idd,$user,$alldatax))->toOthers();
        }
        elseif ($check->pick==5) {
          $getevent = Event::where('status',1)->first();
          $events= Event::where('event_name',$getevent->event_name)->where('pick',5)->get();
          $arrays = array();
          foreach ($events as $key) {
            array_push($arrays, $key->id);
          }
          $getallevents = Event::
          whereIn('id',$arrays)
          ->select('startingfight','event_name','id')
          // with('expertbet', function ($query) {
          //   $query->select('amount','event_id','turn','bet', DB::raw('sum(amount) as total'));
          //  $query->where('turn',5)->groupBy('bet');
          // })
          ->get();
          $data = array();


          foreach ($getallevents as $key) {
            $combination = array();
            $totalbetsamount = array();
            $getbet = expertbet::where('event_id',$key->id)->where('startingfight',$key->startingfight)->select('startingfight','amount','event_id','turn','bet', DB::raw('sum(amount) as total'))->groupBy('bet')->get();
            $getbettotal = expertbet::where('event_id',$key->id)->sum('amount');
              foreach ($getbet as $combinations) {
                $rake = $getcontrol->rakepick2/100;
                $total = $combinations->total*$rake;
                $totalamountcalc = $getbettotal*$rake;
                $totalamountfinal = $getbettotal-$totalamountcalc;

                $MMMM = $combinations->total;
                $MMMMsum = $combinations->total;
                $MMMM1 = $MMMM * $rake;
                $MMMM = $MMMM - $MMMM1;
                if ($MMMM) {
                  $MMMM = $totalamountfinal / $MMMMsum;
                  $MMMM = floor($MMMM * 100);
                }
                else {
                  $MMMM = $totalamountfinal;
                }

                array_push($combination ,['bet'=>$combinations->bet,'total'=>$MMMM]);
              }
              $totalbetsamountfinal = array_sum($totalbetsamount);
              array_push($data ,['id'=>$key->id,'startingfight'=>$key->startingfight,'eventname'=>$key->event_name,'combination'=>$combination,'totalamount'=>$totalbetsamountfinal]);


          }
          // return $data;
          broadcast(new betevent($amount,$starting,$idd,$user,$data))->toOthers();
        }
        elseif ($check->pick==6) {
          $getevent = Event::where('status',1)->first();
          $events= Event::where('event_name',$getevent->event_name)->where('pick',6)->get();
          $arrays = array();
          foreach ($events as $key) {
            array_push($arrays, $key->id);
          }
          $getallevents = Event::
          whereIn('id',$arrays)
          ->select('startingfight','event_name','id')
          // with('expertbet', function ($query) {
          //   $query->select('amount','event_id','turn','bet', DB::raw('sum(amount) as total'));
          //  $query->where('turn',5)->groupBy('bet');
          // })
          ->get();
          $data = array();


          foreach ($getallevents as $key) {
            $combination = array();
            $totalbetsamount = array();
            $getbet = expertbet::where('event_id',$key->id)->where('startingfight',$key->startingfight)->select('startingfight','amount','event_id','turn','bet', DB::raw('sum(amount) as total'))->groupBy('bet')->get();
            $getbettotal = expertbet::where('event_id',$key->id)->sum('amount');
              foreach ($getbet as $combinations) {
                $rake = $getcontrol->rakepick2/100;
                $total = $combinations->total*$rake;
                $totalamountcalc = $getbettotal*$rake;
                $totalamountfinal = $getbettotal-$totalamountcalc;

                $MMMM = $combinations->total;
                $MMMMsum = $combinations->total;
                $MMMM1 = $MMMM * $rake;
                $MMMM = $MMMM - $MMMM1;
                if ($MMMM) {
                  $MMMM = $totalamountfinal / $MMMMsum;
                  $MMMM = floor($MMMM * 100);
                }
                else {
                  $MMMM = $totalamountfinal;
                }

                array_push($combination ,['bet'=>$combinations->bet,'total'=>$MMMM]);
              }
              $totalbetsamountfinal = array_sum($totalbetsamount);
              array_push($data ,['id'=>$key->id,'startingfight'=>$key->startingfight,'eventname'=>$key->event_name,'combination'=>$combination,'totalamount'=>$totalbetsamountfinal]);


          }
          // return $data;
          broadcast(new betevent($amount,$starting,$idd,$user,$data))->toOthers();
        }
        elseif ($check->pick==8) {
          $getevent = Event::where('status',1)->first();
          $events= Event::where('event_name',$getevent->event_name)->where('pick',8)->get();
          $arrays = array();
          foreach ($events as $key) {
            array_push($arrays, $key->id);
          }
          $getallevents = Event::
          whereIn('id',$arrays)
          ->select('startingfight','event_name','id')
          // with('expertbet', function ($query) {
          //   $query->select('amount','event_id','turn','bet', DB::raw('sum(amount) as total'));
          //  $query->where('turn',5)->groupBy('bet');
          // })
          ->get();
          $data = array();


          foreach ($getallevents as $key) {
            $combination = array();
            $totalbetsamount = array();
            $getbet = expertbet::where('event_id',$key->id)->where('startingfight',$key->startingfight)->select('startingfight','amount','event_id','turn','bet', DB::raw('sum(amount) as total'))->groupBy('bet')->get();
            $getbettotal = expertbet::where('event_id',$key->id)->sum('amount');
              foreach ($getbet as $combinations) {
                $rake = $getcontrol->rakepick2/100;
                $total = $combinations->total*$rake;
                $totalamountcalc = $getbettotal*$rake;
                $totalamountfinal = $getbettotal-$totalamountcalc;

                $MMMM = $combinations->total;
                $MMMMsum = $combinations->total;
                $MMMM1 = $MMMM * $rake;
                $MMMM = $MMMM - $MMMM1;
                if ($MMMM) {
                  $MMMM = $totalamountfinal / $MMMMsum;
                  $MMMM = floor($MMMM * 100);
                }
                else {
                  $MMMM = $totalamountfinal;
                }

                array_push($combination ,['bet'=>$combinations->bet,'total'=>$MMMM]);
              }
              $totalbetsamountfinal = array_sum($totalbetsamount);
              array_push($data ,['id'=>$key->id,'startingfight'=>$key->startingfight,'eventname'=>$key->event_name,'combination'=>$combination,'totalamount'=>$totalbetsamountfinal]);


          }
          // return $data;
          broadcast(new betevent($amount,$starting,$idd,$user,$data))->toOthers();
        }
        elseif ($check->pick==14) {
          $getevent = Event::where('status',1)->first();
          $events= Event::where('event_name',$getevent->event_name)->where('pick',14)->get();
          $arrays = array();
          foreach ($events as $key) {
            array_push($arrays, $key->id);
          }
          $getallevents = Event::
          whereIn('id',$arrays)
          ->select('startingfight','event_name','id')
          // with('expertbet', function ($query) {
          //   $query->select('amount','event_id','turn','bet', DB::raw('sum(amount) as total'));
          //  $query->where('turn',5)->groupBy('bet');
          // })
          ->get();
          $data = array();


          foreach ($getallevents as $key) {
            $combination = array();
            $totalbetsamount = array();
            $getbet = expertbet::where('event_id',$key->id)->where('startingfight',$key->startingfight)->select('startingfight','amount','event_id','turn','bet', DB::raw('sum(amount) as total'))->groupBy('bet')->get();
            $getbettotal = expertbet::where('event_id',$key->id)->sum('amount');
              foreach ($getbet as $combinations) {
                $rake = $getcontrol->rakepick2/100;
                $total = $combinations->total*$rake;
                $totalamountcalc = $getbettotal*$rake;
                $totalamountfinal = $getbettotal-$totalamountcalc;

                $MMMM = $combinations->total;
                $MMMMsum = $combinations->total;
                $MMMM1 = $MMMM * $rake;
                $MMMM = $MMMM - $MMMM1;
                if ($MMMM) {
                  $MMMM = $totalamountfinal / $MMMMsum;
                  $MMMM = floor($MMMM * 100);
                }
                else {
                  $MMMM = $totalamountfinal;
                }

                array_push($combination ,['bet'=>$combinations->bet,'total'=>$MMMM]);
              }
              $totalbetsamountfinal = array_sum($totalbetsamount);
              array_push($data ,['id'=>$key->id,'startingfight'=>$key->startingfight,'eventname'=>$key->event_name,'combination'=>$combination,'totalamount'=>$totalbetsamountfinal]);


          }
          // return $data;
          broadcast(new betevent($amount,$starting,$idd,$user,$data))->toOthers();
        }
        else {
          $getallbets = expertbet::where('event_id',$idd )->where('turn',3)->get();
          $mmm = $getallbets->where('bet','RRR')->sum('amount');
          $mmmsum = $getallbets->where('bet','RRR')->sum('amount');
          $mmw = $getallbets->where('bet','RRw')->sum('amount');
          $mmwsum = $getallbets->where('bet','RRw')->sum('amount');
          $mmd = $getallbets->where('bet','MMD')->sum('amount');
          $mmdsum = $getallbets->where('bet','MMD')->sum('amount');
          $mwd = $getallbets->where('bet','MwD')->sum('amount');
          $mwdsum = $getallbets->where('bet','MwD')->sum('amount');
          $mww = $getallbets->where('bet','Rww')->sum('amount');
          $mwwsum = $getallbets->where('bet','Rww')->sum('amount');
          $mwm = $getallbets->where('bet','RwR')->sum('amount');
          $mwmsum = $getallbets->where('bet','RwR')->sum('amount');
          $mdm = $getallbets->where('bet','MDM')->sum('amount');
          $mdmsum = $getallbets->where('bet','MDM')->sum('amount');
          $mdw = $getallbets->where('bet','MDw')->sum('amount');
          $mdwsum = $getallbets->where('bet','MDw')->sum('amount');
          $mdd = $getallbets->where('bet','MDD')->sum('amount');
          $mddsum = $getallbets->where('bet','MDD')->sum('amount');

          $wwm = $getallbets->where('bet','wwR')->sum('amount');
          $wwmsum = $getallbets->where('bet','wwR')->sum('amount');
          $www = $getallbets->where('bet','www')->sum('amount');
          $wwwsum = $getallbets->where('bet','www')->sum('amount');
          $wwd = $getallbets->where('bet','wwD')->sum('amount');
          $wwdsum = $getallbets->where('bet','wwD')->sum('amount');
          $wdd = $getallbets->where('bet','wDD')->sum('amount');
          $wddsum = $getallbets->where('bet','wDD')->sum('amount');
          $wdw = $getallbets->where('bet','wDw')->sum('amount');
          $wdwsum = $getallbets->where('bet','wDw')->sum('amount');
          $wdm = $getallbets->where('bet','wDM')->sum('amount');
          $wdmsum = $getallbets->where('bet','wDM')->sum('amount');
          $wmm = $getallbets->where('bet','wRR')->sum('amount');
          $wmmsum = $getallbets->where('bet','wRR')->sum('amount');
          $wmw = $getallbets->where('bet','wRw')->sum('amount');
          $wmwsum = $getallbets->where('bet','wRw')->sum('amount');
          $wmd = $getallbets->where('bet','wMD')->sum('amount');
          $wmdsum = $getallbets->where('bet','wMD')->sum('amount');

          $ddd = $getallbets->where('bet','DDD')->sum('amount');
          $dddsum = $getallbets->where('bet','DDD')->sum('amount');
          $ddm = $getallbets->where('bet','DDM')->sum('amount');
          $ddmsum = $getallbets->where('bet','DDM')->sum('amount');
          $ddw = $getallbets->where('bet','DDw')->sum('amount');
          $ddwsum = $getallbets->where('bet','DDw')->sum('amount');
          $dww = $getallbets->where('bet','Dww')->sum('amount');
          $dwwsum = $getallbets->where('bet','Dww')->sum('amount');
          $dwd = $getallbets->where('bet','DwD')->sum('amount');
          $dwdsum = $getallbets->where('bet','DwD')->sum('amount');
          $dwm = $getallbets->where('bet','DwM')->sum('amount');
          $dwmsum = $getallbets->where('bet','DwM')->sum('amount');
          $dmm = $getallbets->where('bet','DMM')->sum('amount');
          $dmmsum = $getallbets->where('bet','DMM')->sum('amount');
          $dmw = $getallbets->where('bet','DMw')->sum('amount');
          $dmwsum = $getallbets->where('bet','DMw')->sum('amount');
          $dmd = $getallbets->where('bet','DMD')->sum('amount');
          $dmdsum = $getallbets->where('bet','DMD')->sum('amount');

          $rakecalc = $getcontrol->rakepick2/100;
          $totalamount1 = $getallbets->sum('amount');
          $totalamountcalc = $totalamount1*$rakecalc;
          $totalamountfinal = $totalamount1-$totalamountcalc;

          $mmm1 = $mmm * $rakecalc;
          $mmm = $mmm - $mmm1;
          if ($mmmsum) {
            $mmm = $totalamountfinal / $mmmsum;
            $mmm = floor($mmm * 100);
          }else {
            $mmm = $totalamountfinal;
          }
          $mmw1 = $mmw * $rakecalc;
          $mmw = $mmw - $mmw1;
          if ($mmwsum) {
            $mmw = $totalamountfinal / $mmwsum;
            $mmw = floor($mmw * 100);
          }else {
            $mmw = $totalamountfinal;
          }
          $mmd1 = $mmd * $rakecalc;
          $mmd = $mmd - $mmd1;
          if ($mmdsum) {
            $mmd = $totalamountfinal / $mmdsum;
            $mmd = floor($mmd * 100);
          }else {
            $mmd = $totalamountfinal;
          }
          $mwd1 = $mwd * $rakecalc;
          $mwd = $mwd - $mwd1;
          if ($mwdsum) {
            $mwd = $totalamountfinal / $mwdsum;
            $mwd = floor($mwd * 100);
          }else {
            $mwd = $totalamountfinal;
          }
          $mww1 = $mww * $rakecalc;
          $mww = $mww - $mww1;
          if ($mwwsum) {
            $mww = $totalamountfinal / $mwwsum;
            $mww = floor($mww * 100);
          }else {
            $mww = $totalamountfinal;
          }
          $mwm1 = $mwm * $rakecalc;
          $mwm = $mwm - $mwm1;
          if ($mwmsum) {
            $mwm = $totalamountfinal / $mwmsum;
            $mwm = floor($mwm * 100);
          }else {
            $mwm = $totalamountfinal;
          }
          $mdm1 = $mdm * $rakecalc;
          $mdm = $mdm - $mdm1;
          if ($mdmsum) {
            $mdm = $totalamountfinal / $mdmsum;
            $mdm = floor($mdm * 100);
          }else {
            $mdm = $totalamountfinal;
          }
          $mdw1 = $mdw * $rakecalc;
          $mdw = $mdw - $mdw1;
          if ($mdwsum) {
            $mdw = $totalamountfinal / $mdwsum;
            $mdw = floor($mdw * 100);
          }else {
            $mdw = $totalamountfinal;
          }
          $mdd1 = $mdd * $rakecalc;
          $mdd = $mdd - $mdd1;
          if ($mddsum) {
            $mdd = $totalamountfinal / $mddsum;
            $mdd = floor($mdd * 100);
          }else {
            $mdd = $totalamountfinal;
          }
          $wwm1 = $wwm * $rakecalc;
          $wwm = $wwm - $wwm1;
          if ($wwmsum) {
            $wwm = $totalamountfinal / $wwmsum;
            $wwm = floor($wwm * 100);
          }else {
            $wwm = $totalamountfinal;
          }
          $www1 = $www * $rakecalc;
          $www = $www - $www1;
          if ($wwwsum) {
            $www = $totalamountfinal / $wwwsum;
            $www = floor($www * 100);
          }else {
            $www = $totalamountfinal;
          }
          $wwd1 = $wwd * $rakecalc;
          $wwd = $wwd - $wwd1;
          if ($wwdsum) {
            $wwd = $totalamountfinal / $wwdsum;
            $wwd = floor($wwd * 100);
          }else {
            $wwd = $totalamountfinal;
          }
          $wdd1 = $wdd * $rakecalc;
          $wdd = $wdd - $wdd1;
          if ($wddsum) {
            $wdd = $totalamountfinal / $wddsum;
            $wdd = floor($wdd * 100);
          }else {
            $wdd = $totalamountfinal;
          }
          $wdw1 = $wdw * $rakecalc;
          $wdw = $wdw - $wdw1;
          if ($wdwsum) {
            $wdw = $totalamountfinal / $wdwsum;
            $wdw = floor($wdw * 100);
          }else {
            $wdw = $totalamountfinal;
          }
          $wdm1 = $wdm * $rakecalc;
          $wdm = $wdm - $wdm1;
          if ($wdmsum) {
            $wdm = $totalamountfinal / $wdmsum;
            $wdm = floor($wdm * 100);
          }else {
            $wdm = $totalamountfinal;
          }
          $wmm1 = $wmm * $rakecalc;
          $wmm = $wmm - $wmm1;
          if ($wmmsum) {
            $wmm = $totalamountfinal / $wmmsum;
            $wmm = floor($wmm * 100);
          }else {
            $wmm = $totalamountfinal;
          }
          $wmw1 = $wmw * $rakecalc;
          $wmw = $wmw - $wmw1;
          if ($wmwsum) {
            $wmw = $totalamountfinal / $wmwsum;
            $wmw = floor($wmw * 100);
          }else {
            $wmw = $totalamountfinal;
          }
          $wmd1 = $wmd * $rakecalc;
          $wmd = $wmd - $wmd1;
          if ($wmdsum) {
            $wmd = $totalamountfinal / $wmdsum;
            $wmd = floor($wmd * 100);
          }else {
            $wmd = $totalamountfinal;
          }
          $ddd1 = $ddd * $rakecalc;
          $ddd = $ddd - $ddd1;
          if ($dddsum) {
            $ddd = $totalamountfinal / $dddsum;
            $ddd = floor($ddd * 100);
          }else {
            $ddd = $totalamountfinal;
          }
          $ddm1 = $ddm * $rakecalc;
          $ddm = $ddm - $ddm1;
          if ($ddmsum) {
            $ddm = $totalamountfinal / $ddmsum;
            $ddm = floor($ddm * 100);
          }else {
            $ddm = $totalamountfinal;
          }
          $ddw1 = $ddw * $rakecalc;
          $ddw = $ddw - $ddw1;
          if ($ddwsum) {
            $ddw = $totalamountfinal / $ddwsum;
            $ddw = floor($ddw * 100);
          }else {
            $ddw = $totalamountfinal;
          }
          $dww1 = $dww * $rakecalc;
          $dww = $dww - $dww1;
          if ($dwwsum) {
            $dww = $totalamountfinal / $dwwsum;
            $dww = floor($dww * 100);
          }else {
            $dww = $totalamountfinal;
          }
          $dwd1 = $dwd * $rakecalc;
          $dwd = $dwd - $dwd1;
          if ($dwdsum) {
            $dwd = $totalamountfinal / $dwdsum;
            $dwd = floor($dwd * 100);
          }else {
            $dwd = $totalamountfinal;
          }
          $dwm1 = $dwm * $rakecalc;
          $dwm = $dwm - $dwm1;
          if ($dwmsum) {
            $dwm = $totalamountfinal / $dwmsum;
            $dwm = floor($dwm * 100);
          }else {
            $dwm = $totalamountfinal;
          }
          $dmm1 = $dmm * $rakecalc;
          $dmm = $dmm - $dmm1;
          if ($dmmsum) {
            $dmm = $totalamountfinal / $dmmsum;
            $dmm = floor($dmm * 100);
          }else {
            $dmm = $totalamountfinal;
          }
          $dmw1 = $dmw * $rakecalc;
          $dmw = $dmw - $dmw1;
          if ($dmwsum) {
            $dmw = $totalamountfinal / $dmwsum;
            $dmw = floor($dmw * 100);
          }else {
            $dmw = $totalamountfinal;
          }
          $dmd1 = $dmd * $rakecalc;
          $dmd = $dmd - $dmd1;
          if ($dmdsum) {
            $dmd = $totalamountfinal / $dmdsum;
            $dmd = floor($dmd * 100);
          }else {
            $dmd = $totalamountfinal;
          }
          $alldatax = array();
          array_push($alldatax, [
            'mmm' => $mmm ,'mmw'=>$mmw,'mmd'=>$mmd,'mwd'=>$mwd,'mww'=>$mww,'mwm'=>$mwm,'mdm'=>$mdm,'mdw'=>$mdw,'mdd'=>$mdd,
            'wwm'=>$wwm,'www'=>$www,'wwd'=>$wwd,'wdd'=>$wdd,'wdw'=>$wdw,'wdm'=>$wdm,'wmm'=>$wmm,'wmw'=>$wmw,'wmd'=>$wmd,
            'ddd'=>$ddd,'ddm'=>$ddm,'ddw'=>$ddw,'dww'=>$dww,'dwd'=>$dwd,'dwm'=>$dwm,'dmm'=>$dmm,'dmw'=>$dmw,'dmd'=>$dmd,
            'final'=>$totalamountfinal,
          ]);
          // $this->alldata=$alldatax;
          broadcast(new betevent($amount,$starting,$idd,$user,$alldatax))->toOthers();
        }
        }
    }
}
