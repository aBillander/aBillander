<?php 

namespace App\Traits;

// A Trait for: class ProductionSheet

use App\CustomerOrderLine;
use App\Product;
use App\LotItem;

trait ProductionSheetLotsTrait
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function assignLotsToCustomerOrders()
    {
        // Step 1: Get Customer Orders

        // Step 2: Get products with lot_control > 0

        // Step 3: Get Customer Orders shortlist

        $production_sheet_id = $this->id;

        $lines = CustomerOrderLine::
//                      with(['customerorder', 'product', 'product.allocableLots'])     // Maybe it saves cpu time, but bloats memory...
                      whereHas('customerorder', function ($query) use ($production_sheet_id) {
                        $query->where('production_sheet_id', $production_sheet_id);
                    })
                    ->whereHas('product', function ($query) {
                        $query->where('lot_tracking', '>', 0);
                    })
                    ->get();
        
        // Products shortlist (testing only)
/*
        $p_ids = [5, 11, 31, 37];
        $lines = $lines->whereIn('product_id', $p_ids);
*/

        // Free Lot allocations (remove lotitems, if any)
        foreach ($lines as $line) {
            // code...
            $line->lotitems->each(function($item) {
                $item->delete();
            });
        }

        // Group collection by product
        $lines = $lines->groupBy('product_id');


        // Main loop throu Products
        $messages = [];
        foreach ($lines as $key => $product_lines) {
            // code...
            $product = Product::with('availableLots')->find($key);
            if (!$product)
                continue;

            // abi_r('>> '.$product->reference);

            // Keep allocable Lots only
            $allocable_lots = $product->availableLots
                                ->map(function ($lot, $key) {
                                    $lot->available_qty = $lot->quantity - $lot->allocatedQuantity();
                                    return $lot;
                                })
                                ->reject(function ($lot, $key) {
                                    return $lot->available_qty <= 0;
                                });
            // $allocable_lots are well ordered according to product lot policy

            // Ready for allocation, now!
            // $product_lines has no articular order (Should have?)
            // <sorting code here>
            foreach ($product_lines as $document_line) {
                // code...
                $quantity = $document_line->quantity;

                // Pre-assign lots & build $lots_allocated collection
                foreach ($allocable_lots as $lot) {
                    if ($quantity <= 0) break;
                    # code...
                    // $lot_available_qty = $lot->quantity - $lot->allocatedQuantity();
                    $allocable = $quantity > $lot->available_qty ?
                                        $lot->available_qty :
                                        $quantity      ;

                    if ( $allocable == 0 )
                        continue;

                    $data = [
                        'lot_id' => $lot->id,
                        'is_reservation' => 1,
                        'quantity' => $allocable,
                    ];

                    $lot_item = LotItem::create( $data );
                    $document_line->lotitems()->save($lot_item);
                    // $lots_allocated->push($lot_item);

                    $quantity = $quantity - $allocable;
                }

                if( $quantity > 0 )
                {
                    // Not enough available quantities from lots!!!
                     $messages[] = $product->reference.' - Not enough available quantity from lots!!!';
                }
            }



            foreach ($allocable_lots as $lot) {
                // code...
                // abi_r($lot->id.' - '.$lot->reference.' - '.$lot->quantity.' - '.$lot->available_qty);
            }
        }

        // die();


        foreach ($lines as $line) {
            // code...
            // abi_r($line->customer_order_id.' - '.$line->product->reference.' - '.$line->quantity);
        }

        // die();

        // Step 4: Delete current assignments

        // Step 5: Create new assignments






        return $messages;
    }

}