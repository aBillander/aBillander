<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use \App\ProductionPlanner;

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
    
    public function calculateProductionOrders()
    {

        // Delete current Production Orders
        $porders = $this->productionorders()->get();
        foreach ($porders as $order) {
//            if ( $order->created_via != 'manual' )
                $order->deleteWithLines();
        }

        // $errors = [];
        $this->sandbox = new ProductionPlanner();

        // Do the Mambo!
        // STEP 1
        // Calculate raw requirements

        foreach ($this->customerorderlinesGrouped() as $pid => $line) {
            //Batch size stuff
            $nbt = ceil($line['quantity'] / $line['manufacturing_batch_size']);
            $order_quantity = $nbt * $line['manufacturing_batch_size'];


            // Create Production Order
            $orders = $this->sandbox->addPlannedMultiLevel([
                'created_via' => 'manufacturing',
                'status' => 'planned',
                'product_id' => $pid,
//                'product_reference' => $line['reference'],
//                'product_name' => $line['name'],
                'required_quantity' => $line['quantity'],
                'planned_quantity' => $order_quantity,
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

        // STEP 2
        // Group Planned Orders, 

        // $this->sandbox->orders_planned = $this->sandbox->orders_planned->groupBy('product_id');

        $lines_summary = $this->sandbox->orders_planned
                ->where('manufacturing_batch_size', '>', 1)     // Take only if batch size must be checked
                ->groupBy('product_id')->reduce(function ($result, $group) {
                  return $result->put($group->first()->product_id, [
                    'product_id' => $group->first()->product_id,
                    'reference' => $group->first()->product_reference,
                    'name' => $group->first()->product_name,
                    'required_quantity' => $group->sum('required_quantity'),
                    'planned_quantity' => $group->sum('planned_quantity'),

                    'manufacturing_batch_size' => $group->first()->product->manufacturing_batch_size,
                  ]);
                }, collect());


        // STEP 3
        // Adjust batch size

        foreach ($lines_summary as $pid => $line) {
            //Batch size stuff
            // Obviously: $line['planned_quantity'] >= $line['required_quantity']
            $nbt = ceil($line['planned_quantity'] / $line['manufacturing_batch_size']);
            $extra_quantity = $nbt * $line['manufacturing_batch_size'] - $line['planned_quantity'];


            // Create Production Order
            $order = $this->sandbox->addExtraPlannedMultiLevel([
                'created_via' => 'manufacturing',
                'status' => 'planned',
                'product_id' => $pid,
//                'product_reference' => $line['reference'],
//                'product_name' => $line['name'],
                'required_quantity' => 0,       // Not required for manufacturing, only to complete batch size
                'planned_quantity' => $extra_quantity,
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

// abi_r($lines_summary);

// abi_r($this->sandbox->orders_planned, true);

        // STEP 4
        // Adjust Release
        // Group Planned Orders
        $lines_summary = $this->sandbox->orders_planned
                ->groupBy('product_id')->reduce(function ($result, $group) {
                  return $result->put($group->first()->product_id, [
                    'product_id' => $group->first()->product_id,
                    'reference' => $group->first()->product_reference,
                    'name' => $group->first()->product_name,
                    'required_quantity' => $group->sum('required_quantity'),
                    'planned_quantity' => $group->sum('planned_quantity'),

                    'manufacturing_batch_size' => $group->first()->product->manufacturing_batch_size,
                  ]);
                }, collect());



        foreach ($lines_summary as $pid => $line) {
            // Create Production Order
            $order = \App\ProductionOrder::createWithLines([
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

            // if (!$order) $errors[] = '<li>['.$line['reference'].'] '.$line['name'].'</li>';
        }

        // STEP 5
        // Some clean-up

        // Delete current -Planned- Production Orders
        /* $porders = $this->productionorders->where('status', 'planned');
        foreach ($porders as $order) {
            $order->deleteWithLines();
        } */

    }
    
    public function calculateProductionOrdersRaw()
    {

        // Delete current Production Orders
        $porders = $this->productionorders()->get();
        foreach ($porders as $order) {
            $order->deleteWithLines();
        }

        // $errors = [];

        // Do the Mambo!
        foreach ($this->customerorderlinesGrouped() as $pid => $line) {
            // Create Production Order
            $order = \App\ProductionOrder::createWithLines([
                'created_via' => 'manufacturing',
//                'status' => 'released',
                'product_id' => $pid,
//                'product_reference' => $line['reference'],
//                'product_name' => $line['name'],
                'planned_quantity' => $line['quantity'],
//                'product_bom_id' => 1,
                'due_date' => $this->due_date,
                'notes' => '',
//                
//                'work_center_id' => 2,
//                'warehouse_id' => 0,
                'production_sheet_id' => $this->id,
            ]);

            // if (!$order) $errors[] = '<li>['.$line['reference'].'] '.$line['name'].'</li>';
        }

    }
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /* Customer Orders */
    
    public function customerorders()
    {
        return $this->hasMany('App\CustomerOrder')->orderBy('shipping_method_id', 'asc');
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

    public function customerorderlinesGrouped()
    {
        $mystuff = collect([]);
        $lines = $this->customerorderlines;     // ()->whereHas('product');

// abi_r($lines, true);

        foreach($lines as $line)
        {
            if ( $line->product )
                if ( ($line->product->procurement_type == 'manufacture') ||
                 ($line->product->procurement_type == 'assembly') ) {

                    $mystuff->push($line);
                }

        }

// abi_r($mystuff, true);

        $num = $mystuff
//                    ->where('procurement_type', 'manufacture')
//                    ->where('procurement_type', 'assembly')
//                    ->filter(function($line) {
//                        return ($line->product->procurement_type == 'manufacture') ||
//                               ($line->product->procurement_type == 'assembly');
//                    })
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

    public function customerorderlinesGroupedByWorkCenter( $work_center_id = null )
    {
        //
        if ($work_center_id === null) $work_center_id = 0;

        $mystuff = collect([]);
        $lines = $this->customerorderlines->load('product');     // ()->whereHas('product');

// abi_r($lines, true);

        foreach($lines as $line)
        {
            if ( $line->product )
                if ( ($work_center_id == 0) ||
                     ($work_center_id == $line->product->work_center_id) ) {

                    $mystuff->push($line);
                }

        }

// abi_r($mystuff, true);

        $num = $mystuff
//                    ->where('procurement_type', 'manufacture')
//                    ->where('procurement_type', 'assembly')
//                    ->filter(function($line) {
//                        return ($line->product->procurement_type == 'manufacture') ||
//                               ($line->product->procurement_type == 'assembly');
//                    })
                    ->groupBy('product_id')->reduce(function ($result, $group) {
                      return $result->put($group->first()->product_id, [
                        'product_id' => $group->first()->product_id,
                        'reference' => $group->first()->reference,
                        'name' => $group->first()->name,
                        'quantity' => $group->sum('quantity'),
                        'measureunit' => $group->first()->product->measureunit->name,
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

//        $num = $mystuff->groupBy('product_id')->map(function ($row) {
//            return $row->sum('planned_quantity');
//        });


        $num = $mystuff->groupBy('product_id')->reduce(function ($result, $group) {
                      return $result->put($group->first()->product_id, collect([
                        'product_id' => $group->first()->product_id,
                        'procurement_type' => $group->first()->procurement_type,
                        'required_quantity' => $group->sum('planned_quantity'),
                        'planned_quantity' => $group->sum('planned_quantity'),

                        'manufacturing_batch_size' => $group->first()->manufacturing_batch_size,
                      ]));
                    }, collect());

//        abi_r($num, true);

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

/*
        $sorted = $num->sortBy(function ($product, $key) {
            abi_r($key);
            abi_r($product);
            return $product['reference'];
        });
*/
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

/*
        $sorted = $num->sortBy(function ($product, $key) {
            abi_r($key);
            abi_r($product);
            return $product['reference'];
        });
*/
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
