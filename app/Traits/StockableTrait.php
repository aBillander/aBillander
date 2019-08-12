<?php 

namespace App\Traits;

use App\Configuration;

trait StockableTrait
{

    public function getStockByWarehouse( $warehouse_id = null )
    {
        $line = \App\WarehouseProductLine::
                          where('product_id', $this->id)
                        ->where('warehouse_id', $warehouse_id)
                        ->first();

        return $line ? $line->quantity : 0.0;
    }
    
/*
    // Deprecated
    public function getStockByWarehouse( $warehouse )
    {
        $wh_id = is_numeric($warehouse)
                    ? $warehouse
                    : $warehouse->id ;

        $this->load(['warehouses']);

    //    $product = \App\Product::find($this->id);

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
        $warehouses = \App\Warehouse::where('active', '>', 0)->get();
        $count = 0;

        foreach ($warehouses as $warehouse) {
            # code...
            $count += $this->getStockByWarehouse( $warehouse->id );
        }

        return $count;
    }


    public function setStockByWarehouse( $warehouse_id = null, $quantity = 0.0 )
    {
        $line = \App\WarehouseProductLine::
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
                $warehouse = \App\Warehouse::find( $warehouse_id );

                if (!$warehouse) return false;

                \App\WarehouseProductLine::create([
                                'product_id' => $this->id, 
                                'quantity' => $quantity, 
                                'warehouse_id' => $warehouse_id,
                ]);
            }

        }

        return $line;
    }

}