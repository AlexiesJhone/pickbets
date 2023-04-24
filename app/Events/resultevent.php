<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Results;

class resultevent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
      public $result;
      public $fightnumber;
      public $name;
      public $type;
      public $id;
      public $declarator_id;
      public $event_id;
      public $newresult;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($result,$fightnumber,$name,$type,$id,$declarator_id,$event_id,$newresult)
    {
        $this->result=$result;
        $this->fightnumber=$fightnumber;
        $this->name=$name;
        $this->type=$type;
        $this->id=$id;
        $this->declarator_id=$declarator_id;
        $this->event_id=$event_id;
        $this->newresult=$newresult;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('grade');
    }
}
