<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductionOrder extends Model
{

//    protected $dates = ['due_date'];
	
    protected $fillable = [ 'sequence_id', 'reference', 'created_via', 
    						'status', 
    						'product_id', 'combination_id', 'product_reference', 'product_name', 
    						'planned_quantity', 'product_bom_id', 'due_date', 'notes', 
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
    
    public static function createWithLines($data = [])
    {
        $fields = [ 'created_via', 'status',
                    'product_id', 'planned_quantity', 'due_date', 
                    'work_center_id', 'warehouse_id', 'production_sheet_id', 'notes'];

        $product = \App\Product::with('bomitems')->with('boms')->findOrFail( $data['product_id'] );
        $bomitem = $product->bomitem();
        $bom     = $product->bom();

        if (!$bom) return NULL;

        // Adjust Manufacturing batch size
        $nbt = ceil($data['planned_quantity'] / $product->manufacturing_batch_size);
        $order_quantity = $nbt * $product->manufacturing_batch_size;

        $order = \App\ProductionOrder::create([
            'created_via' => $data['created_via'] ?? 'manual',
            'status'      => $data['status']      ?? 'released',

            'product_id' => $product->id,
            'product_reference' => $product->reference,
            'product_name' => $product->name,

            'planned_quantity' => $order_quantity,
            'product_bom_id' => $bom->id ?? 0,

            'due_date' => $data['due_date'],
            'notes' => $data['notes'],

            'work_center_id' => $data['work_center_id'] ?? $product->work_center_id,
//            'warehouse_id' => '',
            'production_sheet_id' => $data['production_sheet_id'],
        ]);


        // Order lines
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

        return $order;
    }
    
    // Update Order Lines when Order Product quantity changes
    public function updateLines()
    {

        $product = \App\Product::with('bomitems')->with('boms')->findOrFail( $this->product_id );
        $bomitem = $product->bomitem();
        $bom     = $product->bom();

        if (!$bom) return $this;

        // Adjust Manufacturing batch size
        $nbt = ceil($this->planned_quantity / $product->manufacturing_batch_size);
        $order_quantity = $nbt * $product->manufacturing_batch_size;
        $this->update(['planned_quantity' => $order_quantity]);

        $order = $this;


        // Order lines

        // Destroy Order Lines
        if ($this->productionorderlines()->count())
            foreach( $this->productionorderlines as $line ) {
                $line->delete();
            }

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
