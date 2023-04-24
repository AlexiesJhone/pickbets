<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prebet extends Model
{
    use HasFactory;

    protected $table = 'prebet';

    public function user()
    {
      return $this->belongsTo('App\Models\User');
    }
    public function events()
    {
      return $this->belongsTo('App\Models\Event');
    }
    public function bets()
    {
      return $this->belongsTo('App\Models\bets');
    }

}
