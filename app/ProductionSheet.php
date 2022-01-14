<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ProductionSheetLotsTrait;
use App\Traits\ViewFormatterTrait;

class ProductionSheet extends Model
{
    use ProductionSheetLotsTrait;
    use ViewFormatterTrait;

    public $sandbox;

//    protected $dates = ['due_date'];
	
    protected $fillable = [ 'sequence_id', 'document_prefix', 'document_id', 'document_reference', 
    						'type', 'due_date', 'name', 'notes', 'is_dirty'
                          ];

    public static $rules = array(
    	'due_date'    => 'required',
    	);
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    
    public function calculateProductionOrders( $params )
    {
        // Params all set?
        if ( !array_key_exists('withStock', $params) )
            $params['withStock'] = Configuration::isTrue('MRP_WITH_STOCK');

        if ( !array_key_exists('mrp_type',  $params) )
            $params['mrp_type'] = $this->type;

        // Set variables: $withStock, $mrp_type
        extract($params);

        // abi_r($params);abi_r(((int)$withStock ).'  '.$mrp_type);die();

        // Delete current Production Orders
        $porders = $this->productionorders;
        foreach ($porders as $order) {
            // Verbose loop:

            // Skip manual orders_manual    <= Not needed, since we do not distinguish manual from manufacturing so far
//             if ( $order->created_via == 'manual' )
//                continue;
            
            // Skip finished orders
            if ( $order->status == 'finished' )
                continue;

            // At last:
            $order->delete();
        }

        $this->sandbox = new ProductionPlanner( $this->id, $this->due_date );


        // Do the Mambo!

        // STEP 1.1
        // Calculate raw requirements from Production Requirements
        $porders = $this->productionorders->where('status', 'finished');
        $requirements = $this->productionrequirements;    // Supposed "grouped" by construction (requirements are unique, not having duplicates)
        foreach ($requirements as $line) {

            // Create Production Order (multilevel)
            // old stuff: $orders = $this->sandbox->addPlannedMultiLevel([], $order);

            $advanced_quantity = 0.0;
            // Already in stock
            if( $stub = $porders->where('product_id', $line->product_id) )
                $advanced_quantity = $stub->sum('finished_quantity');

            $required_quantity = $line->required_quantity - $advanced_quantity;

            // Create Production Order (multilevel) if needed
            if ( $required_quantity > 0 )
            $orders = $this->sandbox->addPlannedMultiLevel([
                'product_id' => $line->product_id,

                'required_quantity' => $required_quantity,   // Cantidad que debe fabricarse
                'planned_quantity'  => $required_quantity,   // Cantidad que se fabricará

                'notes' => '',

                'production_sheet_id' => $this->id,
            ]);
        }

        // Group & Save Production Orders for later use
        // Could be retrieved with:  $this->sandbox->getManualOrders();
        // No se ha tenido en cuenta el stock hasta aquí
        $this->sandbox->saveManualOrders();


        // abi_r('Man>');abi_r($this->sandbox->orders_manual);
        // abi_r('Plan>');abi_r($this->sandbox->orders_planned, true);


        // STEP 1.2
        // Calculate raw requirements from Customer Orders
        // $requirements : Collection of arrays [product_id, stock, quantity, measureunit, ...]
        $requirements = $this->customerorderlinesGrouped( $withStock, $mrp_type );

//        $orders_manual = $this->sandbox->getManualOrders();

        // abi_r($requirements); die();

        foreach ( $requirements as $pid => $line ) {

            $product = $line['product'];

            $order_quantity = $line['quantity'];   // Cantidad que debe fabricarse por agrupación de órdenes

            if ( $withStock )
            // Productos con mrp_type = 'reorder' se comportan diferente según el Tipo de Hoja
            if ( $product->mrp_type == 'reorder')
            {
                if ( $mrp_type == 'onorder' )
                {
                    // Deduct stock
                    $order_quantity -= ($product->quantity_available + $order_quantity);
                    // ^-- Since $order_quantity is part of quantity_available: $order_quantity = -($product->quantity_available)
                }

                if ( $mrp_type == 'reorder' )
                {
                    if (0)  // Only if we allow Finished Products in Production Requirements; Otherwise, skip
                    if ( $stub = $this->sandbox->getManualOrders()->where('product_id', $line['product_id'])->first() )
                    {
                        // Deduct "future stock"
                        $order_quantity -= $stub->planned_quantity;
                    }
                }

            }

            // Adjust Batch size (although it will be done later; maybe doing this here we save some time ???)
            $batch_size = $product->manufacturing_batch_size;
            $nbt = ceil( $order_quantity / $batch_size );
            $order_quantity = $nbt * $batch_size;

            // So far, so good.
            // Create Production Order (multilevel) if needed
            if ( $order_quantity > 0 )
            $orders = $this->sandbox->addPlannedMultiLevel([
                'product_id' => $pid,

                'required_quantity' => $line['quantity'],   // Cantidad que debe fabricarse, // y deducido el stock
                'planned_quantity'  => $order_quantity,     // Cantidad que se fabricará, // ajustando el tamaño del lote

                'notes' => '',

                'production_sheet_id' => $this->id,
            ]);

        }
        // Resultado hasta aquí:
        //  ->sandbox->orders_manual  hay las ProductionOrder (s) que deben fabricarse según las BOM (Production Requeriments).
        //  ->sandbox->orders_planned hay las ProductionOrder (s) que deben fabricarse según las BOM (Customer Orders).
        // La cantidad de Producto Terminado resulta de:
        // - Sumar los Pedidos
        // - Descontar el Stock (si se controla el stock del producto ??? )
        // - Ajustar con el tamaño de lote en orders_manual (Ahorrará tiempo luego ??? )
        // 
        // Para los semielaborados:
        // - NO se tiene en cuenta el stock
        // - NO se tiene en cuenta el tamaño del lote
        // - NO están agrupados
        //
        // ^-- Esto se ajustará en un paso posterior

        // abi_r($this->sandbox->getPlannedOrders(), true);



        // STEP 2.1
        // Group Planned Orders, adjust according to onhand stock

        $this->sandbox->groupPlannedOrders( $withStock );
        // ^-- Finished products are already grouped!!

        // abi_r($this->sandbox->getManualOrders()); abi_r('***********************');
        // abi_r($this->sandbox->getPlannedOrders(), true);

        // Load Products into memory
        $products_planned = $this->sandbox->loadPannedProducts();

        // Production Requirements act as a source for available stock
        $requirements = $this->productionrequirements;
        $pIDs = $requirements->pluck('product_id');

        // Sólo las órdenes derivadas de Production Requirements son fuente de stock, 
        // ya que los semielaborados generados por BOM a partir de ellos se consumen integramente 
        // en fabricar los Production Requirements
        // Maybe some Requirements are already manufactured:
        $orders_manual = $this->sandbox->getManualOrders()->whereIn('product_id', $pIDs);

        // abi_r($orders_manual); abi_r('***********************');
        // abi_r($orders_manual_lines, true);

        // Manage Stock
        foreach ($this->sandbox->getPlannedOrders() as $order) {
            // code...
            $product = $products_planned->firstWhere('id', $order->product_id); // Should be found

            // Finished Products are already set
            if ($product->procurement_type == 'manufacture')
                continue ;

            // abi_r('- Planned: '.$order->product_id.' : '.$order->planned_quantity);

            // Stock Available
            $stock_available_1 = $withStock ? $product->quantity_available : 0.0;

            // abi_r('+ '.$order->product_id.' : '.$stock_available_1);

            // Stock futuro
            // En esta Hoja de Producción:
            $stock_onorder_1 = $orders_manual->firstWhere('product_id', $order->product_id) ? 
                               $orders_manual->firstWhere('product_id', $order->product_id)->planned_quantity : 
                               0.0;

            // abi_r('+ '.$order->product_id.' : '.$stock_onorder_1);

            $stock_available = $stock_available_1 + $stock_onorder_1;

            // Cantidad a DESCONTAR de la Orden de Fabricación porque hay (o habrá) stock:
            $qty = ( $order->planned_quantity < $stock_available ) ?
                    $order->planned_quantity :  // No hace falta fabricar (hay stock), por tanto se descuenta 
                                                //  toda la cantidad planificada (que es lo que se iba a fabricar!)
                    $stock_available;           // Se descuenta la cantidad en stock (onhand+onorder), ya que 
                                                //  no hace fata fabricar estas unidades porque hay (habrá) stock
            
            $quantity = (-1.0) * $qty;  // <= esta es la cantidad que hay que restar a 
                                        // la Orden de Fabricación (y a sus hijos según BOM)
            
            // abi_r('=> '.$order->product_id.' : '.$quantity);

            if ( $quantity != 0.0 )
                $this->sandbox->equalizePlannedMultiLevel($order->product_id, $quantity);
        }

        // abi_r('Man>');abi_r($this->sandbox->orders_manual);
        // abi_r('Plan>');abi_r($this->sandbox->orders_planned, true);



        // STEP 2.2
        // Group Planned Orders with Manual Orders

        // abi_r($this->sandbox->getPlannedOrders()->pluck('id'));

        // Manual Production Orders are not merged and are managed "As-Is"
        $this->sandbox->groupPlannedOrdersManualOrders();

        // abi_r($this->sandbox->getPlannedOrders()->pluck('id')); // die();
        // abi_r('+++>');abi_r($this->sandbox->getPlannedOrders()->values(), true);



        // STEP 3
        // Adjust batch size

        $lines_summary = $this->sandbox->getPlannedOrders()
                ->where('manufacturing_batch_size', '>', 1);     // Take only if batch size must be checked

        // abi_r( $lines_summary , true);

        foreach ($lines_summary as $pid => $line) {

            $product = $products_planned->firstWhere('id', $line->product_id); // Should be found

            // Finished Products are already set
            if ($product->procurement_type == 'manufacture')
                continue ;

            // abi_r('<<<< '); abi_r($pid);

            $order = $this->sandbox->addExtraPlannedMultiLevel($line->product_id, 0.0);

        }

        // abi_r($this->sandbox->getPlannedOrders()->pluck('id'), true);
        // abi_r($this->sandbox->getPlannedOrders()->pluck('product_id'), true);


        // STEP 4
        // Release

        $lines_summary = $this->sandbox->getPlannedOrders();

        // abi_r($this->sandbox->getPlannedOrders()->pluck('id'));


        // Release
        foreach ($lines_summary as $pid => $line) {        // $line es un objeto ProductionOrder

            // Skip by now
            if ( 0 && Configuration::isFalse('MRP_WITH_ZERO_ORDERS') && $line->planned_quantity <= 0.0 )
                continue;       // Nothing to do here

            // Create Production Order
            $order = ProductionOrder::createWithLines([
                'created_via' => 'manufacturing',
                'status' => 'released',
                'product_id' => $line->product_id,
//                'product_reference' => $line['reference'],
//                'product_name' => $line['name'],
                'required_quantity' =>  $line->required_quantity,
                'planned_quantity' => $line->planned_quantity,
//                'product_bom_id' => 1,
                'due_date' => $this->due_date,
                'notes' => '',
//                
//                'work_center_id' => 2,
                'manufacturing_batch_size' => $line->manufacturing_batch_size,
//                'warehouse_id' => 0,
                'production_sheet_id' => $this->id,
            ]);

        }

        // abi_r('**************');
        // abi_r($this->sandbox->getPlannedOrders()->pluck('product_id'));

        // abi_r('**************');
        // abi_r($this->productionorders()->pluck('product_id'), true);

        // STEP 5
        // Some clean-up ???

    }
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /* Customer Shipping Slips */
    
