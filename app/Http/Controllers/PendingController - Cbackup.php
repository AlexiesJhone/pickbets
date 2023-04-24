<?php

namespace App\Http\Controllers;

use App\Models\bet;
use App\Models\expertbet;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class PendingController extends Controller
{
  public function transferfunds(Request $req)
  {

    return 'hi';

  }
  public function leaders()
  {
    //  $getevent = Event::where('status',1)->first();
    // $Data = bet::where('event_id',$getevent->id)->where('winner',0)->latest()->get();
    return redirect('login');
  }
  public function pendingtopplayersx()
  {
    // $getalluser = User::all();
    // foreach (  $getalluser as $key) {
    //   $user = $key->id;
    //  $data = User::whereHas('bet', function($q) use ($user)
    //  {
    $get=Event::where('status',1)->get();
    $array = array();
    foreach ($get as $key) {
      array_push($array, $key->id);
    }
       // $getevent = Event::where('status',1)->first();
    //    $q->where('event_id',$getevent->id)->where('winner',0)->where('user_id',$user)->orderBy('wins','DESC')->get();
    //
    //  })->latest()->get();
    //    return $data;
    //  }
     return expertbet::with('user')->whereIn('event_id',$array)->where('winner',0)->where('wins','>=',5)->orderBy('startingfight','DESC')->orderBy('wins','DESC')->take(20)->get();

  }
  public function allpastwinners()
  {
    $get=Event::where('status',1)->get();
    $array = array();
    foreach ($get as $key) {
      array_push($array, $key->id);
    }
     // $getevent = Event::where('status',1)->first();
     return expertbet::whereIn('event_id',$array)->where('winner',0)->orderBy('wins','DESC')->get();
  }
  public function winnersfortoday()
  {
     $getevent = Event::where('status',1)->first();
     $get=Event::where('status',1)->get();
     $array = array();
     foreach ($get as $key) {
       array_push($array, $key->id);
     }
     // $updateinfo = User::all();
     // foreach ($updateinfo as $key) {
     //   $getbet = bet::where('user_id',$key->id)->get();
     //   foreach ($getbet as $key2) {
     //     $updatebet = bet::findOrFail($key2->id);
     //     $updatebet->owner = $key->name;
     //     $updatebet->save();
     //   }
     // }

     // return expertbet::with('user')->whereIn('event_id',$array)->where('winner','!=',0)->where('winner','!=',3)->orderBy('startingfight','desc')->orderBy('wins','desc')->get();
     return expertbet::with('user')->where('winner','!=',0)->whereIn('event_id',$array)->orderBy('startingfight','desc')->orderBy('wins','desc')->limit(25)->get();
  }

}
