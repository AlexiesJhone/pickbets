<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Potmoney extends Model
{
    use HasFactory;
    protected $table = 'potmoney';
    public function event()
    {
      return $this->belongsTo('App\Models\Event');
    }
    public function bet()
    {
      return $this->hasMany('App\Models\bet');
    }
}