    public function customershippingslips()
    {
        return $this->hasMany('App\CustomerShippingSlip', 'production_sheet_id')->orderBy('shipping_method_id', 'asc');
    }

    /* Customer Orders */
    
    public function customerorders()
    {
        return $this->hasMany('App\CustomerOrder', 'production_sheet_id')->orderBy('shipping_method_id', 'asc');
    }
    
    public function nbr_customerorders()
    {
        return $this->customerorders->count();
    }
    
    public function customerorderlines()
    {
        return $this->hasManyThrough('App\CustomerOrderLine', 'App\CustomerOrder', 'production_sheet_id', 'customer_order_id', 'id', 'id');
    }


    /*
     *
     *  Group requirements from Customer Orders
     *
     */
    public function customerorderlinesGrouped( $withStock = false, $mrp_type = 'onorder' )
    {
        $this->load('customerorderlines', 'customerorderlines.product');        

        // Filter Lines
        // Filter 1: procurement_type
        // Only manufactured items
        $lines = $this->customerorderlines->filter(function ($value, $key) {
            return $value->product && 
                   ( ($value->product->procurement_type == 'manufacture') ||
                     ($value->product->procurement_type == 'assembly'   )    ) &&
                     ($value->quantity > 0.0 ) ;    // Skip cero quantity lines or returned items
        });

        // Filter 2: mrp_type
        // According to $params (MRP engine call)
/*
        $lines = $lines->filter(function ($value, $key) use ($mrp_type) {
            $condition = $mrp_type == 'all' ?
                            ($value->product->mrp_type == 'onorder') || ($value->product->mrp_type == 'reorder') :
                            ($value->product->mrp_type == $mrp_type);
            
            return $value->product && $condition;
        });
*/
        // ^-- Este filtro no tiene sentido, ya que:
        // * mrp_type == 'onorder' => productos 'reorder' se toman de stock, pero si no hay suficiente, se deberá fabricar
        // * mrp_type == 'reorder' => productos 'reorder' se han de fabricar en la cantidad requerida por los pedidos


//
// Sólo para TESTING

        $lines = $lines->filter(function ($value, $key) use ($mrp_type) {
            $condition = true;
            // Force:
            if ($mrp_type = 'onorder')
                if ( Configuration::isTrue('MRP_ONORDER_WITHOUT_REORDER') )
                    $condition = ($value->product->mrp_type == 'onorder');
            
            return $condition;
        });

// Sólo para TESTING ENDS
//


        $num = $lines
                    ->groupBy('product_id')->reduce(function ($result, $group) use ( $withStock ) {
                      $first = $group->first();
                      $product = $first->product;
                      $stock = 0.0;

//                      $stock = $product->quantity_onhand;   // Stock físico
                      if (  $stock < 0.0 )
                        $stock = 0.0;                       // No onhand stock available

                      // Cantidad que se debe fabricar
                      // $quantity = $group->sum('quantity') - $stock;
                      $quantity = $group->sum('quantity');      // Raw requirements
                      
                      // if ( $quantity < 0.0 ) $quantity = 0.0;        // No Manufacturing needed (cero quantity line or returned item)

                      return $result->put($first->product_id, [
                        'product_id' => $first->product_id,
                        'reference' => $first->reference,
                        'name' => $first->name,
//                        'stock' => $stock,
                        'quantity' => $quantity,
                        'mrp_type' => $product->mrp_type,
                        'measure_unit_id' => $product->measure_unit_id,
                        // Do I need these two?
//                        'measureunit' => $product->measureunit->name,
//                        'measureunit_sign' => $product->measureunit->sign,

                        'manufacturing_batch_size' => $product->manufacturing_batch_size,

                        'product' => $product,
                      ]);
                    }, collect());


        // Skip items if no Manufacturing needed (cero quantity line)
        // Kind of redundant: (see Filter 1 above)
/*
        $num = $num->reject(function ($value, $key) {
                    return $value['quantity'] <= 0.0;
                });
*/

        // abi_r( $num, true);

        // Sort order
        return $num;        // <= colección product_id => [stock, quantity, ...];
    }

