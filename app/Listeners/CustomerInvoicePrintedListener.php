<?php

namespace App\Listeners;

use App\Events\CustomerInvoicePrinted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerInvoicePrintedListener
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
     * @param  CustomerInvoicePrinted  $event
     * @return void
     */
    public function handle(CustomerInvoicePrinted $event)
    {
        # abi_r($event, true);

        $document = $event->document;

        // Logic here

        $document->close();

    }
}
