<?php

namespace App\Listeners;

use App\Events\CustomerShippingSlipPrinted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerShippingSlipPrintedListener
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
     * @param  CustomerShippingSlipPrinted  $event
     * @return void
     */
    public function handle(CustomerShippingSlipPrinted $event)
    {
        # abi_r($event, true);

        $document = $event->document;

        // Logic here

        $document->close();

    }
}
