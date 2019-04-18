<?php

namespace App\Listeners;

use App\Events\CustomerQuotationClosed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerQuotationClosedListener
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
    public function handle(CustomerQuotationClosed $event)
    {
        $document = $event->document;

/*        // Perform Stock Movements
        // Only if invoice has not "left document(s)", i.e., only if it is manually generated
        // 'created_via' != 'aggregate_shipping_slips'
        if ( $document->shouldPerformStockMovements() )
        {
            //
            $document->makeStockMovements();
        }
*/        
    }
}
