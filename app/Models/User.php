<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'group_id',
        'role',
    ];

    public function Prebets()
    {
      return $this->hasMany('App\Models\Prebet');
    }
    public function transactions()
    {
      return $this->hasMany('App\Models\Transactions');
    }
    public function bet()
    {
      return $this->hasMany('App\Models\bet');
    }
    public function expertbet()
    {
      return $this->hasMany('App\Models\expertbet');
    }
	public function past_expertbet()
    {
      return $this->hasMany('App\Models\past_expertbet');
    }
    public function group()
    {
      return $this->belongsTo('App\Models\Group');
    }
    public function logs()
    {
      return $this->belongsTo('App\Models\Logs');
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
