<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class startingfights extends Model
{
    use HasFactory;
    protected $table = 'startingfightnumber';

    public function events()
    {
      return $this->belongsTo('App\Models\Event');
    }
}
