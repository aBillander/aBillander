<?php

namespace App\Listeners;

use App\Events\CustomerQuotationPrinted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerQuotationPrintedListener
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
     * @param  CustomerQuotationPrinted  $event
     * @return void
     */
    public function handle(CustomerQuotationPrinted $event)
    {
        # abi_r($event, true);

        $document = $event->document;

        // Logic here
/*
        $document->close();
*/
    }
}
