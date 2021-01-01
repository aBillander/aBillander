<?php

namespace App\Listeners;

use App\Events\SupplierInvoiceConfirmed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SupplierInvoiceConfirmedListener
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
     * @param  SupplierInvoiceConfirmed  $event
     * @return void
     */
    public function handle(SupplierInvoiceConfirmed $event)
    {
        $document = $event->document;

        // Logic here

        // 
        // Vouchers stuff
        // 
        // $document->makePaymentDeadlines();
    }
}
