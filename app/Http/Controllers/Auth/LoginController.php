<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Logs;
use App\Models\Group;
use App\Events\usersession;
use Session;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
      protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }
    public function logout(Request $request)
    {
      if (Auth::User()->cash!=0&&Auth::User()->role===9) {
        // return ['error'=>'You have balance. you need to cash out first.'];
        return view('logoutcash');
        // return back()->with('error', 'You have balance. you need to cash out first.');
      }elseif (Auth::User()->cash!=0&&Auth::User()->role===4) {
        return view('logoutcash2');
      }
      else {
        $newlogin = new Logs();
        $newlogin->type = 'Logout';
        $newlogin->user_id = auth()->user()->id;
        $newlogin->message = auth()->user()->name.' logged out';
        $newlogin->save();
        $user = User::findOrFail(auth()->user()->id);
        $user->page=null;
        $user->save();
          Auth::logout();

          $request->session()->invalidate();

          $request->session()->regenerateToken();

          return redirect('login');
      }

    }
    public function logoutforce(Request $request)
    {
        $newlogin = new Logs();
        $newlogin->type = 'Forced_Logout';
        $newlogin->user_id = auth()->user()->id;
        $newlogin->message = auth()->user()->name.' logged out';
        $newlogin->save();
        $user = User::findOrFail(auth()->user()->id);
        $user->page=null;
        $user->save();
          Auth::logout();

          $request->session()->invalidate();

          $request->session()->regenerateToken();

          return redirect('login');

    }
    public function authenticate(Request $request)
   {
       $credentials = $request->only('username', 'password');

       if (Auth::attempt($credentials)) {

           $previous_session = Auth::User()->session_id;
                  if ($previous_session) {
                      Session::getHandler()->destroy( auth()->user()->session_id );
                      broadcast(new usersession(auth()->user()->id))->toOthers();
                  }

                  // $accessToken = Auth::user()->createToken('authToken')->accessToken;
                  $getgroup= Group::where('id',Auth::user()->group_id)->first();
                  $getuser= User::where('username',Auth::user()->username)->first();
                  // return redirect('login')->with('info', 'Your Account is deactivated');
                  // return $getuser;
                  // return $getgroup->active;
                  if ($getgroup->active===0) {
                    $user = User::findOrFail(auth()->user()->id);
                    $user->page=null;
                    $user->save();
                      Auth::logout();

                      $request->session()->invalidate();

                      $request->session()->regenerateToken();

                      return redirect('login')->with('info', 'Your Account is deactivated');
                  }
                  elseif ($getuser->active===2) {
                    $user = User::findOrFail(auth()->user()->id);
                    $user->page=null;
                    $user->save();
                      Auth::logout();

                      $request->session()->invalidate();

                      $request->session()->regenerateToken();
                    return redirect('login')->with('info', 'Your account is deactivated');
                  }
    				  elseif ( $getuser->role===2) {
    					   Auth::logout();

                          $request->session()->invalidate();

                          $request->session()->regenerateToken();
                        return redirect('login')->with('info', 'Your Cannot login');
                      }
                  else {
                    $request->session()->regenerate();
                    Auth::user()->session_id = \Session::getId();
                    Auth::user()->save();
                    $newlogin = new Logs();
                    $newlogin->type = 'Login';
                    $newlogin->user_id = auth()->user()->id;
                    $newlogin->message =auth()->user()->name.' logged in';
                    $newlogin->save();
                  }



           // return $accessToken;
       }

       return back()->withErrors([
           'username' => 'The provided credentials do not match our records.',
       ]);
   }


}
