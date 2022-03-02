<?php

namespace App\Listeners;

use App\Events\SupplierInvoiceClosed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Configuration;

class SupplierInvoiceClosedListener
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
    public function handle(SupplierInvoiceClosed $event)
    {
        $document = $event->document;

        // Perform Stock Movements
        // Only if invoice has not "left document(s)", i.e., only if it is manually generated
        // 'created_via' != 'aggregate_shipping_slips'
/*
        if ( $document->shouldPerformStockMovements() )
        {
            //
            $document->makeStockMovements();

        }

        //
        $document->stock_status = 'completed';
        $document->save();
*/
/*
        // 
        // Ecotaxes stuff
        // 
        if ( Configuration::isTrue('ENABLE_ECOTAXES') )
        {
            $document->loadLineEcotaxes();
        }
*/

        // 
        // Vouchers stuff
        // 
        $document->makePaymentDeadlines();

        // Update Supplier Risk
        $supplier = $document->supplier;
        $supplier->addRisk($document->total_tax_incl);
        // Or: $supplier->addRisk($document->total_currency_tax_incl, $document->document_currency);
    }
}
