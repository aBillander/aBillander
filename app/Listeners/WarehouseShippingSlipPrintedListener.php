<?php

namespace App\Listeners;

use App\Events\WarehouseShippingSlipPrinted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WarehouseShippingSlipPrintedListener
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
     * @param  WarehouseShippingSlipPrinted  $event
     * @return void
     */
    public function handle(WarehouseShippingSlipPrinted $event)
    {
        # abi_r($event, true);

        $document = $event->document;

        // Logic here

        // What to do? Dunno...
        // $document->close();

    }
}
