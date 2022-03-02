<?php

namespace App\Listeners;

use App\Events\CustomerInvoiceViewed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerInvoiceSetDates
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
     * @param  CustomerInvoiceViewed  $event
     * @return void
     */
    public function handle(CustomerInvoiceViewed $event)
    {
        $event->customerInvoice->{$event->dateField} = \Carbon\Carbon::now();
        $event->customerInvoice->save();
    }
}
