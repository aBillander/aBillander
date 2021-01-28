<?php

namespace App\Listeners;

use App\Events\AssemblyOrderFinished;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Configuration;

class AssemblyOrderFinishedListener
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
     * @param  AssemblyOrderClosed  $event
     * @return void
     */
    public function handle(AssemblyOrderFinished $event)
    {
        $document = $event->document;
        $params   = $event->params ?? [];

        // Perform Stock Movements
        // Only if invoice has not "left document(s)", i.e., only if it is manually generated
        // 'created_via' != 'aggregate_shipping_slips'
        if ( $document->shouldPerformStockMovements() )
        {
            //
            $document->makeStockMovements( $params );
        }
        
    }
}
