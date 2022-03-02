<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Payment;

class CustomerPaymentBounced
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $payment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( Payment $payment, $from_status = 'pending' )
    {
        $this->payment     = $payment;
        $this->from_status = $from_status;
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
