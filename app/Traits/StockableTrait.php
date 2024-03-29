<?php

namespace App\Traits;

use App\Models\Configuration;
use App\Models\Lot;
use App\Models\Warehouse;
use App\Models\WarehouseProductLine;

trait StockableTrait
{

    public function getStockByWarehouse( $warehouse_id = null )
    {
        $line = WarehouseProductLine::
                          where('product_id', $this->id)
                        ->where('warehouse_id', $warehouse_id)
                        ->first();

        return $line ? $line->quantity : 0.0;
    }

    public function getLotStockByWarehouse( $warehouse_id = null )
    {
        return Lot::
                          where('product_id', $this->id)
                        ->where('warehouse_id', $warehouse_id)
//                        ->where('quantity', '>', 0)
                        ->get()
                        ->sum('quantity');
    }
    
/*
    // Deprecated
    public function getStockByWarehouse( $warehouse )
    {
        $wh_id = is_numeric($warehouse)
                    ? $warehouse
                    : $warehouse->id ;

        $this->load(['warehouses']);

    //    $product = Product::find($this->id);

        $whs = $this->warehouses;
        if ($whs->contains($wh_id)) {
            $wh = $this->warehouses()->get();
            $wh = $wh->find($wh_id);
            $quantity = $wh->pivot->quantity;
        } else {
            $quantity = 0;
        }

        return $quantity;
    }
*/

    public function getStock()
    {
        $warehouses = Warehouse::where('active', '>', 0)->get();
        $count = 0;

        foreach ($warehouses as $warehouse) {
            # code...
            $count += $this->getStockByWarehouse( $warehouse->id );
        }

        return $count;
    }


    public function setStockByWarehouse( $warehouse_id = null, $quantity = 0.0 )
    {
        $line = WarehouseProductLine::
                          where('product_id', $this->id)
                        ->where('warehouse_id', $warehouse_id)
                        ->first();

        if ( $line ) {
        
            if ( $quantity == 0.0 ) {

                // No stock in this warehouse
                $line->delete();
                return true;

            } else {

                // Update stock
                $line->quantity = $quantity;
                $line->save();

            }
        
        } else {
            
            if ( $quantity == 0.0 ) {

                // Nothing to do here:
                return true;

            } else {

                // Check Warehouse
                $warehouse = Warehouse::find( $warehouse_id );

                if (!$warehouse) return false;

                WarehouseProductLine::create([
                                'product_id' => $this->id, 
                                'quantity' => $quantity, 
                                'warehouse_id' => $warehouse_id,
                ]);
            }

        }

        return $line;
    }

}