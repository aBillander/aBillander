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
    
    public function calculateProductionOrders( $withStock = false )
    {

        // Delete current Production Orders
        $porders = $this->productionorders()->get();
        foreach ($porders as $order) {
//            if ( $order->created_via != 'manual' )
                $order->delete();
        }

        // $errors = [];
        $this->sandbox = new ProductionPlanner( $this->id, $this->due_date );

        // Do the Mambo!
        // STEP 1
        // Calculate raw requirements
        $requirements = $this->customerorderlinesGrouped( $withStock );

        foreach ( $requirements as $pid => $line ) {
            // Discard Products with stock
            if ($line['quantity'] <= 0.0) continue;

            //Batch size stuff
            $nbt = ceil($line['quantity'] / $line['manufacturing_batch_size']);
            $order_quantity = $nbt * $line['manufacturing_batch_size'];


            // Create Production Order
            $orders = $this->sandbox->addPlannedMultiLevel([
                'product_id' => $pid,

                'required_quantity' => $line['quantity'],
                'planned_quantity' => $order_quantity,

                'notes' => '',

                'production_sheet_id' => $this->id,
            ]);

        }
        // Resultado hasta aquí:
        // en ->sandbox->orders_planned hay las ProductionOrder (s) que deben fabricarse según las BOM.
        // La cantidad de Producto Terminado resulta de:
        // - Sumar los Pedidos
        // - Descontar el Stock (si se controla el stock del producto)
        // - Ajustar con el tamaño de lote
        // 
        // Para los semielaborados:
        // - NO se tiene en cuenta el stock
        // - NO se tiene en cuenta el tamaño del lote


        // STEP 2
        // Group Planned Orders, adjust according to onhand stock

        $this->sandbox->groupPlannedOrders( $withStock );

        // Now we may have orders with negative quantity, when $product->quantity_onhand > $order->required_quantity.

        $pIDs = $this->sandbox->getPlannedOrders()
                ->where('planned_quantity', '<', 0.0)
                ->pluck('product_id');

        foreach ($pIDs as $pID) {
            
            $order = $this->sandbox->getPlannedOrders()->firstWhere('product_id', $product->id);
            // this check is necessary, since collection is modified on the fly
            if (  $order->planned_quantity >= 0.0 ) continue;     // Noting to do here

            $quantity = (-1.0) * $order->planned_quantity;      // $quantity is positive now
            $this->sandbox->equalizePlannedMultiLevel($pID, $quantity);

            // ProductionOrders collection has been equalized (balanced negative values)
        }


        // STEP 3
        // Adjust batch size

        $lines_summary = $this->sandbox->getPlannedOrders()
                ->where('manufacturing_batch_size', '>', 1);     // Take only if batch size must be checked

        foreach ($lines_summary as $pid => $line) {

            $order = $this->sandbox->addExtraPlannedMultiLevel($line->product_id, 0.0);

        }


        // STEP 4
        // Release

        $lines_summary = $this->sandbox->getPlannedOrders();



        // Release
        foreach ($lines_summary as $pid => $line) {
            // Create Production Order
            $order = ProductionOrder::createWithLines([
                'created_via' => 'manufacturing',
                'status' => 'released',
                'product_id' => $pid,
//                'product_reference' => $line['reference'],
//                'product_name' => $line['name'],
                'required_quantity' =>  $line['required_quantity'],
                'planned_quantity' => $line['planned_quantity'],
//                'product_bom_id' => 1,
                'due_date' => $this->due_date,
                'notes' => '',
//                
//                'work_center_id' => 2,
                'manufacturing_batch_size' => $line['manufacturing_batch_size'],
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
    
    public function customerorderlinesQuantity()
    {
        $mystuff = $this->customerorderlines;

        $num = $mystuff->groupBy('product_id')->map(function ($row) {
            return $row->sum('quantity');
        });

        return $num;
    }

    public function customerorderlinesGrouped( $withStock = false )
    {
        $lines = $this->customerorderlines
                    ->whereHas('product', function($query) {
                       $query->  where('procurement_type', 'manufacture');
                       $query->orWhere('procurement_type', 'assembly');
                    })
                    ->with('product');

        $num = $lines
                    ->groupBy('product_id')->reduce(function ($result, $group) use ( $withStock ) {
                      $first = $group->first();
                      $product = $first->product;
                      $stock = 0.0;

                      if ($product->procurement_type == 'manufacture')
                      // Assembies will be fit later on (groupPlannedOrders)
                      if ( $withStock )
                      {
                            if ( $product->stock_control )
                                $stock = $product->quantity_onhand;
                      }

                      return $result->put($first->product_id, [
                        'product_id' => $first->product_id,
                        'reference' => $first->reference,
                        'name' => $first->name,
                        'quantity' => $group->sum('quantity') - $stock,
                        // Do I need these two?
//                        'measureunit' => $product->measureunit->name,
//                        'measureunit_sign' => $product->measureunit->sign,

                        'manufacturing_batch_size' => $product->manufacturing_batch_size,
                      ]);
                    }, collect());

        // Sort order
        return $num;        // ->sortBy('reference');
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

    /* Products not Scheduled */
    
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
