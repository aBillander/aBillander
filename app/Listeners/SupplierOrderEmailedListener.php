<?php

namespace App\Listeners;

use App\Events\SupplierOrderEmailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SupplierOrderEmailedListener
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
     * @param  SupplierOrderEmailed  $event
     * @return void
     */
    public function handle(SupplierOrderEmailed $event)
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