    public function customerorderlinesGroupedByWorkCenter( $work_center_id = null )
    {
        //
        if ($work_center_id === null) $work_center_id = 0;

        $mystuff = collect([]);
        $lines = $this->customerorderlines->load('product');     // ()->whereHas('product');

        foreach($lines as $line)
        {
            if ( $line->product )
                if ( ($work_center_id == 0) ||
                     ($work_center_id == $line->product->work_center_id) ) {

                    $mystuff->push($line);
                }

        }

        $num = $mystuff
                    ->groupBy('product_id')->reduce(function ($result, $group) {
                      return $result->put($group->first()->product_id, [
                        'product_id' => $group->first()->product_id,
                        'reference' => $group->first()->reference,
                        'name' => $group->first()->name,
                        'quantity' => $group->sum('quantity'),
                        'measureunit' => $group->first()->product->measureunit->name,
                        'measureunit_sign' => $group->first()->product->measureunit->sign,

                        'manufacturing_batch_size' => $group->first()->product->manufacturing_batch_size,
                      ]);
                    }, collect());

        // Sort order
        return $num->sortBy('reference');
    }


    /*
    *
    * Production Orders 
    *
    */
    
    public function productionorders()
    {
        return $this->hasMany('App\ProductionOrder')->orderBy('work_center_id', 'asc')->orderBy('product_reference', 'asc');
    }
    
