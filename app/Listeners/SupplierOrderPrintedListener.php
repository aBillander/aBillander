<?php

namespace App\Listeners;

use App\Events\SupplierOrderPrinted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SupplierOrderPrintedListener
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
     * @param  SupplierOrderPrinted  $event
     * @return void
     */
    public function handle(SupplierOrderPrinted $event)
    {
        # abi_r($event, true);

        $document = $event->document;

        // Logic here
/*
        $document->close();
*/
    }
}
