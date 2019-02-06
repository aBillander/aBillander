<?php

namespace App\Listeners;

use App\Events\CustomerShippingSlipEmailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerShippingSlipEmailedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CustomerShippingSlipEmailed  $event
     * @return void
     */
    public function handle(CustomerShippingSlipEmailed $event)
    {
        $document = $event->document;

        // Logic here

        $document->edocument_sent_at = \Carbon\Carbon::now();
        $document->save();

        $document->close();

    }
}
