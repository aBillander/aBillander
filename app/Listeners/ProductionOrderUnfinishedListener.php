<?php

namespace App\Listeners;

use App\Events\ProductionOrderUnfinished;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductionOrderUnfinishedListener
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
     * @param  ProductionOrderClosed  $event
     * @return void
     */
    public function handle(ProductionOrderUnfinished $event)
    {
        $document = $event->document;

        // Update Customer Risk
//        $customer = $document->customer;
//        $customer->removeRisk($document->total_tax_incl);

        // Revert Stock Movements
        // Only if invoice has not "left document(s)", i.e., only if it is manually generated
        // 'created_via' != 'aggregate_shipping_slips'
        if ( $document->canRevertStockMovements() )
        {
            //
            $document->revertStockMovements();
        }

        // Check / Perform Vouchers

    }
}
