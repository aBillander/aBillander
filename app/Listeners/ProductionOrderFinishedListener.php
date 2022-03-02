<?php

namespace App\Listeners;

use App\Events\ProductionOrderFinished;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Configuration;

class ProductionOrderFinishedListener
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
     * @param  ProductionOrderClosed  $event
     * @return void
     */
    public function handle(ProductionOrderFinished $event)
    {
        $document = $event->document;
        $params   = $event->params ?? [];

        // Perform Stock Movements
        $document->makeStockMovements( $params );

/* Old stuff
        // Perform Stock Movements
        if ( $document->shouldPerformStockMovements() )
        {
            //
            $document->makeStockMovements( $params );
        }
*/
        
    }
}
