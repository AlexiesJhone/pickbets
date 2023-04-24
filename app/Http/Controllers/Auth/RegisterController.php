<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Logs;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255','unique:users'],
            'preferred_by' => ['required'],
            'pnumber' => ['required','digits:11', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // return User::create([
        //     'name' => $data['name'],
        //     'username' => $data['username'],
        //     'group_id' => $data['group_id'],
        //     'email' => $data['email'],
        //     'role' => 3,
        //     'password' => Hash::make($data['password']),
        // ]);
        $adduser = new User();
        $adduser->name = $data['name'];
        $adduser->username = $data['username'];
        $adduser->email = $data['email'];
        $adduser->role = 3;
        $adduser->pnumber = $data['pnumber'];
        $adduser->cash = 0;
        $adduser->preferred_by = $data['preferred_by'];
        $adduser->password = Hash::make($data['password']);
		if ($data['group_id']) {
          $adduser->group_id =$data['group_id'];
        }else {
          $adduser->group_id =10;
        }
        $adduser->save();

        $createlogs = new Logs();
        $createlogs->type = 'Register_New_User';
        $createlogs->user_id = $adduser->id;
        $createlogs->message = $adduser->name.' Registered.';
        $createlogs->save();
        return $adduser;
    }
}
