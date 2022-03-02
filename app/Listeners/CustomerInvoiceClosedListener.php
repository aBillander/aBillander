<?php

namespace App\Listeners;

use App\Events\CustomerInvoiceClosed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Configuration;

class CustomerInvoiceClosedListener
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
     * @param  CustomerInvoiceClosed  $event
     * @return void
     */
    public function handle(CustomerInvoiceClosed $event)
    {
        $document = $event->document;

        // Perform Stock Movements
        // Only if invoice has not "left document(s)", i.e., only if it is manually generated
        // 'created_via' != 'aggregate_shipping_slips'
        if ( $document->shouldPerformStockMovements() )
        {
            //
            $document->makeStockMovements();

        }

        //
        $document->stock_status = 'completed';
        $document->save();


        // 
        // Ecotaxes stuff
        // 
        if ( Configuration::isTrue('ENABLE_ECOTAXES') )
        {
            $document->loadLineEcotaxes();
        }


        // 
        // Vouchers stuff
        // 
        $document->makePaymentDeadlines();

        // Update Customer Risk
        $customer = $document->customer;
        $customer->addRisk($document->total_tax_incl);
        // Or: $customer->addRisk($document->total_currency_tax_incl, $document->document_currency);
    }
}
