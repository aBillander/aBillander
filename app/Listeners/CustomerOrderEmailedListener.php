<?php

namespace App\Listeners;

use App\Events\CustomerOrderEmailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerOrderEmailedListener
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
     * @param  CustomerOrderEmailed  $event
     * @return void
     */
    public function handle(CustomerOrderEmailed $event)
    {
        $document = $event->document;

        // Logic here
/*
        $document->edocument_sent_at = \Carbon\Carbon::now();
        $document->save();

        $document->close();
*/
    }
}
