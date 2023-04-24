<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Events;

class betevent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $bet;
    public $startingfight;
    public $id;
    public $user;
    public $alldata;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($bet,$startingfight,$id,$user,$alldata)
    {
        $this->bet=$bet;
        $this->startingfight=$startingfight;
        $this->id=$id;
        $this->user=$user;
        $this->alldata=$alldata;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('insert_bet');
    }
}
