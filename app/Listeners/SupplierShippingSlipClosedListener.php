<?php

namespace App\Listeners;

use App\Events\SupplierShippingSlipClosed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Configuration;

class SupplierShippingSlipClosedListener
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
     * @param  SupplierShippingSlipClosed  $event
     * @return void
     */
    public function handle(SupplierShippingSlipClosed $event)
    {
        $document = $event->document;

        // Perform Stock Movements
        // Only if invoice has not "left document(s)", i.e., only if it is manually generated
        // 'created_via' != 'aggregate_shipping_slips'
        if ( $document->shouldPerformStockMovements() )
        {
            //
            $document->makeSupplierStockMovements();
        }


        // 
        // Ecotaxes stuff
        // 
        if ( Configuration::isTrue('ENABLE_ECOTAXES') )
        {
            $document->loadLineEcotaxes();
        }
        
    }
}
