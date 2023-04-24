<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Results extends Model
{
    use HasFactory;
      protected $table = 'results';

      protected $fillable = [
          'result',
          'fightnumber',
          'event_id',
          'confirm1',
          'confirm2',
      ];

      public function event()
      {
        return $this->belongsTo('App\Models\Event');
      }
}
