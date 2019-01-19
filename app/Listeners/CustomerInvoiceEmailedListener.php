<?php

namespace App\Listeners;

use App\Events\CustomerInvoiceEmailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerInvoiceEmailedListener
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
    public function handle(CustomerInvoiceEmailed $event)
    {
        $document = $event->document;

        // Logic here

        $document->edocument_sent_at = \Carbon\Carbon::now();
        $document->save();

        $document->close();

    }
}
