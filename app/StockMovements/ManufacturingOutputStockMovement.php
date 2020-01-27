<?php

namespace App\StockMovements;

use App\StockMovement;
use App\WarehouseProductLine;

// 55
class ManufacturingOutputStockMovement extends StockMovement implements StockMovementInterface
{

    public function prepareToProcess()
    {
        parent::prepareToProcess();
    }

    public function process()
    {
        $this->prepareToProcess();

        // Price 4 Cost average calculations
        $price_currency_in = $this->price_currency;	// Price in Stock Movement Currency
        $price_in = $this->price;						// Price in Company's Currency

        // Update Product
        $product = $this->product;							// Relation loaded in prepareToProcess()
        $quantity_onhand = $product->quantity_onhand + $this->quantity;
        $this->quantity_before_movement = $product->getStockByWarehouse( $this->warehouse_id );

        // Mean Average calculation
        // More at: https://www.linnworks.com/support/inventory-management-and-stock-control/inventory-management-and-stock-control-key-concepts/calculating-stock-value#mean
        if ( !($this->combination_id > 0) ) {
            // $cost = $product->cost_average;
            $this->cost_price_before_movement = $product->cost_price;

            // Recalculate Cost Average if needed

            $this->cost_price_after_movement = $product->cost_price;
        }

        $product->quantity_onhand = $quantity_onhand;
        $product->save();

/*
        // Update Combination
        if ($this->combination_id > 0) {
            $combination = \App\Combination::find($this->combination_id);
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

        $this->quantity_after_movement = $this->quantity_before_movement + $this->quantity;
        $this->save();

        // Update Product-Warehouse relationship (quantity)

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