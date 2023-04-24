<?php

namespace App\Http\Controllers;
// use Auth;
// use App\Models\bet;
// use App\Models\Event;
// use App\Models\Transactions;
// use Illuminate\Foundation\Validation\ValidatesRequests;

use App\Models\Results;
use App\Events\resultevent;
use App\Events\leaderboards;
use App\Models\Event;
use App\Models\Prebet;
use App\Models\bet;
use App\Models\Potmoney;
use App\Models\User;
use App\Models\Logs;
use Auth;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
     public function login()
     {
       return redirect('home');
     }
     public function topbets()
     {
      //  $getevent = Event:where('status',1)->first();
      // return bet::where('event_id',$getevent->id)->where('winner',0)->latest()->get();
     }
     public function leaders()
     {
       return redirect('home');
     }
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
      if (auth()->user()->role===9 || auth()->user()->role===3) {
        if (!auth()->user()->lock) {
          return view('home');
        }
        else {
          if (auth()->user()->role===9 ) {
            return view('summary');
          }
        }
      }
      elseif(auth()->user()->role===1||auth()->user()->role===5||auth()->user()->role===8) {
        return view('dashboard');
      }elseif(auth()->user()->role===4) {
        if (!auth()->user()->lock) {
          return view('cashier');
        }
        else {
          if (auth()->user()->role===4 ) {
            return view('summary2');
          }
        }
      }
      elseif(auth()->user()->role===6) {
        return view('usermanagement');
      }
      elseif(auth()->user()->role===10) {
        return view('boardadmin');
      }
      elseif(auth()->user()->role===7) {
        return view('Reports');
      }
    }

    public function claims()
    {
        if (auth()->user()->role===0 || auth()->user()->role===3) {
          return view('claims');
        }else {
          // return view('dashboard');
        }
    }
    public function logs()
    {
        if (auth()->user()->role===1) {
          return view('logs');
        }
    }
    public function loginxxx(Request $req)
    {
      $this->validate($req, [
        'username'=>'required',
        'password'=>'required',
      ]);
      $userdata = array('username' => $req['username'],'password' => $req['password']);
      if (auth::attempt($userdata)) {
        return redirect('home');
      }
          // $newlogin = new Transactions();
          // $newlogin->type = 'login';
          // $newlogin->amount = 1;
          // $newlogin->barcode = 1;
          // $newlogin->event_id = 1;
          // $newlogin->save();
          // return redirect('login');
          // return redirect('login');
    }

    public function admin()
    {
      if (auth()->user()->role===0 || auth()->user()->role===3) {
        return view('home');
      }
      elseif(auth()->user()->role===1) {
        return view('dashboard');
      }
    }
    public function usermanagement()
    {
      if(auth()->user()->role===1||Auth::user()->role ===7||Auth::user()->role ===6) {
        return view('usermanagement');
      }
    }
    public function arena()
    {
      if(auth()->user()->role===1) {
        return view('arena');
      }
    }
    public function groups()
    {
      if(auth()->user()->role===1) {
        return view('groups');
      }
    }
    public function Reports()
    {
      if(auth()->user()->role===1||auth()->user()->role===7) {
        return view('reports');
      }
    }
    public function transactionhistory()
    {
      if(auth()->user()->role===3) {
        return view('transactionuser');
      }
    }
    public function cashier()
    {
      if(auth()->user()->role===4) {
        if (!auth()->user()->lock) {
          return view('withdrawalcashier');
        }else {
          if (auth()->user()->role===4) {
            return view('summary2');
          }
        }
      }
    }

}
