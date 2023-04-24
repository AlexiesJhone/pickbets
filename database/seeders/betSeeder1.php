<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\expertbet;
use App\Models\startingfights;
use App\Models\Event;
use App\Models\Potmoney;
use App\Models\Logs;
use App\Models\User;
use App\Models\control;
use App\Models\Prebet;
use App\Models\selection;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Events\betevent;
use App\Jobs\insertbet;

class betSeeder1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      for ($alex = 1; $alex < 200000; $alex++)  {
        $userid = 14;
        $userko = User::findOrFail($userid);
        $getcontrol = control::first();
        $getactiveevent = control::first();
          // code...
          // $data = "";
          // $num_cols = 2;
          $num_rows = 161+$getcontrol->pick; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
          // $a=array('fightnumber'=>'','selection'=>'','amount'=>'');
          // $a=array('fightnumber'=>'','selection'=>'');
          $req = array();
          $selection = array('meron'=>true,'wala'=>false,'draw'=>false,);
          $array = ['Draw','Meron','Meron','Meron','Meron','Meron','Wala','Wala','Wala','Wala','Wala'];
          // $data=Arr::random($array);

          $num_rows = 161+$getactiveevent->pick; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
          $a=array('fightnumber'=>'','selection'=>'');
          $data = array();

            for ($i = 161; $i < $num_rows; $i++)  {
              $getnumber =  mt_rand(0, 10);
              $selections = null;
              $bet = null;
              if ($getnumber===0) {
                $selections = array('meron'=>false,'wala'=>false,'draw'=>true,);
                $bet = 'D';
              }elseif ($getnumber >= 1 && $getnumber <= 5) {
                $selections = array('meron'=>true,'wala'=>false,'draw'=>false,);
                $bet = 'M';
              }elseif ($getnumber >= 6 && $getnumber <= 10) {
                $selections = array('meron'=>false,'wala'=>true,'draw'=>false,);
                $bet = 'w';
              }
              array_push($data, ['fightnumber' => $i, 'bet'=>$bet,'id'=>27, 'amount'=>1, 'finalamount'=>100, 'selection'=>$selections]);
            }

          $confirm = Event::where('status',1)->where('startingfight',$data[0]['fightnumber'])->where('control','Open')->latest()->first();
        // $confirm2 = startingfights::where('event_id',$confirm->id)->where('startingfight',$data['data'][0]['fightnumber'])->first();
        if ($confirm) {
          $getpotmoney=Potmoney::where('startingfight',$data[0]['fightnumber'])->where('event_id',$confirm->id)->latest()->first();
          $getactiveevent =Event::where('status',1)->where('id',$data[0]['id'])->first();
          if ($getpotmoney) {
          $getpotmoney->amount=$getpotmoney->amount+$data[0]['finalamount'];
          // $getpotmoney->save();
          }else {
            $getpotmoney= new Potmoney();
            $getpotmoney->amount=$data[0]['finalamount'];
            $getpotmoney->event_id=$getactiveevent->id;
            $getpotmoney->startingfight=$data[0]['fightnumber'];
            // $getpotmoney->save();
          }
          $cashiermoney = User::findOrFail($userid);
          if ($cashiermoney->role==3) {
            // deduct cash to player
            $deductcash = User::findOrFail($userid);
            $checkifmoneyisgood = $deductcash->cash - $data[0]['finalamount'];
            if ($checkifmoneyisgood>=0) {
              $deductcash->cash =$deductcash->cash - $data[0]['finalamount'];
              $deductcash->save();
              $getpotmoney->save();
              // broadcast(new userupdate(auth()->user()->id));
            }else {
              return error;
            }

          }else{
            // add cash to cashier
            if ($cashiermoney===0.000) {
              $newmoney = User::findOrFail($userid);
              $newmoney->cash = $data[0]['finalamount'];
              $newmoney->save();
              $getpotmoney->save();
              // broadcast(new userupdate(auth()->user()->id));
            }else {
                $newmoney = User::findOrFail($userid);
                $newmoney->cash = $newmoney->cash+$data[0]['finalamount'];
                $newmoney->save();
                $getpotmoney->save();
                // broadcast(new userupdate(auth()->user()->id));
            }
          }
          // generate barcode
          $control = control::first();
          $doublecheckmoney=Potmoney::where('startingfight',$data[0]['fightnumber'])->where('event_id',  $confirm->id)->latest()->first();
          $getbarcode= expertbet::where('event_id',23)->latest()->first();
          $bet = new expertbet();
          $bet->user_id=$userid;
          if ($getbarcode) {
            $bet->barcode=$getbarcode->barcode=$getbarcode->barcode+1;
            // $bet->potmoney_id=$doublecheckmoney->id;
            $bet->startingbalance=$userko->cash;
            $bet->event_id=$getactiveevent->id;
            $bet->turn=$control->pick;
            $bet->amount=$data[0]['finalamount'];
            $bet->startingfight=$data[0]['fightnumber'];
            $bet->save();
          }else {
            $time = Carbon::now()->timestamp;
            $bet->barcode=substr($time, -6);
            // if (auth()->user()->role===3) {
              // $bet->owner=auth()->user()->name;
            // }
            $bet->turn=$control->pick;
            // $bet->potmoney_id=$doublecheckmoney->id;
            $bet->startingbalance=$userko->cash;
            $bet->event_id=$getactiveevent->id;
            $bet->amount=$data[0]['finalamount'];
            $bet->startingfight=$data[0]['fightnumber'];
            $bet->save();
          }
          foreach ($data as $key) {
            $data2 = expertbet::findOrFail($bet->id);
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
                $data->user_id=$userid;
                $data->startingfight=161;
                $data->expertbet_id=$bet->id;
                $data->save();
              }
              if ($key['selection']['wala']) {
                $data= new selection();
                // $data->bet=$key['bet'];
                $data->event_id=$getactiveevent->id;
                $data->fightnumber=$key['fightnumber'];
                $data->selection='Wala';
                $data->user_id=$userko->id;
                $data->startingfight=161;
                $data->expertbet_id=$bet->id;
                $data->save();
              }
              if ($key['selection']['draw']) {
                $data= new selection();
                // $data->bet=$key['bet'];
                $data->event_id=$getactiveevent->id;
                $data->fightnumber=$key['fightnumber'];
                $data->selection='Draw';
                $data->user_id=$userko->id;
                $data->startingfight=161;
                $data->expertbet_id=$bet->id;
                $data->save();
              }

          }
          // return expertbet::where('id',$bet->id)->with('selection')->first();
        }else {
          return ['error'=>'this starting fight is not available'];
        }
      }
    }
}
