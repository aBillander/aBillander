<?php

namespace App\Listeners;

use App\Events\SupplierPaymentReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SupplierPaymentPaidListener
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
    public function handle(SupplierPaymentReceived $event)
    {
        $payment = $event->payment;
        $document = $payment->supplierinvoice;

        // Update Document
        $document->checkPaymentStatus();

        // Update Supplier Risk
        $supplier = $payment->supplier;
        $supplier->removeRisk($payment->amount);
/*
        // Update bankorder
        if ( $bankorder = $payment->bankorder )
            $bankorder->checkStatus();
*/
    }
}
