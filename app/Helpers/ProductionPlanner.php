<?php 

namespace App;

class ProductionPlanner 
{
    public $production_sheet_id;
    public $due_date;
    public $orders_planned;
    public $products_planned;
	
    public function __construct( $production_sheet_id, $due_date )
    {
        //
        $this->production_sheet_id; 
        $this->due_date = $due_date;

        $this->orders_planned = collect([]);
        $this->products_planned = collect([]);
    }


    public function getPlannedOrders()
    {
        return $this->orders_planned;
    }


    public function addPlannedMultiLevel($data = [])
    {
        $product = Product::where('procurement_type', 'manufacture')
                        ->orWhere('procurement_type', 'assembly')
                        ->findOrFail( $data['product_id'] );

        $bom     = $product->bom;

        $required_quantity = $data['required_quantity'];
        $order_quantity    = $data['planned_quantity'];

        $order = new ProductionOrder([
            'created_via' => 'manufacturing',
            'status'      => 'planned',

            'product_id' => $product->id,
            'product_reference' => $product->reference,
            'product_name' => $product->name,
            'procurement_type' => $product->procurement_type,

            'required_quantity' => $required_quantity,
            'planned_quantity' => $order_quantity,
            'product_bom_id' => $bom ? $bom->id : 0,

            'due_date' => $this->due_date,
//            'schedule_sort_order' => 0,
            'notes' => $data['notes'],

            'work_center_id' => $product->work_center_id,

            'manufacturing_batch_size' => $product->manufacturing_batch_size,
            'machine_capacity' => $product->machine_capacity, 
            'units_per_tray' => $product->units_per_tray,
//            'warehouse_id' => '',
            'production_sheet_id' => $this->production_sheet_id,
        ]);

        $this->orders_planned->push($order);





        // Order lines
        if ( !$bom ) return null;

        foreach( $bom->BOMmanufacturablelines as $line ) {

            // Parent planned quantity is child required quantity
            $quantity = $order_quantity * ( $line->quantity / $bom->quantity ) * (1.0 + $line->scrap/100.0);

            $order = $this->addPlannedMultiLevel([
                'created_via' => 'manufacturing',
                'status'      => 'planned',

                'product_id' => $line_product->id,
                'product_reference' => $line_product->reference,
                'product_name' => $line_product->name,
                'procurement_type' => $line_product->procurement_type,

                'required_quantity' => $quantity,
                'planned_quantity' => $quantity,
                'product_bom_id' => $line_product->bom ? $line_product->bom->id : 0,

                'due_date' => $this->due_date,
//            'schedule_sort_order' => 0,
                'notes' => $data['notes'],

                'work_center_id' => $line_product->work_center_id,
                'manufacturing_batch_size' => $line_product->manufacturing_batch_size,  // $mbs,
    //            'warehouse_id' => '',
                'production_sheet_id' => $this->production_sheet_id,
            ]);

        }

        // return ProductionOrders collection
    }



    public function equalizePlannedMultiLevel($product_id, $new_required = 0.0)
    {
        // Retrieve Planned Order for this Product (shouls exists!)
        $order = $this->getPlannedOrders()->where('product_id', $product->id)->first();

        if ( !$order ) return ;     // Noting to do here
//        if (  $order->planned_quantity >= 0,0 ) return ;     // <= carry this check at the top level only!


        // Wanna dance, baby? For sure I do.
        $product_id = $order->product_id;
        $quantity = $new_required;
            
        $this->orders_planned->transform(function ($item, $key) use ($product_id, $quantity) {
                        if($item->product_id == $product_id) {

                            // should equal to zero !!!
                            $item->required_quantity += $quantity;
                            $item->planned_quantity  += $quantity;
                        } 

                        return $item;
                    });


        // In the end, 
        $extra_manufacture = $quantity;

        $bom     = $product->bom;


        // Order lines
        if ( !$bom ) return null;

        foreach( $bom->BOMmanufacturablelines as $line ) {
            
            // $quantity es la cantidad extra que hay que fabricar del hijo.
            // Para el padre es "planned", pero para el hijo es "required"
            $quantity = $extra_manufacture * ( $line->quantity / $bom->quantity ) * (1.0 + $line->scrap/100.0);
                
            $this->equalizePlannedMultiLevel($line->product_id, $quantity);

        }

        // ProductionOrders collection has been equalized
    }





