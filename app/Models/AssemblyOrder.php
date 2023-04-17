<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class AssemblyOrder extends Model
{
    use ViewFormatterTrait;

    public static $statuses = array(
            'simulated', 
            'planned', 
            'firmplanned', 
            'released', 
            'finished',
        );

    protected $dates = [
                        'due_date', 'finish_date'
                       ];
	
    protected $fillable = [ 'sequence_id', 'reference', 'created_via', 
    						'status',
    						'product_id', 'combination_id', 'product_reference', 'product_name', 
    						'required_quantity', 'planned_quantity', 'finished_quantity', 'measure_unit_id', 
                            'due_date', 'finish_date', 'notes', 
                            'work_center_id', 'manufacturing_batch_size', 
                            'warehouse_id', 
                          ];

    public static $rules = array(
//    	'id'    => 'required|unique',
    	);
    


    public static function boot()
    {
        parent::boot();

        static::deleting(function ($document)
        {
            // before delete() method call this
            foreach($document->assemblyorderlines as $line) {
                $line->delete();
            }

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public static function getStatusList()
    {
            $list = [];
            foreach (static::$statuses as $status) {
                $list[$status] = l($status, [], 'appmultilang');
                // alternative => $list[$status] = l(static::class.'.'.$status, [], 'appmultilang');
            }

            return $list;
    }

    public static function getStatusName( $status )
    {
            return l($status, [], 'appmultilang');
    }

    public static function isStatus( $status )
    {
            return in_array($status, self::$statuses);
    }

    public function getStatusNameAttribute()
    {
            return l($this->status, 'appmultilang');
    }

    
    public static function createWithLines($data = [])
    {
        $product = Product::with('packitems')
        					   ->with('packitems.product')
                               ->findOrFail( $data['product_id'] );

        // if (!$bom) return NULL;

         $order_quantity = $data['planned_quantity'] ?? 1.0;
         $order_required = $data['required_quantity'] ?? $data['planned_quantity'];
         $order_manufacturing_batch_size =  $data['manufacturing_batch_size'] ?? 1.0;

        $order = AssemblyOrder::create([
            'created_via' => $data['created_via'] ?? 'manual',
            'status'      => $data['status']      ?? 'released',
//            'procurement_type' => $product->procurement_type,

            'product_id' => $product->id,
            'product_reference' => $product->reference,
            'product_name' => $product->name,

            'required_quantity' => $order_required,
            'planned_quantity' => $order_quantity,
            // 'finished_quantity'

            'measure_unit_id' => $product->measure_unit_id,

            'due_date' => $data['due_date'] ?? null,
            // 'finish_date'

            'notes' => $data['notes'] ?? null,

            'work_center_id' => $data['work_center_id'] ?? $product->work_center_id,

            'manufacturing_batch_size' => $order_manufacturing_batch_size,
            'warehouse_id' => $data['warehouse_id'] ?? Configuration::get('DEF_WAREHOUSE'),
        ]);


        // Assembly Order lines
        if ( $product->packitems->count() > 0 )
        {
            foreach( $product->packitems as $line ) {
                
                 $line_product = $line->product;

                 $order_line = AssemblyOrderLine::create([
                    'product_id' => $line_product->id,
                    'reference' => $line_product->reference,
                    'name' => $line_product->name, 

                    'pack_item_quantity' => $line->quantity,
                    'required_quantity' => $line->quantity * $order_quantity,
                    // 'real_quantity'

                    'measure_unit_id' => $line_product->measure_unit_id,
    //                'warehouse_id'
                ]);

                $order->assemblyorderlines()->save($order_line);
            }
        }


        return $order;
    }
    

    /*
    |--------------------------------------------------------------------------
    | Statuss changes & actions
    |--------------------------------------------------------------------------
    */
    
    public function finish( $params = [] )
    {
        // Can I ...?
        if ( ($this->status == 'finished') ) return false;  // Any other status is good to go!??

        // onhold?
        // if ( $this->onhold ) return false;

        // Do stuf...
        $this->document_reference = $this->id;      // <= No sequences used!
        $this->finished_quantity = $params['finished_quantity'] ?? $this->planned_quantity;     // By now... (need inteface to inform *real* finished quantity)
        if ( $this->warehouse_id <= 0 ) $this->warehouse_id = Configuration::getInt('DEF_WAREHOUSE');
        $this->status = 'finished';
        $this->finish_date = $params['finish_date'] ?? \Carbon\Carbon::now();

        $this->save();

        // Dispatch event
        event( new \App\Events\AssemblyOrderFinished($this, $params) );

        return true;
    }

    public function unfinish( $status = null )
    {
        // Can I ...?
        if ( $this->status != 'finished' ) return false;

        // Do stuf...
        $this->status = $status ?: 'released';
        $this->finish_date =null;

        $this->save();

        // Dispatch event
        event( new \App\Events\AssemblyOrderUnfinished($this) );

        return true;
    }
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function workcenter()
    {
        return $this->belongsTo(WorkCenter::class, 'work_center_id');
    }
    
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function measureunit()
    {
        return $this->belongsTo(MeasureUnit::class, 'measure_unit_id');
    }
    
    public function assemblyorderlines()
    {
        return $this->hasMany(AssemblyOrderLine::class, 'assembly_order_id');
    }
    
    // Alias
    public function lines()
    {
        return $this->assemblyorderlines();
    }

    /**
     * Get all of the Production Order Header's Stock Movements.
     */
    public function stockmovements()
    {
        return $this->morphMany( StockMovement::class, 'stockmovementable' );
    }

    // Seems to be needed to show stock movements
    public function document()
    {
       return $this->belongsTo(AssemblyOrder::class, 'id');
    }


    /*
    |--------------------------------------------------------------------------
    | Stock Movements
    |--------------------------------------------------------------------------
    */

    public function makeStockMovements( $params = [] )
    {
        // Let's rock!

        // Assembly Order Header
            $data = [
                    'date' => \Carbon\Carbon::now(),

//                    'stockmovementable_id' => $this->,
//                    'stockmovementable_type' => $this->,

                    'document_reference' => $this->document_reference,

//                    'quantity_before_movement' => $this->,
                    'quantity' => $this->finished_quantity,
                    'measure_unit_id' => $this->measure_unit_id,
//                    'quantity_after_movement' => $this->,

                    'price' => $this->product->cost_price,
                    'price_currency' => $this->product->cost_price,
//                    'currency_id' => $this->currency_id,
//                    'conversion_rate' => $this->currency_conversion_rate,

                    'notes' => '',

                    'product_id' => $this->product_id,
                    'combination_id' => $this->combination_id,
                    'reference' => $this->product_reference,
                    'name' => $this->product_name,

                    'warehouse_id' => $this->warehouse_id,
//                    'warehouse_counterpart_id' => $this->,

                    'movement_type_id' => StockMovement::MANUFACTURING_OUTPUT,

//                    'user_id' => $this->,

//                    'inventorycode'
            ];

            $stockmovement = StockMovement::createAndProcess( $data );

            if ( $stockmovement )
            {
                //
                $this->stockmovements()->save( $stockmovement );
            }

        //

        // Production Order Lines
        foreach ($this->lines as $line) {
            //
            // Only products, please!!!
            if ( ! ( $line->product_id > 0 ) )         continue;

            //
            $data = [
                    'date' => \Carbon\Carbon::now(),

//                    'stockmovementable_id' => $line->,
//                    'stockmovementable_type' => $line->,

                    'document_reference' => $this->document_reference,

//                    'quantity_before_movement' => $line->,
                    'quantity' => $line->required_quantity,
                    'measure_unit_id' => $line->measure_unit_id,
//                    'quantity_after_movement' => $line->,

                    'price' => $line->product->cost_price,
                    'price_currency' => $line->product->cost_price,
//                    'currency_id' => $this->currency_id,
//                    'conversion_rate' => $this->currency_conversion_rate,

                    'notes' => '',

                    'product_id' => $line->product_id,
                    'combination_id' => $line->combination_id,
                    'reference' => $line->reference,
                    'name' => $line->name,

                    'warehouse_id' => $this->warehouse_id,
//                    'warehouse_counterpart_id' => $line->,

                    'movement_type_id' => StockMovement::MANUFACTURING_INPUT,

//                    'user_id' => $line->,

//                    'inventorycode'
            ];

            $stockmovement = StockMovement::createAndProcess( $data );

            if ( $stockmovement )
            {
                //
                $line->stockmovements()->save( $stockmovement );
            }
        }

        // $this->stock_status = 'completed';
        $this->save();

        return true;
    }


    public function shouldPerformStockMovements()
    {
        return true;

        if ( $this->created_via == 'manual' && $this->stock_status == 'pending' ) return true;
/*
        if ($this->stock_status == 'pending') return true;

        if ($this->stock_status == 'completed') return false;

        if ($this->created_via == 'aggregate_shipping_slips') return false;
*/
        return false;
    }


    public function canRevertStockMovements()
    {
        if ( $this->status == 'finished' ) return true;

        return false;
/*
        if ($this->created_via == 'manual' && $this->stock_status == 'completed' ) return true;

        return false;
*/
    }

    
    public function revertStockMovements()
    {
        // Let's rock!
        foreach ($this->lines as $line) {
            //
            // Only products, please!!!
            if ( ! ( $line->product_id > 0 ) ) continue;

            //
            foreach ( $line->stockmovements as $mvt ) {
                # code...
                $data = [
                        'date' => \Carbon\Carbon::now(),

    //                    'stockmovementable_id' => $line->,
    //                    'stockmovementable_type' => $line->,

                        'document_reference' => $mvt->document_reference,

    //                    'quantity_before_movement' => $line->,
                        'quantity' => -$mvt->quantity,
                        'measure_unit_id' => $mvt->measure_unit_id,
    //                    'quantity_after_movement' => $line->,

                        'price' => $mvt->price,
                        'price_currency' => $mvt->price_currency,
                        'currency_id' => $mvt->currency_id,
                        'conversion_rate' => $mvt->conversion_rate,

                        'notes' => '',

                        'product_id' => $mvt->product_id,
                        'combination_id' => $mvt->combination_id,
                        'reference' => $mvt->reference,
                        'name' => $mvt->name,

                        'warehouse_id' => $mvt->warehouse_id,
    //                    'warehouse_counterpart_id' => $line->,

                        'movement_type_id' => $mvt->movement_type_id,

    //                    'user_id' => $line->,

    //                    'inventorycode'
                ];

                $stockmovement = StockMovement::createAndProcess( $data );

                if ( $stockmovement )
                {
                    //
                    $line->stockmovements()->save( $stockmovement );
                }

            }   // Movements loop ENDS

        }   // Lines loop ENDS

        // $this->stock_status = 'pending';
        $this->save();

        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */


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

        if ( isset($params['id']) && $params['id'] > 0 )
        {
            $query->where('id', $params['id']);
        }
/*
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
*/
        return $query;
    }

}