    public function productionordersGrouped( $status = null )
    {
        $mystuff = $status ?
                      $this->productionorders->where('status', $status)
                    : $this->productionorders;

        $num = $mystuff->groupBy('product_id')->reduce(function ($result, $group) {
                      return $result->put($group->first()->product_id, collect([
                        'product_id' => $group->first()->product_id,
                        'procurement_type' => $group->first()->procurement_type,
                        'required_quantity' => $group->sum('planned_quantity'),
                        'planned_quantity' => $group->sum('planned_quantity'),

                        'manufacturing_batch_size' => $group->first()->manufacturing_batch_size,
                      ]));
                    }, collect());

        return $num;
    }
    
    public function nbr_productionorders()
    {
        return $this->productionorders->count();
    }
    
    public function nbr_productionrequirements()
    {
        return $this->productionrequirements->count();
    }
    
    public function productionorderlines()
    {
        return $this->hasManyThrough('App\ProductionOrderLine', 'App\ProductionOrder', 'production_sheet_id', 'production_order_id', 'id', 'id');
    }
    
    public function productionorderlinesQuantity()
    {
        $mystuff = $this->productionorderlines;

        $num = $mystuff->groupBy('product_id')->map(function ($row) {
            return $row->sum('required_quantity');
        });

        return $num;
    }

