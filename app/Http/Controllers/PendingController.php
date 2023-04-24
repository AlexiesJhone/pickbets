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
    $wins = array();
    $gethighest = expertbet::with('user')->whereIn('event_id',$array)->where('winner',0)->where('turn',20)->max('wins');
    if ($gethighest) {
      array_push($wins, $gethighest);
    }
    $get2nd = expertbet::with('user')->whereIn('event_id',$array)->where('winner',0)->where('turn',20)->where('wins','!=',$gethighest)->max('wins');
    if ($get2nd) {
      array_push($wins, $get2nd);
    }
    $get3rd =  expertbet::with('user')->whereIn('event_id',$array)->where('winner',0)->where('turn',20)->where('wins','!=',$gethighest)->where('wins','!=',$get2nd)->max('wins');
    if ($get3rd) {
      array_push($wins, $get3rd);
    }
    $get4th =  expertbet::with('user')->whereIn('event_id',$array)->where('winner',0)->where('turn',20)->where('wins','!=',$gethighest)->where('wins','!=',$get2nd)->where('wins','!=',$get3rd)->max('wins');
    if ($get4th ) {
      array_push($wins, $get4th );
    }
     return expertbet::with('user')->whereIn('event_id',$array)->where('winner',0)->where('turn',20)->where('wins','>=',5)->whereIn('wins',$wins)->orderBy('startingfight','DESC')->orderBy('wins','DESC')->get();

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
     $wins = array();
     // $gethighest = expertbet::with('user')->whereIn('event_id',$array)->where('winner','!=',0)->max('wins');
     // if ($gethighest) {
     //   array_push($wins, $gethighest);
     // }
     // $get2nd = expertbet::with('user')->whereIn('event_id',$array)->where('winner','!=',0)->where('wins','!=',$gethighest)->max('wins');
     // if ($get2nd) {
     //   array_push($wins, $get2nd);
     // }
     // $get3rd =  expertbet::with('user')->whereIn('event_id',$array)->where('winner','!=',0)->where('wins','!=',$gethighest)->where('wins','!=',$get2nd)->max('wins');
     // if ($get3rd) {
     //   array_push($wins, $get3rd);
     // }
     // $get4th =  expertbet::with('user')->whereIn('event_id',$array)->where('winner','!=',0)->where('wins','!=',$gethighest)->where('wins','!=',$get2nd)->where('wins','!=',$get3rd)->max('wins');
     // if ($get4th) {
     //   array_push($wins, $get4th);
     // }

      // return expertbet::with('user')->whereIn('event_id',$array)->where('winner',0)->where('wins','>=',5)->whereIn('wins',$wins)->orderBy('startingfight','DESC')->orderBy('wins','DESC')->get();
     // return expertbet::with('user')->whereIn('event_id',$array)->where('winner','!=',0)->where('winner','!=',3)->orderBy('startingfight','desc')->orderBy('wins','desc')->get();
     // ORIGINAL TO return expertbet::with('user')->where('winner','!=',0)->whereIn('event_id',$array)->whereIn('wins',$wins)->orderBy('startingfight','desc')->orderBy('wins','desc')->get();
   //   return $posts = Event::with(['expertbet.user' => function ($query) use ($wins) {
   //   $query->whereIn('wins', $wins);
   // }])->whereIn('id',$array)->get();
        $get = Event::whereIn('id', $array)->where('pick',20)
        ->with(['expertbet' => function($query) use ($wins) {
         $query->where('winner', 1)->with('user')->orderBy('wins','desc');
         // ->whereNotIn('wins', [0,1,2,3])
      }])->whereHas('expertbet', function ($query) {
          $query->where('winner',1);
      })->latest()->get();
      $winners=array();
      foreach ($get as $key) {
        $wins = array();
        $gethighest = expertbet::with('user')->where('startingfight',$key->startingfight)->whereIn('event_id',$array)->where('winner','!=',0)->max('wins');
        if ($gethighest) {
          array_push($wins, $gethighest);
        }
        $get2nd = expertbet::with('user')->where('startingfight',$key->startingfight)->whereIn('event_id',$array)->where('winner','!=',0)->where('wins','!=',$gethighest)->max('wins');
        if ($get2nd) {
          array_push($wins, $get2nd);
        }
        $get3rd =  expertbet::with('user')->where('startingfight',$key->startingfight)->whereIn('event_id',$array)->where('winner','!=',0)->where('wins','!=',$gethighest)->where('wins','!=',$get2nd)->max('wins');
        if ($get3rd) {
          array_push($wins, $get3rd);
        }
        $get4th =  expertbet::with('user')->where('startingfight',$key->startingfight)->whereIn('event_id',$array)->where('winner','!=',0)->where('wins','!=',$gethighest)->where('wins','!=',$get2nd)->where('wins','!=',$get3rd)->max('wins');
        if ($get4th) {
          array_push($wins, $get4th);
        }
        $get = Event::whereIn('id', $array)->where('pick',20)->where('startingfight',$key->startingfight)
            ->with(['expertbet' => function($query) use ($wins) {
             $query->where('winner', 1)->with('user')->whereIn('wins',$wins)->orderBy('wins','desc');
             // ->whereNotIn('wins', [0,1,2,3])
          }])->whereHas('expertbet', function ($query) {
              $query->where('winner',1);
          })->latest()->first();
        array_push($winners, $get);
      }
      return $winners;
  }

}
