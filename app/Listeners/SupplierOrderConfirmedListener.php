<?php

namespace App\Listeners;

use App\Events\SupplierOrderConfirmed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SupplierOrderConfirmedListener
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
     * @param  SupplierOrderConfirmed  $event
     * @return void
     */
    public function handle(SupplierOrderConfirmed $event)
    {
        $document = $event->document;

        // Logic here

        // 
        // Vouchers stuff
        // 
        // $document->makePaymentDeadlines();
    }
}
