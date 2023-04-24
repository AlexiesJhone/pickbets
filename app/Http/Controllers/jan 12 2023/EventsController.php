<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Events\eventlistener;
use App\Events\usersession;
use App\Models\Prebet;
use App\Models\Potmoney;
use App\Models\derby;
// use App\Models\derbyexport;
use App\Events\announcement;
use App\Models\bet;
use App\Jobs\transferpastbets;
use App\Models\User;
use App\Models\Logs;
use App\Models\Results;
use App\Models\expertbet;
use App\Models\selection;
use App\Models\control;
use App\Models\past_expertbet;
use App\Models\Group;
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
use App\Imports\UsersImport;
use App\Exports\derbyexport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getmygroup()
    {
      return Group::where('id',auth()->user()->group_id)->first();
    }
    public function control()
    {
      return control::first();
    }
	public function addpin(Request $req)
    {
      $this->validate($req, [
		'pin'=>'required|max:4|unique:users,pin,'.$req['pin']
      ]);

      $updatepin = User::where('id',$req['id'])->update(['pin'=>$req['pin']]);
      $updatepin = User::where('id',$req['id'])->first();
      $createlogs = new Logs();
      $createlogs->user_id = auth()->user()->id;
      $createlogs->type = 'Updated_Pin';
      $createlogs->message = auth()->user()->username.' Updated '.$updatepin->username.' Pin to '.$req['pin'].'.';
      $createlogs->save();
	  return $updatepin;
    }
    public function downloadexcelsample($id1,$id2)
    {
      // return Excel::import(new UsersImport(),'');
      // return (new derbyexport)->download('invoices.xlsx');
       // return $id1.' and '.$id2;
       return Excel::download(new derbyexport($id1,$id2), 'esffsefesf.xlsx');
    }
    public function derbyprogram(Request $req)
    {
      derby::truncate();
      $path = "C:\Users\Alexies\Desktop\derbyprogram.xlsx";
      // $name = $req->file('derby')->getRealPath();
      if ($req->hasFile('file')) {
        $file=$req->file('file');
        $filename = $file->getclientOriginalName();
        $file->move(public_path('derbyexcel'),$filename);
        $path = public_path('derbyexcel').'/'.$filename;
        // return $path;
        Excel::import(new UsersImport(), $path);
        $createlogs = new Logs();
        $createlogs->type = 'Derby_Program';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = auth()->user()->username.' Uploaded Deby Program '.$filename.'.';
        $createlogs->save();
      }else {
        return 'wala';
      }
       // Excel::import(new UsersImport, $path);
      //  $path = $req->file('derby')->store(
      //     'img', 'public'
      // );
    }
    public function removeaanouncement(Request $req)
    {
      $announce =  control::first();
      $announce->announcement = '';
      $announce->save();
      $createlogs = new Logs();
      $createlogs->user_id = auth()->user()->id;
      $createlogs->message = auth()->user()->name.' Removed Announcement';
      $createlogs->Type = 'Removed_Announcement';
      $createlogs->save();
	  event(new announcement($announce));
    }
    public function announcement(Request $req)
    {
      $announce =  control::first();
      $announce->announcement = $req['announcement'];
      $announce->save();
      $createlogs = new Logs();
      $createlogs->user_id = auth()->user()->id;
      $createlogs->message = auth()->user()->name." Created Announcement \n".$req['announcement']."";
      $createlogs->Type = 'Set_Announcement';
      $createlogs->save();
	  event(new announcement($announce));
    }
	public function personalreport()
    {
      // $getevent = Event::where('id',44)->first();
      // return expertbet::where('event_id',$getevent->id)->select('wins')->get();
	  $from = date('2021-11-05');
      //$from = Carbon::today();
	  $to = Carbon::today();
      $daysToAdd = 1;
      $date = $to->addDays($daysToAdd);

	  $getevent = Event::whereBetween('fightdate', [$from, $date])->select('id')->get();
      $user_info = DB::table('expertbet')
                 ->whereIn('event_id',$getevent)
				 ->where('winner','!=',0)
		  //->where('wins','!=',0)
				->selectRaw('wins, count(wins) as total')
				->groupby('wins')
				->orderBy('wins', 'DESC')
                 ->get();
				 //$bets = array();
				//foreach($user_info as $data){
					//$counted2 = substr_count($data->bet,"D");
					//if($counted2 <= 2){
					//	array_push($bets,$data);
					//}
				//}
				  $a = array();
                 foreach ($getevent as $key) {
					 $count2 = expertbet::where('event_id',$key->id)->select('user_id')->where('winner','!=',0)->groupBy('user_id')->get();
					$count = count($count2);
                   array_push($a, array('wins'=>$count));
                 }
				  $total = count($a);
					$control = control::first();
					$maxwin2=expertbet::where('startingfight',141)->where('event_id',154)->orderBy('wins','DESC')->select('wins')->groupBy('wins')->get()->take($control->numberofwinners);
					  //$maxwin2=expertbet::where('startingfight',$data2)->whereIn('event_id',$a)->orderBy('wins','DESC')->select('wins')->groupBy('wins')->get()->take($control->numberofwinners);
					  $getoneprebetonly = selection::select('expertbet_id')->where('fightnumber',141)->where('event_id',157)->get()->take(1);
					  $getoneprebetonly1 = selection::select('expertbet_id')->where('fightnumber',141)->where('event_id',157)->first();

                 return $a;
    }
	public function getwinnerscount(Request $req)
    {
      if ($req['pick']==2) {
        $getevent = Event::where('id', $req['id'])->where('pick',2)->select('id')->get();
          $user_info = DB::table('expertbet')
                     ->whereIn('event_id',$getevent)
    				 ->where('winner','!=',0)

    		  //->where('wins','!=',0)
    				->selectRaw('wins, count(wins) as total')
    				->groupby('wins')
    				->orderBy('wins', 'DESC')
                     ->get();

              $winners = array();
              $top1 = $user_info->max('wins');
              // $top2 = $user_info->where('wins','!=',$top1)->max('wins');
              // $top3 = $user_info->where('wins','!=',$top1)->where('wins','!=',$top2)->max('wins');
              // $top4 = $user_info->where('wins','!=',$top1)->where('wins','!=',$top2)->where('wins','!=',$top3)->max('wins');
              foreach ($user_info as $key) {
                if ($key->wins==$top1) {
                  array_push($winners, array('wins'=>$key->wins,'total'=>$key->total));
                }
              }

              return $winners;
      }else {
        $getevent = Event::where('id', $req['id'])->where('pick',20)->select('id')->get();
          $user_info = DB::table('expertbet')
                     ->whereIn('event_id',$getevent)
    				 ->where('winner','!=',0)
    				 // ->where('pick',20)
    		  //->where('wins','!=',0)
    				->selectRaw('wins, count(wins) as total')
    				->groupby('wins')
    				->orderBy('wins', 'DESC')
                     ->get();

              $winners = array();
              $top1 = $user_info->max('wins');
              $top2 = $user_info->where('wins','!=',$top1)->max('wins');
              $top3 = $user_info->where('wins','!=',$top1)->where('wins','!=',$top2)->max('wins');
              $top4 = $user_info->where('wins','!=',$top1)->where('wins','!=',$top2)->where('wins','!=',$top3)->max('wins');
              foreach ($user_info as $key) {
                if ($key->wins==$top1||$key->wins==$top2||$key->wins==$top3||$key->wins==$top4||$key->wins==0||$key->wins==1||$key->wins==2||$key->wins==3) {
                  array_push($winners, array('wins'=>$key->wins,'total'=>$key->total));
                }
              }

              return $winners;
      }

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

	  // expertbet::where('event_id',101)->delete();
	  // Event::where('id',101)->delete();
	  // selection::where('event_id',101)->delete();
	  // Potmoney::where('event_id',101)->delete();
    // $getevent=Event::where('status',1)->first();
    // $control=control::first();
    //
    //
    // $data1=250-$control->pick;
    // $data2=$data1-1;
    // $data3=$data2+$control->pick+2;
    //
    // $checkforcancel = Results::whereIn('event_id',[69,70,71,72,73])->where('result','Cancelled')->whereBetween('fightnumber', [$data2, $data3])->count();
    $code = Carbon::now()->timestamp;
    $barcoded = substr($code, -6);

    // return $data2.'   '.$data3;
      if ($req['pass']==='Alex@fia') {
        // code...
        $all = User::where('role',3)->update([
          'cash'=>0
        ]);
      //  $all = User::where('id',1)->update([
        //  'cash'=>100000
        //]);

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
	public function registergroup($id)
    {
      $groupid = $id;
	  if (Auth::check()) {
        return redirect('home');
      }else {
        return view('registerwithgroup',  ['groupid' => $groupid]);
      }

    }
    public function getdeclarators()
    {
      return User::where('group_id',auth()->user()->group_id)
      // ->where('role',8)
      // ->where('role',8)
      ->where('role','!=',9)->where('role','!=',3)->where('role','!=',4)->where('role','!=',2)->where('role','!=',6)->where('role','!=',7)->where('role','!=',10)
      ->where('id','!=',auth()->user()->id)->latest()->get();
    }
    public function pastwinners()
    {
      $froms = date('2022-3-19');
      $to = Carbon::today();
      $from = Carbon::now()->subDays(2);
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
      $getevent = Event::where('pick',20)->whereBetween('fightdate', [$from, $date])->select('id','startingfight','fightdate','status')->latest()->get();


      $bets = array();
      foreach ($getevent as $key) {
        $getfirstwin = expertbet::with('user')->where('event_id',$key->id)->get()->max('wins');
		$getfirstwin2 = past_expertbet::with('user')->where('event_id',$key->id)->get()->max('wins');

        if ($key->status==2) {

          $get = DB::table('past_expertbet as a')
          ->where('a.winner',1)
          ->where('a.startingfight',$key->startingfight)
          ->where('a.event_id',$key->id)
          ->where('a.wins',$getfirstwin2)
          ->join('users as c', 'a.user_id', '=', 'c.id')
          ->join('events as d', 'a.event_id', '=', 'd.id')
          ->select('a.updated_at','c.name','a.wins','c.id','d.fightdate','a.result AS winnings')
          ->get();

        }else {
          $get = DB::table('expertbet as a')
          ->where('a.winner',1)
          ->where('a.startingfight',$key->startingfight)
          ->where('a.event_id',$key->id)
          ->where('a.wins',$getfirstwin)
          ->join('users as c', 'a.user_id', '=', 'c.id')
          ->join('events as d', 'a.event_id', '=', 'd.id')
          ->select('a.updated_at','c.name','a.wins','c.id','d.fightdate','a.result AS winnings')
          ->get();
        }
        // $count = DB::table('expertbet as a')
        // ->where('a.winner',1)
        // ->where('a.startingfight',$key->startingfight)
        // ->where('a.event_id',$key->id)
        // ->where('a.wins',$getfirstwin)
        // // ->join('users as c', 'a.user_id', '=', 'c.id')
        // // ->select('a.created_at','c.name','a.wins')
        // ->count();

        // if ($count) {
        //   // code...
        //   if ($getfirstwin===20) {
        //     $total = 100000/$count;
        //     $final = round($total, 2);
        //   }elseif ($getfirstwin===19) {
        //     $total = 5000/$count;
        //     $final = round($total, 2);
        //   }elseif ($getfirstwin===18) {
        //     $total = 2000/$count;
        //     $final = round($total, 2);
        //   }else {
        //     $total = 500/$count;
        //     $final = round($total, 2);
        //   }
          // $total = 500/$count;
          //   $final = round($total, 2);
        // }else {
        //   $final = null;
        // }
        // $final = number_format((float)$total, 2, '.', '');
        // $getbets = expertbet::with('user')->where('startingfight',$key->startingfight)->where('event_id',$key->id)->where('wins',$getfirstwin)->select('wins','startingfight','created_at','expertbet.user')->get();
        // array_push($bets, $get);
        foreach ($get as $keys) {
          array_push($bets,array('name'=>$keys->name,'created_at'=>$keys->updated_at,'winnings'=>$keys->winnings,'id'=>$keys->id,'fightdate'=>$keys->fightdate,'wins'=>$keys->wins));
        }
      }
        return $bets;
    }
    public function pastwinners2()
    {
    $from = Carbon::now()->subDays(2);
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
      $getevent = Event::where('pick',20)->whereBetween('fightdate', [$from, $date])->select('id','startingfight','fightdate','status')->latest()->get();


      $bets = array();
      foreach ($getevent as $key) {
        $getfirstwin = expertbet::with('user')->where('event_id',$key->id)->get()->max('wins');
		$getfirstwinfinal = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$getfirstwin)->get()->max('wins');
		$getfirstwin2 = past_expertbet::with('user')->where('event_id',$key->id)->get()->max('wins');
	    $getfirstwinfinal2 = past_expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$getfirstwin2)->get()->max('wins');

        if ($key->status==2) {
          $get = DB::table('past_expertbet as a')
          ->where('a.winner',1)
          ->where('a.startingfight',$key->startingfight)
          ->where('a.event_id',$key->id)
          ->where('a.wins','=',$getfirstwinfinal2)
          ->join('users as c', 'a.user_id', '=', 'c.id')
          ->join('events as d', 'a.event_id', '=', 'd.id')
          ->select('a.updated_at','c.name','a.wins','c.id','d.fightdate','a.result AS winnings')
          ->get();
        }else {
          $get = DB::table('expertbet as a')
          ->where('a.winner',1)
          ->where('a.startingfight',$key->startingfight)
          ->where('a.event_id',$key->id)
          ->where('a.wins','=',$getfirstwinfinal)
          ->join('users as c', 'a.user_id', '=', 'c.id')
          ->join('events as d', 'a.event_id', '=', 'd.id')
          ->select('a.updated_at','c.name','a.wins','c.id','d.fightdate','a.result AS winnings')
          ->get();
        }
        // $final = number_format((float)$total, 2, '.', '');
        // $getbets = expertbet::with('user')->where('startingfight',$key->startingfight)->where('event_id',$key->id)->where('wins',$getfirstwin)->select('wins','startingfight','created_at','expertbet.user')->get();
        // array_push($bets, $get);
        foreach ($get as $keys) {
          array_push($bets,array('name'=>$keys->name,'id'=>$keys->name,'created_at'=>$keys->updated_at,'winnings'=>$keys->winnings,'id'=>$keys->id,'fightdate'=>$keys->fightdate,'wins'=>$keys->wins));
        }
      }
        return $bets;
    }
    public function pastwinners3()
    {
      $from = Carbon::now()->subDays(2);
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
      $getevent = Event::where('pick',20)->whereBetween('fightdate', [$from, $date])->select('id','startingfight','fightdate','status')->latest()->get();


      $bets = array();
      foreach ($getevent as $key) {
        $getfirstwin = expertbet::with('user')->where('event_id',$key->id)->where('winner',1)->get()->max('wins');
		    $getfirstwin1 = expertbet::with('user')->where('event_id',$key->id)->where('winner',1)->where('wins','!=',$getfirstwin)->get()->max('wins');
		    $getfirstwin2 = expertbet::with('user')->where('event_id',$key->id)->where('winner',1)->where('wins','!=',$getfirstwin)->where('wins','!=',$getfirstwin1)->get()->max('wins');
        $getfirstwins = past_expertbet::with('user')->where('event_id',$key->id)->where('winner',1)->get()->max('wins');
		    $getfirstwin1s = past_expertbet::with('user')->where('event_id',$key->id)->where('winner',1)->where('wins','!=',$getfirstwins)->get()->max('wins');
		    $getfirstwin2s = past_expertbet::with('user')->where('event_id',$key->id)->where('winner',1)->where('wins','!=',$getfirstwins)->where('wins','!=',$getfirstwin1s)->get()->max('wins');
		    // $getfirstwin3 = expertbet::with('user')->where('event_id',$key->id)->where('winner',1)->where('wins','!=',$getfirstwin)->where('wins','!=',$getfirstwin1)->where('wins','!=',$getfirstwin2)->get()->max('wins');
        if ($key->status==2) {
          $get = DB::table('past_expertbet as a')
          ->where('a.winner',1)
          ->where('a.startingfight',$key->startingfight)
          ->where('a.event_id',$key->id)
          ->where('a.wins','=',$getfirstwin2s)
          ->join('users as c', 'a.user_id', '=', 'c.id')
          ->join('events as d', 'a.event_id', '=', 'd.id')
          ->select('a.updated_at','c.name','a.wins','c.id','d.fightdate','a.result AS winnings')
          ->get();
        }else {
          $get = DB::table('expertbet as a')
          ->where('a.winner',1)
          ->where('a.startingfight',$key->startingfight)
          ->where('a.event_id',$key->id)
          ->where('a.wins','=',$getfirstwin2)
          ->join('users as c', 'a.user_id', '=', 'c.id')
          ->join('events as d', 'a.event_id', '=', 'd.id')
          ->select('a.updated_at','c.name','a.wins','c.id','d.fightdate','a.result AS winnings')
          ->get();
        }
        // $count = DB::table('expertbet as a')
        // ->where('a.winner',1)
        // ->where('a.startingfight',$key->startingfight)
        // ->where('a.event_id',$key->id)
        // ->where('a.wins',$getfirstwin)
        // // ->join('users as c', 'a.user_id', '=', 'c.id')
        // // ->select('a.created_at','c.name','a.wins')
        // ->count();
        //   $counts = count($get);
        // if ($counts) {
        //   // code...
        //   $total = 300/$counts;
        //   $final = round($total, 2);
        // }else {
        //   $final = null;
        // }
        // $final = number_format((float)$total, 2, '.', '');
        // $getbets = expertbet::with('user')->where('startingfight',$key->startingfight)->where('event_id',$key->id)->where('wins',$getfirstwin)->select('wins','startingfight','created_at','expertbet.user')->get();
        // array_push($bets, $get);
        foreach ($get as $keys) {
          array_push($bets,array('name'=>$keys->name,'created_at'=>$keys->updated_at,'winnings'=>$keys->winnings,'id'=>$keys->wins,'fightdate'=>$keys->fightdate));
        }
      }
        return $bets;
    }
    public function pastwinners4()
    {
      $from = Carbon::now()->subDays(2);
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
      $getevent = Event::where('pick',20)->whereBetween('fightdate', [$from, $date])->select('id','startingfight','fightdate','status')->latest()->get();


      $bets = array();
      foreach ($getevent as $key) {
        $getfirstwin = expertbet::with('user')->where('event_id',$key->id)->where('winner',1)->get()->max('wins');
		    $getfirstwin1 = expertbet::with('user')->where('event_id',$key->id)->where('winner',1)->where('wins','!=',$getfirstwin)->get()->max('wins');
		    $getfirstwin2 = expertbet::with('user')->where('event_id',$key->id)->where('winner',1)->where('wins','!=',$getfirstwin)->where('wins','!=',$getfirstwin1)->get()->max('wins');
		    $getfirstwin3 = expertbet::with('user')->where('event_id',$key->id)->where('winner',1)->where('wins','!=',$getfirstwin)->where('wins','!=',$getfirstwin1)->where('wins','!=',$getfirstwin2)->get()->max('wins');
        $getfirstwins = past_expertbet::with('user')->where('event_id',$key->id)->where('winner',1)->get()->max('wins');
		    $getfirstwin1s = past_expertbet::with('user')->where('event_id',$key->id)->where('winner',1)->where('wins','!=',$getfirstwins)->get()->max('wins');
		    $getfirstwin2s = past_expertbet::with('user')->where('event_id',$key->id)->where('winner',1)->where('wins','!=',$getfirstwins)->where('wins','!=',$getfirstwin1s)->get()->max('wins');
		    $getfirstwin3s = past_expertbet::with('user')->where('event_id',$key->id)->where('winner',1)->where('wins','!=',$getfirstwins)->where('wins','!=',$getfirstwin1s)->where('wins','!=',$getfirstwin2s)->get()->max('wins');
        if ($key->status==2) {
          $get = DB::table('past_expertbet as a')
          ->where('a.winner',1)
          ->where('a.startingfight',$key->startingfight)
          ->where('a.event_id',$key->id)
          ->where('a.wins','=',$getfirstwin3s)
          ->join('users as c', 'a.user_id', '=', 'c.id')
          ->join('events as d', 'a.event_id', '=', 'd.id')
          ->select('a.updated_at','c.name','a.wins','c.id','d.fightdate','a.result AS winnings')
          ->get();
        }else {
          $get = DB::table('expertbet as a')
          ->where('a.winner',1)
          ->where('a.startingfight',$key->startingfight)
          ->where('a.event_id',$key->id)
          ->where('a.wins','=',$getfirstwin3)
          ->join('users as c', 'a.user_id', '=', 'c.id')
          ->join('events as d', 'a.event_id', '=', 'd.id')
          ->select('a.updated_at','c.name','a.wins','c.id','d.fightdate','a.result AS winnings')
          ->get();
        }

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
          $total = 200/$counts;
          $final = round($total, 2);
        }else {
          $final = null;
        }
        // $final = number_format((float)$total, 2, '.', '');
        // $getbets = expertbet::with('user')->where('startingfight',$key->startingfight)->where('event_id',$key->id)->where('wins',$getfirstwin)->select('wins','startingfight','created_at','expertbet.user')->get();
        // array_push($bets, $get);
        foreach ($get as $keys) {
          array_push($bets,array('name'=>$keys->name,'created_at'=>$keys->updated_at,'winnings'=>$keys->winnings,'id'=>$keys->wins,'fightdate'=>$keys->fightdate));
        }
      }
        return $bets;
    }
    public function lowestscore2()
    {
      $from = date('2022-2-16');
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
      $from = date('2022-3-19');
      $to = Carbon::today();
      $daysToAdd = 1;
      $date = $to->addDays($daysToAdd);

      $getevent = Event::whereBetween('fightdate', [$from, $date])->select('id','startingfight','fightdate')->latest()->get();



      $bets = array();

      foreach ($getevent as $key) {
        $getlowest = expertbet::with('user')->where('event_id',$key->id)->get()->min('wins');
        $getbets1 = expertbet::with('user','event')->where('event_id',$key->id)->whereIn('wins',[0,1,2,3])->where('winner',1)->get();
        $bet = array();
        foreach ($getbets1 as $key1) {
          $counted = substr_count($key1->bet,"D");
          if ($counted <= 2) {
            array_push($bet,array('event_id'=>$key1->user->id,'date' => $key->fightdate ,'wins'=>$key1->wins,'name'=>$key1->user->name,'drawcounts'=>$counted,'id'=>$key1->id));
          }
          // else {
          //   if (!$bet) {
          //     unset($bet);
          //     $bet = array();
          //     $getlowest2 = expertbet::with('user')->where('event_id',$key->id)->where('id','!=',$key1->id)->where('wins','!=',$key1->wins)->where('wins','!=',$key->wins)->get()->min('wins');
          //     $getbets2 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest2)->where('winner',3)->get();
          //     foreach ($getbets2 as $key2) {
          //       $counted2 = substr_count($key2->bet,"D");
          //       if ($counted2 <= 2) {
          //         array_push($bet,array('event_id'=>$key2->event->id,'date' => $key->fightdate ,'wins'=>$key2->wins,'name'=>$key2->user->name,'drawcounts'=>$counted2,'id'=>$key2->id));
          //       }else {
          //         if (!$bet) {
          //         unset($bet);
          //         $bet = array();
          //         $getlowest3 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key2->wins)->where('id','!=',$key2->id)->where('wins','!=',$key1->wins)->where('wins','!=',$key->wins)->get()->min('wins');
          //         $getbets3 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest3)->where('winner',3)->get();
          //         foreach ($getbets3 as $key3) {
          //           $counted3 = substr_count($key3->bet,"D");
          //           if ($counted3<=2) {
          //             array_push($bet,array('event_id'=>$key3->event->id,'date' => $key->fightdate ,'wins'=>$key3->wins,'name'=>$key3->user->name,'drawcounts'=>$counted3,'id'=>$key3->id));
          //           }else {
          //             if (!$bet) {
          //               unset($bet);
          //               $bet = array();
          //               $getlowest4 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key3->wins)->where('id','!=',$key3->id)->where('id','!=',$key2->id)->where('wins','!=',$key1->wins)
          //               ->where('wins','!=',$key->wins)->get()->min('wins');
          //               $getbets4 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest4)->where('winner',3)->get();
          //               foreach ($getbets4 as $key4) {
          //                 $counted4 = substr_count($key4->bet,"D");
          //                 if ($counted4<=2) {
          //                   array_push($bet,array('event_id'=>$key4->event->id,'date' => $key->fightdate ,'wins'=>$key4->wins,'name'=>$key4->user->name,'drawcounts'=>$counted4,'id'=>$key4->id));
          //                 }else {
          //                   if (!$bet) {
          //                     unset($bet);
          //                     $bet = array();
          //                     $getlowest5 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key4->wins)->where('id','!=',$key4->id)->get()->min('wins');
          //                     $getbets5 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest5)->where('winner',3)->get();
          //                     foreach ($getbets5 as $key5) {
          //                       $counted5 = substr_count($key5->bet,"D");
          //                       if ($counted5<=2) {
          //                         array_push($bet,array('event_id'=>$key5->event->id,'date' => $key->fightdate ,'wins'=>$key5->wins,'name'=>$key5->user->name,'drawcounts'=>$counted5,'id'=>$key5->id));
          //                       }else {
          //                         if (!$bet) {
          //                           unset($bet);
          //                           $bet = array();
          //                           $getlowest6 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key5->wins)->where('id','!=',$key5->id)->get()->min('wins');
          //                           $getbets6 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest6)->where('winner',3)->get();
          //                           foreach ($getbets6 as $key6) {
          //                             $counted6 = substr_count($key6->bet,"D");
          //                             if ($counted6<=2) {
          //                               array_push($bet,array('event_id'=>$key6->event->id,'date' => $key->fightdate ,'wins'=>$key6->wins,'name'=>$key6->user->name,'drawcounts'=>$counted6,'id'=>$key6->id));
          //                             }else {
          //                               if (!$bet) {
          //                                 unset($bet);
          //                                 $bet = array();
          //                                 $getlowest7 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key5->wins)->where('id','!=',$key6->id)->get()->min('wins');
          //                                 $getbets7 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest7)->where('winner',3)->get();
          //                                 foreach ($getbets7 as $key7) {
          //                                   $counted7= substr_count($key7->bet,"D");
          //                                   if ($counted7<=2) {
          //                                     array_push($bet,array('event_id'=>$key7->event->id,'date' => $key->fightdate ,'wins'=>$key7->wins,'name'=>$key7->user->name,'drawcounts'=>$counted7,'id'=>$key7->id));
          //                                   }else {
          //                                     // array_push($bet,array('event_id'=>'alex','date' => $key->fightdate ,'wins'=>$key7->wins,'name'=>$key7->user->name,'drawcounts'=>$counted7,'id'=>$key7->id));
          //                                   }
          //                                 }
          //                               }
          //                             }
          //                           }
          //                         }
          //                       }
          //                     }
          //                   }
          //                 }
          //               }
          //             }
          //           }
          //         }
          //       }
          //       }
          //     }
          //   }
          // }

        }
        $betfinal = array();

        $maxwin = collect($bet);

        foreach ($bet as $data2) {
         // if ($maxwin==$data2['wins']) {
            array_push($betfinal,array('event_id'=>$data2['event_id'],'bet_id'=>$data2['id'],'wins'=>$data2['wins'],'drawcounts'=>$data2['drawcounts'],'fightdates'=>$data2['date'],'name'=>$data2['name']));
        //  }
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
    public function lowestwinners()
    {
      // $from = date('2022-3-19');
      $from = Carbon::now()->subDays(2);
      $to = Carbon::today();
      $daysToAdd = 1;
      $date = $to->addDays($daysToAdd);

      // $getevent = Event::whereBetween('fightdate', [$from, $date])->select('id','startingfight','fightdate')->latest()->get();
      $data = array();
      $event = Event::where('pick',20)->whereBetween('fightdate', [$from, $date])->with(['expertbet' => function($query) {
           $query->where('winner', 1)->whereIn('wins',[0,1,2,3])->with('user')->orderBy('wins','desc');
        }])->whereHas('expertbet', function ($query) {
            $query->where('winner',1)->whereIn('wins',[0,1,2,3]);
        })->latest()->get();
        foreach ($event as $key) {
          array_push($data,$key);
        }
        $event2 = Event::where('pick',20)->whereBetween('fightdate', [$from, $date])->with(['past_expertbet' => function($query) {
             $query->where('winner', 1)->whereIn('wins',[0,1,2,3])->with('user')->orderBy('wins','desc');
          }])->whereHas('past_expertbet', function ($query) {
              $query->where('winner',1)->whereIn('wins',[0,1,2,3]);
          })->latest()->get();
          foreach ($event2 as $key) {
            array_push($data,$key);
          }

        return $data;
    }
    public function pick2winners()
    {
      // $from = date('2022-3-19');
      $from = Carbon::now()->subDays(2);
      $to = Carbon::today();
      $daysToAdd = 1;
      $date = $to->addDays($daysToAdd);

      // $getevent = Event::whereBetween('fightdate', [$from, $date])->select('id','startingfight','fightdate')->latest()->get();
      $data = array();


       $event = Event::where('pick',2)->whereBetween('fightdate', [$from, $date])->with(['expertbet' => function($query) {
           $query->where('winner', 1)->with('user')->orderBy('wins','desc');
        }])->whereHas('expertbet', function ($query) {
            $query->where('winner',1);
        })->orderBy('startingfight','desc')->latest()->get();
        foreach ($event as $key) {
          array_push($data,$key);
        }

        $event2 = Event::where('pick',2)->whereBetween('fightdate', [$from, $date])->with(['past_expertbet' => function($query) {
          $query->where('winner', 1)->with('user')->orderBy('wins','desc');
        }])->whereHas('past_expertbet', function ($query) {
          $query->where('winner',1);
        })->orderBy('startingfight','desc')->latest()->get();
        foreach ($event2 as $key) {
          array_push($data,$key);
        }


        return $data;
    }
   public function lowestleaders()
    {
      $from = Carbon::today();
      $to = Carbon::today();
      $daysToAdd = 1;
      $date = $to->addDays($daysToAdd);

      $getevent = Event::where('pick',20)->whereHas('expertbet', function ($query) {
          $query->where('winner',0);
      })->whereBetween('fightdate', [$from, $date])->select('id','startingfight','fightdate')->latest()->get();



      $bets = array();

      foreach ($getevent as $key) {
        $getallbets = expertbet::with('user')->where('event_id',$key->id)->get();
        $gethighest = $getallbets->where('event_id',$key->id)->max('wins');
        $gethighest2 = $getallbets->where('event_id',$key->id)->whereNotIn('wins', [$gethighest])->max('wins');
        $gethighest3 = $getallbets->where('event_id',$key->id)->whereNotIn('wins', [$gethighest,$gethighest2])->max('wins');
        $gethighest4 = $getallbets->where('event_id',$key->id)->whereNotIn('wins', [$gethighest,$gethighest2,$gethighest3])->max('wins');

        $getlowest = $getallbets->where('event_id',$key->id)->whereNotIn('wins', [$gethighest,$gethighest2,$gethighest3,$gethighest4])->min('wins');
        $getlowest2 = $getallbets->where('event_id',$key->id)->whereNotIn('wins', [$gethighest,$gethighest2,$gethighest3,$gethighest4,$getlowest])->min('wins');
        $getlowest3 = $getallbets->where('event_id',$key->id)->whereNotIn('wins', [$gethighest,$gethighest2,$gethighest3,$gethighest4,$getlowest,$getlowest2])->min('wins');
        $getlowest4 = $getallbets->where('event_id',$key->id)->whereNotIn('wins', [$gethighest,$gethighest2,$gethighest3,$gethighest4,$getlowest,$getlowest2,$getlowest3])->min('wins');
        $getlowest1 = $getlowest+1;
        $wins = array(1,0,2,3);
        $getbets1 = expertbet::with('user','event')->where('event_id',$key->id)->whereIn('wins',[$getlowest,$getlowest2,$getlowest3,$getlowest4])->where('winner',0)->orderBy('wins','desc')->get();
        // return $getbets1 ;
        $bet = array();
        foreach ($getbets1 as $key1) {
          $counted = substr_count($key1->bet,"D");
          if ($counted <= 2) {
            array_push($bet,array('event_id'=>$key1->event->id,'date' => $key->fightdate ,'wins'=>$key1->wins,'name'=>$key1->user->name,'drawcounts'=>$counted,'id'=>$key1->id,'startingfight'=>$key1->startingfight,'role'=>$key1->user->role));
          }
          // else
          // {
          //   if (!$bet) {
          //     unset($bet);
          //     $bet = array();
          //     $getlowest2 = expertbet::with('user')->where('event_id',$key->id)->where('id','!=',$key1->id)->where('wins','!=',$key1->wins)->where('wins','!=',$key->wins)->get()->min('wins');
          //     $getbets2 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest2)->where('winner',3)->get();
          //     foreach ($getbets2 as $key2) {
          //       $counted2 = substr_count($key2->bet,"D");
          //       if ($counted2 <= 2) {
          //         array_push($bet,array('event_id'=>$key2->event->id,'date' => $key->fightdate ,'wins'=>$key2->wins,'name'=>$key2->user->name,'drawcounts'=>$counted2,'id'=>$key2->id,'startingfight'=>$key2->startingfight));
          //       }else {
          //         if (!$bet) {
          //         unset($bet);
          //         $bet = array();
          //         $getlowest3 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key2->wins)->where('id','!=',$key2->id)->where('wins','!=',$key1->wins)->where('wins','!=',$key->wins)->get()->min('wins');
          //         $getbets3 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest3)->where('winner',3)->get();
          //         foreach ($getbets3 as $key3) {
          //           $counted3 = substr_count($key3->bet,"D");
          //           if ($counted3<=2) {
          //             array_push($bet,array('event_id'=>$key3->event->id,'date' => $key->fightdate ,'wins'=>$key3->wins,'name'=>$key3->user->name,'drawcounts'=>$counted3,'id'=>$key3->id,'startingfight'=>$key3->startingfight));
          //           }else {
          //             if (!$bet) {
          //               unset($bet);
          //               $bet = array();
          //               $getlowest4 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key3->wins)->where('id','!=',$key3->id)->where('id','!=',$key2->id)->where('wins','!=',$key1->wins)
          //               ->where('wins','!=',$key->wins)->get()->min('wins');
          //               $getbets4 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest4)->where('winner',3)->get();
          //               foreach ($getbets4 as $key4) {
          //                 $counted4 = substr_count($key4->bet,"D");
          //                 if ($counted4<=2) {
          //                   array_push($bet,array('event_id'=>$key4->event->id,'date' => $key->fightdate ,'wins'=>$key4->wins,'name'=>$key4->user->name,'drawcounts'=>$counted4,'id'=>$key4->id,'startingfight'=>$key4->startingfight));
          //                 }else {
          //                   if (!$bet) {
          //                     unset($bet);
          //                     $bet = array();
          //                     $getlowest5 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key4->wins)->where('id','!=',$key4->id)->get()->min('wins');
          //                     $getbets5 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest5)->where('winner',3)->get();
          //                     foreach ($getbets5 as $key5) {
          //                       $counted5 = substr_count($key5->bet,"D");
          //                       if ($counted5<=2) {
          //                         array_push($bet,array('event_id'=>$key5->event->id,'date' => $key->fightdate ,'wins'=>$key5->wins,'name'=>$key5->user->name,'drawcounts'=>$counted5,'id'=>$key5->id,'startingfight'=>$key5->startingfight));
          //                       }else {
          //                         if (!$bet) {
          //                           unset($bet);
          //                           $bet = array();
          //                           $getlowest6 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key5->wins)->where('id','!=',$key5->id)->get()->min('wins');
          //                           $getbets6 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest6)->where('winner',3)->get();
          //                           foreach ($getbets6 as $key6) {
          //                             $counted6 = substr_count($key6->bet,"D");
          //                             if ($counted6<=2) {
          //                               array_push($bet,array('event_id'=>$key6->event->id,'date' => $key->fightdate ,'wins'=>$key6->wins,'name'=>$key6->user->name,'drawcounts'=>$counted6,'id'=>$key6->id,'startingfight'=>$key6->startingfight));
          //                             }else {
          //                               if (!$bet) {
          //                                 unset($bet);
          //                                 $bet = array();
          //                                 $getlowest7 = expertbet::with('user')->where('event_id',$key->id)->where('wins','!=',$key5->wins)->where('id','!=',$key6->id)->get()->min('wins');
          //                                 $getbets7 = expertbet::with('user','event')->where('event_id',$key->id)->where('wins',$getlowest7)->where('winner',3)->get();
          //                                 foreach ($getbets7 as $key7) {
          //                                   $counted7= substr_count($key7->bet,"D");
          //                                   if ($counted7<=2) {
          //                                     array_push($bet,array('event_id'=>$key7->event->id,'date' => $key->fightdate ,'wins'=>$key7->wins,'name'=>$key7->user->name,'drawcounts'=>$counted7,'id'=>$key7->id,'startingfight'=>$key7->startingfight));
          //                                   }else {
          //                                   }
          //                                 }
          //                               }
          //                             }
          //                           }
          //                         }
          //                       }
          //                     }
          //                   }
          //                 }
          //               }
          //             }
          //           }
          //         }
          //       }
          //       }
          //     }
          //   }
          // }

        }
        // $betfinal = array();
        //
        // $maxwin = collect($bet)->min('wins');
        //
        // foreach ($bet as $data2) {
        //   if ($maxwin==$data2['wins']) {
        //     array_push($betfinal,array('event_id'=>$data2['event_id'],'bet_id'=>$data2['id'],'wins'=>$data2['wins'],'drawcounts'=>$data2['drawcounts'],'fightdates'=>$data2['date'],'name'=>$data2['name'],'startingfight'=>$data2['startingfight']));
        //   }
        // }

        // foreach ($betfinal as $data3) {
        //     array_push($bets,array('event_id'=>$data3['event_id'],'bet_id'=>$data3['bet_id'],'wins'=>$data3['wins'],'drawcounts'=>$data3['drawcounts'],'fightdates'=>$data3['fightdates'],'name'=>$data3['name'],'startingfight'=>$data3['startingfight']));
        // }
      }
        // $datalang = $bets->take(3);
        $getevent=Event::where('status',1)->first();
        $checking = Results::where('event_id',$getevent->id)->latest()->count();
        if ($checking>4) {
          if (isset($bet)) {
            return collect($bet);
          }
        }else {
          return 'wala pang 149';
        }
    }

   public function pastlowesttoday()
    {
      $from = Carbon::today();
      $to = Carbon::today();
      $daysToAdd = 1;
      $date = $to->addDays($daysToAdd);
      $array = array();
      $getevent = Event::where('pick',20)->where('status',1)->select('id','startingfight','fightdate')->latest()->get();
      //original $getevent = Event::where('pick',20)->where('status',1)->whereBetween('fightdate', [$from, $date])->select('id','startingfight','fightdate')->latest()->get();
      // foreach ($getevent as $key) {
      //   array_push($array,$key->id);
      // }
      $lastupdate = array();
      foreach ($getevent as $key) {
        $getallbets = expertbet::where('event_id',$key->id)->where('winner',1)->get();
        $gethighest = $getallbets->where('event_id',$key->id)->where('winner',1)->max('wins');
        $gethighest2 = $getallbets->where('event_id',$key->id)->where('winner',1)->whereNotIn('wins', [$gethighest])->max('wins');
        $gethighest3 = $getallbets->where('event_id',$key->id)->where('winner',1)->whereNotIn('wins', [$gethighest,$gethighest2])->max('wins');
        $gethighest4 = $getallbets->where('event_id',$key->id)->where('winner',1)->whereNotIn('wins', [$gethighest,$gethighest2,$gethighest3])->max('wins');

        $getlowest = $getallbets->where('event_id',$key->id)->where('winner',1)->whereNotIn('wins', [$gethighest,$gethighest2,$gethighest3,$gethighest4])->min('wins');
        $getlowest2 = $getallbets->where('event_id',$key->id)->where('winner',1)->whereNotIn('wins', [$gethighest,$gethighest2,$gethighest3,$gethighest4,$getlowest])->min('wins');
        $getlowest3 = $getallbets->where('event_id',$key->id)->where('winner',1)->whereNotIn('wins', [$gethighest,$gethighest2,$gethighest3,$gethighest4,$getlowest,$getlowest2])->min('wins');
        $getlowest4 = $getallbets->where('event_id',$key->id)->where('winner',1)->whereNotIn('wins', [$gethighest,$gethighest2,$gethighest3,$gethighest4,$getlowest,$getlowest2,$getlowest3])->min('wins');

        $getall =  Event::where('id',$key->id)->where('pick',20)->where('status',1)
        // ->whereBetween('fightdate', [$from, $date])
        ->with(['expertbet' => function($query)use($getlowest,$getlowest2,$getlowest3,$getlowest4){
         $query->where('winner',1)->whereIn('wins', [$getlowest,$getlowest2,$getlowest3,$getlowest4])->with('user')->orderBy('wins','desc');
        }])
        ->whereHas('expertbet', function ($query) use($getlowest,$getlowest2,$getlowest3,$getlowest4){
            $query->where('winner',1)->whereIn('wins',[$getlowest,$getlowest2,$getlowest3,$getlowest4]);
        })->latest()->get();

        foreach ($getall as $keys) {
          array_push($lastupdate,array('id'=>$keys->id,'startingfight'=>$keys->startingfight,'expertbet'=>$keys->expertbet));
        }
      }
      return $lastupdate;
      // return $gethighest.' '.$gethighest2.' '.$gethighest3.' '.  $gethighest4;


    }
    public function getallpastbets()
    {
		return Event::where('status',2)->groupBy('event_name')->latest()->paginate(10);
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
    public function alluserx(Request $req)
    {
      if ($req['group']||$req['id']||$req['name']||$req['phone']||$req['username']||$req['active']||$req['role']) {
        $num = intval($req['active']);
        return $getuser = User::with('group')
        ->Where('username', 'like', '%' . $req['username'] . '%')
        ->Where('name', 'like', '%' . $req['name'] . '%')
        ->Where('pnumber', 'like', '%' . $req['phone'] . '%')
        ->Where('group_id','like', '%' . $req['group']. '%')
        ->Where('id', 'like', '%' . $req['id'] . '%')
        ->Where('role','like', '%' . $req['role'] . '%')
        ->Where('active','like', '%' . $req['active'] . '%' )
        ->paginate(10);
      }else {
        return User::with('group')->latest()->paginate(10);
      }
    }
    public function allusers2()
    {
      return User::latest()->get();
    }
    public function allusersdeposit()
    {
      return User::where('active',1)->where('role','!=',1)->where('role','!=',2)->where('role','!=',9)->where('role','!=',10)->where('role','!=',5)->where('role','!=',8)->where('role','!=',7)->where('role','!=',6)->
      where('id','!=',auth()->user()->id)->where('group_id',auth()->user()->group_id)->latest()->get();
    }
    public function pager(Request $req)
    {
      $add = User::where('id',auth()->user()->id)->first();
      $add->page = $req['id'];
      $add->save();
    }
    public function partner(Request $req)
    {
      $add = User::where('id',auth()->user()->id)->first();
      $add->partner = $req['declarator_id'];
      $add->save();
    }
    public function back()
    {
      $add = User::where('id',auth()->user()->id)->first();
      $add->page = null;
      $add->partner = null;
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
      $eventname = Event::where('id',$req['id'])->first();
      $getevents = Event::where('event_name',$eventname->event_name)->get();
      $array = array();
      $eventid = array();

      foreach ($events as $key) {
        array_push($array, $key->event_name);
      }
      foreach ($getevents as $key) {
        array_push($eventid, $key->id);
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

	   $event = Event::whereIn('event_name',$array)->where('status',1)->withCount([
        'expertbet' => function ($query) {
            $query->where('winner', 0);
        },
        'potmoney'=> function ($query) {
            $query->where('claim', 1);
        },
        ])->get();
        $checktellerandcashier = User::whereIn('role',[0,4])->sum('cash');
        // return $checktellerandcashier;
        if ($event->sum('expertbet_count')) {
          return ['error'=>'You cannot close the event because there are still pending bets'];
        }elseif ($event->sum('potmoney_count')) {
          return ['error'=>'You cannot close the event because you need to release all the total net fees before you close this event'];
        }elseif ($checktellerandcashier>0) {
          return ['error'=>'You cannot close the event because you need to cash out all tellers and cashiers before you close this event'];
		  //return $checktellerandcashier;
        }else{
			       $event = Event::whereIn('event_name',$array)->where('status',1)->update([
          'status'=>2,
          'fightclosed'=>Carbon::now(),
        ]);
		    }

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
        $this->dispatch(new transferpastbets($eventid));
		// expertbet::query()->
    //     whereIn('event_id',$eventid)
    //     ->each(function ($oldRecord) {
    //       $newRecord = $oldRecord->replicate();
    //       $newRecord->setTable('past_expertbet');
    //       $newRecord->save();
    //
    //       $oldRecord->delete();
    //     });
    //     selection::query()->
    //     whereIn('event_id',$eventid)
    //     ->each(function ($oldRecord) {
    //       $newRecord = $oldRecord->replicate();
    //       $newRecord->setTable('past_selection');
    //       $newRecord->save();
    //
    //       $oldRecord->delete();
    //     });
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
		'pnumber' =>   'required|unique:users,pnumber,'. $req['id'],
      ]);
	  $active = intval($req['active']);
      $edituser = User::findOrFail($req['id']);
      $edituser->name = $req['name'];
      $edituser->username = $req['username'];
      $edituser->email = $req['email'];
      $edituser->role = $req['role'];
	  $edituser->active = $active;
      $edituser->group_id = $req['group_id'];
	  $edituser->pnumber = $req['pnumber'];
      if ($req['password']) {
        $edituser->password = Hash::make($req['password']);
      }
      $edituser->save();

      $createlogs = new Logs();
      $createlogs->type = 'Update_User_Details';
      $createlogs->user_id = auth()->user()->id;
       if ($req['role']==1) {
        $position = 'Admin';
      }elseif ($req['role']==9) {
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
	  elseif ($req['role']==2) {
        $position = 'Supervisor';
      }
	  elseif ($req['role']==8) {
        $position = 'Confirm Declarator';
      }
	  elseif ($req['role']==10) {
        $position = 'Board Admin';
      }
      if ($edituser->active==1) {
          $active = 'Active';
        }else {
          $active = 'Deactivated';
        }
      broadcast(new usersession($edituser))->toOthers();
      // $createlogs->message = auth()->user()->username.' Updated user '.$position.' '.$req['username'].'.';
      $createlogs->message = auth()->user()->username.' Updated user '.$position.' '.$req['username']."\nName : ".$edituser->name."\nUsername : ".$edituser->username."\nEmail : ".$edituser->email."\nRole : ".$position."\nStatus : ".$active.
       "\nGroup Id : ".$edituser->group_id."\nPhone Number : ".$edituser->pnumber;
      $createlogs->save();

    }
    public function adduser(Request $req)
    {
      $this->validate($req, [
        'name'=>'required|unique:users',
        'username'=>'required',
        'email'=>'required|unique:users',
        'role'=>'required',
        'group_id'=>'required',
        'password'=>'required',
		// 'pnumber' => 'required | numeric | digits:10 | starts_with: 9',
		  'pnumber' => 'digits:11|numeric|required|regex:/(09)/',
		'active'=>'required',
      ]);
      $adduser = new User();
      $adduser->name = $req['name'];
      $adduser->username = $req['username'];
      $adduser->email = $req['email'];
      $adduser->role = $req['role'];
	  $adduser->pnumber = $req['pnumber'];
      $adduser->password = Hash::make($req['password']);
      $adduser->group_id =$req['group_id'];
      $adduser->save();

      $createlogs = new Logs();
      $createlogs->type = 'Create_New_User';
      $createlogs->user_id = auth()->user()->id;
      if ($req['role']==1) {
        $position = 'Admin';
      }elseif ($req['role']==9) {
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
	  elseif ($req['role']==2) {
        $position = 'Supervisor';
      }
	  elseif ($req['role']==8) {
        $position = 'Confirm Declarator';
      }
	  elseif ($req['role']==10) {
        $position = 'Board Admin';
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
        'fights'=>'required|numeric|min:23',
        'arena'=>'required',
        // 'rake'=>'required',
        'fightdate'=>'required|date|after:now',
        'startingfight'=>'required|numeric|min:1',
        // 'amount'=>'required',
        // 'jackpot'=>'required',
        // 'pjackpot'=>'required',
      ]);
      $control = control::first();
      $getevent = Event::where('status',1)->latest()->first();
      $computed = $req['startingfight'] + $control->pick +2;
      $lastfight = $req['startingfight'] + $control->pick +2;

      if ($lastfight <= $req['fights'] && $req['fights']>=23&&$req['startingfight']>0){
        $newevent = new Event();
        $newevent->event_name = $req['event_name'];
        $newevent->fights = $req['fights'];
        $newevent->currentfight = 0;
        $newevent->startingfight = $req['startingfight'];
        $newevent->control = 'Closed';
        $newevent->pick = $control->pick;
        $newevent->status = 0;
        $newevent->venue = $req['arena'];
        $newevent->fightdate = $req['fightdate'];
        $newevent->save();
        $createpotmoneypick20 = new Potmoney();
        $createpotmoneypick20->amount=0;
        $createpotmoneypick20->event_id=$newevent->id;
        $createpotmoneypick20->pick=$newevent->pick;
        $createpotmoneypick20->startingfight=$newevent->startingfight;
        $createpotmoneypick20->save();
        $newevent = new Event();
        $newevent->event_name = $req['event_name'];
        $newevent->fights = $req['fights'];
        $newevent->currentfight = 0;
        $newevent->startingfight = $req['startingfight'];
        $newevent->control = 'Closed';
        $newevent->pick = 2;
        $newevent->status = 0;
        $newevent->venue = $req['arena'];
        $newevent->fightdate = $req['fightdate'];
        $newevent->save();




        $lastfight = $req['startingfight']+$control->pick-2;
        // return $lastfight;
        for ($i=$req['startingfight']; $i < $lastfight ; $i++) {
          $neweventpick2 = new Event();
          $neweventpick2->event_name = $req['event_name'];
          $neweventpick2->fights = $req['fights'];
          $neweventpick2->currentfight = 0;
          $neweventpick2->startingfight = $i+2;
          $i = $i+2-1;
          $neweventpick2->control = 'Closed';
          $neweventpick2->pick = 2;
          $neweventpick2->status = 0;
          $neweventpick2->venue = $req['arena'];
          $neweventpick2->fightdate = $req['fightdate'];
          $neweventpick2->save();

          $createpotmoneypick2 = new Potmoney();
          $createpotmoneypick2->amount=0;
          $createpotmoneypick2->event_id=$neweventpick2->id;
          $createpotmoneypick2->pick=$neweventpick2->pick;
          $createpotmoneypick2->startingfight=$neweventpick2->startingfight;
          $createpotmoneypick2->save();
        }
        $createpotmoneypick20 = new Potmoney();
        $createpotmoneypick20->amount=0;
        $createpotmoneypick20->event_id=$newevent->id;
        $createpotmoneypick20->pick=$newevent->pick;
        $createpotmoneypick20->startingfight=$newevent->startingfight;
        $createpotmoneypick20->save();


        // create logs
        $createlogs = new Logs();
        $createlogs->type = 'Create_New_Event';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = auth()->user()->username.' Create '.$req['event_name'].'.';
        $createlogs->save();
      }else {
        return ['error'=>'Please double check the starting fight and the number of fights, make sure that there`s no overlap.'];
      }

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
      $this->validate($req, [
        'pin'=>'required|max:4',
      ]);
      $checkpin = User::where('pin',$req['pin'])->where('group_id',auth()->user()->group_id)->first();
      if ($checkpin) {
        $bet2 = expertbet::where('reprint','!=',5)->with('selection')->where('id', $req['id'])->where('user_id',auth()->user()->id)->update(['reprint'=>DB::raw('reprint+1') ]);
        $bet3 = expertbet::with('selection')->where('id', $req['id'])->where('user_id',auth()->user()->id)->first();
        //$bet = expertbet::with('selection')->where('id', $req['id'])->where('user_id',auth()->user()->id)->get();

        // return $bet2;
        if ($bet2) {
          // code...
        }else {
          return ['error'=>'You reach the maximum of reprint.'];
        }
        $createlogs = new Logs();
        $createlogs->type = 'Reprint_Receipt';
        $createlogs->user_id = auth()->user()->id;
        // $getuser1 = User::findOrFail($req['user_id']);
        $createlogs->message = auth()->user()->username.' Reprint Bet ID : '.$req['id']."\nReprint Count : ".$bet3->reprint;
        $createlogs->save();
        return expertbet::with('selection')->where('id', $bet3->id)->where('user_id',auth()->user()->id)->get();;
      }else {
        return ['error'=>'Incorrect Pin'];
      }
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
        // $active = Event::where('status',1)->first();
        // $transactions=Transactions::where('event_id',$active->id)->where('user_id',auth()->user()->id)->latest()->paginate(10);

        $posts = Event::with(['transactions' => function ($query) {
        $query->where('user_id', auth()->user()->id)->latest();
        }])->whereHas('transactions', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->latest()->paginate(5);

        return $posts;
    }
    public function geteventsformonitoring()
    {
        return Event::latest()->get();
    }
    public function transactions()
    {
		if(Auth::check()){
	     return view('/transactions');
		}else{
			return redirect('login');
		}

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
		if (isset(auth()->user()->role)) {
      if (auth()->user()->role===0&& Auth::check() || auth()->user()->role===3&& Auth::check()) {
        return view('/withdrawalpage');
      }else{
        return redirect('/home');
      }
    }else {
      return redirect('/home');
    }
    }
    public function bethistoryx()
    {
      if (Auth::check()) {
        if (auth()->user()->role===9 || auth()->user()->role===3 && Auth::check()) {
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
        if (auth()->user()->role===9 || auth()->user()->role===3) {
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
        $checkifhavemoney = User::findOrFail(auth()->user()->id);
        if ($checkifhavemoney->cash<100 && $checkifhavemoney->role==3) {
          return ['error'=>'You dont have enough balance.'];
        }
        $getcontrol = control::first();
          // code...
          // $data = "";
          // $num_cols = 2;
          $num_rows = $req['start']+$getcontrol->pick+3; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
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
            array_push($data, ['fightnumber' => $i, 'bet'=>'M','id'=>$req['id'],'amount'=>1, 'finalamount'=>0, 'selection'=>$selection,'pick'=>$getcontrol->pick]);
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
