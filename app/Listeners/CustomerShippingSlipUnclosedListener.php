<?php

namespace App\Listeners;

use App\Events\CustomerShippingSlipUnclosed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerShippingSlipUnclosedListener
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
    public function handle(CustomerShippingSlipUnclosed $event)
    {
        $document = $event->document;

        // Update Customer Risk
//        $customer = $document->customer;
//        $customer->removeRisk($document->total_tax_incl);

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
