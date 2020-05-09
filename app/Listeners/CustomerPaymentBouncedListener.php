<?php

namespace App\Listeners;

use App\Events\CustomerPaymentBounced;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerPaymentBouncedListener
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
    public function handle(CustomerPaymentBounced $event)
    {
        $payment = $event->payment;
        $from_status = $event->from_status;

        $document = $payment->customerinvoice;

        if ( $from_status == 'paid' )   // Kind of "undo payment"
        {

            // Update Document
            $document->checkPaymentStatus();

            // Update Customer Risk
            $customer = $payment->customer;
            $customer->addRisk($payment->amount);
        }

        // Update bankorder
        if ( $bankorder = $payment->bankorder )
            $bankorder->checkStatus();
    }
}
