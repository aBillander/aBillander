<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Models\CustomerInvoice;

class CustomerInvoiceViewed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $customerInvoice;
    public $dateField;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( CustomerInvoice $customerInvoice, $dateField )
    {
        $this->customerInvoice = $customerInvoice;
        $this->dateField       = $dateField;
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
