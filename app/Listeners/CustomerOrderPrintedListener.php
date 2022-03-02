<?php

namespace App\Listeners;

use App\Events\CustomerOrderPrinted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerOrderPrintedListener
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
     * @param  CustomerOrderPrinted  $event
     * @return void
     */
    public function handle(CustomerOrderPrinted $event)
    {
        # abi_r($event, true);

        $document = $event->document;

        // Logic here
/*
        $document->close();
*/
    }
}
