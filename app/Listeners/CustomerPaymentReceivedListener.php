<?php

namespace App\Listeners;

use App\Events\CustomerPaymentReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerPaymentReceivedListener
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
    public function handle(CustomerPaymentReceived $event)
    {
        $payment = $event->payment;
        $document = $payment->customerinvoice;

        // Update Document
        $document->checkPaymentStatus();

        // Update Customer Risk
        $customer = $payment->customer;
        $customer->removeRisk($payment->amount);

        // Update bankorder
        if ( $bankorder = $payment->bankorder )
            $bankorder->checkStatus();

        // Update cheque
        if ( $cheque = $payment->cheque )
        {
            $cheque->checkStatus();
        }

    }
}
