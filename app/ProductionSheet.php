<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class ProductionSheet extends Model
{
    use ViewFormatterTrait;

    public $sandbox;

//    protected $dates = ['due_date'];
	
    protected $fillable = [ 'sequence_id', 'document_prefix', 'document_id', 'document_reference', 
    						'due_date', 'name', 'notes', 'is_dirty'
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
            $params['withStock'] = false;

        if ( !array_key_exists('mrp_type',  $params) )
            $params['mrp_type'] = 'onorder';

        // Set variables: $withStock, $mrp_type
        extract($params);

        // abi_r($params);abi_r(((int)$withStock ).'  '.$mrp_type);die();

        // Delete current Production Orders
        $porders = $this->productionorders()->get();
        foreach ($porders as $order) {
            // Skip manual orders
            if ( $order->created_via == 'manual' )
                continue;
            
            // Skip finished orders
            if ( $order->status == 'finished' )
                continue;

            // At last:
            $order->delete();
        }

        // $errors = [];
        $this->sandbox = new ProductionPlanner( $this->id, $this->due_date );

        // Do the Mambo!
        // STEP 1.1
        // Calculate raw requirements from Customer Orders
        // Collection of arrays [product_id, stock, quantity, measureunit, ...]
        $requirements = $this->customerorderlinesGrouped( $withStock, $mrp_type );

        foreach ( $requirements as $pid => $line ) {
            // Discard Products with no quantity
            if ($line['quantity'] <= 0.0) continue;

            /*
            // Batch size stuff
            $nbt = ceil($line['quantity'] / $line['manufacturing_batch_size']);
            $order_quantity = $nbt * $line['manufacturing_batch_size'];
            */
            $order_quantity = $line['quantity'];   // Cantidad que debe fabricarse por agrupación de órdenes


            // Create Production Order
            $orders = $this->sandbox->addPlannedMultiLevel([
                'product_id' => $pid,

                'required_quantity' => $line['quantity'],   // Cantidad que debe fabricarse, // y deducido el stock
                'planned_quantity'  => $order_quantity,     // Cantidad que se fabricará, // ajustando el tamaño del lote

                'notes' => '',

                'production_sheet_id' => $this->id,
            ]);

        }
        // Resultado hasta aquí:
        // en ->sandbox->orders_planned hay las ProductionOrder (s) que deben fabricarse según las BOM.
        // La cantidad de Producto Terminado resulta de:
        // - Sumar los Pedidos
        // // Quitado: - Descontar el Stock (si se controla el stock del producto)
        // // Quitado: - Ajustar con el tamaño de lote
        // 
        // Para los semielaborados:
        // - NO se tiene en cuenta el stock
        // - NO se tiene en cuenta el tamaño del lote
        // - NO están agrupados
        //
        // ^-- Esto se ajustará en un paso posterior

        // abi_r($this->sandbox->getPlannedOrders(), true);


        // STEP 1.2
        // Calculate raw requirements from Released Production Orders
        $requirements = $this->productionorders()->where('status', 'released')->get();
        foreach ($requirements as $order) {

            // Create Production Order
            $orders = $this->sandbox->addPlannedMultiLevel([], $order);
        }


        // STEP 2
        // Group Planned Orders, adjust according to onhand stock

        $this->sandbox->groupPlannedOrders( $withStock );

        // abi_r($this->sandbox->getPlannedOrders(), true);

        // Now we may have orders with some onhand quantity
        // Productos que debe "descontarse" el stock físico
        $pIDs = $this->sandbox->getPlannedOrders()
                ->where('product_stock', '>', 0.0)
                ->pluck('product_id');

        // abi_r($pIDs, true); // die();

        foreach ($pIDs as $pID) {       // abi_r($pID); continue;
            
            $order = $this->sandbox->getPlannedOrders()->firstWhere('product_id', $pID);
            // this check is necessary, since collection is modified on the fly
            if (  $order->product_stock <= 0.0 ) continue;     // Noting to do here

            // Cantidad a descontar de la Orden de Fabricación porque hay stock:
            $qty = ( $order->planned_quantity < $order->product_stock ) ?
                    $order->planned_quantity :  // No hace falta fabricar (hay stock), por tanto se descuenta 
                                                //  toda la cantidad planificada (que es lo que se iba a fabricar!)
                    $order->product_stock    ;  // Se descuenta la cantidad en stock, ya que como está en stock, 
                                                // no hace fata fabricar etas unidades

            $quantity = (-1.0) * $qty;  // <= esta es la cantidad que hay que restar a 
                                        // la Orden de Fabricación (y a sus hijos según BOM)
            $this->sandbox->equalizePlannedMultiLevel($pID, $quantity);

            // ProductionOrders collection has been equalized ()
        }

        // abi_r($this->sandbox->getPlannedOrders(), true);
        // die();


        // STEP 3
        // Adjust batch size

        $lines_summary = $this->sandbox->getPlannedOrders()
                ->where('manufacturing_batch_size', '>', 1);     // Take only if batch size must be checked

        // abi_r( $lines_summary , true);

        foreach ($lines_summary as $pid => $line) {

            $order = $this->sandbox->addExtraPlannedMultiLevel($line->product_id, 0.0);

        }


        // STEP 4
        // Release

        $lines_summary = $this->sandbox->getPlannedOrders();



        // Release
        foreach ($lines_summary as $pid => $line) {        // $line es un objeto ProductionOrder

            if ( Configuration::isFalse('MRP_WITH_ZERO_ORDERS') && $line->planned_quantity <= 0.0 )
                continue;       // Nothing to do here

            // Create Production Order
            $order = ProductionOrder::createWithLines([
                'created_via' => 'manufacturing',
                'status' => 'released',
                'product_id' => $pid,
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

    public function customerorderlinesGrouped( $withStock = false, $mrp_type = 'onorder' )
    {
        $this->load('customerorderlines', 'customerorderlines.product');        

        // Filter Lines
        // Filter 1: procurement_type
        $lines = $this->customerorderlines->filter(function ($value, $key) {
            return $value->product && 
                   ( ($value->product->procurement_type == 'manufacture') ||
                     ($value->product->procurement_type == 'assembly'   )    ) &&
                     ($value->quantity > 0.0 ) ;    // Skip cero quantity lines or returned items
        });

        // Filter 2: mrp_type
        $lines = $lines->filter(function ($value, $key) use ($mrp_type) {
            $condition = $mrp_type == 'all' ?
                            ($value->product->mrp_type == 'onorder') || ($value->product->mrp_type == 'reorder') :
                            ($value->product->mrp_type == $mrp_type);
            
            return $value->product && $condition;
        });

        $num = $lines
                    ->groupBy('product_id')->reduce(function ($result, $group) use ( $withStock ) {
                      $first = $group->first();
                      $product = $first->product;
                      $stock = 0.0;

                      /*
                      if ($product->procurement_type == 'manufacture')
                      // Assemblies will be fit later on (groupPlannedOrders)
                      if ( $withStock )
                      {
                            if ( $product->stock_control )
                                $stock = $product->quantity_onhand; // Stock físico
                      }
                      */
                      $stock = $product->quantity_onhand; // Stock físico

                      // Cantidad que se debe fabricar
                      // $quantity = $group->sum('quantity') - $stock;
                      $quantity = $group->sum('quantity');      // Raw requeriments
                      
                      if ( $quantity < 0.0 ) $quantity = 0.0;        // No Manufacturing needed (cero quantity line or returned item)

                      return $result->put($first->product_id, [
                        'product_id' => $first->product_id,
                        'reference' => $first->reference,
                        'name' => $first->name,
                        'stock' => $stock,
                        'quantity' => $quantity,
                        'measure_unit_id' => $product->measure_unit_id,
                        // Do I need these two?
//                        'measureunit' => $product->measureunit->name,
//                        'measureunit_sign' => $product->measureunit->sign,

                        'manufacturing_batch_size' => $product->manufacturing_batch_size,
                      ]);
                    }, collect());


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
