<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class derby extends Model
{
    use HasFactory;
    protected $table = 'derby';
    protected $fillable = [
        'fightnumber',
        'entryname1',
        'entryname2',
    ];

    public function expertbet()
    {
      return $this->belongsTo('App\Models\Event');
    }
}
