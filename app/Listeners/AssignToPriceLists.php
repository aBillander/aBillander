<?php

namespace App\Listeners;

use App\Events\ProductCreated;
use App\Models\Configuration;
use App\Models\PriceList;
use App\Models\PriceListLine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AssignToPriceLists
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
        if ( Configuration::get('PRODUCT_NOT_IN_PRICELIST') == 'pricelist' ) 
        {
            $product = $event->product;
            $data    = $event->data;

            $plists = PriceList::get();

            foreach ($plists as $list) {

                $price = $list->calculatePrice( $product );
                // $product->pricelists()->attach($list->id, array('price' => $price));
                $line = PriceListLine::create( [ 'product_id' => $product->id, 'price' => $price ] );

                $list->pricelistlines()->save($line);
            }
        }
    }
}
