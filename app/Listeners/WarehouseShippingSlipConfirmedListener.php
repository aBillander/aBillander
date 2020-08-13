<?php

namespace App\Listeners;

use App\Events\WarehouseShippingSlipConfirmed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WarehouseShippingSlipConfirmedListener
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
     * @param  WarehouseShippingSlipConfirmed  $event
     * @return void
     */
    public function handle(WarehouseShippingSlipConfirmed $event)
    {
        $document = $event->document;

        // Logic here

        // 
        // Vouchers stuff
        // 
        // $document->makePaymentDeadlines();
    }
}
