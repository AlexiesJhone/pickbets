<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Events\eventlistener;
use App\Models\Prebet;
use App\Models\Potmoney;
use App\Models\bet;
use App\Models\User;
use App\Models\Logs;
use App\Models\expertbet;
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
    public function doit()
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
      $getactiveevent = control::first();
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
      $maxwin2=expertbet::where('startingfight',161)->where('event_id',27)->update([
        'wins'=>0,
        'result'=>null,
        'claimed'=>null,
        'lose'=>null,
        'winner'=>0,
      ]);
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
    public function getallpastbets()
    {
      return Event::where('status',2)->latest()->get();
    }
    public function index()
    {
      return Event::where('status',1)->first();
    }
    public function alluserx()
    {
      return User::with('group')->where('active',1)->latest()->get();
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
    }
    public function user()
    {
      return User::where('id',auth()->user()->id)->first();
    }
    public function updatestatus(Request $req)
    {
      $checkevents=Event::where('status',1)->first();

      if ($checkevents && $checkevents->status==1 && $req['status']==1) {
        return error;
      }else {
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
    }
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
        'pick'=>'required',
        'fights'=>'required',
        'arena'=>'required',
        'rake'=>'required',
        'fightdate'=>'required',
        'amount'=>'required',
        'jackpot'=>'required',
        'pjackpot'=>'required',
      ]);
        $editevent = Event::findOrFail($req['id']);
        $editevent->event_name = $req['event_name'];
        $editevent->pick = $req['pick'];
        $editevent->fights = $req['fights'];
        $editevent->amount = $req['amount'];
        $editevent->rake = $req['rake'];
        $editevent->jackpot = $req['jackpot'];
        $editevent->pjackpot = $req['pjackpot'];
        $editevent->venue = $req['arena'];
        $editevent->fightdate = $req['fightdate'];
        $editevent->save();

        $createlogs = new Logs();
        $createlogs->type = 'Update_Event';
        $createlogs->user_id = auth()->user()->id;
        $createlogs->message = auth()->user()->username.' Updated '.$req['event_name'].'.';
        $createlogs->save();


    }
    public function getpast()
    {
      return Event::where('status',0)->latest()->get();
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
          $num_rows = $req['start']+$getcontrol->pick; // correctly validated as integer and must be more than 0 because you're doing multiplication here in the following loop
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
