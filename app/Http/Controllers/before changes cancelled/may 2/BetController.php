<?php

namespace App\Http\Controllers;

use App\Models\Prebet;
use App\Events\userupdate;
use App\Models\bet;
use App\Models\control;
use App\Models\startingfights;
use App\Models\Logs;
use App\Models\User;
use App\Events\betevent;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Arr;
use App\Models\Event;
use App\Models\Potmoney;
use App\Models\selection;
use App\Models\expertbet;
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
    public function userbet()
    {
        // Prebet::where('user_id',auth()->user()->id)->get();
    }
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

      $num_rows = $req['start']+$getcontrol->pick+3;
      $a=array('fightnumber'=>'','selection'=>'');
      $data = array();

        for ($i = $req['start']; $i < $num_rows; $i++)  {
          if ($checkif2draws<2) {
            $getnumber =  mt_rand(0, 10);
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

          $confirm = Event::where('status',1)->where('startingfight',$req['start'])->where('control','Open')->orWhere('control','Last Call')->latest()->first();

          // $confirm2 = startingfights::where('event_id',$confirm->id)->where('startingfight',$req['data'][0]['fightnumber'])->first();

            // code...

          if ($confirm) {
            $getpotmoney=Potmoney::where('startingfight',$req['start'])->where('event_id',$confirm->id)->latest()->first();
            $getactiveevent =Event::where('status',1)->where('id',$req['id'])->first();
            if ($getpotmoney) {
            $getpotmoney->amount=$getpotmoney->amount+$getcontrol->amount;
            // $getpotmoney->save();
            }else {
              $getpotmoney= new Potmoney();
              $getpotmoney->amount=$getcontrol->amount;
              $getpotmoney->event_id=$getactiveevent->id;
              $getpotmoney->startingfight=$req['start'];
              // $getpotmoney->save();
            }
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
              do {
				            $code = Carbon::now()->timestamp;
                    $barcoded = auth()->user()->id.'-'.substr($code, -6);


               } while (expertbet::where("barcode", $barcoded)->first());
               $bet->barcode=$barcoded;
              // $bet->potmoney_id=$doublecheckmoney->id;
              $bet->startingbalance=auth()->user()->cash;
              $bet->event_id=$getactiveevent->id;
              $bet->turn=$control->pick;
              $bet->amount=$getcontrol->amount;
              $bet->startingfight=$req['start'];
              $bet->save();

            $selections = array();
			$data2 = expertbet::where('id', $bet->id)->firstOrFail();
            foreach ($data as $key) {
              if (!$data2->bet) {
                if (strlen($key['bet'])>1) {
                  $data2->bet='['.$key['bet'].']';
                  $data2->save();
                }else {
                  $data2->bet = $key['bet'];
                  $data2->save();
                }
              }else {
                if (strlen($key['bet'])>1) {
                  $data2->bet=$data2->bet.'['.$key['bet'].']';
                  $data2->save();
                }else {
                  $data2->bet = $data2->bet.$key['bet'];
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
            $this->dispatch(new insertbet($finalamount,$req['start']));

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
        $query->where('user_id', auth()->user()->id)->where('winner',0);
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
        //   $active = Event::where('status',1)->latest()->first();
        // return expertbet::where('event_id', $active->id)->where('user_id',auth()->user()->id)->where('winner',0)->orderBy("startingfight", 'desc')->paginate(10);
      $data = selection::where('expertbet_id',$id)->select('selection','fightnumber')->get();
        return $data;
        // original
        // return bet::where('event_id', $active->id)->where('user_id',auth()->user()->id)->where('winner',0)->orderBy("startingfight", 'desc')->paginate(10);
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
      // $try = DB::table('events as a')
      //   ->where('a.status',1)
      //   ->join('expertbet as c', 'a.id', '=', 'c.event_id')
      //   ->where('c.winner','!=',0)->where('user_id',auth()->user()->id)
      //   ->select('c.startingfight','c.bet','c.amount','c.wins','c.winner','c.result','c.lose')
      //   ->orderBy("startingfight", 'desc')
      //   ->orderBy("wins", 'desc')
      //   ->paginate(10);
      //11$try = expertbet::with('event')
        //11->where('user_id',auth()->user()->id)
      //11  ->where('winner','!=',0)
        // ->select('a.*')
        // ->join('expertbet as c', 'a.id', '=', 'c.event_id')
        // ->where('c.winner','!=',0)->where('user_id',auth()->user()->id)
        // ->orderBy("startingfight", 'desc')
        // ->orderBy("wins", 'desc')
      //  ->latest()11
        // ->paginate(10); 11
        //   $active = Event::where('status',1)->latest()->first();
        // return expertbet::where('event_id', $active->id)->where('user_id',auth()->user()->id)->where('winner','!=',0)->orderBy("startingfight", 'desc')->paginate(10);
        // return $try; 11
        //   $active = Event::where('status',1)->latest()->first();
        // return expertbet::where('event_id', $active->id)->where('user_id',auth()->user()->id)->where('winner','!=',0)->orderBy("startingfight", 'desc')->paginate(10);
        // return $try;

        $posts = Event::with(['expertbet' => function ($query) {
        $query->where('user_id', auth()->user()->id)->where('winner','!=',0);
        }])->whereHas('expertbet', function ($query) {
            $query->where('user_id', auth()->user()->id)->where('winner','!=',0);
        })->latest()->paginate(5);

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
      if ($bilangin>2) {
        return ['error'=>'You have more than 2 draws, please make sure your pick has 2 or less than 2 draws'];
      }

      $confirm = Event::where('status',1)->where('startingfight',$req['data'][0]['fightnumber'])->where('control','Open')->orWhere('control','Last Call')->latest()->first();

      // $confirm2 = startingfights::where('event_id',$confirm->id)->where('startingfight',$req['data'][0]['fightnumber'])->first();
      if ($confirm) {
        $getpotmoney=Potmoney::where('startingfight',$req['data'][0]['fightnumber'])->where('event_id',$confirm->id)->latest()->first();
        $getactiveevent =Event::where('status',1)->where('id',$req['data'][0]['id'])->first();
        if ($getpotmoney) {
        $getpotmoney->amount=$getpotmoney->amount+$req['data'][0]['finalamount'];
        // $getpotmoney->save();
        }else {
          $getpotmoney= new Potmoney();
          $getpotmoney->amount=$req['data'][0]['finalamount'];
          $getpotmoney->event_id=$getactiveevent->id;
          $getpotmoney->startingfight=$req['data'][0]['fightnumber'];
          // $getpotmoney->save();
        }
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
          do {
            $code = Carbon::now()->timestamp;
            $barcoded = auth()->user()->id.'-'.substr($code, -6);
           } while (expertbet::where("barcode", "=", $barcoded)->first());
           $bet->barcode=$barcoded;
          // $bet->barcode=substr($time, -6);
          // if (auth()->user()->role===3) {
            // $bet->owner=auth()->user()->name;
          // }
          $bet->turn=$control->pick;
          // $bet->potmoney_id=$doublecheckmoney->id;
          $bet->startingbalance=auth()->user()->cash;
          $bet->event_id=$getactiveevent->id;
          $bet->amount=$req['data'][0]['finalamount'];
          $bet->startingfight=$req['data'][0]['fightnumber'];
          $bet->save();
        // $data2 = expertbet::with('selection')->where($req['data'][0]['id']);
        $selections = array();
        foreach ($req->data as $key) {
          $data2 = expertbet::where('id', $bet->id)->firstOrFail();
          if (!$data2->bet) {
            if (strlen($key['bet'])>1) {
              $data2->bet='['.$key['bet'].']';
              $data2->save();
            }else {
              $data2->bet = $key['bet'];
              $data2->save();
            }
          }else {
            if (strlen($key['bet'])>1) {
              $data2->bet=$data2->bet.'['.$key['bet'].']';
              $data2->save();
            }else {
              $data2->bet = $data2->bet.$key['bet'];
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
        $amount1 = $req['data'][0]['finalamount'] * $rake ;
        $amount2 = $req['data'][0]['finalamount'] * $rake2 ;
        $finalamount = $req['data'][0]['finalamount'] - $amount1 - $amount2;
        $this->dispatch(new insertbet($finalamount,$req['data'][0]['fightnumber']));
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
            $this->dispatch(new insertbet($finalamount,$req['data'][0]['fightnumber']));
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

      $getactiveevent = control::first();;
      // $getactiveevent =Event::where('status',1)->first();

      // ito ung original
      // $array = ['Meron','Wala'];
      // ito ung may draw
      $checkif2draws = 0;
      $array = ['Draw','Meron','Meron','Meron','Meron','Meron','Wala','Wala','Wala','Wala','Wala'];
      // $data=Arr::random($array);

      $num_rows = $req['start']+$getactiveevent->pick+3; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
      $a=array('fightnumber'=>'','selection'=>'');
      $data = array();

        for ($i = $req['start']; $i < $num_rows; $i++)  {
          if ($checkif2draws<2) {
            $getnumber =  mt_rand(0, 10);
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
