<?php

namespace App\Listeners;

use App\Events\SupplierInvoicePosted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SupplierInvoicePostedListener
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
     * @param  SupplierInvoicePrinted  $event
     * @return void
     */
    public function handle(SupplierInvoicePosted $event)
    {
        # abi_r($event, true);

        $document = $event->document;

        // Logic here

        if ( $document->status == 'closed' ) {
            $document->posted_at = \Carbon\Carbon::now();
        
            $document->save();
        }

    }
}
