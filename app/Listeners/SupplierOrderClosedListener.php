<?php

namespace App\Listeners;

use App\Events\SupplierOrderClosed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Configuration;

class SupplierOrderClosedListener
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
     * @param  SupplierOrderClosed  $event
     * @return void
     */
    public function handle(SupplierOrderClosed $event)
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

        // 
        // Ecotaxes stuff
        // 
        if ( Configuration::isTrue('ENABLE_ECOTAXES') )
        {
            $document->loadLineEcotaxes();
        }

        // 
        // Cost stuff
        // 
        $document->loadLineCosts();
        
    }
}
