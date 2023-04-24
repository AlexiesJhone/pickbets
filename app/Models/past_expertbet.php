<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class past_expertbet extends Model
{
    use HasFactory;
    protected $table = 'past_expertbet';
    protected $fillable = [
        'user_id',
        'event_id',
        'potmoney_id',
    ];


    public function user()
    {
      return $this->belongsTo('App\Models\User');
    }
    public function event()
    {
      return $this->belongsTo('App\Models\Event');
    }
    public function selection()
    {
      return $this->hasMany('App\Models\past_selection');
    }
    public function potmoney()
    {
      return $this->belongsTo('App\Models\Potmoney');
    }

}
