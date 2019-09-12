<?php 

namespace App;

use \App\Product;
use \App\ProductionOrder;

class ProductionPlanner 
{
    public $orders_planned;
	
    public function __construct( )
    {
        //
        $this->orders_planned = collect([]);
    }


    public function addPlannedMultiLevel($data = [])
    {
        $fields = [ 'created_via', 'status',
                    'product_id', 'required_quantity', 'planned_quantity', 'due_date', 'schedule_sort_order',
                    'work_center_id', 'manufacturing_batch_size', 'machine_capacity', 'units_per_tray', 
                    'warehouse_id', 'production_sheet_id', 'notes'];

        $product = Product::findOrFail( $data['product_id'] );
        $bom     = $product->bom;

//        $production_orders = collect([]);

        // if (!$bom) return NULL;

        // Adjust Manufacturing batch size <- Not here, boy
//        $nbt = ceil($data['planned_quantity'] / $product->manufacturing_batch_size);
//        $order_quantity = $nbt * $product->manufacturing_batch_size;

        $order_quantity = $data['planned_quantity'];
//        + $order_manufacturing_batch_size = $data['manufacturing_batch_size'] ?? $product->manufacturing_batch_size;

        $order = new ProductionOrder([
            'created_via' => $data['created_via'] ?? 'manual',
            'status'      => $data['status']      ?? 'planned',

            'product_id' => $product->id,
            'product_reference' => $product->reference,
            'product_name' => $product->name,
            'procurement_type' => $product->procurement_type,

            'required_quantity' => $order_quantity,
            'planned_quantity' => $order_quantity,
            'product_bom_id' => $bom ? $bom->id : 0,

            'due_date' => $data['due_date'],
//            'schedule_sort_order' => 0,
            'notes' => $data['notes'],

            'work_center_id' => $data['work_center_id'] ?? $product->work_center_id,

            'manufacturing_batch_size' => $product->manufacturing_batch_size,
            'machine_capacity' => $product->machine_capacity, 
            'units_per_tray' => $product->units_per_tray,
//            'warehouse_id' => '',
            'production_sheet_id' => $data['production_sheet_id'],
        ]);

        $this->orders_planned->push($order);





        // Order lines
        if ( !$bom ) return null;

        foreach( $bom->BOMlines as $line ) {
            
             $line_product = $line->product;

             if ( !( 
                       ($line_product->procurement_type == 'manufacture') 
                    || ($line_product->procurement_type == 'assembly') 
                ) ) continue;

/*
            // Calculate $mbs (manufacturing_batch_size) for line
            // According to BOM parent: $mbs = $order_manufacturing_batch_size * ( $line->quantity / $bom->quantity ) * (1.0 + $line->scrap/100.0)
            // According to prodcut:    $mbs = $line_product->manufacturing_batch_size
            // Then;
            $parent_mbs = $order_manufacturing_batch_size * ( $line->quantity / $bom->quantity ) * (1.0 + $line->scrap/100.0);
            $current_mbs = $line_product->manufacturing_batch_size;
            $mbs = ceil( $parent_mbs / $current_mbs ) * $current_mbs;
*/
            $quantity = $order_quantity * ( $line->quantity / $bom->quantity ) * (1.0 + $line->scrap/100.0);

            $order = $this->addPlannedMultiLevel([
                'created_via' => $data['created_via'] ?? 'manual',
                'status'      => $data['status']      ?? 'planned',

                'product_id' => $line_product->id,
                'product_reference' => $line_product->reference,
                'product_name' => $line_product->name,
                'procurement_type' => $line_product->procurement_type,

                'required_quantity' => $quantity,
                'planned_quantity' => $quantity,
                'product_bom_id' => $line_product->bom ? $line_product->bom->id : 0,

                'due_date' => $data['due_date'],
//            'schedule_sort_order' => 0,
                'notes' => $data['notes'],

//                'work_center_id' => $data['work_center_id'] ?? $line_product->work_center_id,
                'manufacturing_batch_size' => $line_product->manufacturing_batch_size,  // $mbs,
    //            'warehouse_id' => '',
                'production_sheet_id' => $data['production_sheet_id'],
            ]);

            // if ($order)                $this->orders_planned->push($order);
        }

        // return $production_orders;
    }


