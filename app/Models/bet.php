<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bet extends Model
{
    use HasFactory;
    protected $table = 'bet';
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
    public function prebets()
    {
      return $this->hasMany('App\Models\Prebet');
    }
    public function potmoney()
    {
      return $this->belongsTo('App\Models\Potmoney');
    }

}
