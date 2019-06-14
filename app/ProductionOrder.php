<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductionOrder extends Model
{

//    protected $dates = ['due_date'];
	
    protected $fillable = [ 'sequence_id', 'reference', 'created_via', 
    						'status', 'procurement_type',
    						'product_id', 'combination_id', 'product_reference', 'product_name', 
    						'planned_quantity', 'finished_quantity', 'product_bom_id', 
                            'due_date', 'schedule_sort_order', 'notes', 
    						'work_center_id', 'warehouse_id', 'production_sheet_id'
                          ];

    public static $rules = array(
//    	'id'    => 'required|unique',
    	);
    

    /*
    |--------------------------------------------------------------------------
    | Methodss
    |--------------------------------------------------------------------------
    */
    
    public static function createPlannedMultiLevel($data = [])
    {
        $fields = [ 'created_via', 'status',
                    'product_id', 'planned_quantity', 'due_date', 'schedule_sort_order',
                    'work_center_id', 'warehouse_id', 'production_sheet_id', 'notes'];

        $product = \App\Product::findOrFail( $data['product_id'] );
        $bom     = $product->bom;

        $production_orders = collect([]);

        // if (!$bom) return NULL;

        // Adjust Manufacturing batch size <- Not here, boy
//        $nbt = ceil($data['planned_quantity'] / $product->manufacturing_batch_size);
//        $order_quantity = $nbt * $product->manufacturing_batch_size;

        $order_quantity = $data['planned_quantity'];

        $order = \App\ProductionOrder::create([
            'created_via' => $data['created_via'] ?? 'manual',
            'status'      => $data['status']      ?? 'planned',

            'product_id' => $product->id,
            'product_reference' => $product->reference,
            'product_name' => $product->name,
            'procurement_type' => $product->procurement_type,

            'planned_quantity' => $order_quantity,
            'product_bom_id' => $bom ? $bom->id : 0,

            'due_date' => $data['due_date'],
//            'schedule_sort_order' => 0,
            'notes' => $data['notes'],

            'work_center_id' => $data['work_center_id'] ?? $product->work_center_id,
//            'warehouse_id' => '',
            'production_sheet_id' => $data['production_sheet_id'],
        ]);

        $production_orders->push($order);





        // Order lines
        if ( !$bom ) return $production_orders;

        foreach( $bom->BOMlines as $line ) {
            
             $line_product = $line->product;

             if ( !( 
                       ($line_product->procurement_type == 'manufacture') 
                    || ($line_product->procurement_type == 'assembly') 
                ) ) continue;


            $order = \App\ProductionOrder::createPlannedMultiLevel([
                'created_via' => $data['created_via'] ?? 'manual',
                'status'      => $data['status']      ?? 'planned',

                'product_id' => $line_product->id,
                'product_reference' => $line_product->reference,
                'product_name' => $line_product->name,
                'procurement_type' => $line_product->procurement_type,

                'planned_quantity' => $order_quantity * ( $line->quantity / $bom->quantity ) * (1.0 + $line->scrap/100.0),
                'product_bom_id' => $line_product->bom ? $line_product->bom->id : 0,

                'due_date' => $data['due_date'],
//            'schedule_sort_order' => 0,
                'notes' => $data['notes'],

//                'work_center_id' => $data['work_center_id'] ?? $line_product->work_center_id,
    //            'warehouse_id' => '',
                'production_sheet_id' => $data['production_sheet_id'],
            ]);

            $production_orders = $production_orders->merge($order);
        }

        return $production_orders;
    }
    
    public static function createWithLines($data = [])
    {
        $fields = [ 'created_via', 'status', 'procurement_type',
                    'product_id', 'planned_quantity', 'due_date', 
                    'work_center_id', 'warehouse_id', 'production_sheet_id', 'notes'];

        $product = \App\Product::with('bomitems')->with('boms')
                                ->with('producttools')
                                ->findOrFail( $data['product_id'] );
        $bomitem = $product->bomitem();
        $bom     = $product->bom;

        $tools     = $product->tools;

        // if (!$bom) return NULL;

        // Adjust Manufacturing batch size
        $nbt = ceil($data['planned_quantity'] / $product->manufacturing_batch_size);
        $order_quantity = $nbt * $product->manufacturing_batch_size;

        $order = \App\ProductionOrder::create([
            'created_via' => $data['created_via'] ?? 'manual',
            'status'      => $data['status']      ?? 'released',
            'procurement_type' => $product->procurement_type,

            'product_id' => $product->id,
            'product_reference' => $product->reference,
            'product_name' => $product->name,

            'planned_quantity' => $order_quantity,
            'product_bom_id' => $bom->id ?? 0,

            'due_date' => $data['due_date'],
            'schedule_sort_order' => $product->category_id,
            'notes' => $data['notes'],

            'work_center_id' => $data['work_center_id'] ?? $product->work_center_id,
//            'warehouse_id' => '',
            'production_sheet_id' => $data['production_sheet_id'],
        ]);


        // Order lines
        if ( $bomitem )
        {
            // BOM quantities
            $line_qty = $order_quantity * $bomitem->quantity / $bom->quantity;

            foreach( $bom->BOMlines as $line ) {
                
                 $line_product = \App\Product::with('measureunit')->findOrFail( $line->product_id );

                 $order_line = \App\ProductionOrderLine::create([
                    'type' => 'product',
                    'product_id' => $line_product->id,
                    'reference' => $line_product->reference,
                    'name' => $line_product->name, 

    //                'base_quantity', 
                    'required_quantity' => $line_qty * $line->quantity * (1.0 + $line->scrap/100.0), 
                    // Assume 1 product == 1 measure unit O_O
                    'measure_unit_id' => $line_product->measure_unit_id,
    //                'warehouse_id'
                ]);

                $order->productionorderlines()->save($order_line);
            }
        }


        // Order Tools
        if ( $tools->count() )
        {
            // BOM quantities
            $line_qty = $order_quantity;

            foreach( $tools as $tool ) {

                 $order_tool_line = \App\ProductionOrderToolLine::create([
                    'tool_id' => $tool->id,
                    'reference' => $tool->reference,
                    'name' => $tool->name, 

    //                'base_quantity', 
                    'quantity' => $line_qty, 
                    // Assume 1 product == 1 measure unit O_O
                    'location' => $tool->location,
                ]);

                $order->productionordertoollines()->save($order_tool_line);
            }
        }


        return $order;
    }
    
    // Update Order Lines when Order Product quantity changes
    public function updateLines()
    {

        $product = \App\Product::with('bomitems')->with('boms')->findOrFail( $this->product_id );
        $bomitem = $product->bomitem();
        $bom     = $product->bom;

        $tools     = $product->tools;

        // if (!$bom) return $this;

        // Adjust Manufacturing batch size
        $nbt = ceil($this->planned_quantity / $product->manufacturing_batch_size);
        $order_quantity = $nbt * $product->manufacturing_batch_size;
        $this->update(['planned_quantity' => $order_quantity]);

        $order = $this;

        // Destroy Order Lines
        if ($this->productionorderlines()->count())
            foreach( $this->productionorderlines as $line ) {
                $line->delete();
            }

        // Destroy Order Tools
        if ($this->productionordertoollines()->count())
            foreach( $this->productionordertoollines as $line ) {
                $line->delete();
            }


        // Order lines
if ( $bomitem )
{

        // BOM quantities
        $line_qty = $order_quantity * $bomitem->quantity / $bom->quantity;

        foreach( $bom->BOMlines as $line ) {
            
             $line_product = \App\Product::with('measureunit')->findOrFail( $line->product_id );

             $order_line = \App\ProductionOrderLine::create([
                'type' => 'product',
                'product_id' => $line_product->id,
                'reference' => $line_product->reference,
                'name' => $line_product->name, 

//                'base_quantity', 
                'required_quantity' => $line_qty * $line->quantity * (1.0 + $line->scrap/100.0), 
                // Assume 1 product == 1 measure unit O_O
                'measure_unit_id' => $line_product->measure_unit_id,
//                'warehouse_id'
            ]);

            $order->productionorderlines()->save($order_line);
        }
}


        // Order Tools
        if ( $tools->count() )
        {
            // BOM quantities
            $line_qty = $order_quantity;

            foreach( $tools as $tool ) {

                 $order_tool_line = \App\ProductionOrderToolLine::create([
                    'tool_id' => $tool->id,
                    'reference' => $tool->reference,
                    'name' => $tool->name, 

    //                'base_quantity', 
                    'quantity' => $line_qty, 
                    // Assume 1 product == 1 measure unit O_O
                    'location' => $tool->location,
                ]);

                $order->productionordertoollines()->save($order_tool_line);
            }
        }



        return $order;
    }

    public function deleteWithLines()
    {
        // Destroy Order Lines
        if ($this->productionorderlines()->count())
            foreach( $this->productionorderlines as $line ) {
                $line->delete();
            }

        // Destroy Order
        $this->delete();
    }


    public function scopeFilter($query, $params)
    {
        if ( isset($params['reference']) && trim($params['reference']) !== '' )
        {
            $query->where('product_reference', 'LIKE', '%' . trim($params['reference']) . '%');
            // $query->orWhere('combinations.reference', 'LIKE', '%' . trim($params['reference'] . '%'));
        }

        if ( isset($params['name']) && trim($params['name']) !== '' )
        {
            $query->where('product_name', 'LIKE', '%' . trim($params['name'] . '%'));
        }

        if ( isset($params['stock']) )
        {
            if ( $params['stock'] == 0 )
                $query->where('quantity_onhand', '<=', 0);
            if ( $params['stock'] == 1 )
                $query->where('quantity_onhand', '>', 0);
        }

        if ( isset($params['category_id']) && $params['category_id'] > 0 )
        {
            $query->where('category_id', '=', $params['category_id']);
        }

        if ( isset($params['active']) )
        {
            if ( $params['active'] == 0 )
                $query->where('active', '=', 0);
            if ( $params['active'] == 1 )
                $query->where('active', '>', 0);
        }

        return $query;
    }
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function productionsheet()
    {
        return $this->belongsTo('App\ProductionSheet', 'production_sheet_id');
    }
    
    public function workcenter()
    {
        return $this->belongsTo('App\WorkCenter', 'work_center_id');
    }
    
    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }
    
    public function productionorderlines()
    {
        return $this->hasMany('App\ProductionOrderLine', 'production_order_id');
    }
    
    public function productionordertoollines()
    {
        return $this->hasMany('App\ProductionOrderLine', 'production_order_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeIsManual($query)
    {
        return $query->where( 'created_via', 'manual' );
    }

    public function scopeIsFromWeb($query)
    {
        return $query->where( 'created_via', 'webshop' );
    }
}
