<?php

namespace App\Listeners;

use App\Events\CustomerShippingSlipConfirmed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerShippingSlipConfirmedListener
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
     * @param  CustomerShippingSlipConfirmed  $event
     * @return void
     */
    public function handle(CustomerShippingSlipConfirmed $event)
    {
        $document = $event->document;

        // Logic here

        // 
        // Vouchers stuff
        // 
        // $document->makePaymentDeadlines();
    }
}