    public function productionorderlinesGrouped()
    {
        $mystuff = $this->productionorderlines;

        $num = $mystuff->groupBy('product_id')->reduce(function ($result, $group) {
                      return $result->put($group->first()->product_id, collect([
                        'product_id' => $group->first()->product_id,
                        'reference' => $group->first()->reference,
                        'name' => $group->first()->name,
                        'quantity' => $group->sum('required_quantity'),
                      ]));
                    }, collect());

        return $num->sortBy('reference');
    }
    
    public function productionordertoollines()
    {
        return $this->hasManyThrough('App\ProductionOrderToolLine', 'App\ProductionOrder', 'production_sheet_id', 'production_order_id', 'id', 'id');
    }

    public function productionordertoollinesGrouped()
    {
        $mystuff = $this->productionordertoollines;

        if ( !$mystuff->count() ) return collect([]);

        $num = $mystuff->groupBy('tool_id')->reduce(function ($result, $group) {
                      return $result->put($group->first()->product_id, collect([
                        'tool_id' => $group->first()->tool_id,
                        'reference' => $group->first()->reference,
                        'name' => $group->first()->name,
                        'location' => $group->first()->location,
                        'quantity' => $group->sum('quantity'),
                      ]));
                    }, collect());

        return $num->sortBy('reference');
    }

    public function productionrequirements()
    {
        return $this->hasMany('App\ProductionRequirement')->orderBy('reference', 'asc');
    }

    /* 
       Products not Scheduled
         Dentro de una Hoja de Producción: son los que hay algún Pedido de Cliente, 
         pero no existe una Orden de Fabricación creada
    */
    
    public function productsNotScheduled()
    {
        $list = [];

        $required  = $this->customerorderlinesGrouped();
        $scheduled = $this->productionordersGrouped();

//        abi_r($required, true);

        if (!$scheduled->count()) return $required;

//        abi_r($scheduled->first(), true);
//         abi_r($scheduled);

        foreach ($required as $pid => $line) {

//            abi_r($line, true);

            $pid = $line['product_id'] ;
            $sch = $scheduled->first(function($item) use ($pid) {
//                abi_r($item, true);
                return $item['product_id'] == $pid;
            });

//                abi_r($sch);

            if ( $sch ) {
                $qty = $line['quantity'] - $sch['planned_quantity'];

                if ($qty) {
                    $line['quantity'] = $qty;
                    $list[] = $line;
                }

            } else {
                $list[] = $line;
            }
        }

        return collect($list)->sortBy('reference');
    }


    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeIsOpen($query)
    {
        return $query->where( 'due_date', '>=', \Carbon\Carbon::now()->toDateString() );
    }
}

/*

This class hab¡ve been moved from /app/Helpers to /app . So:

Try to clear composer cache and then run composer dump-autoload.

composer clear-cache
composer dump-autoload



if you don't want to run commands then try this one, i think it's solve your problem.

-    goto your laravel_project_folder\vendor\composer
-    now open the file autoload_classmap.php and autoload_static.php in your text editor
-    and find your old/backup file name line
-    and rename with actual filename and correct their path.
-    now run your project again and check for the error occurance, i think your problem is solved.



*/
