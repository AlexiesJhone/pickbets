<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pending extends Model
{
    use HasFactory;
    protected $table = 'pending';
    public function event()
    {
      return $this->belongsTo('App\Models\Event');
    }
    public function user()
    {
      return $this->belongsTo('App\Models\User');
    }
}
