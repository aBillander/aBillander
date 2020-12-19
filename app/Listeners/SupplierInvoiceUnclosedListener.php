<?php

namespace App\Listeners;

use App\Events\SupplierInvoiceUnclosed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SupplierInvoiceUnclosedListener
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
     * @param  SupplierInvoiceClosed  $event
     * @return void
     */
    public function handle(SupplierInvoiceUnclosed $event)
    {
        $document = $event->document;

        // Update Supplier Risk
        $supplier = $document->supplier;
        $supplier->removeRisk($document->total_tax_incl);

        // Revert Stock Movements
        // Only if invoice has not "left document(s)", i.e., only if it is manually generated
        // 'created_via' != 'aggregate_shipping_slips'
/*
        if ( $document->canRevertStockMovements() )
        {
            //
            $document->revertStockMovements();
            //
            $document->stock_status = 'pending';
            $document->save();
        }
*/

        // Check / Perform Vouchers
        $document->payments()->delete();

    }
}
