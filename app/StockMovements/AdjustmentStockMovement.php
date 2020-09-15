<?php

namespace App\StockMovements;

use App\StockMovement;
use App\WarehouseProductLine;

class AdjustmentStockMovement extends StockMovement implements StockMovementInterface
{

    public function prepareToProcess()
    {
        parent::prepareToProcess();
    }

    public function process()
    {
        $this->prepareToProcess();

        // Update Product
        $product = $this->product;							// Relation loaded in prepareToProcess()

        if ($this->price === null) 
        {
            // $this->price = ($this->combination_id > 0) ? $combination->getPriceForStockValuation() : $product->getPriceForStockValuation();
            // No combinations, so far:
            $this->price = ($this->combination_id > 0) ? $product->getPriceForStockValuation() : $product->getPriceForStockValuation();
        }

        // Price 4 Cost average calculations
        // $price_currency_in = $this->price_currency; // Price in Stock Movement Currency
        $price_in = $this->price;                       // Price in Company's Currency

        $current_quantity_onhand = $product->quantity_onhand;

        $quantity_onhand = $this->quantity;
        $this->quantity_before_movement = $product->getStockByWarehouse( $this->warehouse_id );
        $this->quantity_after_movement = $quantity_onhand;

        if ($this->quantity_before_movement == $this->quantity_after_movement)
        {
            // Nothing said about cost price
            // Nothing to do
            return false;
            // throw new StockMovementException( l('Cannot process Stock Movement because Quantity has not changed', 'stockmovements') );
        }

        if ( !($this->combination_id > 0) ) {
            
            // Cost Average stuff
            // $cost = $product->cost_average;
            $this->cost_price_before_movement = $product->getPriceForStockValuation();

//            if (   $this->quantity  > 0     // if < 0 : This is not a purchase. Maybe a return??
//                && $quantity_onhand > 0     // if = 0 : division by 0 error
//                )
            if (1) {
                $cost_average = (  $current_quantity_onhand        * $this->cost_price_before_movement 
                                 - $this->quantity_before_movement * $this->cost_price_before_movement
                                 + $this->quantity * $price_in
                                ) / (
                                   $current_quantity_onhand - $this->quantity_before_movement + $this->quantity
                                );
            
                $product->cost_average = $cost_average;         // <= calculated by the System
//                $product->cost_price   = $cost_average;       // <= Entered by the User
//                $product->last_purchase_price = $price_in;
            }

            $this->cost_price_after_movement = $cost_average;

            // Product cost stuff
            $this->product_cost_price = $product->cost_price;
        }

        $this->save();


        // All warehouses
        $product->quantity_onhand = $current_quantity_onhand - $this->quantity_before_movement + $this->quantity;
        $product->save();

        // Update Combination ???
        if ($this->combination_id > 0) {
            $combination = $this->combination;
            $quantity_onhand = $this->quantity;

            // Average price stuff
//            $combination->cost_average = $this->price_in;

            $combination->quantity_onhand += $quantity_onhand;
            $combination->save();
        }


        // Update Product-Warehouse relationship (quantity)
        $product->setStockByWarehouse( $this->warehouse_id, $this->quantity );
    }

}