    public function addExtraPlannedMultiLevel($product_id, $new_required = 0.0)
    {
        $product = $this->products_planned->firstWhere('id', $product_id) 
                 ?: Product::where('procurement_type', 'manufacture')
                            ->orWhere('procurement_type', 'assembly')
                            ->findOrFail( $product_id );
        
        $bom     = $product->bom;

        // Retrieve Planned Order for this Product
        $order = $this->getPlannedOrders()->where('product_id', $product->id)->first();
        // If no order, issue one
        if ( !$order )
        {
            $order = new ProductionOrder([
                'created_via' => 'manufacturing',
                'status'      => 'planned',

                'product_id' => $product->id,
                'product_reference' => $product->reference,
                'product_name' => $product->name,
                'procurement_type' => $product->procurement_type,

                'required_quantity' => 0.0,
                'planned_quantity' => 0.0,
                'product_bom_id' => $bom ? $bom->id : 0,

                'due_date' => $this->due_date,
    //            'schedule_sort_order' => 0,
    //            'notes' => $data['notes'],

                'work_center_id' => $product->work_center_id,

                'manufacturing_batch_size' => $product->manufacturing_batch_size,
                'machine_capacity' => $product->machine_capacity, 
                'units_per_tray' => $product->units_per_tray,
    //            'warehouse_id' => '',
                'production_sheet_id' => $this->production_sheet_id,
            ]);

            $this->orders_planned->push($order);
        }


        // Do Continue
        $quantity = $new_required ;

        // Let's see if we have something to manufacture.  Two use cases:
        $diff = $order->planned_quantity - $order->required_quantity;
        // [1] 
        if ( $diff >= $quantity )   // No more manufacturing needed!
        {
            $product_id = $order->product_id;
            $order_required = $quantity;
            $order_planned  = 0.0;
            
            $this->orders_planned->transform(function ($item, $key) use ($product_id, $order_required, $order_planned) {
                            if($item->product_id == $product_id) {

                                $item->required_quantity += $order_required;
                                $item->planned_quantity  += $order_planned;         // Do not check bat size, as it should be checked before call to this function
                            } 

                            return $item;
                        });

            // That's all Folcks!
            return ;
        }

        // [2] $diff < $quantity 
        $product_id = $order->product_id;
        $order_required = $quantity;
        // Total Planned Quantity will be determined by Total Required Quantity and Batch size
        $total_required = $order->required_quantity + $order_required;

        $nbt = ceil($total_required / $order->manufacturing_batch_size);
        $extra_quantity = $nbt * $order->manufacturing_batch_size - $total_required;

        $order_planned  = $total_required + $extra_quantity - $order->planned_quantity;       // Maybe positive or negative amout. Sexy!

        
        $this->orders_planned->transform(function ($item, $key) use ($product_id, $order_required, $order_planned) {
                        if($item->product_id == $product_id) {

                            $item->required_quantity += $order_required;
                            $item->planned_quantity  += $order_planned;         // Do not check bat size, as it should be checked before call to this function
                        } 

                        return $item;
                    });


        // In the end, 
        $extra_manufacture = $extra_quantity;


        // Order lines
        if ( !$bom ) return null;

        foreach( $bom->BOMmanufacturablelines as $line ) {
            
            // $quantity es la cantidad extra que hay que fabricar del hijo.
            // Para el padre es "planned", pero para el hijo es "required"
            $quantity = $extra_manufacture * ( $line->quantity / $bom->quantity ) * (1.0 + $line->scrap/100.0);
                
            $order = $this->addExtraPlannedMultiLevel($line->product_id, $quantity);

        }

    }


    public function groupPlannedOrders( $withStock = false )
    {
        $this->orders_planned = $this->getPlannedOrders()
                ->groupBy('product_id')->reduce(function ($result, $group) {
                      $reduced = $group->first();

                      $reduced->required_quantity = $group->sum('required_quantity');
                      $reduced->planned_quantity  = $group->sum('planned_quantity');

                      return $result->put($reduced->product_id, $reduced);

                }, collect());

        // Load Products into memory
        $pIDs = $this->orders_planned->pluck('product_id');
        $this->products_planned = Product::whereIn('id', $pIDs)->get();

        $products_planned = &$this->products_planned;

        // Stock adjustment
        $this->orders_planned->transform(function($order, $key) use ($products_planned, $withStock) {
                      $product = $products_planned->firstWhere('id', $order->product_id);
                      $product_stock = 0.0;

                      if ($product->procurement_type == 'assembly')
                      // Only Assembies since Manufacture Products are already adjusted in STEP 1
                      if ( $withStock )
                      {
                            if ( $product->stock_control )
                                $product_stock = $product->quantity_onhand;
                      }

            // Watch out! Quantities maybe negative amounts!!! (see STEP 2)
            $order->required_quantity = $order->required_quantity - $product_stock;
            $order->planned_quantity  = $order->planned_quantity  - $product_stock;
            return $order;
        });

    }
	
}