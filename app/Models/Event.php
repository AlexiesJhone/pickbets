<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $table = 'events';

    public function expertbet()
    {
      return $this->hasMany('App\Models\expertbet');
    }
	public function past_expertbet()
    {
      return $this->hasMany('App\Models\past_expertbet');
    }
    public function bets()
    {
      return $this->hasMany('App\Models\bet');
    }
    public function transactions()
    {
      return $this->hasMany('App\Models\Transactions');
    }
    public function results()
    {
      return $this->hasMany('App\Models\Results');
    }
    public function prebets()
    {
      return $this->hasMany('App\Models\Prebet');
    }
    public function pending()
    {
      return $this->hasMany('App\Models\pending');
    }
    public function potmoney()
    {
      return $this->hasMany('App\Models\Potmoney');
    }
    public function startingfights()
    {
      return $this->hasMany('App\Models\startingfights');
    }
}
