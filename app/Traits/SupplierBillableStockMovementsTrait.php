<?php 

namespace App\Traits;

use App\StockMovement;

use App\Configuration;

trait SupplierBillableStockMovementsTrait
{
    
    public function makeSupplierStockMovements()
    {
        // Let's rock!
        foreach ($this->lines as $line) {
            //
            // Only products, please!!!
            if ( ! ( $line->line_type == 'product' ) ) continue;
            if ( ! ( $line->product_id > 0 ) )         continue;

            if ( Configuration::isTrue('ENABLE_LOTS') && ($line->product->lot_tracking > 0)  )
            {
                $this->makeSupplierStockMovementsLineLots( $line );
                continue;
            }

            //
            $data = [
                    'date' => \Carbon\Carbon::now(),

//                    'stockmovementable_id' => $line->,
//                    'stockmovementable_type' => $line->,

                    'document_reference' => $this->document_reference,

//                    'quantity_before_movement' => $line->,
                    'quantity' => $line->quantity,
                    'measure_unit_id' => $line->measure_unit_id,
//                    'quantity_after_movement' => $line->,

                    'price' => $line->unit_final_price,
                    'price_currency' => $line->unit_final_price,
                    'currency_id' => $this->currency_id,
                    'conversion_rate' => $this->currency_conversion_rate,

                    'notes' => '',

                    'product_id' => $line->product_id,
                    'combination_id' => $line->combination_id,
                    'reference' => $line->reference,
                    'name' => $line->name,

                    'warehouse_id' => $this->warehouse_id,
//                    'warehouse_counterpart_id' => $line->,

                    'movement_type_id' => StockMovement::PURCHASE_ORDER,

//                    'user_id' => $line->,

//                    'inventorycode'
            ];

            $stockmovement = StockMovement::createAndProcess( $data );

            if ( $stockmovement )
            {
                //
                $line->stockmovements()->save( $stockmovement );
            }
        }

        // $this->stock_status = 'completed';
        $this->save();

        return true;
    }

    
    public function revertSupplierStockMovements()
    {
        // Let's rock!
        foreach ($this->lines as $line) {
            //
            // Only products, please!!!
            if ( ! ( $line->product_id > 0 ) ) continue;

            //
            foreach ( $line->stockmovements as $mvt ) {
                # code...
                $data = [
                        'date' => \Carbon\Carbon::now(),

    //                    'stockmovementable_id' => $line->,
    //                    'stockmovementable_type' => $line->,

                        'document_reference' => $mvt->document_reference,

    //                    'quantity_before_movement' => $line->,
                        'quantity' => -$mvt->quantity,
                        'measure_unit_id' => $mvt->measure_unit_id,
    //                    'quantity_after_movement' => $line->,

                        'price' => $mvt->price,
                        'price_currency' => $mvt->price_currency,
                        'currency_id' => $mvt->currency_id,
                        'conversion_rate' => $mvt->conversion_rate,

                        'notes' => '',

                        'product_id' => $mvt->product_id,
                        'combination_id' => $mvt->combination_id,
                        'reference' => $mvt->reference,
                        'name' => $mvt->name,

                        'lot_id' => $mvt->lot_id,

                        'warehouse_id' => $mvt->warehouse_id,
    //                    'warehouse_counterpart_id' => $line->,

                        'movement_type_id' => $mvt->movement_type_id,

    //                    'user_id' => $line->,

    //                    'inventorycode'
                ];

                $stockmovement = StockMovement::createAndProcess( $data );

                if ( $stockmovement )
                {
                    //
                    $line->stockmovements()->save( $stockmovement );
                }

            }   // Movements loop ENDS

            
            // Line Lots stuff
            if ( Configuration::isTrue('ENABLE_LOTS') && ($line->product->lot_tracking > 0)  )
            foreach ( $line->lots as $lot ) {
                # code...
                $lot->update(['blocked' => 1]);
            }

        }   // Lines loop ENDS

        // $this->stock_status = 'pending';
        $this->save();

        return true;
    }



/* *********************************************************************************************** */

    
    public function makeSupplierStockMovementsLineLots( $line )
    {
        // Let's rock!
        foreach ($line->lots as $lot) {
            //
            $data = [
                    'date' => \Carbon\Carbon::now(),

//                    'stockmovementable_id' => $line->,
//                    'stockmovementable_type' => $line->,

                    'document_reference' => $this->document_reference,

//                    'quantity_before_movement' => $line->,
                    'quantity' => $lot->quantity_initial,
                    'measure_unit_id' => $lot->measure_unit_id,
//                    'quantity_after_movement' => $line->,

                    'price' => $line->unit_final_price,
                    'price_currency' => $line->unit_final_price,
                    'currency_id' => $this->currency_id,
                    'conversion_rate' => $this->currency_conversion_rate,

                    'notes' => '',

                    'product_id' => $line->product_id,
                    'combination_id' => $line->combination_id,
                    'reference' => $line->reference,
                    'name' => $line->name,

                    'warehouse_id' => $this->warehouse_id,
//                    'warehouse_counterpart_id' => $line->,

                    'movement_type_id' => StockMovement::PURCHASE_ORDER,

//                    'user_id' => $line->,

//                    'inventorycode'
            ];

            $stockmovement = StockMovement::createAndProcess( $data );

            if ( $stockmovement )
            {
                //
                $line->stockmovements()->save( $stockmovement );

                $lot->stockmovements()->save( $stockmovement );
                $lot->update(['blocked' => 0]);
            }
        }

        return true;
    }


}