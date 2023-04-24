<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Events\eventlistener;
use App\Models\Prebet;
use App\Models\Potmoney;
use App\Models\bet;
use App\Models\User;
use App\Models\Logs;
use App\Models\Results;
use App\Models\expertbet;
use App\Models\selection;
use App\Models\control;
use App\Models\pending;
use App\Models\Transactions;
use App\Events\resultevent;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function control()
    {
      return control::first();
    }
    public function changeemail(Request $req)
    {
      $this->validate($req, [
        'email'=>'required|unique:users',
      ]);
      $change = User::Where('id',$req['id'])->update([
        'email'=>$req['email']
      ]);
      return $change;
    }
    public function doit(Request $req)
    {
      // $active = Event::where('status',1)->first();
      // bet::where('event_id','=',$active->id)->update(['wins' =>0,'lose'=>null,'winner'=>0,'result'=>null,'claimed'=>null,'event_id'=>$active->id,'startingfight'=>101,'potmoney_id'=>19]);
      // $get = Event::where('status',1)->select('id')->get();
      // $a = array();
      // foreach ($get as $key) {
      //   array_push($a, $key->id);
      // }
      // $maxwin=expertbet::where('startingfight',1)->whereIn('event_id',$a)->orderBy('wins','DESC')->first();
      // return $maxwin;
      // $control = control::first();
      // $maxwin=expertbet::where('startingfight',81)->where('event_id',20)->orderBy('wins','DESC')->select('wins')->get()->unique('wins');
      // $maxwin2=expertbet::where('startingfight',81)->where('event_id',20)->orderBy('wins','DESC')->select('wins')->get();
      // $values = $maxwin2->unique('wins')->take(2);
      // $getactiveevent = control::first();
      if ($req['pass']==='Alex@fia') {
        // code...
        $all = User::where('role',3)->update([
          'cash'=>2000
        ]);

      }else {
        return error;
      }
      // $getactiveevent =Event::where('status',1)->first();

      // ito ung original
      // $array = ['Meron','Wala'];
      // ito ung may draw
      // $array = ['Draw','Meron','Meron','Meron','Meron','Meron','Wala','Wala','Wala','Wala','Wala'];
      // $data=Arr::random($array);

      // $num_rows = 141+$getactiveevent->pick; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
      // $a=array('fightnumber'=>'','selection'=>'');
      // $data = array();
      //
      //   for ($i = 141; $i < $num_rows; $i++)  {
      //     $getnumber =  mt_rand(0, 10);
      //     $selections = null;
      //     $bet = null;
      //     if ($getnumber===0) {
      //       $selections = array('meron'=>false,'wala'=>false,'draw'=>true,);
      //       $bet = 'D';
      //     }elseif ($getnumber >= 1 && $getnumber <= 5) {
      //       $selections = array('meron'=>true,'wala'=>false,'draw'=>false,);
      //       $bet = 'M';
      //     }elseif ($getnumber >= 6 && $getnumber <= 10) {
      //       $selections = array('meron'=>false,'wala'=>true,'draw'=>false,);
      //       $bet = 'w';
      //     }
      //     array_push($data, ['fightnumber' => $i, 'bet'=>$bet,'id'=>23, 'amount'=>1, 'finalamount'=>100, 'selection'=>$selections]);
      // $maxwin2=expertbet::where('startingfight',161)->where('event_id',27)->orderBy('wins','DESC')->select('wins')->groupBy('wins')->get()->take($getactiveevent->numberofwinners)->count();
      // $maxwin2=expertbet::where('startingfight',161)->where('event_id',27)->update([
      //   'wins'=>0,
      //   'result'=>null,
      //   'claimed'=>null,
      //   'lose'=>null,
      //   'winner'=>0,
      // ]);
      // $maxwin2count=expertbet::where('startingfight',161)->where('event_id',27)->orderBy('wins','DESC')->groupBy('wins')->get()->take(2)->count();
      // $maxwin2=expertbet::where('startingfight',161)->where('event_id',27)->orderBy('wins','DESC')->select('wins')->max('wins'); ito gumagana
      // $maxwin2=expertbet::where('startingfight',161)->where('event_id',27)->orderBy('wins','DESC')->select('wins')->unique('wins')->get(); ito original
      // expertbet::where('event_id',23)->where('startingfight',161)->update([
      //   'event_id'=>27
      // ]);
      // return $maxwin2;
    }

    public function getdeclarators()
    {
      return User::where('group_id',auth()->user()->group_id)->where('role','!=',0)->where('role','!=',3)->where('role','!=',4)->where('role','!=',6)->where('role','!=',7)
      ->where('id','!=',auth()->user()->id)->latest()->get();
    }
    public function pastwinners()
    {
      $from = date('2021-12-5');
      $to = Carbon::today();
      $daysToAdd = 1;
      $date = $to->addDays($daysToAdd);
      // $get = DB::table('events as a')
      // ->whereBetween('a.fightdate', [$from, $to])
      //  ->join('expertbet as c', 'a.id', '=', 'c.event_id')
      //  ->join('users as b', 'c.user_id', '=', 'b.id')
      //  ->where('c.winner',1)
      //  ->orWhere('c.winner',2)
      //  ->where('c.wins', \DB::raw("(select max(`wins`) from expertbet)"))
      //   ->select('a.fightdate','b.name','c.wins','c.startingfight')
      //   ->get();
      // whereBetween('fightdate', [$from, $to])->
      $getevent = Event::whereBetween('fightdate', [$from, $date])->select('id','startingfight','fightdate')->latest()->get();


      $bets = array();
      foreach ($getevent as $key) {
        $getfirstwin = expertbet::with('user')->where('event_id',$key->id)->get()->max('wins');

        $get = DB::table('expertbet as a')
        ->where('a.winner',1)
        ->where('a.startingfight',$key->startingfight)
        ->where('a.event_id',$key->id)
        ->where('a.wins',$getfirstwin)
        ->join('users as c', 'a.user_id', '=', 'c.id')
        ->join('events as d', 'a.event_id', '=', 'd.id')
        ->select('a.updated_at','c.name','a.wins','a.id','d.fightdate')
        ->get();
        $count = DB::table('expertbet as a')
        ->where('a.winner',1)
        ->where('a.startingfight',$key->startingfight)
        ->where('a.event_id',$key->id)
        ->where('a.wins',$getfirstwin)
        // ->join('users as c', 'a.user_id', '=', 'c.id')
        // ->select('a.created_at','c.name','a.wins')
        ->count();

        if ($count) {
          // code...
          if ($getfirstwin===20) {
            $total = 100000/$count;
            $final = round($total, 2);
          }elseif ($getfirstwin===19) {
            $total = 5000/$count;
            $final = round($total, 2);
          }elseif ($getfirstwin===18) {
            $total = 2000/$count;
            $final = round($total, 2);
          }else {
            $total = 500/$count;
            $final = round($total, 2);
          }
          // $total = 500/$count;
          //   $final = round($total, 2);
        }else {
          $final = null;
        }
        // $final = number_format((float)$total, 2, '.', '');
        // $getbets = expertbet::with('user')->where('startingfight',$key->startingfight)->where('event_id',$key->id)->where('wins',$getfirstwin)->select('wins','startingfight','created_at','expertbet.user')->get();
        // array_push($bets, $get);
        foreach ($get as $keys) {
          array_push($bets,array('name'=>$keys->name,'created_at'=>$keys->updated_at,'winnings'=>$final,'id'=>$keys->id,'fightdate'=>$keys->fightdate,'wins'=>$keys->wins));
        }
      }
        return $bets;
    }
    public function pastwinners2()
    {
      $from = date('2021-12-1');
      $to = Carbon::today();
      $daysToAdd = 1;
      $date = $to->addDays($daysToAdd);
      // $get = DB::table('events as a')
      // ->whereBetween('a.fightdate', [$from, $to])
      //  ->join('expertbet as c', 'a.id', '=', 'c.event_id')
      //  ->join('users as b', 'c.user_id', '=', 'b.id')
      //  ->where('c.winner',1)
      //  ->orWhere('c.winner',2)
      //  ->where('c.wins', \DB::raw("(select max(`wins`) from expertbet)"))
      //   ->select('a.fightdate','b.name','c.wins','c.startingfight')
      //   ->get();
      // whereBetween('fightdate', [$from, $to])->
      $getevent = Event::whereBetween('fightdate', [$from, $date])->select('id','startingfight','fightdate')->latest()->get();


      $bets = array();
      foreach ($getevent as $key) {
        $getfirstwin = expertbet::with('user')->where('event_id',$key->id)->get()->max('wins');

        $get = DB::table('expertbet as a')
        ->where('a.winner',1)
        ->where('a.startingfight',$key->startingfight)
        ->where('a.event_id',$key->id)
        ->where('a.wins','!=',$getfirstwin)
        ->join('users as c', 'a.user_id', '=', 'c.id')
        ->join('events as d', 'a.event_id', '=', 'd.id')
        ->select('a.updated_at','c.name','a.wins','a.id','d.fightdate')
        ->get();
        // $count = DB::table('expertbet as a')
        // ->where('a.winner',1)
        // ->where('a.startingfight',$key->startingfight)
        // ->where('a.event_id',$key->id)
        // ->where('a.wins',$getfirstwin)
        // // ->join('users as c', 'a.user_id', '=', 'c.id')
        // // ->select('a.created_at','c.name','a.wins')
        // ->count();
          $counts = count($get);
        if ($counts) {
          // code...
          $total = 250/$counts;
          $final = round($total, 2);
        }else {
          $final = null;
        }
        // $final = number_format((float)$total, 2, '.', '');
        // $getbets = expertbet::with('user')->where('startingfight',$key->startingfight)->where('event_id',$key->id)->where('wins',$getfirstwin)->select('wins','startingfight','created_at','expertbet.user')->get();
        // array_push($bets, $get);
        foreach ($get as $keys) {
          array_push($bets,array('name'=>$keys->name,'created_at'=>$keys->updated_at,'winnings'=>$final,'id'=>$keys->id,'fightdate'=>$keys->fightdate,'wins'=>$keys->wins));
        }
      }
        return $bets;
    }
    public function pastwinners3()
    {
      $from = date('2021-12-5');
      $to = Carbon::today();
      $daysToAdd = 1;
      $date = $to->addDays($daysToAdd);
      // $get = DB::table('events as a')
      // ->whereBetween('a.fightdate', [$from, $to])
      //  ->join('expertbet as c', 'a.id', '=', 'c.event_id')
      //  ->join('users as b', 'c.user_id', '=', 'b.id')
      //  ->where('c.winner',1)
      //  ->orWhere('c.winner',2)
      //  ->where('c.wins', \DB::raw("(select max(`wins`) from expertbet)"))
      //   ->select('a.fightdate','b.name','c.wins','c.startingfight')
      //   ->get();
      // whereBetween('fightdate', [$from, $to])->
      $getevent = Event::whereBetween('fightdate', [$from, $date])->select('id','startingfight','fightdate')->latest()->get();


      $bets = array();
      foreach ($getevent as $key) {
        $getfirstwin = expertbet::with('user')->where('event_id',$key->id)->where('winner',3)->get()->max('wins');

        $get = DB::table('expertbet as a')
        ->where('a.winner',3)
        ->where('a.startingfight',$key->startingfight)
        ->where('a.event_id',$key->id)
        ->where('a.wins','=',$getfirstwin)
        ->join('users as c', 'a.user_id', '=', 'c.id')
        ->join('events as d', 'a.event_id', '=', 'd.id')
        ->select('a.updated_at','c.name','a.wins','a.id','d.fightdate')
        ->get();
        // $count = DB::table('expertbet as a')
        // ->where('a.winner',1)
        // ->where('a.startingfight',$key->startingfight)
        // ->where('a.event_id',$key->id)
        // ->where('a.wins',$getfirstwin)
        // // ->join('users as c', 'a.user_id', '=', 'c.id')
        // // ->select('a.created_at','c.name','a.wins')
        // ->count();
          $counts = count($get);
        if ($counts) {
          // code...
          $total = 150/$counts;
          $final = round($total, 2);
        }else {
          $final = null;
        }
        // $final = number_format((float)$total, 2, '.', '');
        // $getbets = expertbet::with('user')->where('startingfight',$key->startingfight)->where('event_id',$key->id)->where('wins',$getfirstwin)->select('wins','startingfight','created_at','expertbet.user')->get();
        // array_push($bets, $get);
        foreach ($get as $keys) {
          array_push($bets,array('name'=>$keys->name,'created_at'=>$keys->updated_at,'winnings'=>$final,'id'=>$keys->id,'fightdate'=>$keys->fightdate));
        }
      }
        return $bets;
    }
    public function lowestscore2()
    {
      $from = date('2021-12-5');
      $to = Carbon::today();
      $daysToAdd = 1;
      $date = $to->addDays($daysToAdd);

      $getevent = Event::whereBetween('fightdate', [$from, $date])->select('id','startingfight','fightdate')->latest()->get();

      $bets = array();

      foreach ($getevent as $key) {
        $getfirstwin = expertbet::with('user')->where('event_id',$key->id)->get()->min('wins');
        $get = expertbet::with('user')->with('selection')->with('event')
        ->where('winner',3)
        ->where('startingfight',$key->startingfight)
        ->where('event_id',$key->id)
        ->where('wins',$getfirstwin)
        ->get();

        $bet=array();

        foreach ($get as $keys) {
            $countselection = selection::where('expertbet_id',$keys->id)->where('selection','draw')->count();
              if ($countselection<=2) {
                array_push($bet,array('event_id'=>$keys->event_id,'drawcounts'=>$countselection,'wins'=>$keys->wins,'name'=>$keys->user->name,'created_at'=>$keys->updated_at,'id'=>$keys->id,'fightdate'=>$keys->event->fightdate));
              }else {
                // $checking = array();
                // foreach ($bet as $test) {
                //   array_push($checking,$test->event_id);
                // }
                if(!$bet)
                {
                  unset($bet);
                  $bet = array();
                  $getfirstwin2 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$keys->wins)->get()->min('wins');
                  $gets = expertbet::with('user')->with('selection')->with('event')
                  ->where('winner',3)
                  ->where('startingfight',$key->startingfight)
                  ->where('event_id',$key->id)
                  ->whereHas('selection', function($q)
                  {
                    $q->where('selection','=','Draw');
                  },'<=',2)
                  ->where('wins',$getfirstwin2)
                  ->get();
                  $counts = count($gets);
                  if ($counts) {
                    $totals = 100/$counts;
                    $finals = round($totals, 2);
                  }else {
                    $finals = null;
                  }
                  foreach ($gets as $data) {
                    $countselection = selection::where('expertbet_id',$data->id)->where('selection','draw')->count();
                    if ($countselection<=2) {
                      array_push($bet,array('event_id'=>$data->event_id,'drawcounts'=>$countselection,'wins'=>$data->wins,'name'=>$data->user->name,'created_at'=>$data->updated_at,'id'=>$data->id,'fightdate'=>$keys->event->fightdate));
                    }else {
                      // part 2

                    }
                  }
                }
              }

        }
        $count2 = count($bet);
        if ($count2) {
          // code...
          $total2 = 100/$count2;
          $final2 = round($total2, 2);
        }else {
          $final2 = null;
        }
        // return $bet;
        foreach ($bet as $data2) {

          array_push($bets,array('event_id'=>$data2['event_id'],'drawcounts'=>$data2['drawcounts'],'wins'=>$data2['wins'],'name'=>$data2['name'],'created_at'=>$data2['created_at'],'id'=>$data2['id'],'winnings'=>$final2,'fightdates'=>$data2['fightdate']));
        }
      }
        return $bets;
    }
    public function lowestscore()
    {
      $from = date('2021-12-5');
      $to = Carbon::today();
      $daysToAdd = 1;
      $date = $to->addDays($daysToAdd);

      $getevent = Event::whereBetween('fightdate', [$from, $date])->select('id','startingfight','fightdate')->latest()->get();



      $bets = array();

      foreach ($getevent as $key) {
        $getlowest = expertbet::with('user')->where('event_id',$key->id)->get()->min('wins');
        $getbets1 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest)->where('winner',3)->get();
        $bet = array();
        foreach ($getbets1 as $key1) {
          $counted = substr_count($key1->bet,"D");
          if ($counted <= 2) {
            array_push($bet,array('event_id'=>$key1->event->id,'date' => $key->fightdate ,'wins'=>$key1->wins,'name'=>$key1->user->name,'drawcounts'=>$counted,'id'=>$key1->id));
          }else {
            if (!$bet) {
              unset($bet);
              $bet = array();
              $getlowest2 = expertbet::with('user')->where('event_id',$key->id)->where('id','!=',$key1->id)->where('wins','!=',$key1->wins)->where('wins','!=',$key->wins)->get()->min('wins');
              $getbets2 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest2)->where('winner',3)->get();
              foreach ($getbets2 as $key2) {
                $counted2 = substr_count($key2->bet,"D");
                if ($counted2 <= 2) {
                  array_push($bet,array('event_id'=>$key2->event->id,'date' => $key->fightdate ,'wins'=>$key2->wins,'name'=>$key2->user->name,'drawcounts'=>$counted2,'id'=>$key2->id));
                }else {
                  if (!$bet) {
                  unset($bet);
                  $bet = array();
                  $getlowest3 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key2->wins)->where('id','!=',$key2->id)->where('wins','!=',$key1->wins)->where('wins','!=',$key->wins)->get()->min('wins');
                  $getbets3 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest3)->where('winner',3)->get();
                  foreach ($getbets3 as $key3) {
                    $counted3 = substr_count($key3->bet,"D");
                    if ($counted3<=2) {
                      array_push($bet,array('event_id'=>$key3->event->id,'date' => $key->fightdate ,'wins'=>$key3->wins,'name'=>$key3->user->name,'drawcounts'=>$counted3,'id'=>$key3->id));
                    }else {
                      if (!$bet) {
                        unset($bet);
                        $bet = array();
                        $getlowest4 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key3->wins)->where('id','!=',$key3->id)->where('id','!=',$key2->id)->where('wins','!=',$key1->wins)
                        ->where('wins','!=',$key->wins)->get()->min('wins');
                        $getbets4 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest4)->where('winner',3)->get();
                        foreach ($getbets4 as $key4) {
                          $counted4 = substr_count($key4->bet,"D");
                          if ($counted4<=2) {
                            array_push($bet,array('event_id'=>$key4->event->id,'date' => $key->fightdate ,'wins'=>$key4->wins,'name'=>$key4->user->name,'drawcounts'=>$counted4,'id'=>$key4->id));
                          }else {
                            if (!$bet) {
                              unset($bet);
                              $bet = array();
                              $getlowest5 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key4->wins)->where('id','!=',$key4->id)->get()->min('wins');
                              $getbets5 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest5)->where('winner',3)->get();
                              foreach ($getbets5 as $key5) {
                                $counted5 = substr_count($key5->bet,"D");
                                if ($counted5<=2) {
                                  array_push($bet,array('event_id'=>$key5->event->id,'date' => $key->fightdate ,'wins'=>$key5->wins,'name'=>$key5->user->name,'drawcounts'=>$counted5,'id'=>$key5->id));
                                }else {
                                  if (!$bet) {
                                    unset($bet);
                                    $bet = array();
                                    $getlowest6 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key5->wins)->where('id','!=',$key5->id)->get()->min('wins');
                                    $getbets6 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest6)->where('winner',3)->get();
                                    foreach ($getbets6 as $key6) {
                                      $counted6 = substr_count($key6->bet,"D");
                                      if ($counted6<=2) {
                                        array_push($bet,array('event_id'=>$key6->event->id,'date' => $key->fightdate ,'wins'=>$key6->wins,'name'=>$key6->user->name,'drawcounts'=>$counted6,'id'=>$key6->id));
                                      }else {
                                        if (!$bet) {
                                          unset($bet);
                                          $bet = array();
                                          $getlowest7 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key5->wins)->where('id','!=',$key6->id)->get()->min('wins');
                                          $getbets7 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest7)->where('winner',3)->get();
                                          foreach ($getbets7 as $key7) {
                                            $counted7= substr_count($key7->bet,"D");
                                            if ($counted7<=2) {
                                              array_push($bet,array('event_id'=>$key7->event->id,'date' => $key->fightdate ,'wins'=>$key7->wins,'name'=>$key7->user->name,'drawcounts'=>$counted7,'id'=>$key7->id));
                                            }else {
                                              // array_push($bet,array('event_id'=>'alex','date' => $key->fightdate ,'wins'=>$key7->wins,'name'=>$key7->user->name,'drawcounts'=>$counted7,'id'=>$key7->id));
                                            }
                                          }
                                        }
                                      }
                                    }
                                  }
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
                }
              }
            }
          }

        }
        $betfinal = array();

        $maxwin = collect($bet)->min('wins');

        foreach ($bet as $data2) {
          if ($maxwin==$data2['wins']) {
            array_push($betfinal,array('event_id'=>$data2['event_id'],'bet_id'=>$data2['id'],'wins'=>$data2['wins'],'drawcounts'=>$data2['drawcounts'],'fightdates'=>$data2['date'],'name'=>$data2['name']));
          }
        }
        $count2 = count($betfinal);
        if ($count2) {
          // code...
          if ($maxwin===0) {
            $total2 = 10000/$count2;
            $final3 = round($total2, 2);
          }elseif ($maxwin===1) {
            $total2 = 5000/$count2;
            $final3 = round($total2, 2);
          }elseif ($maxwin===2) {
            $total2 = 2000/$count2;
            $final3 = round($total2, 2);
          }else {
            $total2 = 100/$count2;
            $final3 = round($total2, 2);
          }

        }else {
          $final3 = null;
        }

        foreach ($betfinal as $data3) {
            array_push($bets,array('event_id'=>$data3['event_id'],'bet_id'=>$data3['bet_id'],'wins'=>$data3['wins'],'drawcounts'=>$data3['drawcounts'],'fightdates'=>$data3['fightdates'],'name'=>$data3['name'],'winnings'=>$final3));
        }
      }
        return $bets;
    }
    public function lowestleaders()
    {
      $from = Carbon::today();
      $to = Carbon::today();
      $daysToAdd = 1;
      $date = $to->addDays($daysToAdd);

      $getevent = Event::whereBetween('fightdate', [$from, $date])->select('id','startingfight','fightdate')->latest()->get();



      $bets = array();

      foreach ($getevent as $key) {
        $getlowest = expertbet::with('user')->where('event_id',$key->id)->get()->min('wins');
        $getbets1 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest)->where('winner',3)->get();
        $bet = array();
        foreach ($getbets1 as $key1) {
          $counted = substr_count($key1->bet,"D");
          if ($counted <= 2) {
            array_push($bet,array('event_id'=>$key1->event->id,'date' => $key->fightdate ,'wins'=>$key1->wins,'name'=>$key1->user->name,'drawcounts'=>$counted,'id'=>$key1->id,'startingfight'=>$key1->startingfight));
          }else {
            if (!$bet) {
              unset($bet);
              $bet = array();
              $getlowest2 = expertbet::with('user')->where('event_id',$key->id)->where('id','!=',$key1->id)->where('wins','!=',$key1->wins)->where('wins','!=',$key->wins)->get()->min('wins');
              $getbets2 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest2)->where('winner',3)->get();
              foreach ($getbets2 as $key2) {
                $counted2 = substr_count($key2->bet,"D");
                if ($counted2 <= 2) {
                  array_push($bet,array('event_id'=>$key2->event->id,'date' => $key->fightdate ,'wins'=>$key2->wins,'name'=>$key2->user->name,'drawcounts'=>$counted2,'id'=>$key2->id,'startingfight'=>$key2->startingfight));
                }else {
                  if (!$bet) {
                  unset($bet);
                  $bet = array();
                  $getlowest3 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key2->wins)->where('id','!=',$key2->id)->where('wins','!=',$key1->wins)->where('wins','!=',$key->wins)->get()->min('wins');
                  $getbets3 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest3)->where('winner',3)->get();
                  foreach ($getbets3 as $key3) {
                    $counted3 = substr_count($key3->bet,"D");
                    if ($counted3<=2) {
                      array_push($bet,array('event_id'=>$key3->event->id,'date' => $key->fightdate ,'wins'=>$key3->wins,'name'=>$key3->user->name,'drawcounts'=>$counted3,'id'=>$key3->id,'startingfight'=>$key3->startingfight));
                    }else {
                      if (!$bet) {
                        unset($bet);
                        $bet = array();
                        $getlowest4 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key3->wins)->where('id','!=',$key3->id)->where('id','!=',$key2->id)->where('wins','!=',$key1->wins)
                        ->where('wins','!=',$key->wins)->get()->min('wins');
                        $getbets4 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest4)->where('winner',3)->get();
                        foreach ($getbets4 as $key4) {
                          $counted4 = substr_count($key4->bet,"D");
                          if ($counted4<=2) {
                            array_push($bet,array('event_id'=>$key4->event->id,'date' => $key->fightdate ,'wins'=>$key4->wins,'name'=>$key4->user->name,'drawcounts'=>$counted4,'id'=>$key4->id,'startingfight'=>$key4->startingfight));
                          }else {
                            if (!$bet) {
                              unset($bet);
                              $bet = array();
                              $getlowest5 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key4->wins)->where('id','!=',$key4->id)->get()->min('wins');
                              $getbets5 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest5)->where('winner',3)->get();
                              foreach ($getbets5 as $key5) {
                                $counted5 = substr_count($key5->bet,"D");
                                if ($counted5<=2) {
                                  array_push($bet,array('event_id'=>$key5->event->id,'date' => $key->fightdate ,'wins'=>$key5->wins,'name'=>$key5->user->name,'drawcounts'=>$counted5,'id'=>$key5->id,'startingfight'=>$key5->startingfight));
                                }else {
                                  if (!$bet) {
                                    unset($bet);
                                    $bet = array();
                                    $getlowest6 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key5->wins)->where('id','!=',$key5->id)->get()->min('wins');
                                    $getbets6 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest6)->where('winner',3)->get();
                                    foreach ($getbets6 as $key6) {
                                      $counted6 = substr_count($key6->bet,"D");
                                      if ($counted6<=2) {
                                        array_push($bet,array('event_id'=>$key6->event->id,'date' => $key->fightdate ,'wins'=>$key6->wins,'name'=>$key6->user->name,'drawcounts'=>$counted6,'id'=>$key6->id,'startingfight'=>$key6->startingfight));
                                      }else {
                                        if (!$bet) {
                                          unset($bet);
                                          $bet = array();
                                          $getlowest7 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key5->wins)->where('id','!=',$key6->id)->get()->min('wins');
                                          $getbets7 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest7)->where('winner',3)->get();
                                          foreach ($getbets7 as $key7) {
                                            $counted7= substr_count($key7->bet,"D");
                                            if ($counted7<=2) {
                                              array_push($bet,array('event_id'=>$key7->event->id,'date' => $key->fightdate ,'wins'=>$key7->wins,'name'=>$key7->user->name,'drawcounts'=>$counted7,'id'=>$key7->id,'startingfight'=>$key7->startingfight));
                                            }else {
                                              // array_push($bet,array('event_id'=>'alex','date' => $key->fightdate ,'wins'=>$key7->wins,'name'=>$key7->user->name,'drawcounts'=>$counted7,'id'=>$key7->id));
                                            }
                                          }
                                        }
                                      }
                                    }
                                  }
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
                }
              }
            }
          }

        }
        $betfinal = array();

        $maxwin = collect($bet)->min('wins');

        foreach ($bet as $data2) {
          if ($maxwin==$data2['wins']) {
            array_push($betfinal,array('event_id'=>$data2['event_id'],'bet_id'=>$data2['id'],'wins'=>$data2['wins'],'drawcounts'=>$data2['drawcounts'],'fightdates'=>$data2['date'],'name'=>$data2['name'],'startingfight'=>$data2['startingfight']));
          }
        }

        foreach ($betfinal as $data3) {
            array_push($bets,array('event_id'=>$data3['event_id'],'bet_id'=>$data3['bet_id'],'wins'=>$data3['wins'],'drawcounts'=>$data3['drawcounts'],'fightdates'=>$data3['fightdates'],'name'=>$data3['name'],'startingfight'=>$data3['startingfight']));
        }
      }
        // $datalang = $bets->take(3);
        $getevent=Event::where('status',1)->first();
        $checking = Results::where('event_id',$getevent->id)->latest()->first();
        if ($checking->fightnumber>149) {
          // code...
          return collect($bet)->take(2);
        }else {
          return 'wala pang 149';
        }
    }
    public function getallpastbets()
    {
      return Event::where('status',2)->latest()->get();
    }
    public function index()
    {
      return Event::where('status',1)->first();
    }
    public function index2()
    {
      $add = User::where('id',auth()->user()->id)->first();
      if ($add->page) {
        // code...
        return Event::where('status',1)->where('id',$add->page)->first();
      }else {
        // code...
        return Event::where('status',1)->groupBy('event_name')->get();
      }
    }
    public function alluserx()
    {
      return User::with('group')->where('active',1)->latest()->paginate(10);
    }
    public function allusers2()
    {
      return User::where('active',1)->latest()->get();
    }
    public function allusersdeposit()
    {
      return User::where('active',1)->where('role','!=',1)->where('role','!=',0)->where('role','!=',5)->where('role','!=',7)->where('role','!=',6)->
      where('id','!=',auth()->user()->id)->where('group_id',auth()->user()->group_id)->latest()->get();
    }
    public function pager(Request $req)
    {
      $add = User::where('id',auth()->user()->id)->first();
      $add->page = $req['id'];
      $add->save();
    }
    public function back()
    {
      $add = User::where('id',auth()->user()->id)->first();
      $add->page = null;
      $add->save();
      return $add;
    }
    public function user()
    {
      return User::where('id',auth()->user()->id)->first();
    }
    public function updatestatus(Request $req)
    {
      $checkevents=Event::where('status',1)->first();

      $events = Event::where('id',$req['id'])->get();
      $array = array();
      foreach ($events as $key) {
        array_push($array, $key->event_name);
      }
      // $updatestatus->status=$req['status'];
      // $updatestatus->control='Closed';
      if ($req['status']==1) {
        $event = Event::whereIn('event_name',$array)->update([
          'status'=>1,
          'fightopened'=>Carbon::now(),
        ]);
        // $updatestatus->fightopened=Carbon::now();
      }elseif ($req['status']==2) {
      $event = Event::whereIn('event_name',$array)->where('status',1)->update([
          'status'=>2,
          'fightclosed'=>Carbon::now(),
        ]);
      }else{
        $event = Event::whereIn('event_name',$array)->where('status',1)->update([
            'status'=>0,
            'fightclosed'=>Carbon::now(),
          ]);
      }
      // $updatestatus->save();
      $userpage = User::where('page',$req->id)->get();
      foreach ($userpage as $user) {
        $data = User::findOrFail($user->id);
        $data->page=null;
        $data->save();
      }
      $createlogs = new Logs();
      $createlogs->type = 'Change_Status_Event';
      $createlogs->user_id = auth()->user()->id;
      if ($req['status']==0) {
        $status = 'Pending';
      }elseif ($req['status']==1) {
        $status = 'Active';
      }else {
        $status = 'Finished';
      }
      $createlogs->message = auth()->user()->name.' Changed the status of '.$array[0].' to '.$status.' status.';
      $createlogs->save();
      broadcast(new eventlistener($event))->toOthers();
      broadcast(new resultevent('Last','endevent',auth()->user()->name,'endevent','id',auth()->user()->name,'id','id'))->toOthers();
    }
    // public function updatestatus(Request $req)
    // {
    //   $checkevents=Event::where('status',1)->first();
    //
    //   if ($checkevents && $checkevents->status==1 && $req['status']==1) {
    //     return error;
    //   }else {
    //   $events = Event::where('id',$req['id'])->get();
    //   $array = array();
    //   foreach ($events as $key) {
    //     array_push($array, $key->event_name);
    //   }
    //   // $updatestatus->status=$req['status'];
    //   // $updatestatus->control='Closed';
    //   if ($req['status']==1) {
    //     $event = Event::whereIn('event_name',$array)->update([
    //       'status'=>1,
    //       'fightopened'=>Carbon::now(),
    //     ]);
    //     // $updatestatus->fightopened=Carbon::now();
    //   }elseif ($req['status']==2) {
    //   $event = Event::whereIn('event_name',$array)->where('status',1)->update([
    //       'status'=>2,
    //       'fightclosed'=>Carbon::now(),
    //     ]);
    //   }else{
    //     $event = Event::whereIn('event_name',$array)->where('status',1)->update([
    //         'status'=>0,
    //         'fightclosed'=>Carbon::now(),
    //       ]);
    //   }
    //   // $updatestatus->save();
    //   $userpage = User::where('page',$req->id)->get();
    //   foreach ($userpage as $user) {
    //     $data = User::findOrFail($user->id);
    //     $data->page=null;
    //     $data->save();
    //   }
    //   $createlogs = new Logs();
    //   $createlogs->type = 'Change_Status_Event';
    //   $createlogs->user_id = auth()->user()->id;
    //   if ($req['status']==0) {
    //     $status = 'Pending';
    //   }elseif ($req['status']==1) {
    //     $status = 'Active';
    //   }else {
    //     $status = 'Finished';
    //   }
    //   $createlogs->message = auth()->user()->name.' Changed the status of '.$array[0].' to '.$status.' status.';
    //   $createlogs->save();
    //   broadcast(new eventlistener($event))->toOthers();
    //   broadcast(new resultevent('Last','endevent',auth()->user()->name,'endevent','id',auth()->user()->name,'id','id'))->toOthers();
    //   }
    // }
    public function changepassword(Request $req)
    {
      $this->validate($req, [
        'oldpassword'=>'required',
        'newpassword'=>'required',
        'confirmpassword'=>'required',
      ]);
      $updatepassword = User::findOrFail(auth()->user()->id);
      $check = Hash::make($req['oldpassword']);
      if (Hash::check($req['oldpassword'], $updatepassword->password)) {
        if ($req['newpassword'] === $req['confirmpassword']) {
            $updatepassword->password = Hash::make($req['newpassword']);
            $updatepassword->save();
        }else {
          return error;
        }
      }else {
        return error;
      }

    }
    public function updateaccount(Request $req)
    {
      $this->validate($req, [
        'name'=>'required',
        'email'=>'required',
        'pnumber'=>'required|digits:11|unique:users',
      ]);
      $updateAccount = User::findOrFail(auth()->user()->id);
      $updateAccount->name = $req['name'];
      $updateAccount->email = $req['email'];
      $updateAccount->pnumber = $req['pnumber'];
      $updateAccount->save();

      $newlog= new Logs();
      $newlog->type = 'Change_Account_Details';
      $newlog->user_id = auth()->user()->id;
      $newlog->message = auth()->user()->name.' Changed Account details';
      $newlog->save();

    }
    public function edituser(Request $req)
    {
      $this->validate($req, [
        'name'=>'required',
        'username'=>'required',
        'email'=>'required',
        'role'=>'required',
        'group_id'=>'required',
      ]);
      $edituser = User::findOrFail($req['id']);
      $edituser->name = $req['name'];
      $edituser->username = $req['username'];
      $edituser->email = $req['email'];
      $edituser->role = $req['role'];
      $edituser->group_id = $req['group_id'];
      if ($req['password']) {
        $edituser->password = Hash::make($req['password']);
      }
      $edituser->save();

      $createlogs = new Logs();
      $createlogs->type = 'Update_User_Details';
      $createlogs->user_id = auth()->user()->id;
      if ($req['role']==1) {
        $position = 'Admin';
      }elseif ($req['role']==0) {
        $position = 'Teller';
      }elseif ($req['role']==4) {
        $position = 'Cashier';
      }elseif ($req['role']==3) {
        $position = 'Mobile Player';
      }
      elseif ($req['role']==5) {
        $position = 'Declarator';
      }
      $createlogs->message = auth()->user()->username.' Updated user '.$position.' '.$req['username'].'.';
      $createlogs->save();

    }
    public function adduser(Request $req)
    {
      $this->validate($req, [
        'name'=>'required',
        'username'=>'required',
        'email'=>'required',
        'role'=>'required',
        'group_id'=>'required',
        'password'=>'required',
      ]);
      $adduser = new User();
      $adduser->name = $req['name'];
      $adduser->username = $req['username'];
      $adduser->email = $req['email'];
      $adduser->role = $req['role'];
      $adduser->password = Hash::make($req['password']);
      $adduser->group_id =$req['group_id'];
      $adduser->save();

      $createlogs = new Logs();
      $createlogs->type = 'Create_New_User';
      $createlogs->user_id = auth()->user()->id;
      if ($req['role']==1) {
        $position = 'Admin';
      }elseif ($req['role']==0) {
        $position = 'Teller';
      }elseif ($req['role']==4) {
        $position = 'Cashier';
      }elseif ($req['role']==3) {
        $position = 'Mobile Player';
      }
      elseif ($req['role']==5) {
        $position = 'Declarator';
      }
      elseif ($req['role']==6) {
        $position = 'CSR';
      }
      elseif ($req['role']==7) {
        $position = 'Boss/Manager';
      }
      $createlogs->message = auth()->user()->username.' Created user '.$position.' '.$req['username'].'.';
      $createlogs->save();

    }
    public function editevent(Request $req)
    {
      $this->validate($req, [
        'event_name'=>'required',
        // 'pick'=>'required',
        'fights'=>'required',
        'arena'=>'required',
        // 'rake'=>'required',
        'fightdate'=>'required',
        // 'amount'=>'required',
        // 'jackpot'=>'required',
        // 'pjackpot'=>'required',
      ]);
        $editevent = Event::where('id',$req['id'])->first();
        // $editevent->event_name = $req['event_name'];
        $allevents = Event::where('event_name',$editevent->event_name)->update([
          'event_name' => $req['event_name'],
          'fights' => $req['fights'],
          'venue' => $req['arena'],
          'fightdate' => $req['fightdate'],
        ]);
        // $editevent->pick = $req['pick'];
        // $editevent->fights = $req['fights'];
        // $editevent->amount = $req['amount'];
        // $editevent->rake = $req['rake'];
        // $editevent->jackpot = $req['jackpot'];
        // $editevent->pjackpot = $req['pjackpot'];
        // $editevent->venue = $req['arena'];
        // $editevent->fightdate = $req['fightdate'];
        // $editevent->save();

        $createlogs = new Logs();
        $createlogs->type = 'Update_Event';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = auth()->user()->username.' Updated '.$req['event_name'].'.';
        $createlogs->save();


    }
    public function getpast()
    {
      // return Event::where('status',0)->latest()->get();
      return Event::where('status',0)->groupBy('event_name')->get();
    }
    public function addeventx(Request $req)
    {
      $this->validate($req, [
        'event_name'=>'required|unique:events',
        // 'pick'=>'required',
        'fights'=>'required',
        'arena'=>'required',
        // 'rake'=>'required',
        'fightdate'=>'required',
        'startingfight'=>'required',
        // 'amount'=>'required',
        // 'jackpot'=>'required',
        // 'pjackpot'=>'required',
      ]);
        $newevent = new Event();
        $newevent->event_name = $req['event_name'];
        // $newevent->pick = $req['pick'];
        $newevent->fights = $req['fights'];
        $newevent->currentfight = 0;
        $newevent->startingfight = $req['startingfight'];
        // $newevent->amount = $req['amount'];
        $newevent->control = 'Closed';
        $newevent->status = 0;
        // $newevent->rake = $req['rake'];
        // $newevent->jackpot = $req['jackpot'];
        // $newevent->pjackpot = $req['pjackpot'];
        $newevent->venue = $req['arena'];
        $newevent->fightdate = $req['fightdate'];
        $newevent->save();

        // create logs
        $createlogs = new Logs();
        $createlogs->type = 'Create_New_Event';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = auth()->user()->username.' Create '.$req['event_name'].'.';
        $createlogs->save();
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
    public function reopen(Request $req)
    {
        $reopen = Event::where('startingfight',$req['startingfight'])->where('id',$req['event_id'])->first();
        $reopen->Control = 'Open';
        $reopen->save();
        // create logs
        $createlogs = new Logs();
        $createlogs->type = 'Confirmed_Reopen_Fight';
        $createlogs->user_id = auth()->user()->id;
        $getuser1 = User::findOrFail($req['user_id']);
        $createlogs->message = auth()->user()->username.' Confirmed '.$getuser1->username.' for reopen betting, for startingfight number '.$req['startingfight'];
        $createlogs->save();
        broadcast(new eventlistener($reopen))->toOthers();
        broadcast(new resultevent('Last',$req['startingfight'],auth()->user()->name,'successreopen','id',auth()->user()->id,'id','id'))->toOthers();

    }
    public function getreceipt(Request $req)
    {
        return expertbet::with('selection')->where('id', $req['id'])->where('user_id',auth()->user()->id)->first();
    }
    public function getalltransactions(Request $req)
    {
        // $payouts=Potmoney::where('event_id',$req['id'])->get();
        // $bet=bet::where('event_id',$req['id'])->where('claimed',1)->get();
        // $bet=bet::where('event_id',$req['id'])->where('claimed',1)->get();
        $transactions=Transactions::where('event_id',$req['id'])->where('user_id',auth()->user()->id)->latest()->get();
        // $result = array_merge($payouts, $bet);
        return $transactions;
    }
    public function getalltransactionhistory()
    {
        $active = Event::where('status',1)->first();
        $transactions=Transactions::where('event_id',$active->id)->where('user_id',auth()->user()->id)->latest()->paginate(10);
        return $transactions;
    }
    public function geteventsformonitoring()
    {
        return Event::latest()->get();
    }
    public function transactions()
    {
        return view('/transactions');
    }
    public function allevents()
    {
      $data = Event::where('status',1)->whereHas('bets', function($q)
      {
        $q->where('user_id','like', auth()->user()->id);

      })->latest()->get();
        return $data;
    }
    public function alleventstrans()
    {
      $data = Event::whereHas('transactions', function($q)
      {
        $q->where('user_id','like', auth()->user()->id);

      })->latest()->get();
        return $data;
    }
    public function transferfunds()
    {
      if (Auth::check()) {
        if (auth()->user()->role===0 || auth()->user()->role===3 && Auth::check()) {
          return view('/transferfunds');
        }else{
          return redirect('home');
        }
      }else {
        return redirect('home');
      }
    }
    public function withdrawal()
    {
      if (auth()->user()->role===0 || auth()->user()->role===3) {
        return view('/withdrawalpage');
      }else{
        return view('/home');
      }
    }
    public function bethistoryx()
    {
      if (Auth::check()) {
        if (auth()->user()->role===0 || auth()->user()->role===3 && Auth::check()) {
          return view('/historybets');
        }else{
          return redirect('home');
        }
      }else {
        return redirect('home');
      }
    }
    public function viewpending()
    {
      if (Auth::check()) {
        if (auth()->user()->role===0 || auth()->user()->role===3) {
          return view('/pendingbets');
        }else{
          return redirect('home');
        }
      }else {
       return redirect('home');
      }
    }
    public function viewhistory()
    {
        return view('/historybets');
    }
    public function closefight(Request $req)
    {
      $this->validate($req, [
        'control'=>'required',
      ]);
      $data = Event::where('status',1)->first();
      $data->control='Close';
      $data->save();
      // create logs

      $createlogs = new Logs();
      $createlogs->type = 'Close_Fight';
      $createlogs->user_id = auth()->user()->id;
      $createlogs->message = auth()->user()->name.' Closed betting for starting fight number '.$req['startingfight'].'.';
      $createlogs->save();
      // send event
      broadcast(new eventlistener($data))->toOthers();
      broadcast(new resultevent('Last','Last','Last','eventupdate','id',auth()->user()->id,'id','id'))->toOthers();
      // broadcast(new event($data))->toOthers();
    }
    public function lastcall(Request $req)
    {
      $this->validate($req, [
        'control'=>'required',
      ]);
      $data = Event::where('status',1)->first();
      $data->control='Last';
      $data->save();
      // create logs

      $createlogs = new Logs();
      $createlogs->type = 'Last_Call';
      $createlogs->user_id = auth()->user()->id;
      $createlogs->message = auth()->user()->name.' initiate last call for starting fight number '.$req['startingfight'].'.';
      $createlogs->save();

      broadcast(new eventlistener($data))->toOthers();
      broadcast(new resultevent('Last','Last','Last','eventupdate','id',auth()->user()->id,'id','id'))->toOthers();
    }
    public function updateevent(Request $req)
    {
      $this->validate($req, [
        'id' => 'required|max:255',
        'event_name' => 'required|max:255',
        'fights'=> 'required',
        'startingfight'=>'required',
        'status'=>'required',
        // 'control'=>'required',
      ]);
      if ($req['control']==='Reopen') {
        // create Logs
        $createlogs = new Logs();
        $createlogs->type = 'Requested Reopen Fight';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = auth()->user()->name.' requested reopen fight for fight number '.$req['startingfight'].'.';
        $createlogs->save();
        // send event
        broadcast(new resultevent('reopen',$req['startingfight'],auth()->user()->name,'reopenfight',$req['id'],auth()->user()->id,'id','id'))->toOthers();
      }else {
      $compare = Event::where('status',1)->first();
      $data = Event::findOrFail($req->id);
      $data->event_name=$req['event_name'];
      $data->fights=$req['fights'];
      $checker = $compare->pick - 1;
      $computedfights=$compare->fights - $checker;
      $computed2fights=$compare->startingfight + $checker;
      $test=$compare->staringfight;
      if (empty($compare->startingfight)&& $req['startingfight']>0 && $req['startingfight'] <= $computedfights) {
        $data->startingfight=$req['startingfight'];
      }
      elseif($req['startingfight'] > $computed2fights && $req['startingfight'] <= $computedfights )
      {
        $data->startingfight=$req['startingfight'];
      }
      else {
        return error;
      }
      $data->status=$req['status'];
      $data->control=$req['control'];
      $data->save();
      // create logs
      $createlogs = new Logs();
      $createlogs->type = 'Moved_Fight';
      $createlogs->user_id = auth()->user()->id;
      $createlogs->message = auth()->user()->name.' moved fight number to '.$req['startingfight'].'.';
      $createlogs->save();

      broadcast(new eventlistener($data))->toOthers();
      broadcast(new resultevent('Last','Last','Last','eventupdate','id',auth()->user()->id,'id','id'))->toOthers();
      return $data;
        }
    }
    public function selected(Request $req)
    {
        // $awd = Prebet::where('user_id',$req['user_id'])->delete();

        $this->validate($req, [
          'start' => 'required|max:255',
          'selection' => 'required|max:255',
          // 'amount'=> 'required',
          'user_id'=>'required'
        ]);
        // $getactiveevent =Event::where('status',1)->first();
        $getcontrol = control::first();
          // code...
          // $data = "";
          // $num_cols = 2;
          $num_rows = $req['start']+$getcontrol->pick+2; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
          // $a=array('fightnumber'=>'','selection'=>'','amount'=>'');
          // $a=array('fightnumber'=>'','selection'=>'');
          $data = array();
          $selection = array('meron'=>true,'wala'=>false,'draw'=>false,);

          for ($i = $req['start']; $i < $num_rows; $i++)  {
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
            array_push($data, ['fightnumber' => $i, 'bet'=>'M','id'=>$req['id'],'amount'=>1, 'finalamount'=>0, 'selection'=>$selection]);
          }
       // return Prebet::where('user_id',$req['user_id'])->get();
       return $data;
    }
    // public function selected(Request $req)
    // {
    //     // $awd = Prebet::where('user_id',$req['user_id'])->delete();
    //
    //     $this->validate($req, [
    //       'start' => 'required|max:255',
    //       'selection' => 'required|max:255',
    //       // 'amount'=> 'required',
    //       'user_id'=>'required'
    //     ]);
    //     $getactiveevent =Event::where('status',1)->first();
    //       // code...
    //       // $data = "";
    //       // $num_cols = 2;
    //       $num_rows = $req['start']+$getactiveevent->pick; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
    //       // $a=array('fightnumber'=>'','selection'=>'','amount'=>'');
    //       $a=array('fightnumber'=>'','selection'=>'');
    //       $data = array();
    //
    //       for ($i = $req['start']; $i < $num_rows; $i++)  {
    //         // part 1 legit original
    //         // $data= new Prebet();
    //         // $data->selection="Meron";
    //         // $data->amount=$req['amount'];
    //         // $data->fightnumber=$i;
    //         // $data->user_id=$req['user_id'];
    //         // $data->save();
    //         // end of part 1 legit original
    //         // testing lang to
    //         // ito naman ay no need database
    //         array_push($data, ['selection' => 'Meron', 'fightnumber' => $i, ]);
    //       }
    //    // return Prebet::where('user_id',$req['user_id'])->get();
    //    return $data;
    // }

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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Events  $events
     * @return \Illuminate\Http\Response
     */
    public function show(Events $events)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Events  $events
     * @return \Illuminate\Http\Response
     */
    public function edit(Events $events)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Events  $events
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Events $events)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Events  $events
     * @return \Illuminate\Http\Response
     */
    public function destroy(Events $events)
    {
        //
    }
}
