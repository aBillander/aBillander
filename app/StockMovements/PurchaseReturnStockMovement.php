<?php

namespace App\StockMovements;

use App\Models\StockMovement;
use App\Models\WarehouseProductLine;

class PurchaseReturnStockMovement extends StockMovement implements StockMovementInterface
{

    public function prepareToProcess()
    {
        parent::prepareToProcess();
    }

    public function process()
    {
        $this->prepareToProcess();

        // Update Product
        $product = $this->product;                          // Relation loaded in prepareToProcess()

        // Price 4 Cost average calculations
        if ($this->price === null) 
        {
            // $this->price = ($this->combination_id > 0) ? $combination->getPriceForStockValuation() : $product->getPriceForStockValuation();
            // No combinations, so far:
            // Reasonable (Lazy) guess for returned Products
            $this->price = ($this->combination_id > 0) ? $product->cost_average : $product->cost_average;
//            ^-- in order to cost average calculation makes sense
        }

        $price_currency_in = $this->price_currency;	// Price in Stock Movement Currency
        $price_in = $this->price;						// Price in Company's Currency

        $current_quantity_onhand = $product->quantity_onhand;

        $quantity_onhand = $current_quantity_onhand - $this->quantity;
        $this->quantity_before_movement = $product->getStockByWarehouse( $this->warehouse_id );
        $this->quantity_after_movement = $this->quantity_before_movement - $this->quantity;

        // Mean Average calculation
        // More at: https://www.linnworks.com/support/inventory-management-and-stock-control/inventory-management-and-stock-control-key-concepts/calculating-stock-value#mean
        if ( !($this->combination_id > 0) ) {
            
            // Cost Average stuff
            // $cost = $product->cost_average;
            $this->cost_price_before_movement = $product->cost_average;

            if ( $quantity_onhand != 0 )        // if = 0 : division by 0 error
            {
                $cost_average = (  $current_quantity_onhand * $this->cost_price_before_movement
                                 - $this->quantity * $price_in
                                ) / $quantity_onhand;
            } else 
            {
                // Heuristic !
                $cost_average = (  $this->cost_price_before_movement
                                 + $price_in
                                ) / 2.0;
            }
            
            $product->cost_average = $cost_average;         // <= calculated by the System
//                $product->cost_price   = $cost_average;       // <= Entered by the User
//            $product->last_purchase_price = $price_in;

            $this->cost_price_after_movement = $cost_average;

            // Product cost stuff
            $this->product_cost_price = $product->cost_price;
        }

        $this->save();


        // All warehouses
        $product->quantity_onhand = $quantity_onhand;
        $product->save();

/*
        // Update Combination
        if ($this->combination_id > 0) {
            $combination = \App\Models\Combination::find($this->combination_id);
            $quantity_onhand = $combination->quantity_onhand + $this->quantity;

            // Average price stuff
            // $cost = $combination->cost_average;
            $cost_average = ($combination->quantity_onhand * $combination->cost_average + $this->quantity * $price_in) / ($combination->quantity_onhand + $this->quantity);
            
            $combination->cost_average = $cost_average;
            $combination->last_purchase_price = $price_in;

            $combination->quantity_onhand = $quantity_onhand;
            $combination->save();
        }
*/


        // Update Product-Warehouse relationship (quantity)
        $product->setStockByWarehouse( $this->warehouse_id, $this->quantity_after_movement );
/*
        $warehouse = $this->warehouse;							// Relation loaded in prepareToProcess()
        
        // Get a line even though product is not in wherhouse. In this case, quantityis 0.0
        $wline = $warehouse->productline( $product->id );
        $quantity = $wline->quantity + $this->quantity;        
            
        if ($quantity != 0) {
            $wline->quantity = $quantity;
            $wline->save();

        } else {
            // Delete record ($quantity = 0) only if it is stored in database
            if ($wline->exists)
            	$wline->delete();
            // $item->wasRecentlyCreated === true => item created (stored) within current request cycle
        }
*/
/*
        // Update Combination-Warehouse relationship (quantity)
        if ($this->combination_id > 0) {
            $whs = $combination->warehouses;
            if ($whs->contains($this->warehouse_id)) {
                $wh = $combination->warehouses()->get();
                $wh = $wh->find($this->warehouse_id);
                $quantity = $wh->pivot->quantity + $this->quantity;
                
                if ($quantity != 0) {
                    $wh->pivot->quantity = $quantity;
                    $wh->pivot->save(); }
                else {
                    // Delete record ($quantity = 0)
                    $combination->warehouses()->detach($this->warehouse_id); }
            } else {
                if ($this->quantity != 0) 
                    $combination->warehouses()->attach($this->warehouse_id, array('quantity' => $this->quantity));
            }
        }
*/
    }

}