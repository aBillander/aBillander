<?php

namespace App\Listeners;

use App\Events\ProductCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class InitializeStock
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
     * @param  ProductCreated  $event
     * @return void
     */
    public function handle(ProductCreated $event)
    {
        $product = $event->product;
        $data    = $event->data;

        if ($request->input('quantity_onhand')>0) 
        {
            // Create stock movement (Initial Stock)
            $data = [   'date' =>  \Carbon\Carbon::now(), 
                        'document_reference' => '', 
                        'price' => $product->price, 
    //                    'price_tax_inc' => $request->input('price_tax_inc'), 
                        'quantity' => $request->input('quantity_onhand'),  
                        'notes' => '',
                        'product_id' => $product->id, 
                        'currency_id' => \App\Context::getContext()->currency->id, 
                        'conversion_rate' => \App\Context::getContext()->currency->conversion_rate, 
                        'warehouse_id' => $request->input('warehouse_id'), 
                        'movement_type_id' => 10,
                        'model_name' => '', 'document_id' => 0, 'document_line_id' => 0, 'combination_id' => 0, 'user_id' => \Auth::id()
            ];
    
            // Initial Stock
            $stockmovement = \App\StockMovement::create( $data );
    
            // Stock movement fulfillment (perform stock movements)
            $stockmovement->process();
        }
    }
}