    public function addExtraPlannedMultiLevel($data = [])
    {
        $fields = [ 'created_via', 'status',
                    'product_id', 'required_quantity', 'planned_quantity', 'due_date', 'schedule_sort_order',
                    'work_center_id', 'manufacturing_batch_size', 'machine_capacity', 'units_per_tray', 
                    'warehouse_id', 'production_sheet_id', 'notes'];

        $product = Product::findOrFail( $data['product_id'] );
        $bom     = $product->bom;

//        $production_orders = collect([]);

        // if (!$bom) return NULL;

        // Adjust Manufacturing batch size <- Not here, boy
//        $nbt = ceil($data['planned_quantity'] / $product->manufacturing_batch_size);
//        $order_quantity = $nbt * $product->manufacturing_batch_size;

        $order_quantity = $data['planned_quantity'];
        $order_required = $data['required_quantity'];
//        + $order_manufacturing_batch_size = $data['manufacturing_batch_size'] ?? $product->manufacturing_batch_size;

        $order = new ProductionOrder([
            'created_via' => $data['created_via'] ?? 'manual',
            'status'      => $data['status']      ?? 'planned',

            'product_id' => $product->id,
            'product_reference' => $product->reference,
            'product_name' => $product->name,
            'procurement_type' => $product->procurement_type,

            'required_quantity' => $order_required,
            'planned_quantity' => $order_quantity,
            'product_bom_id' => $bom ? $bom->id : 0,

            'due_date' => $data['due_date'],
//            'schedule_sort_order' => 0,
            'notes' => $data['notes'],

            'work_center_id' => $data['work_center_id'] ?? $product->work_center_id,

            'manufacturing_batch_size' => $product->manufacturing_batch_size,
            'machine_capacity' => $product->machine_capacity, 
            'units_per_tray' => $product->units_per_tray,
//            'warehouse_id' => '',
            'production_sheet_id' => $data['production_sheet_id'],
        ]);

        $this->orders_planned->push($order);





        // Order lines
        if ( !$bom ) return null;

        foreach( $bom->BOMlines as $line ) {
            
             $line_product = $line->product;

             if ( !( 
                       ($line_product->procurement_type == 'manufacture') 
                    || ($line_product->procurement_type == 'assembly') 
                ) ) continue;

/*
            // Calculate $mbs (manufacturing_batch_size) for line
            // According to BOM parent: $mbs = $order_manufacturing_batch_size * ( $line->quantity / $bom->quantity ) * (1.0 + $line->scrap/100.0)
            // According to prodcut:    $mbs = $line_product->manufacturing_batch_size
            // Then;
            $parent_mbs = $order_manufacturing_batch_size * ( $line->quantity / $bom->quantity ) * (1.0 + $line->scrap/100.0);
            $current_mbs = $line_product->manufacturing_batch_size;
            $mbs = ceil( $parent_mbs / $current_mbs ) * $current_mbs;
*/
            $quantity = $order_quantity * ( $line->quantity / $bom->quantity ) * (1.0 + $line->scrap/100.0);

            $order = $this->addPlannedMultiLevel([
                'created_via' => $data['created_via'] ?? 'manual',
                'status'      => $data['status']      ?? 'planned',

                'product_id' => $line_product->id,
                'product_reference' => $line_product->reference,
                'product_name' => $line_product->name,
                'procurement_type' => $line_product->procurement_type,

                'required_quantity' => $quantity,
                'planned_quantity' => $quantity,
                'product_bom_id' => $line_product->bom ? $line_product->bom->id : 0,

                'due_date' => $data['due_date'],
//            'schedule_sort_order' => 0,
                'notes' => $data['notes'],

//                'work_center_id' => $data['work_center_id'] ?? $line_product->work_center_id,
                'manufacturing_batch_size' => $line_product->manufacturing_batch_size,  // $mbs,
    //            'warehouse_id' => '',
                'production_sheet_id' => $data['production_sheet_id'],
            ]);

            // if ($order)                $this->orders_planned->push($order);
        }

        // return $production_orders;
    }
	
}