<?php

namespace App\Listeners;

use App\Events\CustomerShippingSlipClosed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerShippingSlipClosedListener
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
     * @param  CustomerShippingSlipClosed  $event
     * @return void
     */
    public function handle(CustomerShippingSlipClosed $event)
    {
        $document = $event->document;

        // Perform Stock Movements
        // Only if invoice has not "left document(s)", i.e., only if it is manually generated
        // 'created_via' != 'aggregate'
        if ( $document->shouldPerformStockMovements() )
        {
            //
            $document->makeStockMovements();
        }
        
    }
}
