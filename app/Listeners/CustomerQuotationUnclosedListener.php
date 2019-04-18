<?php

namespace App\Listeners;

use App\Events\CustomerQuotationUnclosed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerQuotationUnclosedListener
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
     * @param  CustomerQuotationClosed  $event
     * @return void
     */
    public function handle(CustomerQuotationUnclosed $event)
    {
        $document = $event->document;
/*
        // Reset some dates:
        $document->shipping_slip_at = null;
        $document->invoiced_at = null;
        $document->aggregated_at = null;
        $document->backordered_at = null;

//        $document->production_sheet_id = null;

        $document->save();
*/
    }
}
