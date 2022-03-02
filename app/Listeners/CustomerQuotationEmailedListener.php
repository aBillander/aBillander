<?php

namespace App\Listeners;

use App\Events\CustomerQuotationEmailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerQuotationEmailedListener
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
     * @param  CustomerQuotationEmailed  $event
     * @return void
     */
    public function handle(CustomerQuotationEmailed $event)
    {
        $document = $event->document;

        // Logic here
/*
        $document->edocument_sent_at = \Carbon\Carbon::now();
        $document->save();

        $document->close();
*/
    }
}
