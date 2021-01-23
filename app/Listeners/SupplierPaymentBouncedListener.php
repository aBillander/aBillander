<?php

// <= Just to be the same as CustomerVouchersController, and maybe future use

namespace App\Listeners;

use App\Events\SupplierPaymentBounced;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SupplierPaymentBouncedListener
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
    public function handle(SupplierPaymentBounced $event)
    {
        $payment = $event->payment;
        $from_status = $event->from_status;

        $document = $payment->supplierinvoice;

        if ( $from_status == 'paid' )   // Kind of "undo payment"
        {

            // Update Document
            $document->checkPaymentStatus();

            // Update Supplier Risk
            $supplier = $payment->supplier;
            $supplier->addRisk($payment->amount);
        }

        // Update bankorder
        if ( $bankorder = $payment->bankorder )
            $bankorder->checkStatus();
    }
}
