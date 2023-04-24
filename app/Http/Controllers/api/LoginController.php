<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transactions;
use App\Models\Logs;
use App\Models\Event;
use App\Events\userupdate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class logincontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function createapi()
     {
         // $accessToken = Auth::user()->createToken('authToken')->accessToken;

         return '321';
     }
     public function login(Request $req)
     {
       $login = $req->validate([
           'email' => 'required',
           'password' => 'required'
         ]);

         if (!Auth::attempt($login)) {
           return response(['message' => 'Invalid login credentials']);
         }

         $accessToken = Auth::user()->createToken('authToken')->accessToken;

         return response(['user' => Auth::user(),'access_token' => $accessToken]);
     }
    public function getall()
    {
        // $data = User::find(1);
        // $data->name = $req['id'];
        // $data->save();
        // return $data;
        return User::all();
    }

    public function transferfunds(Request $req)
    {
      $req->validate([
          'id' => 'required',
          'amount' => 'required'
        ]);
        $getuser = User::findOrFail($req['id']);
        if (Hash::check($req['password'], $getuser->password)) {
          DB::transaction(function () use($req,$getuser){
              $getactiveevent = Event::where('status',1)->first();
              $transferfundstransact = new Transactions();
              $transferfundstransact->type = 'Transfer Funds Recieve';
              $transferfundstransact->amount = $req['amount'];
              $transferfundstransact->user_id = $req['id'];
              $transferfundstransact->event_id = $getactiveevent->id;
              $transferfundstransact->startingbalance = $getuser->cash;
              $transferfundstransact->barcode = $req['id'];
              $transferfundstransact->startingbalancecashier = 0;
              $transferfundstransact->endingbalancecashier = 0;
              $getuser->cash = $getuser->cash+$req['amount'];
              $getuser->save();
              $transferfundstransact->endingbalance = $getuser->cash;
              $transferfundstransact->save();
              broadcast(new userupdate($req['id']));
              // event(new userupdate($req['id']));
            });
        }else{
          return ['error'=>'Password not match'];
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
