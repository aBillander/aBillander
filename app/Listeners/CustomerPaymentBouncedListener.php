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
        $document = $payment->customerinvoice;
/*
        // Update Document
        $document->open_balance -= $payment->amount;

        if ( abs($document->open_balance) < 0.0001 )
        {
            $document->open_balance = 0.0;
            $document->payment_status = 'paid';
        }
        else
            $document->payment_status = 'halfpaid';

        $document->save();

        // Update Customer Risk
        $customer = $payment->customer;
        $customer->removeRisk($payment->amount);
*/
        // Update bankorder
        if ( $bankorder = $payment->bankorder )
            $bankorder->checkStatus();
    }
}
