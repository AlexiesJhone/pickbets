<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class selection extends Model
{
    use HasFactory;

    protected $table = 'selection';

    public function user()
    {
      return $this->belongsTo('App\Models\User');
    }
    public function events()
    {
      return $this->belongsTo('App\Models\Event');
    }
    public function expertbets()
    {
      return $this->belongsTo('App\Models\expertbet');
    }

}
