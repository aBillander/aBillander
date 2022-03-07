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
        $this->production_sheet_id = $production_sheet_id; 
        $this->due_date = $due_date;

        $this->orders_manual = collect([]);     // Collection of Production Order Models
        $this->orders_planned = collect([]);    // Collection of Production Order Models
        $this->products_planned = collect([]);  // Collection of Product Models
    }


    /*
     *  @Return: Collection of Production Order Models
    */
    public function getPlannedOrders()
    {
        if ( !$this->orders_planned ) return collect([]);

        return $this->orders_planned;
    }


    /*
     *  @Return: Collection of Production Order Models
    */
    public function getManualOrders()
    {
        if ( !$this->orders_manual ) return collect([]);

        return $this->orders_manual;
    }


    /*
     *  @Return: Collection of Production Order Line Models
    */
    public function getManualOrderLines()
    {
        if ( !$this->orders_manual ) return collect([]);

        $pIDs = $this->orders_manual->pluck('id');

        return ProductionOrderLine::whereIn('production_order_id', $pIDs)->get()
                ->groupBy('product_id')->reduce(function ($result, $group) {
                      $reduced = $group->first();

                      $reduced->required_quantity  = $group->sum('required_quantity');

                      return $result->put($reduced->product_id, $reduced);

                }, collect());
    }


    /*
     *  Calcula recursivamente los requerimientos de fabricación (OFs) según el BOM del Producto
     *
     *   @Return: Collection of Production Order Models
    */
    public function addPlannedMultiLevel($data = [], ProductionOrder $order = null)
    {
        // abi_r($data);

if( $order == null )
{
        $product = Product::findOrFail( $data['product_id'] );

        $bom     = $product->bom;

        // abi_r($data);    // die();

        $required_quantity = $data['required_quantity'];
        $order_quantity    = $data['planned_quantity'];

        $order = new ProductionOrder([
            'created_via' => array_key_exists('created_via', $data) ? $data['created_via'] : 'manufacturing',
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
//            'notes' => $data['notes'],

            'work_center_id' => $product->work_center_id,

            'manufacturing_batch_size' => $product->manufacturing_batch_size,
            'machine_capacity' => $product->machine_capacity, 
            'units_per_tray' => $product->units_per_tray,
//            'warehouse_id' => '',
            'production_sheet_id' => $this->production_sheet_id,
        ]);

} else {

        $product = $order->product;

        $bom     = $product->bom;

        $required_quantity = $order->planned_quantity;
        $order_quantity    = $order->planned_quantity;
}


        // Push order
        if ($order->created_via == 'manufacturing')
            $this->orders_planned->push($order);
        
        else 
        if ($order->created_via == 'manual')
            $this->orders_manual->push($order);




        // Order lines
        if ( !$bom ) return null;

        $created_via = $order->created_via;

        foreach( $bom->BOMmanufacturablelines as $line ) {

            // Parent planned quantity is child required quantity
            $quantity = $order_quantity * ( $line->quantity / $bom->quantity ) * (1.0 + $line->scrap/100.0);

            $order = $this->addPlannedMultiLevel([

// Generated by MRP engine, so => 'manufacturing' <= Nope!! Inherit!!
                'created_via' => $created_via,
                
                'product_id' => $line->product_id,

                'required_quantity' => $quantity,
                'planned_quantity' => $quantity,
//                'product_bom_id' => $line_product->bom ? $line_product->bom->id : 0,

//                'notes' => $data['notes'],

                'production_sheet_id' => $this->production_sheet_id,
            ]);

        }

        // return ProductionOrders collection
    }



    /*
     *  Modifica la colección $this->orders_planned (Collection of Production Order Models)
     *  
     *  @Return: none
    */
    // Cantidad que hay que sumar a la cantidad planificada/requerida de product_id
    // cuando se ajusta la cantidad al considerar el stock físico
    public function equalizePlannedMultiLevel($product_id, $new_required = 0.0)
    {
        // abi_r(compact('product_id', 'new_required'));

        // Retrieve Planned Order for this Product (should exist!)
        $order = $this->getPlannedOrders()->firstWhere('product_id', $product_id);

        if ( !$order ) return ;     // <= Noting to do here

        // abi_r('pid, qty:'.$product_id.' '. $new_required);


        // Wanna dance, baby? For sure I do.
        $products_planned = $this->products_planned;
        $product = $products_planned->firstWhere('id', $product_id);

        $quantity = $new_required;
            
        // Adjust Product Production Order quantity
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

        // abi_r($product->bom->BOMmanufacturablelines);die();


        // Order lines
        if ( !$bom ) return null;

        // Adjust Product Children Production Order quantity
        foreach( $bom->BOMmanufacturablelines as $line ) {
            
            // $quantity es la cantidad extra que hay que fabricar del hijo.
            // Para el padre es "planned", pero para el hijo es "required"
            $quantity = $extra_manufacture * ( $line->quantity / $bom->quantity ) * (1.0 + $line->scrap/100.0);
                
            $this->equalizePlannedMultiLevel($line->product_id, $quantity);

        }

        // ProductionOrders collection has been equalized
    }





    /*
     *  Modifica la colección $this->orders_planned (Collection of Production Order Models)
     *  
     *  @Return: none
    */
    // Cantidad que hay que sumar a la cantidad planificada/requerida de product_id
    // cuando se ajusta la cantidad al considerar el lote de fabricación
    public function addExtraPlannedMultiLevel($product_id, $new_required = 0.0)
    {
        $product = $this->products_planned->firstWhere('id', $product_id) 
                 ?: Product::where('procurement_type', 'manufacture')
                            ->orWhere('procurement_type', 'assembly')
                            ->findOrFail( $product_id );

        // Retrieve Planned Order for this Product
        $order = $this->getPlannedOrders()->firstWhere('product_id', $product->id);
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

            $this->orders_planned->push($order);  // Collection of Production Order Models
        }


        // Do Continue
        $quantity = $new_required ;     // Cantidad a sumar

        // Let's see if we have something to manufacture.  Two use cases:
        $diff = $order->planned_quantity - $order->required_quantity;
        // [1] 
        if ( $diff > $quantity )   // No more manufacturing needed! Ya que lo planificado es mayor que lo requerido
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

            // No children to manufacture. That's all Folcks!
            return ;
        }

        // [2] $diff <= $quantity
        // Lo planificado es menor o igual que lo requerido. Lo planificado NO debería ser menor que lo requerido,
        // ya que lo requerido se va ajustando cuando se deduce el stock físico, se ajusta el lote de fabricación, etc.
        //
        // This use case include $quantity = 0 and $diff = 0, that means: "Please, adjust batch size"
        // NOTE: if $quantity = 0 and $diff > 0,
        // this means that batch size has already taken into consideration, and we are in Use Case [1]
        $product_id = $order->product_id;
        $order_required = $quantity;
        // Total Planned Quantity will be determined by Total Required Quantity and Batch size
        $total_required = $order->required_quantity + $order_required;

        $nbt = ceil($total_required / $order->manufacturing_batch_size);
        $extra_quantity = $nbt * $order->manufacturing_batch_size - $total_required;

        // Cantidad planificada a añadir:
        // Maybe positive or negative amout. Sexy!
        $order_planned  = $total_required + $extra_quantity - $order->planned_quantity;

        
        $this->orders_planned->transform(function ($item, $key) use ($product_id, $order_required, $order_planned) {
                        if($item->product_id == $product_id) {

                            $item->required_quantity += $order_required;
                            $item->planned_quantity  += $order_planned;
                        } 

                        return $item;
                    });


        // In the end,
        // Esta es la cantidad que ha variado la Orden de fabricación del Padre:
        $extra_manufacture = $order_planned;

        
        $bom     = $product->bom;

        // Order lines
        if ( !$bom ) return null;

        foreach( $bom->BOMmanufacturablelines as $line ) {
            
            // $quantity es la cantidad extra que hay que fabricar del hijo.
            // Para el padre es "planned", pero para el hijo es "required"
            $quantity = $extra_manufacture * ( $line->quantity / $bom->quantity ) * (1.0 + $line->scrap/100.0);
                
            $order = $this->addExtraPlannedMultiLevel($line->product_id, $quantity);

        }

    }


    /*
     *  Crea una ProductionOrder con la cantidad agrupada de cada Producto 
     *  y carga el Stock físico, perdiente y disponible a cada una
     *  
     *  @Return: Collection of Production Order Models
    */
    public function groupPlannedOrders( $withStock = false )
    {
//        $this->orders_manual = $this->getPlannedOrders();


        $this->orders_planned = $this->getPlannedOrders()
                ->groupBy('product_id')->reduce(function ($result, $group) {
                      $reduced = $group->first();

                      $reduced->required_quantity = $group->sum('required_quantity');
                      $reduced->planned_quantity  = $group->sum('planned_quantity');

                      return $result->put($reduced->product_id, $reduced);

                }, collect());

        // En este punto, planned = required (por construcción) 

        // Guess nothing more to do here, so:
        return ;



        // Load Products into memory
        $pIDs = $this->orders_planned->pluck('product_id');
        // ^-- Estos son todos los Productos que se han de Planificar. Suponemos que los productos en $this->orders_manual ya están incluidos (¿o no?  ???)

        $this->products_planned = Product::with('measureunit')->whereIn('id', $pIDs)->get();

        $products_planned = &$this->products_planned;

        // Manual created Production Orders
        $orders_manual = &$this->orders_manual;

        // Load OnHand Stock (only value) onto Production Order
        // Load OnOrder Stock (only value) onto Production Order
        $this->orders_planned->transform(function($order, $key) use ($products_planned, $withStock, $orders_manual) {
                      $product = $products_planned->firstWhere('id', $order->product_id);
                      $product_stock = 0.0;
                      $product_onorder = 0.0;

                      if ( $withStock )
                      {
                            if ( ( ($product->stock_control > 0) && ($product->mrp_type == 'manual') ) || 
                                    $product->mrp_type == 'reorder'  || 
                                    $product->mrp_type == 'onorder' 
                            ) {
                                $product_stock   = $product->quantity_onhand;
                                $product_onorder = $orders_manual->where('product_id', $product->id)->sum('planned_quantity');
                            }
                      }

            // Load Stock
            $order->product_stock   = $product_stock   > 0 ? $product_stock    : 0.0;
            $order->product_onorder = $product_onorder > 0 ? $product_onorder  : 0.0;

            $order->product_available = $order->product_stock + $order->product_onorder;

            $order->mrp_type = $product->mrp_type;

            // abi_r( $product->reference.' '.$order->product_stock.' '.$order->product_onorder.' '.$order->product_available);

            return $order;
        });

    }

	
    public function groupPlannedOrdersManualOrders()
    {
        $this->orders_manual = $this->getManualOrders();
        // abi_r('Man>');abi_r($this->orders_manual);

        $this->orders_planned = $this->getPlannedOrders();
        // abi_r('Plan>');abi_r($this->orders_planned, true);

        // Merge
        $this->orders_planned = $this->orders_planned->merge( $this->orders_manual );

        // Reject empty orders
        if(1)
        $this->orders_planned = $this->orders_planned
                                        ->reject(function ($value, $key) {
                                            return $value->planned_quantity == 0.0;
                                        });

        // abi_r('***>');abi_r($this->orders_planned, true);

        // Group
        if(1)
        $this->orders_planned = $this->getPlannedOrders()
                ->groupBy('product_id')->reduce(function ($result, $group) {
                      $reduced = $group->first();

                      $reduced->required_quantity = $group->sum('required_quantity');
                      $reduced->planned_quantity  = $group->sum('planned_quantity');

                      return $result->put($reduced->product_id, $reduced);

                }, collect());
    }

    
    public function saveManualOrders()
    {
        // Retrieve & Group Orders
        $this->orders_manual = $this->getPlannedOrders()
                ->groupBy('product_id')->reduce(function ($result, $group) {
                      $reduced = $group->first();

                      $reduced->required_quantity = $group->sum('required_quantity');
                      $reduced->planned_quantity  = $group->sum('planned_quantity');

                      return $result->put($reduced->product_id, $reduced);

                }, collect());
        
        // abi_r('Man>');abi_r($this->orders_manual);

        // Empty
        $this->orders_planned = collect([]);
        // abi_r('Plan>');abi_r($this->orders_planned, true);
    }

    
    public function loadPannedProducts()
    {
        // abi_r('Plan>');abi_r($this->getPlannedOrders()->pluck('product_id')->toArray());
        // abi_r('Man>'); abi_r($this->getManualOrders() ->pluck('product_id')->toArray());

        // Load Products into memory
        $pIDs = $this->getPlannedOrders()->pluck('product_id')->toArray() + $this->getManualOrders()->pluck('product_id')->toArray();
        // Just in case!
        $pIDs = array_unique( $pIDs );

        // abi_r('All>');abi_r($pIDs, true);

        $this->products_planned = Product::with('measureunit')->whereIn('id', $pIDs)->get();

        return $this->products_planned;
    }
}