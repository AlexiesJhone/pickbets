<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    public function event()
    {
      return $this->belongsTo('App\Models\Event');
    }
    public function user()
    {
      return $this->belongsTo('App\Models\User');
    }
}
