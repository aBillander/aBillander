<?php

namespace App\Listeners;

use App\Events\SupplierShippingSlipConfirmed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SupplierShippingSlipConfirmedListener
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
     * @param  SupplierShippingSlipConfirmed  $event
     * @return void
     */
    public function handle(SupplierShippingSlipConfirmed $event)
    {
        $document = $event->document;

        // Logic here

        // 
        // Vouchers stuff
        // 
        // $document->makePaymentDeadlines();
    }
}
