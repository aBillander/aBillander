<?php

namespace App\Listeners;

use App\Events\CustomerOrderUnclosed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerOrderUnclosedListener
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
     * @param  CustomerOrderClosed  $event
     * @return void
     */
    public function handle(CustomerOrderUnclosed $event)
    {
        $document = $event->document;

        // Reset some dates:
        $document->shipping_slip_at = null;
        $document->invoiced_at = null;
        $document->aggregated_at = null;
        $document->backordered_at = null;

//        $document->production_sheet_id = null;

        $document->save();

    }
}
