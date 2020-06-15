<?php

namespace App\Listeners;

use App\Events\CustomerInvoicePosted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerInvoicePostedListener
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
     * @param  CustomerInvoicePrinted  $event
     * @return void
     */
    public function handle(CustomerInvoicePosted $event)
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
