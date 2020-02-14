<?php 

namespace App;

class ProductionPlanner 
{
    public $orders_planned;
    public $products_planned;
	
    public function __construct( )
    {
        //
        $this->orders_planned = collect([]);
        $this->products_planned = collect([]);
    }


    public function getPlannedOrders()
    {
        return $this->orders_planned;
    }


    public function addPlannedMultiLevel($data = [])
    {
        $fields = [ 'created_via', 'status',
                    'product_id', 'required_quantity', 'planned_quantity', 'due_date', 'schedule_sort_order',
                    'work_center_id', 'manufacturing_batch_size', 'machine_capacity', 'units_per_tray', 
                    'warehouse_id', 'production_sheet_id', 'notes'];

        $product = Product::findOrFail( $data['product_id'] );

         if ( !( 
                   ($product->procurement_type == 'manufacture') 
                || ($product->procurement_type == 'assembly') 
            ) )
         return null;

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

         if ( !( 
                   ($product->procurement_type == 'manufacture') 
                || ($product->procurement_type == 'assembly') 
            ) )
         return null;
        
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


    public function groupPlannedOrders()
    {
        $this->orders_planned = $this->getPlannedOrders()
                ->groupBy('product_id')->reduce(function ($result, $group) {
                      $reduced = $group->first();

                      $reduced->required_quantity = $group->sum('required_quantity');
                      $reduced->planned_quantity  = $group->sum('planned_quantity');

                      return $result->put($reduced->product_id, $reduced);

                }, collect());

        // Load Products in memory
        $pIDs = $this->orders_planned->pluck('product_id');
        $this->products_planned = Product::whereIn('id', $pIDs)->get();

        $products_planned = &$this->products_planned;

        // Stock adjustment
        $this->orders_planned->transform(function($order, $key) use ($products_planned) {
            $product_stock = $products_planned->firstWhere('id', $order->product_id)->quantity_onhand;
            abi_r($order->product_id.' - '.$product_stock.' - '.$order->required_quantity);
            $order->required_quantity = $order->required_quantity - $product_stock;
            $order->planned_quantity  = $order->required_quantity;
            return $order;
        });


        abi_r('* *************************** *');
        abi_r($this->getPlannedOrders());

        // die();
    }
	
}