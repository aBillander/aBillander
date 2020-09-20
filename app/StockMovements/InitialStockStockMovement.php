<?php

namespace App\StockMovements;

use App\StockMovement;
use App\WarehouseProductLine;

class InitialStockStockMovement extends StockMovement implements StockMovementInterface
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

        // Price 4 Cost average calculations
        if ($this->price === null) 
        {
            // $this->price = ($this->combination_id > 0) ? $combination->getPriceForStockValuation() : $product->getPriceForStockValuation();
            // No combinations, so far:
            $this->price = ($this->combination_id > 0) ? $product->cost_average : $product->cost_average;
//            ^-- in order to cost average calculation makes sense
        }

        // $price_currency_in = $this->price_currency; // Price in Stock Movement Currency
        $price_in = $this->price;                       // Price in Company's Currency

        if ( $product->getStockByWarehouse( $this->warehouse_id ) > 0.0 ) 
            throw new StockMovementException( l('Cannot set Initial Stock because Product has already stock', 'stockmovements') );

        $current_quantity_onhand = $product->quantity_onhand;

        $quantity_onhand = $this->quantity;
        $this->quantity_before_movement = 0.0;
        $this->quantity_after_movement = $quantity_onhand;

        if ($this->quantity_before_movement == $this->quantity_after_movement)
        {
            // Nothing said about cost price
            // Nothing to do
            // return false;

            // throw new StockMovementException( l('Cannot process Stock Movement because Quantity has not changed', 'stockmovements') );
        }

        if ( !($this->combination_id > 0) ) {
            
            // Cost Average stuff
            // $cost = $product->cost_average;
            $this->cost_price_before_movement = $product->cost_average;

//            if (   $this->quantity  > 0     // if < 0 : This is not a purchase. Maybe a return??
//                && $quantity_onhand > 0     // if = 0 : division by 0 error
//                )
            if (1) {
                $cost_average = $price_in;
            
            }

            $product->cost_average = $cost_average;         // <= calculated by the System
//                $product->cost_price   = $cost_average;       // <= Entered by the User
//                $product->last_purchase_price = $price_in;
            
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
        $product->setStockByWarehouse( $this->warehouse_id, $this->quantity_after_movement );
    }

}