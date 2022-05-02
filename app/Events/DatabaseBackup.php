<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DatabaseBackup
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $status;
    public $message;
    public $params;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($status = 'OK', $message = '', $params = [])
    {
        $this->status  = $status;
        $this->message = $message;
        $this->params  = $params;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
