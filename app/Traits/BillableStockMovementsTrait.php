<?php 

namespace App\Traits;

use App\StockMovement;
use App\AssemblyOrder;

use App\Configuration;

trait BillableStockMovementsTrait
{
    use SupplierBillableStockMovementsTrait;
    
    public function makeStockMovements()
    {
        // Let's rock!
        foreach ($this->lines as $line) {
            //
            // Only products, please!!!
            if ( ! ( $line->line_type == 'product' ) ) continue;
            if ( ! ( $line->product_id > 0 ) )         continue;

            if ( Configuration::isTrue('ENABLE_LOTS') && ($line->product->lot_tracking > 0)  )
            {
                $this->makeCustomerStockMovementsLineLots( $line );
                continue;
            }

            if ( $line->product->isPack() ) {
                // Issue Assembly Order in backstage!
                $product = $line->product;
                $order_quantity = $line->quantity;
    
                $assembly_data = [
            //            'created_via' => $data['created_via'] ?? 'manual',
            //            'status'      => $data['status']      ?? 'released',

                        'product_id' => $product->id,
                        'product_reference' => $product->reference,
                        'product_name' => $product->name,

            //            'required_quantity' => $order_required,
                        'planned_quantity' => $order_quantity,
                        // 'finished_quantity'

                        'measure_unit_id' => $product->measure_unit_id,

                        'due_date' => \Carbon\Carbon::now(),
                        // 'finish_date'

            //            'notes' => $data['notes'] ?? null,

                        'work_center_id' => $product->work_center_id,

            //            'manufacturing_batch_size' => $order_manufacturing_batch_size,
                        'warehouse_id' => $this->warehouse_id,      // Configuration::get('DEF_WAREHOUSE')
                    ];

                    $assemblyorder = AssemblyOrder::createWithLines($assembly_data);

                    $assemblyorder->finish();
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

                    'movement_type_id' => StockMovement::SALE_ORDER,

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

    
    public function revertStockMovements()
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

            
            if ( $line->product->isPack() ) {
                // Issue Assembly Order in backstage!
                $product = $line->product;
                $order_quantity = -$line->quantity;
    
                $assembly_data = [
            //            'created_via' => $data['created_via'] ?? 'manual',
            //            'status'      => $data['status']      ?? 'released',

                        'product_id' => $product->id,
                        'product_reference' => $product->reference,
                        'product_name' => $product->name,

            //            'required_quantity' => $order_required,
                        'planned_quantity' => $order_quantity,
                        // 'finished_quantity'

                        'measure_unit_id' => $product->measure_unit_id,

                        'due_date' => \Carbon\Carbon::now(),
                        // 'finish_date'

            //            'notes' => $data['notes'] ?? null,

                        'work_center_id' => $product->work_center_id,

            //            'manufacturing_batch_size' => $order_manufacturing_batch_size,
                        'warehouse_id' => $this->warehouse_id,      // Configuration::get('DEF_WAREHOUSE')
                    ];

                    $assemblyorder = AssemblyOrder::createWithLines($assembly_data);

                    $assemblyorder->finish();
            }

        }   // Lines loop ENDS

        // $this->stock_status = 'pending';
        $this->save();

        return true;
    }



/* *********************************************************************************************** */

    
    // Esto lo llama el listener al cerrar el Albarán. Actualmente es copia sin modificar de Suppliers
    public function makeCustomerStockMovementsLineLots( $line )
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
                $stockmovement->update(['lot_quantity_after_movement' => $stockmovement->quantity]);
                $lot->update(['blocked' => 0]);
            }
        }

        return true;
    }


}