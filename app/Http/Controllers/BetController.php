<?php

namespace App\Http\Controllers;

use App\Models\Prebet;
use App\Events\userupdate;
use App\Models\bet;
use App\Models\control;
use App\Models\startingfights;
use App\Models\Logs;
use App\Models\User;
use App\Models\derby;
use App\Events\betevent;
use Illuminate\Http\Request;
use Auth;
use App\Models\past_selection;
use Illuminate\Support\Arr;
use App\Models\Event;
use App\Models\Potmoney;
use App\Models\selection;
use App\Models\expertbet;
use App\Models\combination;
use Carbon\Carbon;
use App\Jobs\insertbet;
use Illuminate\Support\Facades\DB;

class BetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function derbynames(Request $req)
    {
        return derby::select('fightnumber','entryname1','entryname2')->paginate(10);
    }
    public function possiblepayout(Request $req)
    {
        $getcontrol = control::first();
        $getallbets = expertbet::where('event_id',$req['id'])->get();

        if ($req['pick']==3) {
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

          if ($mmmsum) {
            $mmm = $totalamountfinal / $mmmsum;
            $mmm = floor($mmm * 100);
          }else {
            $mmm = $totalamountfinal;
          }
          if ($mmwsum) {
            $mmw = $totalamountfinal / $mmwsum;
            $mmw = floor($mmw * 100);
          }else {
            $mmw = $totalamountfinal;
          }
          if ($mmdsum) {
            $mmd = $totalamountfinal / $mmdsum;
            $mmd = floor($mmd * 100);
          }else {
            $mmd = $totalamountfinal;
          }
          if ($mwdsum) {
            $mwd = $totalamountfinal / $mwdsum;
            $mwd = floor($mwd * 100);
          }else {
            $mwd = $totalamountfinal;
          }
          if ($mwwsum) {
            $mww = $totalamountfinal / $mwwsum;
            $mww = floor($mww * 100);
          }else {
            $mww = $totalamountfinal;
          }
          if ($mwmsum) {
            $mwm = $totalamountfinal / $mwmsum;
            $mwm = floor($mwm * 100);
          }else {
            $mwm = $totalamountfinal;
          }
          if ($mdmsum) {
            $mdm = $totalamountfinal / $mdmsum;
            $mdm = floor($mdm * 100);
          }else {
            $mdm = $totalamountfinal;
          }
          if ($mdwsum) {
            $mdw = $totalamountfinal / $mdwsum;
            $mdw = floor($mdw * 100);
          }else {
            $mdw = $totalamountfinal;
          }
          if ($mddsum) {
            $mdd = $totalamountfinal / $mddsum;
            $mdd = floor($mdd * 100);
          }else {
            $mdd = $totalamountfinal;
          }
          if ($wwmsum) {
            $wwm = $totalamountfinal / $wwmsum;
            $wwm = floor($wwm * 100);
          }else {
            $wwm = $totalamountfinal;
          }

          if ($wwwsum) {
            $www = $totalamountfinal / $wwwsum;
            $www = floor($www * 100);
          }else {
            $www = $totalamountfinal;
          }
          if ($wwdsum) {
            $wwd = $totalamountfinal / $wwdsum;
            $wwd = floor($wwd * 100);
          }else {
            $wwd = $totalamountfinal;
          }
          if ($wddsum) {
            $wdd = $totalamountfinal / $wddsum;
            $wdd = floor($wdd * 100);
          }else {
            $wdd = $totalamountfinal;
          }
          if ($wdwsum) {
            $wdw = $totalamountfinal / $wdwsum;
            $wdw = floor($wdw * 100);
          }else {
            $wdw = $totalamountfinal;
          }
          if ($wdmsum) {
            $wdm = $totalamountfinal / $wdmsum;
            $wdm = floor($wdm * 100);
          }else {
            $wdm = $totalamountfinal;
          }
          if ($wmmsum) {
            $wmm = $totalamountfinal / $wmmsum;
            $wmm = floor($wmm * 100);
          }else {
            $wmm = $totalamountfinal;
          }
          if ($wmwsum) {
            $wmw = $totalamountfinal / $wmwsum;
            $wmw = floor($wmw * 100);
          }else {
            $wmw = $totalamountfinal;
          }
          if ($wmdsum) {
            $wmd = $totalamountfinal / $wmdsum;
            $wmd = floor($wmd * 100);
          }else {
            $wmd = $totalamountfinal;
          }

          if ($dddsum) {
            $ddd = $totalamountfinal / $dddsum;
            $ddd = floor($ddd * 100);
          }else {
            $ddd = $totalamountfinal;
          }
          if ($ddmsum) {
            $ddm = $totalamountfinal / $ddmsum;
            $ddm = floor($ddm * 100);
          }else {
            $ddm = $totalamountfinal;
          }
          if ($ddwsum) {
            $ddw = $totalamountfinal / $ddwsum;
            $ddw = floor($ddw * 100);
          }else {
            $ddw = $totalamountfinal;
          }
          if ($dwwsum) {
            $dww = $totalamountfinal / $dwwsum;
            $dww = floor($dww * 100);
          }else {
            $dww = $totalamountfinal;
          }
          if ($dwdsum) {
            $dwd = $totalamountfinal / $dwdsum;
            $dwd = floor($dwd * 100);
          }else {
            $dwd = $totalamountfinal;
          }
          if ($dwmsum) {
            $dwm = $totalamountfinal / $dwmsum;
            $dwm = floor($dwm * 100);
          }else {
            $dwm = $totalamountfinal;
          }
          if ($dmmsum) {
            $dmm = $totalamountfinal / $dmmsum;
            $dmm = floor($dmm * 100);
          }else {
            $dmm = $totalamountfinal;
          }
          if ($dmwsum) {
            $dmw = $totalamountfinal / $dmwsum;
            $dmw = floor($dmw * 100);
          }else {
            $dmw = $totalamountfinal;
          }
          if ($dmdsum) {
            $dmd = $totalamountfinal / $dmdsum;
            $dmd = floor($dmd * 100);
          }else {
            $dmd = $totalamountfinal;
          }

          $alldata = array();
          array_push($alldata, [
            'mmm' => $mmm ,'mmw'=>$mmw,'mmd'=>$mmd,'mwd'=>$mwd,'mww'=>$mww,'mwm'=>$mwm,'mdm'=>$mdm,'mdw'=>$mdw,'mdd'=>$mdd,
            'wwm'=>$wwm,'www'=>$www,'wwd'=>$wwd,'wdd'=>$wdd,'wdw'=>$wdw,'wdm'=>$wdm,'wmm'=>$wmm,'wmw'=>$wmw,'wmd'=>$wmd,
            'ddd'=>$ddd,'ddm'=>$ddm,'ddw'=>$ddw,'dww'=>$dww,'dwd'=>$dwd,'dwm'=>$dwm,'dmm'=>$dmm,'dmw'=>$dmw,'dmd'=>$dmd,
          ]);
          return $alldata;
          }
          elseif ($req['pick']==4) {
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
            $alldata = array();
            array_push($alldata, [
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
            return $alldata;
          }
          elseif ($req['pick']==5) {
            //alexpick5
            $test = expertbet::where('event_id',$req['id'])->select('startingfight','amount','event_id','turn','bet', DB::raw('sum(amount) as total'))->groupBy('bet')->get();
            $getbettotal = $getallbets->where('event_id',$req['id'])->sum('amount');
            $mm = $getallbets->where('bet','MM')->sum('amount');
            $data = array();
            $totalbetsamount = array();
            $combination = array();
            foreach ($test as $combinations) {
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
            array_push($data ,['id'=>$req->id,'startingfight'=>$req->fightnumber,'combination'=>$combination,'totalamountfinal'=>$totalamountfinal]);
            return $data;

          }
          elseif ($req['pick']==6) {
            //alexpick5
            $test = expertbet::where('event_id',$req['id'])->select('startingfight','amount','event_id','turn','bet', DB::raw('sum(amount) as total'))->groupBy('bet')->get();
            $getbettotal = $getallbets->where('event_id',$req['id'])->sum('amount');
            $mm = $getallbets->where('bet','MM')->sum('amount');
            $data = array();
            $totalbetsamount = array();
            $combination = array();
            foreach ($test as $combinations) {
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
            array_push($data ,['id'=>$req->id,'startingfight'=>$req->fightnumber,'combination'=>$combination,'totalamountfinal'=>$totalamountfinal]);
            return $data;

          }
          elseif ($req['pick']==8) {
            //alexpick5
            $test = expertbet::where('event_id',$req['id'])->select('startingfight','amount','event_id','turn','bet', DB::raw('sum(amount) as total'))->groupBy('bet')->get();
            $getbettotal = $getallbets->where('event_id',$req['id'])->sum('amount');
            $mm = $getallbets->where('bet','MM')->sum('amount');
            $data = array();
            $totalbetsamount = array();
            $combination = array();
            foreach ($test as $combinations) {
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
            array_push($data ,['id'=>$req->id,'startingfight'=>$req->fightnumber,'combination'=>$combination,'totalamountfinal'=>$totalamountfinal]);
            return $data;

          }
          elseif ($req['pick']==14) {
            //alexpick5
            $test = expertbet::where('event_id',$req['id'])->select('startingfight','amount','event_id','turn','bet', DB::raw('sum(amount) as total'))->groupBy('bet')->get();
            $getbettotal = $getallbets->where('event_id',$req['id'])->sum('amount');
            $mm = $getallbets->where('bet','MM')->sum('amount');
            $data = array();
            $totalbetsamount = array();
            $combination = array();
            foreach ($test as $combinations) {
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
            array_push($data ,['id'=>$req->id,'startingfight'=>$req->fightnumber,'combination'=>$combination,'totalamountfinal'=>$totalamountfinal]);
            return $data;

          }
        elseif ($req['pick']==2) {
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
          $dd = $totalamountfinal / $ddsum;
          $dd = floor($dd * 100);
        }else {
          $dd = $totalamountfinal;
        }


        $alldata = array();
        array_push($alldata, ['mm' => $mm , 'mw'=>$mw,'md'=>$md,'wm'=>$wm,'ww'=>$ww,'wd'=>$wd,'dm'=>$dm,'dw'=>$dw,'dd'=>$dd,'final'=>$totalamountfinal,"sumngmm"=>$mmsum]);
        return $alldata;
        }
        }


    public function possiblepayoutall()
    {
        $getcontrol = control::first();
        $getevent = Event::where('status',1)->first();
        $events= Event::where('event_name',$getevent->event_name)->where('pick',2)->get();
        $arrays = array();
        foreach ($events as $key) {
          array_push($arrays, $key->id);
        }
        $getallevents = Event::
        whereIn('id',$arrays)
        ->select('startingfight','event_name','id')->
        with('expertbet', function ($query) {
          $query->select('amount','event_id','turn','bet');
         $query->where('turn',2);
        })
        // ->withSum(['expertbet as MM' => function ($query) use ($arrays) {
        //     $query->where('bet','MM')->where('turn',2);
        //   }],'amount')
        // ->withSum(['expertbet as Mw' => function ($query) use ($arrays) {
        //     $query->where('bet','Mw')->where('turn',2);
        //   }],'amount')
        // ->withSum(['expertbet as MD' => function ($query) use ($arrays) {
        //     $query->where('bet','MD')->where('turn',2);
        //   }],'amount')
        // ->withSum(['expertbet as wM' => function ($query) use ($arrays) {
        //     $query->where('bet','wM')->where('turn',2);
        //   }],'amount')
        // ->withSum(['expertbet as ww' => function ($query) use ($arrays) {
        //     $query->where('bet','ww')->where('turn',2);
        //   }],'amount')
        // ->withSum(['expertbet as wD' => function ($query) use ($arrays) {
        //     $query->where('bet','wD')->where('turn',2);
        //   }],'amount')
        // ->withSum(['expertbet as DM' => function ($query) use ($arrays) {
        //     $query->where('bet','DM')->where('turn',2);
        //   }],'amount')
        // ->withSum(['expertbet as Dw' => function ($query) use ($arrays) {
        //     $query->where('bet','Dw')->where('turn',2);
        //   }],'amount')
        // ->withSum(['expertbet as DD' => function ($query) use ($arrays) {
        //     $query->where('bet','DD')->where('turn',2);
        //   }],'amount')
        ->get();
        // return $getallevents;
        $alldata = array();
        foreach ($getallevents as $key) {
          // $getallbets = expertbet::where('event_id',$req['id'])->get();
          $mm = $key->expertbet->where('bet','RR')->sum('amount');
          // return $mm;
          $mmsum = $key->expertbet->where('bet','RR')->sum('amount');
          $mw = $key->expertbet->where('bet','Rw')->sum('amount');
          $mwsum = $key->expertbet->where('bet','Rw')->sum('amount');
          $md = $key->expertbet->where('bet','MD')->sum('amount');
          $mdsum = $key->expertbet->where('bet','MD')->sum('amount');
          $wm = $key->expertbet->where('bet','wR')->sum('amount');
          $wmsum = $key->expertbet->where('bet','wR')->sum('amount');
          $ww = $key->expertbet->where('bet','ww')->sum('amount');
          $wwsum = $key->expertbet->where('bet','ww')->sum('amount');
          $wd = $key->expertbet->where('bet','wD')->sum('amount');
          $wdsum = $key->expertbet->where('bet','wD')->sum('amount');
          $dm = $key->expertbet->where('bet','DM')->sum('amount');
          $dmsum = $key->expertbet->where('bet','DM')->sum('amount');
          $dw = $key->expertbet->where('bet','Dw')->sum('amount');
          $dwsum = $key->expertbet->where('bet','Dw')->sum('amount');
          $dd = $key->expertbet->where('bet','DD')->sum('amount');
          $ddsum = $key->expertbet->where('bet','DD')->sum('amount');


          $rakecalc = $getcontrol->rakepick2/100;
          $totalamount1 = $mm + $mw+ $md+ $wm+ $ww+ $wd+ $dm+ $dw+ $dd;
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
            $dd = $totalamountfinal / $ddsum;
            $dd = floor($dd * 100);
          }else {
            $dd = $totalamountfinal;
          }
          array_push($alldata, ['id'=>$key->id,'startingfight'=>$key->startingfight,'eventname'=>$key->event_name,'mm' => $mm
          , 'mw'=>$mw,'md'=>$md,'wm'=>$wm,'ww'=>$ww,'wd'=>$wd,'dm'=>$dm,'dw'=>$dw,'dd'=>$dd,'final'=>$totalamountfinal,"sumngmm"=>$mmsum]);
        }

        return $alldata;

    }
    public function possiblepayoutallpick5(){
      $getcontrol = control::first();
      $getevent = Event::where('status',1)->first();
      $events= Event::where('event_name',$getevent->event_name)->where('pick',5)->get();
      $arrays = array();
      foreach ($events as $key) {
        array_push($arrays, $key->id);
      }
      $getallevents = Event::whereIn('id',$arrays)
      // ->with('expertbet', function ($query)  {
      //   $query->select('startingfight','amount','event_id','turn','bet', DB::raw('sum(amount) as total'))->groupBy('bet');
      // })
      ->select('startingfight','event_name','id')
      ->get();
      $data = array();


      foreach ($getallevents as $key) {
        $combination = null;
        $combinations = 0;
        $getbet = null;
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
      return $data;
    }
    public function possiblepayoutallpick6(){
      $getcontrol = control::first();
      $getevent = Event::where('status',1)->first();
      $events= Event::where('event_name',$getevent->event_name)->where('pick',6)->get();
      $arrays = array();
      foreach ($events as $key) {
        array_push($arrays, $key->id);
      }
      $getallevents = Event::whereIn('id',$arrays)
      // ->with('expertbet', function ($query)  {
      //   $query->select('startingfight','amount','event_id','turn','bet', DB::raw('sum(amount) as total'))->groupBy('bet');
      // })
      ->select('startingfight','event_name','id')
      ->get();
      $data = array();


      foreach ($getallevents as $key) {
        $combination = null;
        $combinations = 0;
        $getbet = null;
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
      return $data;
    }
    public function possiblepayoutallpick8(){
      $getcontrol = control::first();
      $getevent = Event::where('status',1)->first();
      $events= Event::where('event_name',$getevent->event_name)->where('pick',8)->get();
      $arrays = array();
      foreach ($events as $key) {
        array_push($arrays, $key->id);
      }
      $getallevents = Event::whereIn('id',$arrays)
      // ->with('expertbet', function ($query)  {
      //   $query->select('startingfight','amount','event_id','turn','bet', DB::raw('sum(amount) as total'))->groupBy('bet');
      // })
      ->select('startingfight','event_name','id')
      ->get();
      $data = array();


      foreach ($getallevents as $key) {
        $combination = null;
        $combinations = 0;
        $getbet = null;
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
      return $data;
    }
    public function possiblepayoutallpick14(){
      $getcontrol = control::first();
      $getevent = Event::where('status',1)->first();
      $events= Event::where('event_name',$getevent->event_name)->where('pick',14)->get();
      $arrays = array();
      foreach ($events as $key) {
        array_push($arrays, $key->id);
      }
      $getallevents = Event::whereIn('id',$arrays)
      // ->with('expertbet', function ($query)  {
      //   $query->select('startingfight','amount','event_id','turn','bet', DB::raw('sum(amount) as total'))->groupBy('bet');
      // })
      ->select('startingfight','event_name','id')
      ->get();
      $data = array();


      foreach ($getallevents as $key) {
        $combination = null;
        $combinations = 0;
        $getbet = null;
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
      return $data;
    }
    public function possiblepayoutallpick4(){
      // $collection = collect(['M', 'w',"D"]);
      //
      // $matrix = $collection->crossJoin(['M', 'w',"D"], ['M', 'w',"D"],['M', 'w',"D"]);
      //
      // $matrix->all();
      // foreach ($matrix as $key) {
      //   $new = New combination();
      //   $new->bet = $key[0].$key[1].$key[2].$key[3];
      //   $new->save();
      // }

      // $combinations = combination::select('bet')->get();
      //
      // return $combinations;

      $getcontrol = control::first();
      $getevent = Event::where('status',1)->first();
      $events= Event::where('event_name',$getevent->event_name)->where('pick',4)->get();
      $arrays = array();
      foreach ($events as $key) {
        array_push($arrays, $key->id);
      }
      $getallevents = Event::
      whereIn('id',$arrays)
      ->select('startingfight','event_name','id')->
      with('expertbet', function ($query) {
        $query->select('amount','event_id','turn','bet');
       $query->where('turn',4);
      })
      ->get();
      // return $getallevents;
      $alldata = array();
      foreach ($getallevents as $key) {
        $MMMM = $key->expertbet->where('bet','RRRR')->sum('amount');
        $MMMw = $key->expertbet->where('bet','RRRw')->sum('amount');
        $MMMD = $key->expertbet->where('bet','MMMD')->sum('amount');
        $MMwM = $key->expertbet->where('bet','RRwR')->sum('amount');
        $MMww = $key->expertbet->where('bet','RRww')->sum('amount');
        $MMwD = $key->expertbet->where('bet','MMwD')->sum('amount');
        $MMDM = $key->expertbet->where('bet','MMDM')->sum('amount');
        $MMDw = $key->expertbet->where('bet','MMDw')->sum('amount');
        $MMDD = $key->expertbet->where('bet','MMDD')->sum('amount');
        $MwMM = $key->expertbet->where('bet','RwRR')->sum('amount');
        $MwMw = $key->expertbet->where('bet','RwRw')->sum('amount');
        $MwMD = $key->expertbet->where('bet','MwMD')->sum('amount');
        $MwwM = $key->expertbet->where('bet','RwwR')->sum('amount');
        $Mwww = $key->expertbet->where('bet','Rwww')->sum('amount');
        $MwwD = $key->expertbet->where('bet','MwwD')->sum('amount');
        $MwDM = $key->expertbet->where('bet','MwDM')->sum('amount');
        $MwDw = $key->expertbet->where('bet','MwDw')->sum('amount');
        $MwDD = $key->expertbet->where('bet','MwDD')->sum('amount');
        $MDMM = $key->expertbet->where('bet','MDMM')->sum('amount');
        $MDMw = $key->expertbet->where('bet','MDMw')->sum('amount');
        $MDMD = $key->expertbet->where('bet','MDMD')->sum('amount');
        $MDwM = $key->expertbet->where('bet','MDwM')->sum('amount');
        $MDww = $key->expertbet->where('bet','MDww')->sum('amount');
        $MDwD = $key->expertbet->where('bet','MDwD')->sum('amount');
        $MDDM = $key->expertbet->where('bet','MDDM')->sum('amount');
        $MDDw = $key->expertbet->where('bet','MDDw')->sum('amount');
        $MDDD = $key->expertbet->where('bet','MDDD')->sum('amount');
        $wMMM = $key->expertbet->where('bet','wRRR')->sum('amount');
        $wMMw = $key->expertbet->where('bet','wRRw')->sum('amount');
        $wMMD = $key->expertbet->where('bet','wRRD')->sum('amount');
        $wMwM = $key->expertbet->where('bet','wRwR')->sum('amount');
        $wMww = $key->expertbet->where('bet','wRww')->sum('amount');
        $wMwD = $key->expertbet->where('bet','wRwD')->sum('amount');
        $wMDM = $key->expertbet->where('bet','wRDR')->sum('amount');
        $wMDw = $key->expertbet->where('bet','wMDw')->sum('amount');
        $wMDD = $key->expertbet->where('bet','wMDD')->sum('amount');
        $wwMM = $key->expertbet->where('bet','wwRR')->sum('amount');
        $wwMw = $key->expertbet->where('bet','wwRw')->sum('amount');
        $wwMD = $key->expertbet->where('bet','wwMD')->sum('amount');
        $wwwM = $key->expertbet->where('bet','wwwR')->sum('amount');
        $wwww = $key->expertbet->where('bet','wwww')->sum('amount');
        $wwwD = $key->expertbet->where('bet','wwwD')->sum('amount');
        $wwDM = $key->expertbet->where('bet','wwDM')->sum('amount');
        $wwDw = $key->expertbet->where('bet','wwDw')->sum('amount');
        $wwDD = $key->expertbet->where('bet','wwDD')->sum('amount');
        $wDMM = $key->expertbet->where('bet','wDMM')->sum('amount');
        $wDMw = $key->expertbet->where('bet','wDMw')->sum('amount');
        $wDMD = $key->expertbet->where('bet','wDMD')->sum('amount');
        $wDwM = $key->expertbet->where('bet','wDwM')->sum('amount');
        $wDww = $key->expertbet->where('bet','wDww')->sum('amount');
        $wDwD = $key->expertbet->where('bet','wDwD')->sum('amount');
        $wDDM = $key->expertbet->where('bet','wDDM')->sum('amount');
        $wDDw = $key->expertbet->where('bet','wDDw')->sum('amount');
        $wDDD = $key->expertbet->where('bet','wDDD')->sum('amount');
        $DMMM = $key->expertbet->where('bet','DMMM')->sum('amount');
        $DMMw = $key->expertbet->where('bet','DMMw')->sum('amount');
        $DMMD = $key->expertbet->where('bet','DMMD')->sum('amount');
        $DMwM = $key->expertbet->where('bet','DMwM')->sum('amount');
        $DMww = $key->expertbet->where('bet','DMww')->sum('amount');
        $DMwD = $key->expertbet->where('bet','DMwD')->sum('amount');
        $DMDM = $key->expertbet->where('bet','DMDM')->sum('amount');
        $DMDw = $key->expertbet->where('bet','DMDw')->sum('amount');
        $DMDD = $key->expertbet->where('bet','DMDD')->sum('amount');
        $DwMM = $key->expertbet->where('bet','DwMM')->sum('amount');
        $DwMw = $key->expertbet->where('bet','DwMw')->sum('amount');
        $DwMD = $key->expertbet->where('bet','DwMD')->sum('amount');
        $DwwM = $key->expertbet->where('bet','DwwM')->sum('amount');
        $Dwww = $key->expertbet->where('bet','Dwww')->sum('amount');
        $DwwD = $key->expertbet->where('bet','DwwD')->sum('amount');
        $DwDM = $key->expertbet->where('bet','DwDM')->sum('amount');
        $DwDw = $key->expertbet->where('bet','DwDw')->sum('amount');
        $DwDD = $key->expertbet->where('bet','DwDD')->sum('amount');
        $DDMM = $key->expertbet->where('bet','DDMM')->sum('amount');
        $DDMw = $key->expertbet->where('bet','DDMw')->sum('amount');
        $DDMD = $key->expertbet->where('bet','DDMD')->sum('amount');
        $DDwM = $key->expertbet->where('bet','DDwM')->sum('amount');
        $DDww = $key->expertbet->where('bet','DDww')->sum('amount');
        $DDwD = $key->expertbet->where('bet','DDwD')->sum('amount');
        $DDDM = $key->expertbet->where('bet','DDDM')->sum('amount');
        $DDDw = $key->expertbet->where('bet','DDDw')->sum('amount');
        $DDDD = $key->expertbet->where('bet','DDDD')->sum('amount');



        $rakecalc = $getcontrol->rakepick2/100;
        $totalamount1 = $key->expertbet->sum('amount');
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

        array_push($alldata, ['set'=>1,'id'=>$key->id,'startingfight'=>$key->startingfight,'eventname'=>$key->event_name,
          'MMMM' => $MMMM ,'MMMw'=>$MMMw,'MMMD'=>$MMMD,'MMwM'=>$MMwM,'MMww'=>$MMww,'MMwD'=>$MMwD,'MMDM'=>$MMDM,'MMDw'=>$MMDw,'MMDD'=>$MMDD,
          'MwMM'=>$MwMM,'MwMw'=>$MwMw,'MwMD'=>$MwMD,'MwwM'=>$MwwM,'Mwww'=>$Mwww,'MwwD'=>$MwwD,'MwDM'=>$MwDM,'MwDw'=>$MwDw,'MwDD'=>$MwDD,
          'MDMM'=>$MDMM,'MDMw'=>$MDMw,'MDMD'=>$MDMD,'MDwM'=>$MDwM,'MDww'=>$MDww,'MDwD'=>$MDwD,'MDDM'=>$MDDM,'MDDw'=>$MDDw,'MDDD'=>$MDDD,
        ]);
        array_push($alldata, ['set'=>2,'id'=>$key->id,'startingfight'=>$key->startingfight,'eventname'=>$key->event_name,
          'wMMM' => $wMMM ,'wMMw'=>$wMMw,'wMMD'=>$wMMD,'wMwM'=>$wMwM,'wMww'=>$wMww,'wMwD'=>$wMwD,'wMDM'=>$wMDM,'wMDw'=>$wMDw,'wMDD'=>$wMDD,
          'wwMM'=>$wwMM,'wwMw'=>$wwMw,'wwMD'=>$wwMD,'wwwM'=>$wwwM,'wwww'=>$wwww,'wwwD'=>$wwwD,'wwDM'=>$wwDM,'wwDw'=>$wwDw,'wwDD'=>$wwDD,
          'wDMM'=>$wDMM,'wDMw'=>$wDMw,'wDMD'=>$wDMD,'wDwM'=>$wDwM,'wDww'=>$wDww,'wDwD'=>$wDwD,'wDDM'=>$wDDM,'wDDw'=>$wDDw,'wDDD'=>$wDDD,
        ]);
        array_push($alldata, ['set'=>3,'id'=>$key->id,'startingfight'=>$key->startingfight,'eventname'=>$key->event_name,
          'DMMM' => $DMMM ,'DMMw'=>$DMMw,'DMMD'=>$DMMD,'DMwM'=>$DMwM,'DMww'=>$DMww,'DMwD'=>$DMwD,'DMDM'=>$DMDM,'DMDw'=>$DMDw,'DMDD'=>$DMDD,
          'DwMM'=>$DwMM,'DwMw'=>$DwMw,'DwMD'=>$DwMD,'DwwM'=>$DwwM,'Dwww'=>$Dwww,'DwwD'=>$DwwD,'DwDM'=>$DwDM,'DwDw'=>$DwDw,'DwDD'=>$DwDD,
          'DDMM'=>$DDMM,'DDMw'=>$DDMw,'DDMD'=>$DDMD,'DDwM'=>$DDwM,'DDww'=>$DDww,'DDwD'=>$DDwD,'DDDM'=>$DDDM,'DDDw'=>$DDDw,'DDDD'=>$DDDD,
        ]);
      }

      return $alldata;
    }
    public function possiblepayoutallpick3()
    {
        $getcontrol = control::first();
        $getevent = Event::where('status',1)->first();
        $events= Event::where('event_name',$getevent->event_name)->where('pick',3)->get();
        $arrays = array();
        foreach ($events as $key) {
          array_push($arrays, $key->id);
        }
        $getallevents = Event::
        whereIn('id',$arrays)
        ->select('startingfight','event_name','id')->
        with('expertbet', function ($query) {
          $query->select('amount','event_id','turn','bet');
         $query->where('turn',3);
        })
        ->get();
        // return $getallevents;
        $alldata = array();
        foreach ($getallevents as $key) {
          $mmm = $key->expertbet->where('bet','RRR')->sum('amount');
          $mmmsum = $key->expertbet->where('bet','RRR')->sum('amount');
          $mmw = $key->expertbet->where('bet','RRw')->sum('amount');
          $mmwsum = $key->expertbet->where('bet','RRw')->sum('amount');
          $mmd = $key->expertbet->where('bet','MMD')->sum('amount');
          $mmdsum = $key->expertbet->where('bet','MMD')->sum('amount');
          $mwd = $key->expertbet->where('bet','MwD')->sum('amount');
          $mwdsum = $key->expertbet->where('bet','MwD')->sum('amount');
          $mww = $key->expertbet->where('bet','Rww')->sum('amount');
          $mwwsum = $key->expertbet->where('bet','Rww')->sum('amount');
          $mwm = $key->expertbet->where('bet','RwR')->sum('amount');
          $mwmsum = $key->expertbet->where('bet','RwR')->sum('amount');
          $mdm = $key->expertbet->where('bet','MDM')->sum('amount');
          $mdmsum = $key->expertbet->where('bet','MDM')->sum('amount');
          $mdw = $key->expertbet->where('bet','MDw')->sum('amount');
          $mdwsum = $key->expertbet->where('bet','MDw')->sum('amount');
          $mdd = $key->expertbet->where('bet','MDD')->sum('amount');
          $mddsum = $key->expertbet->where('bet','MDD')->sum('amount');

          $wwm = $key->expertbet->where('bet','wwR')->sum('amount');
          $wwmsum = $key->expertbet->where('bet','wwR')->sum('amount');
          $www = $key->expertbet->where('bet','www')->sum('amount');
          $wwwsum = $key->expertbet->where('bet','www')->sum('amount');
          $wwd = $key->expertbet->where('bet','wwD')->sum('amount');
          $wwdsum = $key->expertbet->where('bet','wwD')->sum('amount');
          $wdd = $key->expertbet->where('bet','wDD')->sum('amount');
          $wddsum = $key->expertbet->where('bet','wDD')->sum('amount');
          $wdw = $key->expertbet->where('bet','wDw')->sum('amount');
          $wdwsum = $key->expertbet->where('bet','wDw')->sum('amount');
          $wdm = $key->expertbet->where('bet','wDM')->sum('amount');
          $wdmsum = $key->expertbet->where('bet','wDM')->sum('amount');
          $wmm = $key->expertbet->where('bet','wRR')->sum('amount');
          $wmmsum = $key->expertbet->where('bet','wRR')->sum('amount');
          $wmw = $key->expertbet->where('bet','wRw')->sum('amount');
          $wmwsum = $key->expertbet->where('bet','wRw')->sum('amount');
          $wmd = $key->expertbet->where('bet','wMD')->sum('amount');
          $wmdsum = $key->expertbet->where('bet','wMD')->sum('amount');

          $ddd = $key->expertbet->where('bet','DDD')->sum('amount');
          $dddsum = $key->expertbet->where('bet','DDD')->sum('amount');
          $ddm = $key->expertbet->where('bet','DDM')->sum('amount');
          $ddmsum = $key->expertbet->where('bet','DDM')->sum('amount');
          $ddw = $key->expertbet->where('bet','DDw')->sum('amount');
          $ddwsum = $key->expertbet->where('bet','DDw')->sum('amount');
          $dww = $key->expertbet->where('bet','Dww')->sum('amount');
          $dwwsum = $key->expertbet->where('bet','Dww')->sum('amount');
          $dwd = $key->expertbet->where('bet','DwD')->sum('amount');
          $dwdsum = $key->expertbet->where('bet','DwD')->sum('amount');
          $dwm = $key->expertbet->where('bet','DwM')->sum('amount');
          $dwmsum = $key->expertbet->where('bet','DwM')->sum('amount');
          $dmm = $key->expertbet->where('bet','DMM')->sum('amount');
          $dmmsum = $key->expertbet->where('bet','DMM')->sum('amount');
          $dmw = $key->expertbet->where('bet','DMw')->sum('amount');
          $dmwsum = $key->expertbet->where('bet','DMw')->sum('amount');
          $dmd = $key->expertbet->where('bet','DMD')->sum('amount');
          $dmdsum = $key->expertbet->where('bet','DMD')->sum('amount');

          $rakecalc = $getcontrol->rakepick2/100;
          $totalamount1 = $mmm + $mmw+ $mmd+ $mwd+ $mww+ $mwm+ $mdm+ $mdw+ $mdd+ $wwm+ $www+ $wwd+$wdd+$wdw+$wdm+$wmm+$wmw+$wmd+$ddd+$ddm+$ddw+$dww+$dwd+$dwm+$dmm+$dmw+$dmd;
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


          array_push($alldata, ['id'=>$key->id,'startingfight'=>$key->startingfight,'eventname'=>$key->event_name,
            'mmm' => $mmm ,'mmw'=>$mmw,'mmd'=>$mmd,'mwd'=>$mwd,'mww'=>$mww,'mwm'=>$mwm,'mdm'=>$mdm,'mdw'=>$mdw,'mdd'=>$mdd,
            'wwm'=>$wwm,'www'=>$www,'wwd'=>$wwd,'wdd'=>$wdd,'wdw'=>$wdw,'wdm'=>$wdm,'wmm'=>$wmm,'wmw'=>$wmw,'wmd'=>$wmd,
            'ddd'=>$ddd,'ddm'=>$ddm,'ddw'=>$ddw,'dww'=>$dww,'dwd'=>$dwd,'dwm'=>$dwm,'dmm'=>$dmm,'dmw'=>$dmw,'dmd'=>$dmd,
          ]);
        }

        return $alldata;

    }
    // public function derbynames()
    // {
    //     // Prebet::where('user_id',auth()->user()->id)->get();
    // }
    public function insertmultiplerandompicks(Request $req)
    {
      // $awd = Prebet::where('user_id',$req['user_id'])->delete();

      // $this->validate($req, [
        // 'start' => 'required|max:255',
        // 'selection' => 'required|max:255',
        // 'amount'=> 'required'
      // ]);
      if ($req['numberofrandompicks']<= 10) {
        // code...
      }else {
        return error;
      }
      $insert = DB::transaction(function () use($req) {
      $checkifhavemoney = User::findOrFail(auth()->user()->id);
      if ($checkifhavemoney->role===3) {
        if ($checkifhavemoney->cash<$req['amount']) {
          return ['error'=>'You dont have enough balance.'];
        }
      }

      $getcontrol = control::first();

      $alldata = array();
  for ($random=0; $random < $req['numberofrandompicks']; $random++) {

      $checkif2draws = 0;
      $array = ['Draw','Meron','Meron','Meron','Meron','Meron','Wala','Wala','Wala','Wala','Wala'];
      if ($req['pick']==2) {
        $num_rows = $req['start']+2;
      }else {
        $num_rows = $req['start']+$getcontrol->pick+3;
      }
      $a=array('fightnumber'=>'','selection'=>'');
      $data = array();

        for ($i = $req['start']; $i < $num_rows; $i++)  {
          if ($checkif2draws<2) {
            $getnumber =  mt_rand(1, 10);
          }else {
            $getnumber =  mt_rand(1, 10);
          }
          $selections = null;
          $bet = null;
          if ($getnumber===0) {
            $selections = array('meron'=>false,'wala'=>false,'draw'=>true,);
            $bet = 'D';
            $checkif2draws = $checkif2draws + 1;
          }elseif ($getnumber >= 1 && $getnumber <= 5) {
            $selections = array('meron'=>true,'wala'=>false,'draw'=>false,);
            $bet = 'M';
          }elseif ($getnumber >= 6 && $getnumber <= 10) {
            $selections = array('meron'=>false,'wala'=>true,'draw'=>false,);
            $bet = 'w';
          }
          array_push($data, ['fightnumber' => $i, 'bet'=>$bet,'id'=>$req['id'], 'amount'=>1, 'finalamount'=>0, 'selection'=>$selections]);
        }

          $confirm = Event::where('pick',$req['pick'])->where('status',1)->where('startingfight',$req['start'])->where('control','Open')->orWhere('control','Last Call')->latest()->first();

          // $confirm2 = startingfights::where('event_id',$confirm->id)->where('startingfight',$req['data'][0]['fightnumber'])->first();

            // code...

          if ($confirm) {
            $getpotmoney=Potmoney::where('startingfight',$req['start'])->where('event_id',$confirm->id)->where('pick',$req['pick'])->latest()->first();
            $getactiveevent =Event::where('status',1)->where('id',$req['id'])->first();
            // if ($getpotmoney) {
            // $getpotmoney->amount=$getpotmoney->amount+$getcontrol->amount;
            // $getpotmoney->save();
            // }
            // else {
            //   $getpotmoney= new Potmoney();
            //   $getpotmoney->amount=$getcontrol->amount;
            //   $getpotmoney->event_id=$getactiveevent->id;
            //   $getpotmoney->startingfight=$req['start'];
            //   $getpotmoney->pick=$req['pick'];
            //   // $getpotmoney->save();
            // }
            $cashiermoney = User::findOrFail(auth()->user()->id);
            if ($cashiermoney->role==3) {
              // deduct cash to player
              $deductcash = User::findOrFail(auth()->user()->id);
              $checkifmoneyisgood = $deductcash->cash - $getcontrol->amount;
              if ($checkifmoneyisgood>=0) {
                $deductcash->cash =$deductcash->cash - $getcontrol->amount;
                $deductcash->save();
                $getpotmoney->save();
                // broadcast(new userupdate(auth()->user()->id));
              }else {
                return error;
              }

            }else{
              // add cash to cashier
              if ($cashiermoney===0.000) {
                $deductcash = User::findOrFail(auth()->user()->id);
                $deductcash->cash = $getcontrol->amount;
                $deductcash->save();
                $getpotmoney->save();
                // broadcast(new userupdate(auth()->user()->id));
              }else {
                  $deductcash = User::findOrFail(auth()->user()->id);
                  $deductcash->cash = $deductcash->cash+$getcontrol->amount;
                  $deductcash->save();
                  $getpotmoney->save();
                  // broadcast(new userupdate(auth()->user()->id));
              }
            }
            // generate barcode
            $control = control::first();
            $doublecheckmoney=Potmoney::where('startingfight',$req['start'])->where('event_id',  $confirm->id)->latest()->first();
            $getbarcode= expertbet::where('event_id',$getactiveevent->id)->latest()->first();
            $bet = new expertbet();
            $bet->user_id=auth()->user()->id;
            if (auth()->user()->role==3) {
              $bet->barcode="Mobile";
              // code...
            }else {
              do {
				            $code = Carbon::now()->timestamp;
                    $barcoded = auth()->user()->id.'-'.substr($code, -6);


               } while (expertbet::where("barcode", $barcoded)->first());
               $bet->barcode=$barcoded;
             }
              // $bet->potmoney_id=$doublecheckmoney->id;
              // $bet->startingbalance=$deductcash->cash+$getcontrol->amount;
              if (auth()->user()->role==3) {
                $bet->startingbalance=$deductcash->cash+$getcontrol->amount;
              }else {
                $bet->startingbalance=$deductcash->cash-$getcontrol->amount;
              }
              $bet->event_id=$getactiveevent->id;
              $bet->turn=$req['pick'];
              $bet->amount=$getcontrol->amount;
              $bet->startingfight=$req['start'];
              $bet->save();

            $selections = array();
			$data2 = expertbet::where('id', $bet->id)->firstOrFail();
            foreach ($data as $key) {
              if (!$data2->bet) {
                if (strlen($key['bet'])>1) {
                  if ($key['bet']=="MM") {
                    $data2->bet=$data2->bet.'[RR]';
                  }elseif ($key['bet']=="Mw") {
                    $data2->bet=$data2->bet.'[Rw]';
                  }elseif ($key['bet']=="wM") {
                    $data2->bet=$data2->bet.'[wR]';
                  }else {
                    $data2->bet='['.$key['bet'].']';
                  }
                  $data2->save();
                }else {
                  if ($key['bet']==="M") {
                    $data2->bet = "R";
                  }else {
                    $data2->bet = $key['bet'];
                  }
                  $data2->save();
                }
              }else {
                if (strlen($key['bet'])>1) {
                  if ($key['bet']=="MM") {
                    $data2->bet=$data2->bet.'[RR]';
                  }elseif ($key['bet']=="Mw") {
                    $data2->bet=$data2->bet.'[Rw]';
                  }elseif ($key['bet']=="wM") {
                    $data2->bet=$data2->bet.'[wR]';
                  }else {
                    $data2->bet=$data2->bet.'['.$key['bet'].']';
                  }
                  $data2->save();
                }else {
                  if ($key['bet']==="M") {
                    $data2->bet = $data2->bet."R";
                  }else {
                    $data2->bet = $data2->bet.$key['bet'];
                  }
                  $data2->save();
                }
              }
                // return $key['selection']['meron'];
                if ($key['selection']['meron']) {

                  $data= new selection();
                  // $data->bet=$key['bet'];
                  $data->event_id=$getactiveevent->id;
                  $data->fightnumber=$key['fightnumber'];
                  $data->selection='Meron';
                  $data->user_id=auth()->user()->id;
                  $data->startingfight=$req['start'];
                  $data->expertbet_id=$bet->id;
                  $data->save();
                  array_push($selections,$data);
                }
                if ($key['selection']['wala']) {
                  $data= new selection();
                  // $data->bet=$key['bet'];
                  $data->event_id=$getactiveevent->id;
                  $data->fightnumber=$key['fightnumber'];
                  $data->selection='Wala';
                  $data->user_id=auth()->user()->id;
                  $data->startingfight=$req['start'];
                  $data->expertbet_id=$bet->id;
                  $data->save();
                  array_push($selections,$data);
                }
                if ($key['selection']['draw']) {
                  $data= new selection();
                  // $data->bet=$key['bet'];
                  $data->event_id=$getactiveevent->id;
                  $data->fightnumber=$key['fightnumber'];
                  $data->selection='Draw';
                  $data->user_id=auth()->user()->id;
                  $data->startingfight=$req['start'];
                  $data->expertbet_id=$bet->id;
                  $data->save();
                  array_push($selections,$data);
                }

            }
             if (auth()->user()->role===3) {
               $endingbalance = $data2->user->cash;
              $startingbalance = $data2->user->cash + $getcontrol->amount;
             }else {
               $endingbalance = $data2->user->cash;
               $startingbalance = $data2->user->cash - $getcontrol->amount;
             }
            $createlogs = new Logs();
            $createlogs->type = 'Insert_Bet';
            $createlogs->user_id = auth()->user()->id;
            $createlogs->message = "bet id : ".$data2->id."\nBarcode : ".$data2->barcode."\nBet : ".$data2->bet."\nStartingfight : ".$req['start']."\nStarting Balance : ".number_format(round($startingbalance, 2))." \nAmount : ".number_format($data2->amount)."\nEnding Balance : ".number_format($endingbalance);
            $createlogs->save();
            $endingfight = $bet->startingfight + $control->pick - 1;
            $endingfightcancelled = $bet->startingfight + $control->pick + 2;
            array_push($alldata, array('id'=>$bet->id,'barcode'=>$bet->barcode,'amount'=>$bet->amount,'selection'=>$data2->bet,'cash'=>$deductcash->cash,'startingfight'=>$bet->startingfight,'endingfight'=>$endingfight,'cancelled'=>$endingfightcancelled));
            $rake = $control->rake/100;
            $rake2 = $control->percentage_jackpot/100;
            $amount1 = $control->amount * $rake ;
            $amount2 = $control->amount * $rake2 ;
            $finalamount = $control->amount - $amount1 - $amount2;
            $this->dispatch(new insertbet($finalamount,$req['start'],$req['id'],auth()->user()->id));

          }else {
            return ['error'=>'this starting fight is not available'];
          }
          }
          return $alldata;

          });
          return $insert;
    }
    public function allbets(Request $req)
    {
        return bet::where('event_id', $req['id'])->where('user_id',auth()->user()->id)->where('winner','!=',0)->latest()->paginate(10);
        $try = DB::table('events as a')
          ->where('a.status',1)
          ->join('expertbet as c', 'a.id', '=', 'c.event_id')
          ->where('c.winner','!=',0)->where('c.user_id',auth()->user()->id)
          ->select('a.startingfight','c.bet','c.amount','c.wins','c.winner')
          ->orderBy("startingfight", 'desc')
          ->paginate(10);
          return $try;
    }
    public function pendingbets(Request $req)
    {
        // return bet::where('event_id', $req['id'])->where('user_id',auth()->user()->id)->where('winner',0)->orderBy("startingfight", 'desc')->paginate(10);

        // $data = DB::table('events as a')
        //   ->where('a.status',1)
        //   ->join('expertbet as c', 'a.id', '=', 'c.event_id')
        //   ->where('c.winner',0)->where('c.user_id',auth()->user()->id)
        //   // ->select('c.startingfight','c.bet','c.amount','c.wins','c.winner','c.id','c.created_at')
        //   ->select('a.event_name','a.fightdate','c.* as bets')
        //   ->orderBy("startingfight", 'desc')
        //   ->orderBy("created_at", 'desc')
        //   ->paginate(10);

        $posts = Event::with(['expertbet' => function ($query) {
        $query->where('user_id', auth()->user()->id)->where('winner',0)->latest();
        }])->whereHas('expertbet', function ($query) {
            $query->where('user_id', auth()->user()->id)->where('winner',0);
        })->paginate(5);

        // $posts = Event::withWhereHas('expertbet', function ($query) {
        //     $query->where('user_id', auth()->user()->id);
        // })->latest()->paginate(5);
          return $posts;
    }
    public function pendingbetsonly()
    {
        //   $active = Event::where('status',1)->latest()->first();
        // return expertbet::where('event_id', $active->id)->where('user_id',auth()->user()->id)->where('winner',0)->orderBy("startingfight", 'desc')->paginate(10);
      $try = DB::table('events as a')
        ->where('a.status',1)
        ->join('expertbet as c', 'a.id', '=', 'c.event_id')
        ->where('c.winner',0)
        ->select('a.startingfight','c.bet','c.amount','c.wins','c.winner')
        ->orderBy("startingfight", 'desc')
        ->paginate(10);
        return $try;
        // original
        // return bet::where('event_id', $active->id)->where('user_id',auth()->user()->id)->where('winner',0)->orderBy("startingfight", 'desc')->paginate(10);
    }
    public function showdetailedbets($id)
    {
        $data = selection::where('expertbet_id',$id)->select('selection','fightnumber','event_id')->first();
      if ($data) {
        $data = selection::where('expertbet_id',$id)->select('selection','fightnumber','event_id')->get();
      }else {
        $data = past_selection::where('expertbet_id',$id)->select('selection','fightnumber','event_id')->get();
      }
        return $data;
     // $data = selection::where('expertbet_id',$id)->select('selection','fightnumber')->get();
        //return $data;

    }
    public function historybet($id)
    {
          $events = Event::where('id',$id)->latest()->first();
          $geteventnames = Event::where('event_name',$events->event_name)->get();
          $array = array();
          foreach ($geteventnames as $key) {
            array_push($array,$key->id);
          };
          $getbets = expertbet::whereIn('event_id',$array)->where('user_id',auth()->user()->id)->paginate(10);
        // return expertbet::where('event_id', $active->id)->where('user_id',auth()->user()->id)->where('winner',0)->orderBy("startingfight", 'desc')->paginate(10);
      // $data = experbet::where('expertbet_id',$id)->select('selection','fightnumber')->get();
        return $getbets;
        // original
        // return bet::where('event_id', $active->id)->where('user_id',auth()->user()->id)->where('winner',0)->orderBy("startingfight", 'desc')->paginate(10);
    }
    public function getbetxx(Request $req)
    {
          $events = Event::where('id',$req['id'])->latest()->first();
          $geteventnames = Event::where('event_name',$events->event_name)->get();
          $array = array();
          foreach ($geteventnames as $key) {
            array_push($array,$key->id);
          };
          $getbets = expertbet::whereIn('event_id',$array)->where('user_id',auth()->user()->id)->paginate(10);
        // return expertbet::where('event_id', $active->id)->where('user_id',auth()->user()->id)->where('winner',0)->orderBy("startingfight", 'desc')->paginate(10);
      // $data = experbet::where('expertbet_id',$id)->select('selection','fightnumber')->get();
        return $getbets;
        // original
        // return bet::where('event_id', $active->id)->where('user_id',auth()->user()->id)->where('winner',0)->orderBy("startingfight", 'desc')->paginate(10);
    }
    public function viewhistorybets1()
    {
      // $try = DB::table('events as a')
      //   ->where('a.status',1)
      //   ->join('expertbet as c', 'a.id', '=', 'c.event_id')
      //   ->where('c.winner','!=',0)->where('user_id',auth()->user()->id)
      //   ->select('c.startingfight','c.bet','c.amount','c.wins','c.winner','c.result','c.lose')
      //   ->orderBy("startingfight", 'desc')
      //   ->orderBy("wins", 'desc')
      //   ->paginate(10);
      $try = DB::table('expertbet as a')
        ->where('a.user_id',auth()->user()->id)
        ->where('winner','!=',0)
        // ->join('expertbet as c', 'a.id', '=', 'c.event_id')
        // ->where('c.winner','!=',0)->where('user_id',auth()->user()->id)
        ->select('a.event_id')
        ->groupBy('event_id')
        // ->orderBy("startingfight", 'desc')
        // ->orderBy("wins", 'desc')
        ->get();
        $array = array();
        foreach ($try as $key) {
          array_push($array,$key->event_id);
        }
        $events = DB::table('events as a')
        ->whereIn('a.id',$array)
        ->groupBy('a.event_name')
        ->latest()
        ->paginate(10);
        //   $active = Event::where('status',1)->latest()->first();
        // return expertbet::where('event_id', $active->id)->where('user_id',auth()->user()->id)->where('winner','!=',0)->orderBy("startingfight", 'desc')->paginate(10);
        return $events;
    }
    public function viewhistorybets()
    {

		  $posts = Event::
        with(['expertbet' => function ($query) {
        $query->where('user_id', auth()->user()->id)->where('winner','!=',0)->orderBy('wins','desc')->orderBy('amount','desc');
        }])
        ->with(['past_expertbet' => function ($query) {
        $query->where('user_id', auth()->user()->id)->where('winner','!=',0)->orderBy('wins','desc')->orderBy('amount','desc');
        }])
        ->whereHas('expertbet', function ($query) {
            $query->where('user_id', auth()->user()->id)->where('winner','!=',0);
        })
      ->orWhereHas('past_expertbet', function ($query) {
            $query->where('user_id', auth()->user()->id)->where('winner','!=',0);
        })
        ->latest()->orderBy('startingfight','desc')->paginate(5);

        // $posts = Event::withWhereHas('expertbet', function ($query) {
        //     $query->where('user_id', auth()->user()->id);
        // })->latest()->paginate(5);
          return $posts;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    public function bets()
    {
        $getactiveevent =Event::where('status',1)->first();
        // return $getactiveevent->startingfight;
        return expertbet::with('selection')->where('event_id',$getactiveevent->id)->where('user_id',auth()->user()->id)->where('winner',0)->orderBy("startingfight", 'desc')->paginate(10);
        // original
        // return bet::with('prebets')->where('event_id',$getactiveevent->id)->where('user_id',auth()->user()->id)->where('winner',0)->orderBy("startingfight", 'desc')->paginate(10);
    }
    public function insertbet(Request $req)
    {
      $this->validate($req, [
        'data.*.bet' => 'required',
        // 'selection' => 'required|max:255',
        'data.*.amount'=> 'required|gt:0',
		'data.*.finalamount'=> 'required|gt:99'
      ]);

	  $check = array();
      foreach ($req->data as $bets) {
        if ($bets['selection']['draw']) {
          array_push($check,'draw');
        }
      }
      // bilangin kung pasok sa 2 or less than 2
      $bilangin = collect($check)->count();
      if ($bilangin>2 && $req['data'][0]['pick']==20) {
        return ['error'=>'You have more than 2 draws, please make sure your pick has 2 or less than 2 draws'];
      }

      $confirm = Event::where('pick',$req['data'][0]['pick'])->where('status',1)->where('startingfight',$req['data'][0]['fightnumber'])->where('control','Open')->orWhere('control','Last Call')->latest()->first();

      // $confirm2 = startingfights::where('event_id',$confirm->id)->where('startingfight',$req['data'][0]['fightnumber'])->first();
      if ($confirm) {
        $getpotmoney=Potmoney::where('startingfight',$req['data'][0]['fightnumber'])->where('event_id',$confirm->id)->where('pick',$req['data'][0]['pick'])->latest()->first();
        $getactiveevent =Event::where('status',1)->where('id',$req['data'][0]['id'])->first();
        // if ($getpotmoney) {
        // $getpotmoney->amount=$getpotmoney->amount+$req['data'][0]['finalamount'];
        // $getpotmoney->save();
        // }
        // else {
        //   $getpotmoney= new Potmoney();
        //   $getpotmoney->amount=$req['data'][0]['finalamount'];
        //   $getpotmoney->event_id=$getactiveevent->id;
        //   $getpotmoney->pick=$req['data'][0]['pick'];
        //   $getpotmoney->startingfight=$req['data'][0]['fightnumber'];
        //   // $getpotmoney->save();
        // }
        $cashiermoney = User::findOrFail(auth()->user()->id);
        if ($cashiermoney->role==3) {
          // deduct cash to player
          $deductcash = User::findOrFail(auth()->user()->id);
          $checkifmoneyisgood = $deductcash->cash - $req['data'][0]['finalamount'];
          if ($checkifmoneyisgood>=0) {
            $deductcash->cash =$deductcash->cash - $req['data'][0]['finalamount'];
            $deductcash->save();
            $getpotmoney->save();
            // broadcast(new userupdate(auth()->user()->id));
          }else {
            return error;
          }

        }else{
          // add cash to cashier
          if ($cashiermoney===0.000) {
            $deductcash = User::findOrFail(auth()->user()->id);
            $deductcash->cash = $req['data'][0]['finalamount'];
            $deductcash->save();
            $getpotmoney->save();
            // broadcast(new userupdate(auth()->user()->id));
          }else {
              $deductcash = User::findOrFail(auth()->user()->id);
              $deductcash->cash = $deductcash->cash+$req['data'][0]['finalamount'];
              $deductcash->save();
              $getpotmoney->save();
              // broadcast(new userupdate(auth()->user()->id));
          }
        }
        // generate barcode
        $control = control::first();
        $doublecheckmoney=Potmoney::where('startingfight',$req['data'][0]['fightnumber'])->where('event_id',  $confirm->id)->latest()->first();
        $getbarcode= expertbet::where('event_id',$getactiveevent->id)->latest()->first();
        $bet = new expertbet();
        $bet->user_id=auth()->user()->id;
        if (auth()->user()->role!=3) {
          do {
            $code = Carbon::now()->timestamp;
            $barcoded = auth()->user()->id.'-'.substr($code, -6);
           } while (expertbet::where("barcode", "=", $barcoded)->first());
           $bet->barcode=$barcoded;
         }else {
           $bet->barcode="Mobile";
         }
          // $bet->barcode=substr($time, -6);
          // if (auth()->user()->role===3) {
            // $bet->owner=auth()->user()->name;
          // }
          $bet->turn=$req['data'][0]['pick'];
          // $bet->potmoney_id=$doublecheckmoney->id;
          $bet->startingbalance=auth()->user()->cash;
          $bet->event_id=$getactiveevent->id;
          $bet->amount=$req['data'][0]['finalamount'];
          $bet->dividendo=$req['data'][0]['amount'];
          $bet->startingfight=$req['data'][0]['fightnumber'];
          $bet->save();
        // $data2 = expertbet::with('selection')->where($req['data'][0]['id']);
        $selections = array();
        foreach ($req->data as $key) {
          $data2 = expertbet::where('id', $bet->id)->firstOrFail();
          if (!$data2->bet) {
            if (strlen($key['bet'])>1) {
              if ($key['bet']=="MM") {
                $data2->bet=$data2->bet.'[RR]';
              }elseif ($key['bet']=="Mw") {
                $data2->bet=$data2->bet.'[Rw]';
              }elseif ($key['bet']=="wM") {
                $data2->bet=$data2->bet.'[wR]';
              }else {
                $data2->bet='['.$key['bet'].']';
              }
              $data2->save();
            }else {
              if ($key['bet']==="M") {
                $data2->bet = "R";
              }else {
                $data2->bet = $key['bet'];
              }
              $data2->save();
            }
          }else {
            if (strlen($key['bet'])>1) {
              if ($key['bet']=="MM") {
                $data2->bet=$data2->bet.'[RR]';
              }elseif ($key['bet']=="Mw") {
                $data2->bet=$data2->bet.'[Rw]';
              }elseif ($key['bet']=="wM") {
                $data2->bet=$data2->bet.'[wR]';
              }else {
                $data2->bet=$data2->bet.'['.$key['bet'].']';
              }
              $data2->save();
            }else {
              if ($key['bet']==="M") {
                $data2->bet = $data2->bet."R";
              }else {
                $data2->bet = $data2->bet.$key['bet'];
              }
              $data2->save();
            }
          }
            // return $key['selection']['meron'];
            if ($key['selection']['meron']) {

              $data= new selection();
              // $data->bet=$key['bet'];
              $data->event_id=$getactiveevent->id;
              $data->fightnumber=$key['fightnumber'];
              $data->selection='Meron';
              $data->user_id=auth()->user()->id;
              $data->startingfight=$req['data'][0]['fightnumber'];
              $data->expertbet_id=$bet->id;
              $data->save();
              array_push($selections,$data);
            }
            if ($key['selection']['wala']) {
              $data= new selection();
              // $data->bet=$key['bet'];
              $data->event_id=$getactiveevent->id;
              $data->fightnumber=$key['fightnumber'];
              $data->selection='Wala';
              $data->user_id=auth()->user()->id;
              $data->startingfight=$req['data'][0]['fightnumber'];
              $data->expertbet_id=$bet->id;
              $data->save();
              array_push($selections,$data);
            }
            if ($key['selection']['draw']) {
              $data= new selection();
              // $data->bet=$key['bet'];
              $data->event_id=$getactiveevent->id;
              $data->fightnumber=$key['fightnumber'];
              $data->selection='Draw';
              $data->user_id=auth()->user()->id;
              $data->startingfight=$req['data'][0]['fightnumber'];
              $data->expertbet_id=$bet->id;
              $data->save();
              array_push($selections,$data);
            }

        }
         if (auth()->user()->role===3) {
           $endingbalance = $data2->startingbalance - $req['data'][0]['finalamount'];
         }else {
           $endingbalance = $data2->startingbalance + $req['data'][0]['finalamount'];
         }
        $createlogs = new Logs();
        $createlogs->type = 'Insert_Bet';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = "bet id : ".$data2->id."\nBarcode : ".$data2->barcode."\nBet : ".$data2->bet."\nStartingfight : ".$req['data'][0]['fightnumber']."\nStarting Balance : ".number_format($data2->startingbalance)." \nAmount : ".number_format($data2->amount)."\nEnding Balance : ".number_format($endingbalance);
        $createlogs->save();
        $alldata = array();
        $endingfight = $bet->startingfight + $control->pick - 1;
        $endingfightcancelled = $bet->startingfight + $control->pick + 2;
        array_push($alldata, array('id'=>$bet->id,'barcode'=>$bet->barcode,'amount'=>$bet->amount,'selection'=>$data2 ->bet,'cash'=>$deductcash->cash,'startingfight'=>$bet->startingfight,'endingfight'=>$endingfight,'cancelled'=>$endingfightcancelled));
        $rake = $control->rake/100;
        $rake2 = $control->percentage_jackpot/100;
        $rake3 = $control->rakepick2/100;
        $amount1 = $req['data'][0]['finalamount'] * $rake ;
        $amount2 = $req['data'][0]['finalamount'] * $rake2 ;
        $amount3 = $req['data'][0]['finalamount'] * $rake3 ;
        if ($req['data'][0]['pick']==2||$req['data'][0]['pick']==3||$req['data'][0]['pick']==4||$req['data'][0]['pick']==5||$req['data'][0]['pick']==6
        ||$req['data'][0]['pick']==8||$req['data'][0]['pick']==14) {
        $finalamount =   $req['data'][0]['finalamount']- $amount3;
        }else {
          $finalamount = $req['data'][0]['finalamount'] - $amount1 - $amount2;
        }
        $this->dispatch(new insertbet($finalamount,$req['data'][0]['fightnumber'],$req['data'][0]['id'],auth()->user()->id));
        // if (auth()->user()->role===3) {
        //   return expertbet::where('id',$bet->id)->first();
        //   return expertbet::where('id',$bet->id)->first();
        // }elseif (auth()->user()->role===0) {
        //   return expertbet::where('id',$bet->id)->first();
        //   return expertbet::where('id',$bet->id)->first();
        // }
        return $alldata;
          // code...
        // $betko = DB::table('expertbet as a')
        //  ->join('selection as c', 'a.id', '=', 'c.expertbet_id')
        //  ->where('a.id',1)
        //  ->first();
        // return $betko;
        // $prebet= selection::where('expertbet_id',$bet->id)->get();
        // $data2 = expertbet::findOrFail($bet->id);
        // $unique = $prebet->unique('fightnumber');
        // foreach ($unique as $select) {
        //   if (!$data2->bet) {
        //     if (strlen($select->bet)>1) {
        //       $data2->bet='['.$select->bet.']';
        //       $data2->save();
        //     }else {
        //       $data2->bet=$select->bet;
        //       $data2->save();
        //     }
        //   }else {
        //     if (strlen($select->bet)>1) {
        //       $data2->bet=$data2->bet.'['.$select->bet.']';
        //       $data2->save();
        //     }else {
        //       $data2->bet=$data2->bet.$select->bet;
        //       $data2->save();
        //     }
        //   }
        // }
      }else {
        return ['error'=>'this starting fight is not available'];
      }
    }
    public function tests(Request $req)
    {
        DB::transaction(function () use($req) {
          $confirm = Event::where('status',1)->where('id',$req['id'])->latest()->first();
          $confirm2 = startingfights::where('event_id',$confirm->id)->where('startingfight',$req['data'][0]['fightnumber'])->first();
        if ($confirm2) {
        // if ($req['data'][0]['fightnumber']===$confirm->startingfight) {

            $getpotmoney=Potmoney::where('startingfight',$req['data'][0]['fightnumber'])->where('event_id',$confirm->id)->latest()->first();
            $getactiveevent =Event::where('status',1)->first();
            if ($getpotmoney) {
            $getpotmoney->amount=$getpotmoney->amount+$getactiveevent->amount;
            // $getpotmoney->save();
            }else {
              $getpotmoney= new Potmoney();
              $getpotmoney->amount=$getactiveevent->amount;
              $getpotmoney->event_id=$getactiveevent->id;
              $getpotmoney->startingfight=$req['data'][0]['fightnumber'];
              // $getpotmoney->save();

            }


            $cashiermoney = User::findOrFail(auth()->user()->id);
            if ($cashiermoney->role==3) {
              // deduct cash to player
              $deductcash = User::findOrFail(auth()->user()->id);
              $checkifmoneyisgood = $deductcash->cash - $getactiveevent->amount;
              if ($checkifmoneyisgood>=0) {
                $deductcash->cash =$deductcash->cash - $getactiveevent->amount;
                $deductcash->save();
                $getpotmoney->save();
                // broadcast(new userupdate(auth()->user()->id));
              }else {
                return error;
              }

            }else{
              // add cash to cashier
              if ($cashiermoney===0.000) {
                $newmoney = User::findOrFail(auth()->user()->id);
                $newmoney->cash = $confirm->amount;
                $newmoney->save();
                $getpotmoney->save();
                // broadcast(new userupdate(auth()->user()->id));
              }else {
                  $newmoney = User::findOrFail(auth()->user()->id);
                  $newmoney->cash = $newmoney->cash+$confirm->amount;
                  $newmoney->save();
                  $getpotmoney->save();
                  // broadcast(new userupdate(auth()->user()->id));
              }
            }


            // generate barcode
            $doublecheckmoney=Potmoney::where('startingfight',$req['data'][0]['fightnumber'])->where('event_id',  $confirm->id)->latest()->first();
            $getbarcode= bet::where('event_id',$getactiveevent->id)->latest()->first();
            $bet = new bet();
            $bet->user_id=auth()->user()->id;
            if ($getbarcode) {
              $bet->barcode=$getbarcode->barcode=$getbarcode->barcode+1;
              $bet->potmoney_id=$doublecheckmoney->id;
              $bet->startingbalance=auth()->user()->cash;
              $bet->event_id=$getactiveevent->id;
              $bet->turn=$getactiveevent->pick;
              $bet->amount=$getactiveevent->amount;
              $bet->startingfight=$req['data'][0]['fightnumber'];
              $bet->save();
            }else {
              $time = Carbon::now()->timestamp;
              $bet->barcode=substr($time, -6);
              // if (auth()->user()->role===3) {
                // $bet->owner=auth()->user()->name;
              // }
              $bet->turn=$getactiveevent->pick;
              $bet->potmoney_id=$doublecheckmoney->id;
              $bet->startingbalance=auth()->user()->cash;
              $bet->event_id=$getactiveevent->id;
              $bet->amount=$getactiveevent->amount;
              $bet->startingfight=$req['data'][0]['fightnumber'];
              $bet->save();
            }

            // insert pick20 to database

            // $bet->user_id=auth()->user()->id;
            foreach ($req->data as $key) {
              $data= new Prebet();
              $data->selection=$key['selection'];
              $data->event_id=$getactiveevent->id;
              $data->fightnumber=$key['fightnumber'];
              $data->user_id=auth()->user()->id;
              $data->bet_id=$bet->id;
              $data->save();
            }

            // insert first letter of each selection to bet table

            $prebet= Prebet::where('bet_id',$bet->id)->get();
            $data2 = bet::findOrFail($bet->id);
            foreach ($prebet as $select) {
              if ($select->selection==="Wala") {
                if (!$data2->bet) {
                  $data2->bet='w';
                  $data2->save();
                }else {
                  $data2->bet=$data2->bet.'w';
                  $data2->save();
                }
              }elseif ($select->selection==="Meron") {
                // code...
                if (!$data2->bet) {
                  $data2->bet='M';
                  $data2->save();
                }else {
                  $data2->bet=$data2->bet.'M';
                  $data2->save();
                }
              }else {
                if (!$data2->bet) {
                  $data2->bet='D';
                  $data2->save();
                }else {
                  $data2->bet=$data2->bet.'D';
                  $data2->save();
                }
              }
            }
            // return ;
            // return $req['data'][0]['fightnumber'].$confirm->startingfight;
            $endingbalance = $data2->startingbalance - $confirm->amount;
            $createlogs = new Logs();
            $createlogs->type = 'Insert_Bet';
            $createlogs->user_id = auth()->user()->id;
            $createlogs->message = "bet id : ".$data2->id."\nBet : ".$data2->bet."\nStartingfight : ".$req['data'][0]['fightnumber']."\nStarting Balance : ".number_format($data2->startingbalance)." \nAmount : ".number_format($data2->amount)."\nEnding Balance : ".number_format($endingbalance);

            $createlogs->save();
            $rake = $getactiveevent->rake/100;
            $rake2 = $getactiveevent->pjackpot/100;
            $amount1 = $getactiveevent->amount * $rake ;
            $amount2 = $getactiveevent->amount * $rake2 ;
            $finalamount = $getactiveevent->amount - $amount1 - $amount2;
            $this->dispatch(new insertbet($finalamount,$req['data'][0]['fightnumber'],$req['id'],auth()->user()->id));
            // broadcast(new betevent($finalamount))->toOthers();
            return bet::with('prebets')->where('event_id',$confirm->id)->where('user_id',auth()->user()->id)->latest()->first();
          }
          else{
            return error;
          }
        });

        // Prebet::where(bet_id)
    }
    public function random(Request $req)
    {
      // $awd = Prebet::where('user_id',$req['user_id'])->delete();

      $this->validate($req, [
        'start' => 'required|max:255',
        'selection' => 'required|max:255',
        // 'amount'=> 'required'
      ]);

      $checkifhavemoney = User::findOrFail(auth()->user()->id);
      if ($checkifhavemoney->cash<100) {
        return ['error'=>'You dont have enough balance.'];
      }

      $getactiveevent = control::first();
      // $getactiveevent =Event::where('status',1)->first();

      // ito ung original
      // $array = ['Meron','Wala'];
      // ito ung may draw
      $checkif2draws = 0;
      $array = ['Meron','Meron','Meron','Meron','Meron','Wala','Wala','Wala','Wala','Wala'];
      // $data=Arr::random($array);

      $num_rows = $req['start']+$getactiveevent->pick+3; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
      $a=array('fightnumber'=>'','selection'=>'');
      $data = array();

        for ($i = $req['start']; $i < $num_rows; $i++)  {
          if ($checkif2draws<2) {
            $getnumber =  mt_rand(1, 10);
          }else {
            $getnumber =  mt_rand(1, 10);
          }
          $selections = null;
          $bet = null;
          // if ($getnumber===0) {
          //   $selections = array('meron'=>false,'wala'=>false,'draw'=>true,);
          //   $bet = 'D';
          //   $checkif2draws = $checkif2draws + 1;
          // }
          if ($getnumber >= 1 && $getnumber <= 5) {
            $selections = array('meron'=>true,'wala'=>false,'draw'=>false,);
            $bet = 'M';
          }
          elseif ($getnumber >= 6 && $getnumber <= 10) {
            $selections = array('meron'=>false,'wala'=>true,'draw'=>false,);
            $bet = 'w';
          }
          array_push($data, ['fightnumber' => $i, 'bet'=>$bet,'id'=>$req['id'], 'amount'=>1, 'finalamount'=>0, 'selection'=>$selections,'pick'=>$getactiveevent->pick]);
        }
     return $data;
    }
    public function selectionpick2(Request $req)
    {
        // $awd = Prebet::where('user_id',$req['user_id'])->delete();

        $this->validate($req, [
          'start' => 'required|max:255',
          'selection' => 'required|max:255',
          // 'amount'=> 'required',
          'user_id'=>'required'
        ]);
        // $getactiveevent =Event::where('status',1)->first();
        $checkifhavemoney = User::findOrFail(auth()->user()->id);
        if ($checkifhavemoney->cash<100 && $checkifhavemoney->role==3) {
          return ['error'=>'You dont have enough balance.'];
        }
        $getcontrol = control::first();
          // code...
          // $data = "";
          // $num_cols = 2;
          $num_rows = $req['start']+1; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
          // $a=array('fightnumber'=>'','selection'=>'','amount'=>'');
          // $a=array('fightnumber'=>'','selection'=>'');
          $data = array();
          $selection = array('meron'=>false,'wala'=>false,'draw'=>false,);

          for ($i = $req['start']; $i <= $num_rows; $i++)  {
            // part 1 legit original
            // $data= new Prebet();
            // $data->selection="Meron";
            // $data->amount=$req['amount'];
            // $data->fightnumber=$i;
            // $data->user_id=$req['user_id'];
            // $data->save();
            // end of part 1 legit original
            // testing lang to
            // ito naman ay no need database
            array_push($data, ['fightnumber' => $i, 'bet'=>'','id'=>$req['id'],'amount'=>1, 'finalamount'=>0, 'selection'=>$selection,'pick'=>2]);
          }
       // return Prebet::where('user_id',$req['user_id'])->get();
       return $data;
    }
    public function selectionpick3(Request $req)
    {
        // $awd = Prebet::where('user_id',$req['user_id'])->delete();

        $this->validate($req, [
          'start' => 'required|max:255',
          'selection' => 'required|max:255',
          // 'amount'=> 'required',
          'user_id'=>'required'
        ]);
        // $getactiveevent =Event::where('status',1)->first();
        $checkifhavemoney = User::findOrFail(auth()->user()->id);
        if ($checkifhavemoney->cash<100 && $checkifhavemoney->role==3) {
          return ['error'=>'You dont have enough balance.'];
        }
        $getcontrol = control::first();
          // code...
          // $data = "";
          // $num_cols = 2;
          $num_rows = $req['start']+2; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
          // $a=array('fightnumber'=>'','selection'=>'','amount'=>'');
          // $a=array('fightnumber'=>'','selection'=>'');
          $data = array();
          $selection = array('meron'=>false,'wala'=>false,'draw'=>false,);

          for ($i = $req['start']; $i <= $num_rows; $i++)  {
            // part 1 legit original
            // $data= new Prebet();
            // $data->selection="Meron";
            // $data->amount=$req['amount'];
            // $data->fightnumber=$i;
            // $data->user_id=$req['user_id'];
            // $data->save();
            // end of part 1 legit original
            // testing lang to
            // ito naman ay no need database
            array_push($data, ['fightnumber' => $i, 'bet'=>'','id'=>$req['id'],'amount'=>1, 'finalamount'=>0, 'selection'=>$selection,'pick'=>3]);
          }
       // return Prebet::where('user_id',$req['user_id'])->get();
       return $data;
    }
    public function selectionpick4(Request $req)
    {
        // $awd = Prebet::where('user_id',$req['user_id'])->delete();

        $this->validate($req, [
          'start' => 'required|max:255',
          'selection' => 'required|max:255',
          // 'amount'=> 'required',
          'user_id'=>'required'
        ]);
        // $getactiveevent =Event::where('status',1)->first();
        $checkifhavemoney = User::findOrFail(auth()->user()->id);
        if ($checkifhavemoney->cash<100 && $checkifhavemoney->role==3) {
          return ['error'=>'You dont have enough balance.'];
        }
        $getcontrol = control::first();
          // code...
          // $data = "";
          // $num_cols = 2;
          $num_rows = $req['start']+3; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
          // $a=array('fightnumber'=>'','selection'=>'','amount'=>'');
          // $a=array('fightnumber'=>'','selection'=>'');
          $data = array();
          $selection = array('meron'=>false,'wala'=>false,'draw'=>false,);

          for ($i = $req['start']; $i <= $num_rows; $i++)  {
            // part 1 legit original
            // $data= new Prebet();
            // $data->selection="Meron";
            // $data->amount=$req['amount'];
            // $data->fightnumber=$i;
            // $data->user_id=$req['user_id'];
            // $data->save();
            // end of part 1 legit original
            // testing lang to
            // ito naman ay no need database
            array_push($data, ['fightnumber' => $i, 'bet'=>'','id'=>$req['id'],'amount'=>1, 'finalamount'=>0, 'selection'=>$selection,'pick'=>4]);
          }
       // return Prebet::where('user_id',$req['user_id'])->get();
       return $data;
    }
    public function selectionpick5(Request $req)
    {
        // $awd = Prebet::where('user_id',$req['user_id'])->delete();

        $this->validate($req, [
          'start' => 'required|max:255',
          'selection' => 'required|max:255',
          // 'amount'=> 'required',
          'user_id'=>'required'
        ]);
        // $getactiveevent =Event::where('status',1)->first();
        $checkifhavemoney = User::findOrFail(auth()->user()->id);
        if ($checkifhavemoney->cash<100 && $checkifhavemoney->role==3) {
          return ['error'=>'You dont have enough balance.'];
        }
        $getcontrol = control::first();
          // code...
          // $data = "";
          // $num_cols = 2;
          $num_rows = $req['start']+4; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
          // $a=array('fightnumber'=>'','selection'=>'','amount'=>'');
          // $a=array('fightnumber'=>'','selection'=>'');
          $data = array();
          $selection = array('meron'=>false,'wala'=>false,'draw'=>false,);

          for ($i = $req['start']; $i <= $num_rows; $i++)  {
            // part 1 legit original
            // $data= new Prebet();
            // $data->selection="Meron";
            // $data->amount=$req['amount'];
            // $data->fightnumber=$i;
            // $data->user_id=$req['user_id'];
            // $data->save();
            // end of part 1 legit original
            // testing lang to
            // ito naman ay no need database
            array_push($data, ['fightnumber' => $i, 'bet'=>'','id'=>$req['id'],'amount'=>1, 'finalamount'=>0, 'selection'=>$selection,'pick'=>5]);
          }
       // return Prebet::where('user_id',$req['user_id'])->get();
       return $data;
    }
    public function selectionpick6(Request $req)
    {
        // $awd = Prebet::where('user_id',$req['user_id'])->delete();

        $this->validate($req, [
          'start' => 'required|max:255',
          'selection' => 'required|max:255',
          // 'amount'=> 'required',
          'user_id'=>'required'
        ]);
        // $getactiveevent =Event::where('status',1)->first();
        $checkifhavemoney = User::findOrFail(auth()->user()->id);
        if ($checkifhavemoney->cash<100 && $checkifhavemoney->role==3) {
          return ['error'=>'You dont have enough balance.'];
        }
        $getcontrol = control::first();
          // code...
          // $data = "";
          // $num_cols = 2;
          $num_rows = $req['start']+5; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
          // $a=array('fightnumber'=>'','selection'=>'','amount'=>'');
          // $a=array('fightnumber'=>'','selection'=>'');
          $data = array();
          $selection = array('meron'=>false,'wala'=>false,'draw'=>false,);

          for ($i = $req['start']; $i <= $num_rows; $i++)  {
            // part 1 legit original
            // $data= new Prebet();
            // $data->selection="Meron";
            // $data->amount=$req['amount'];
            // $data->fightnumber=$i;
            // $data->user_id=$req['user_id'];
            // $data->save();
            // end of part 1 legit original
            // testing lang to
            // ito naman ay no need database
            array_push($data, ['fightnumber' => $i, 'bet'=>'','id'=>$req['id'],'amount'=>1, 'finalamount'=>0, 'selection'=>$selection,'pick'=>6]);
          }
       // return Prebet::where('user_id',$req['user_id'])->get();
       return $data;
    }
    public function selectionpick8(Request $req)
    {
        // $awd = Prebet::where('user_id',$req['user_id'])->delete();

        $this->validate($req, [
          'start' => 'required|max:255',
          'selection' => 'required|max:255',
          // 'amount'=> 'required',
          'user_id'=>'required'
        ]);
        // $getactiveevent =Event::where('status',1)->first();
        $checkifhavemoney = User::findOrFail(auth()->user()->id);
        if ($checkifhavemoney->cash<100 && $checkifhavemoney->role==3) {
          return ['error'=>'You dont have enough balance.'];
        }
        $getcontrol = control::first();
          // code...
          // $data = "";
          // $num_cols = 2;
          $num_rows = $req['start']+7; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
          // $a=array('fightnumber'=>'','selection'=>'','amount'=>'');
          // $a=array('fightnumber'=>'','selection'=>'');
          $data = array();
          $selection = array('meron'=>false,'wala'=>false,'draw'=>false,);

          for ($i = $req['start']; $i <= $num_rows; $i++)  {
            // part 1 legit original
            // $data= new Prebet();
            // $data->selection="Meron";
            // $data->amount=$req['amount'];
            // $data->fightnumber=$i;
            // $data->user_id=$req['user_id'];
            // $data->save();
            // end of part 1 legit original
            // testing lang to
            // ito naman ay no need database
            array_push($data, ['fightnumber' => $i, 'bet'=>'','id'=>$req['id'],'amount'=>1, 'finalamount'=>0, 'selection'=>$selection,'pick'=>8]);
          }
       // return Prebet::where('user_id',$req['user_id'])->get();
       return $data;
    }
    public function selectionpick14(Request $req)
    {
        // $awd = Prebet::where('user_id',$req['user_id'])->delete();

        $this->validate($req, [
          'start' => 'required|max:255',
          'selection' => 'required|max:255',
          // 'amount'=> 'required',
          'user_id'=>'required'
        ]);
        // $getactiveevent =Event::where('status',1)->first();
        $checkifhavemoney = User::findOrFail(auth()->user()->id);
        if ($checkifhavemoney->cash<100 && $checkifhavemoney->role==3) {
          return ['error'=>'You dont have enough balance.'];
        }
        $getcontrol = control::first();
          // code...
          // $data = "";
          // $num_cols = 2;
          $num_rows = $req['start']+13; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
          // $a=array('fightnumber'=>'','selection'=>'','amount'=>'');
          // $a=array('fightnumber'=>'','selection'=>'');
          $data = array();
          $selection = array('meron'=>false,'wala'=>false,'draw'=>false,);

          for ($i = $req['start']; $i <= $num_rows; $i++)  {
            // part 1 legit original
            // $data= new Prebet();
            // $data->selection="Meron";
            // $data->amount=$req['amount'];
            // $data->fightnumber=$i;
            // $data->user_id=$req['user_id'];
            // $data->save();
            // end of part 1 legit original
            // testing lang to
            // ito naman ay no need database
            array_push($data, ['fightnumber' => $i, 'bet'=>'','id'=>$req['id'],'amount'=>1, 'finalamount'=>0, 'selection'=>$selection,'pick'=>14]);
          }
       // return Prebet::where('user_id',$req['user_id'])->get();
       return $data;
    }
    public function randompick2(Request $req)
    {
      // $awd = Prebet::where('user_id',$req['user_id'])->delete();

      $this->validate($req, [
        'start' => 'required|max:255',
        'selection' => 'required|max:255',
        // 'amount'=> 'required'
      ]);

      $checkifhavemoney = User::findOrFail(auth()->user()->id);
      if ($checkifhavemoney->cash<100) {
        return ['error'=>'You dont have enough balance.'];
      }

      $getactiveevent = control::first();;
      // $getactiveevent =Event::where('status',1)->first();

      // ito ung original
      // $array = ['Meron','Wala'];
      // ito ung may draw
      $checkif2draws = 0;
      $array = ['Draw','Meron','Wala'];
      // $data=Arr::random($array);

      $num_rows = $req['start']+1; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
      $a=array('fightnumber'=>'','selection'=>'');
      $data = array();

        for ($i = $req['start']; $i <= $num_rows; $i++)  {
          $getnumber =  mt_rand(1, 2);
          $selections = null;
          $bet = null;
          if ($getnumber===0) {
            $selections = array('meron'=>false,'wala'=>false,'draw'=>true,);
            $bet = 'D';
            $checkif2draws = $checkif2draws + 1;
          }elseif ($getnumber == 1) {
            $selections = array('meron'=>true,'wala'=>false,'draw'=>false,);
            $bet = 'M';
          }elseif ($getnumber == 2) {
            $selections = array('meron'=>false,'wala'=>true,'draw'=>false,);
            $bet = 'w';
          }
          array_push($data, ['fightnumber' => $i, 'bet'=>$bet,'id'=>$req['id'], 'amount'=>1, 'finalamount'=>0, 'selection'=>$selections,'pick'=>2]);
        }
     return $data;
    }
    public function randompick3(Request $req)
    {

      $this->validate($req, [
        'start' => 'required|max:255',
        'selection' => 'required|max:255',
      ]);

      $checkifhavemoney = User::findOrFail(auth()->user()->id);
      if ($checkifhavemoney->cash<100) {
        return ['error'=>'You dont have enough balance.'];
      }

      $getactiveevent = control::first();

      $checkif2draws = 0;
      $array = ['Draw','Meron','Wala'];
      // $data=Arr::random($array);

      $num_rows = $req['start']+2; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
      $a=array('fightnumber'=>'','selection'=>'');
      $data = array();

        for ($i = $req['start']; $i <= $num_rows; $i++)  {
          $getnumber =  mt_rand(1, 2);
          $selections = null;
          $bet = null;
          if ($getnumber===0) {
            $selections = array('meron'=>false,'wala'=>false,'draw'=>true,);
            $bet = 'D';
            $checkif2draws = $checkif2draws + 1;
          }elseif ($getnumber == 1) {
            $selections = array('meron'=>true,'wala'=>false,'draw'=>false,);
            $bet = 'M';
          }elseif ($getnumber == 2) {
            $selections = array('meron'=>false,'wala'=>true,'draw'=>false,);
            $bet = 'w';
          }
          array_push($data, ['fightnumber' => $i, 'bet'=>$bet,'id'=>$req['id'], 'amount'=>1, 'finalamount'=>0, 'selection'=>$selections,'pick'=>3]);
        }
     return $data;
    }
    public function randompick4(Request $req)
    {

      $this->validate($req, [
        'start' => 'required|max:255',
        'selection' => 'required|max:255',
      ]);

      $checkifhavemoney = User::findOrFail(auth()->user()->id);
      if ($checkifhavemoney->cash<100) {
        return ['error'=>'You dont have enough balance.'];
      }

      $getactiveevent = control::first();

      $checkif2draws = 0;
      $array = ['Draw','Meron','Wala'];
      // $data=Arr::random($array);

      $num_rows = $req['start']+3; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
      $a=array('fightnumber'=>'','selection'=>'');
      $data = array();

        for ($i = $req['start']; $i <= $num_rows; $i++)  {
          $getnumber =  mt_rand(1, 2);
          $selections = null;
          $bet = null;
          if ($getnumber===0) {
            $selections = array('meron'=>false,'wala'=>false,'draw'=>true,);
            $bet = 'D';
            $checkif2draws = $checkif2draws + 1;
          }elseif ($getnumber == 1) {
            $selections = array('meron'=>true,'wala'=>false,'draw'=>false,);
            $bet = 'M';
          }elseif ($getnumber == 2) {
            $selections = array('meron'=>false,'wala'=>true,'draw'=>false,);
            $bet = 'w';
          }
          array_push($data, ['fightnumber' => $i, 'bet'=>$bet,'id'=>$req['id'], 'amount'=>1, 'finalamount'=>0, 'selection'=>$selections,'pick'=>4]);
        }
     return $data;
    }
    public function randompick5(Request $req)
    {

      $this->validate($req, [
        'start' => 'required|max:255',
        'selection' => 'required|max:255',
      ]);

      $checkifhavemoney = User::findOrFail(auth()->user()->id);
      if ($checkifhavemoney->cash<100) {
        return ['error'=>'You dont have enough balance.'];
      }

      $getactiveevent = control::first();

      $checkif2draws = 0;
      $array = ['Draw','Meron','Wala'];
      // $data=Arr::random($array);

      $num_rows = $req['start']+4; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
      $a=array('fightnumber'=>'','selection'=>'');
      $data = array();

        for ($i = $req['start']; $i <= $num_rows; $i++)  {
          $getnumber =  mt_rand(1, 2);
          $selections = null;
          $bet = null;
          if ($getnumber===0) {
            $selections = array('meron'=>false,'wala'=>false,'draw'=>true,);
            $bet = 'D';
            $checkif2draws = $checkif2draws + 1;
          }elseif ($getnumber == 1) {
            $selections = array('meron'=>true,'wala'=>false,'draw'=>false,);
            $bet = 'M';
          }elseif ($getnumber == 2) {
            $selections = array('meron'=>false,'wala'=>true,'draw'=>false,);
            $bet = 'w';
          }
          array_push($data, ['fightnumber' => $i, 'bet'=>$bet,'id'=>$req['id'], 'amount'=>1, 'finalamount'=>0, 'selection'=>$selections,'pick'=>5]);
        }
     return $data;
    }
    public function randompick6(Request $req)
    {

      $this->validate($req, [
        'start' => 'required|max:255',
        'selection' => 'required|max:255',
      ]);

      $checkifhavemoney = User::findOrFail(auth()->user()->id);
      if ($checkifhavemoney->cash<100) {
        return ['error'=>'You dont have enough balance.'];
      }

      $getactiveevent = control::first();

      $checkif2draws = 0;
      $array = ['Draw','Meron','Wala'];
      // $data=Arr::random($array);

      $num_rows = $req['start']+5; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
      $a=array('fightnumber'=>'','selection'=>'');
      $data = array();

        for ($i = $req['start']; $i <= $num_rows; $i++)  {
          $getnumber =  mt_rand(1, 2);
          $selections = null;
          $bet = null;
          if ($getnumber===0) {
            $selections = array('meron'=>false,'wala'=>false,'draw'=>true,);
            $bet = 'D';
            $checkif2draws = $checkif2draws + 1;
          }elseif ($getnumber == 1) {
            $selections = array('meron'=>true,'wala'=>false,'draw'=>false,);
            $bet = 'M';
          }elseif ($getnumber == 2) {
            $selections = array('meron'=>false,'wala'=>true,'draw'=>false,);
            $bet = 'w';
          }
          array_push($data, ['fightnumber' => $i, 'bet'=>$bet,'id'=>$req['id'], 'amount'=>1, 'finalamount'=>0, 'selection'=>$selections,'pick'=>6]);
        }
     return $data;
    }
    public function randompick8(Request $req)
    {

      $this->validate($req, [
        'start' => 'required|max:255',
        'selection' => 'required|max:255',
      ]);

      $checkifhavemoney = User::findOrFail(auth()->user()->id);
      if ($checkifhavemoney->cash<100) {
        return ['error'=>'You dont have enough balance.'];
      }

      $getactiveevent = control::first();

      $checkif2draws = 0;
      $array = ['Draw','Meron','Wala'];
      // $data=Arr::random($array);

      $num_rows = $req['start']+7; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
      $a=array('fightnumber'=>'','selection'=>'');
      $data = array();

        for ($i = $req['start']; $i <= $num_rows; $i++)  {
          $getnumber =  mt_rand(1, 2);
          $selections = null;
          $bet = null;
          if ($getnumber===0) {
            $selections = array('meron'=>false,'wala'=>false,'draw'=>true,);
            $bet = 'D';
            $checkif2draws = $checkif2draws + 1;
          }elseif ($getnumber == 1) {
            $selections = array('meron'=>true,'wala'=>false,'draw'=>false,);
            $bet = 'R';
          }elseif ($getnumber == 2) {
            $selections = array('meron'=>false,'wala'=>true,'draw'=>false,);
            $bet = 'w';
          }
          array_push($data, ['fightnumber' => $i, 'bet'=>$bet,'id'=>$req['id'], 'amount'=>1, 'finalamount'=>0, 'selection'=>$selections,'pick'=>8]);
        }
     return $data;
    }
    public function randompick14(Request $req)
    {

      $this->validate($req, [
        'start' => 'required|max:255',
        'selection' => 'required|max:255',
      ]);

      $checkifhavemoney = User::findOrFail(auth()->user()->id);
      if ($checkifhavemoney->cash<100) {
        return ['error'=>'You dont have enough balance.'];
      }

      $getactiveevent = control::first();

      $checkif2draws = 0;
      $array = ['Draw','Meron','Wala'];
      // $data=Arr::random($array);

      $num_rows = $req['start']+13; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
      $a=array('fightnumber'=>'','selection'=>'');
      $data = array();

        for ($i = $req['start']; $i <= $num_rows; $i++)  {
          $getnumber =  mt_rand(1, 2);
          $selections = null;
          $bet = null;
          if ($getnumber===0) {
            $selections = array('meron'=>false,'wala'=>false,'draw'=>true,);
            $bet = 'D';
            $checkif2draws = $checkif2draws + 1;
          }elseif ($getnumber == 1) {
            $selections = array('meron'=>true,'wala'=>false,'draw'=>false,);
            $bet = 'R';
          }elseif ($getnumber == 2) {
            $selections = array('meron'=>false,'wala'=>true,'draw'=>false,);
            $bet = 'w';
          }
          array_push($data, ['fightnumber' => $i, 'bet'=>$bet,'id'=>$req['id'], 'amount'=>1, 'finalamount'=>0, 'selection'=>$selections,'pick'=>14]);
        }
     return $data;
    }
    // public function random(Request $req)
    // {
    //   // $awd = Prebet::where('user_id',$req['user_id'])->delete();
    //
    //   $this->validate($req, [
    //     'start' => 'required|max:255',
    //     'selection' => 'required|max:255',
    //     // 'amount'=> 'required'
    //   ]);
    //   $getactiveevent =Event::where('status',1)->first();
    //
    //   // ito ung original
    //   // $array = ['Meron','Wala'];
    //   // ito ung may draw
    //   $array = ['Draw','Meron','Meron','Meron','Meron','Meron','Wala','Wala','Wala','Wala','Wala'];
    //   // $data=Arr::random($array);
    //
    //   $num_rows = $req['start']+$getactiveevent->pick; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
    //   $a=array('fightnumber'=>'','selection'=>'');
    //   $data = array();
    //
    //     for ($i = $req['start']; $i < $num_rows; $i++)  {
    //       $getnumber =  mt_rand(0, 10);
    //       $selection = null;
    //       if ($getnumber===0) {
    //         $selection = 'Draw';
    //       }elseif ($getnumber >= 1 && $getnumber <= 5) {
    //         $selection = 'Meron';
    //       }elseif ($getnumber >= 6 && $getnumber <= 10) {
    //         $selection = 'Wala';
    //       }
    //       array_push($data, ['selection' => $selection, 'fightnumber' => $i,]);
    //     }
    //  return $data;
    // }
    public function deleteprebet()
    {
        $data=Prebet::where('user_id',auth()->user()->id)->delete();
        // $array = ['Meron','Wala'];
        // $data=Arr::random($array);
    return $data;
    }

    public function switchw(Request $req)
    {
      $data = Prebet::findOrFail($req['id']);
      $data->selection='Wala';
      // $data->amount=$req['amount'];
      $data->fightnumber=$req['fightnumber'];
      $data->save();
      return Prebet::where('user_id',$req['user_id'])->get();
    }
    public function switchm(Request $req)
    {
      $data = Prebet::findOrFail($req['id']);
      $data->selection='Meron';
      // $data->amount=$req['amount'];
      $data->fightnumber=$req['fightnumber'];
      $data->save();
      return Prebet::where('user_id',$req['user_id'])->get();
    }
    public function switch(Request $req)
    {
      $data = Prebet::findOrFail($req['id']);
      if ($req['selection']==="Meron") {
        $data->selection='Wala';
      }else {
        $data->selection='Meron';
      }
      $data->save();
      return Prebet::where('user_id',$req['user_id'])->get();
    }
    public function meronall(Request $req)
    {
      $data = Prebet::where('user_id', $req['user_id'])->get();

      foreach ($data as $wala) {
        $datas = Prebet::findOrFail($wala->id);
        $datas->selection="Meron";
        $datas->save();
      }
      return Prebet::where('user_id',$req['user_id'])->get();
    }
    public function walaall(Request $req)
    {
      $data = Prebet::where('user_id', $req['user_id'])->get();

      foreach ($data as $wala) {
        $datas = Prebet::findOrFail($wala->id);
        $datas->selection="Wala";
        $datas->save();
      }
      return Prebet::where('user_id',$req['user_id'])->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\bet  $bet
     * @return \Illuminate\Http\Response
     */
    public function show(bet $bet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\bet  $bet
     * @return \Illuminate\Http\Response
     */
    public function edit(bet $bet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\bet  $bet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, bet $bet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\bet  $bet
     * @return \Illuminate\Http\Response
     */
    public function destroy(bet $bet)
    {
        //
    }
}
