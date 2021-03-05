<?php

namespace App\Listeners;

use App\Events\CustomerInvoiceEmailing;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerInvoiceEmailingListener
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
     * @param  CustomerInvoiceEmailed  $event
     * @return void
     */
    public function handle(CustomerInvoiceEmailing $event)
    {
        $document = $event->document;

        // Logic here

        $document->close();

    }
}
