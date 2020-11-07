<?php

namespace App\Listeners;

use App\Events\WarehouseShippingSlipUnclosed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WarehouseShippingSlipUnclosedListener
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
     * @param  WarehouseShippingSlipClosed  $event
     * @return void
     */
    public function handle(WarehouseShippingSlipUnclosed $event)
    {
        $document = $event->document;

        // Revert Stock Movements
        // Only if invoice has not "left document(s)", i.e., only if it is manually generated
        // 'created_via' != 'aggregate_shipping_slips'
        if ( $document->canRevertStockMovements() || 1 )
        {
            //
            $document->revertStockMovements();
        }

        // Check / Perform Vouchers

    }
}
