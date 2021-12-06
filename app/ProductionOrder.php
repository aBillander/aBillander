<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class ProductionOrder extends Model
{
    use ViewFormatterTrait;

    public static $created_vias = array(
            'manufacturing', 
            'manual',
        );

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
    						'status', 'procurement_type',
    						'product_id', 'combination_id', 'product_reference', 'product_name', 
    						'required_quantity', 'planned_quantity', 'finished_quantity', 'measure_unit_id', 'product_bom_id', 
                            'due_date', 'schedule_sort_order', 'finish_date', 'notes', 
                            'work_center_id', 'manufacturing_batch_size', 'machine_capacity', 'units_per_tray',
                            'warehouse_id', 'production_sheet_id'
                          ];

    public static $rules = [
                            'due_date' => 'required|date',
                            'finish_date' => 'nullable|date|after_or_equal:document_date',
//                            'warehouse_id' => 'exists:warehouses,id',
//                            'work_center_id' => 'exists:work_centers,id',
                            'planned_quantity' => 'required|numeric',
                            'finished_quantity' => 'required|numeric',
               ];
    


    public static function boot()
    {
        parent::boot();

        static::deleting(function ($document)
        {
            // before delete() method call this
            foreach($document->productionorderlines as $line) {
                $line->delete();
            }

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function getDocumentDateAttribute()
    {
            return $this->due_date;
    }

    public function getFinishableAttribute()
    {
        if ( $this->status == 'finished' ) return false;

        return true;
    }


    public function getEditableAttribute()
    {
        // return !( $this->locked || $this->status == 'closed' || $this->status == 'canceled' );
        return !( $this->status == 'finished' );
    }

    public function getDeletableAttribute()
    {
        // return !( $this->status == 'closed' || $this->status == 'canceled' );
        return $this->status != 'finished';
    }

    public function getUnfinishableAttribute()
    {
        if ( $this->status != 'finished' ) return false;

        // if ( optional($this->rightAscriptions)->count() || optional($this->leftAscriptions)->count() ) return false;

        return true;
    }


    public static function getStatusList()
    {
            $list = [];
            foreach (static::$statuses as $status) {
                $list[$status] = l(get_called_class().'.'.$status, [], 'appmultilang');
                // alternative => $list[$status] = l(static::class.'.'.$status, [], 'appmultilang');
            }

            return $list;
    }

    public static function getStatusName( $status )
    {
            return l(get_called_class().'.'.$status, [], 'appmultilang');
    }

    public static function isStatus( $status )
    {
            return in_array($status, self::$statuses);
    }

    public function getStatusNameAttribute()
    {
            return l(get_called_class().'.'.$this->status, 'appmultilang');
    }

    
    public function getMachineCapacityList()
    {
        
        if ( !trim($this->machine_capacity) ) return [];

        $dstr = str_replace( [';', ':'], ',', $this->machine_capacity );
        $list = array_map( 'floatval', explode(',', $dstr) );

        sort( $list, SORT_NUMERIC );

        return $list;
    }
    
    public function getMachineLoads($quantity = 1.0, $capacity = 1.0)
    {
        //
        if ( ($quantity <= 0) || ($capacity <= 0) ) return 0.0;

        // Just a convention: $quantity in gr., $capacity in kg.
        return ceil( ( $quantity / 1000.0 ) / $capacity );
    }

    
    public function getMachineLoadsLabel($quantity = 1.0, $capacity = 1.0)
    {
        //
        if ( ($quantity <= 0) || ($capacity <= 0) ) return '';

        return ceil( ( $quantity / 1000.0 ) / $capacity ).'x'.$capacity;
    }
    

    public function getTrays($quantity = 1.0, $capacity = 1.0)
    {
        //
        if ( ($quantity <= 0) || ($capacity <= 0) ) return 0;

        return ceil( $quantity / $capacity );
    }
    

    public function getTraysLabel($quantity = 1.0, $capacity = 1.0)
    {
        //
        if ( ($quantity <= 0) || ($capacity <= 0) ) return '';

        return ceil( $quantity / $capacity ).' x ['.niceQuantity($capacity, 0).']';
    }


    
    public static function createPlannedMultiLevel($data = [])
    {
        $fields = [ 'created_via', 'status',
                    'product_id', 'required_quantity', 'planned_quantity', 'due_date', 'schedule_sort_order',
                    'work_center_id', 'manufacturing_batch_size', 'machine_capacity', 'units_per_tray', 
                    'warehouse_id', 'production_sheet_id', 'notes'];

        $product = \App\Product::findOrFail( $data['product_id'] );
        $bom     = $product->bom;

        $production_orders = collect([]);

        // if (!$bom) return NULL;

        // Adjust Manufacturing batch size <- Not here, boy
//        $nbt = ceil($data['planned_quantity'] / $product->manufacturing_batch_size);
//        $order_quantity = $nbt * $product->manufacturing_batch_size;

        $order_quantity = $data['planned_quantity'];
//        + $order_manufacturing_batch_size = $data['manufacturing_batch_size'] ?? $product->manufacturing_batch_size;

        $order = \App\ProductionOrder::create([
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

        $production_orders->push($order);





        // Order lines
        if ( !$bom ) return $production_orders;

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

            $order = \App\ProductionOrder::createPlannedMultiLevel([
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

            $production_orders = $production_orders->merge($order);
        }

        return $production_orders;
    }
    
    public static function createWithLines($data = [])
    {
        $fields = [ 'created_via', 'status', 'procurement_type',
                    'product_id', 'planned_quantity', 'due_date', 
                    'work_center_id', 'machine_capacity', 'units_per_tray', 
                    'warehouse_id', 'production_sheet_id', 'notes'];

        $product = \App\Product::with('bomitems')->with('boms')
                                ->with('producttools')
                                ->findOrFail( $data['product_id'] );
        $bomitem = $product->bomitem();
        $bom     = $product->bom;

        $tools     = $product->tools;

        // if (!$bom) return NULL;

         $order_quantity = $data['planned_quantity'];
         $order_required = $data['required_quantity'] ?? $data['planned_quantity'];
         $order_manufacturing_batch_size =  $product->manufacturing_batch_size;

        $order = \App\ProductionOrder::create([
            'created_via' => $data['created_via'] ?? 'manual',
            'status'      => $data['status']      ?? 'released',
            'procurement_type' => $product->procurement_type,

            'product_id' => $product->id,
            'product_reference' => $product->reference,
            'product_name' => $product->name,

            'required_quantity' => $order_required,
            'planned_quantity' => $order_quantity,
            'product_bom_id' => $bom->id ?? 0,

            'due_date' => $data['due_date'],
            'schedule_sort_order' => $product->category_id,
            'notes' => $data['notes'],

            'work_center_id' => $data['work_center_id'] ?? $product->work_center_id,

            'manufacturing_batch_size' => $order_manufacturing_batch_size,
            'machine_capacity' => $product->machine_capacity, 
            'units_per_tray' => $product->units_per_tray,
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

                    'bom_line_quantity' => $line->quantity * (1.0 + $line->scrap/100.0), 
                    'bom_quantity' => $bom->quantity,
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
/*
        if ($this->productionorderlines()->count())
            foreach( $this->productionorderlines as $line ) {
                $line->delete();
            }
*/

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

        if (array_key_exists('due_date', $params) && $params['due_date'])
        {
            $query->where('production_orders.due_date', $params['due_date']);
        }

        return $query;
    }
    

    /*
    |--------------------------------------------------------------------------
    | Status changes & actions
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
        event( new \App\Events\ProductionOrderFinished($this, $params) );

        return true;
    }

    public function unfinish( $status = 'released' )
    {
        // Can I ...?
        if ( $this->status != 'finished' ) return false;

        // Do stuf...
        $this->document_reference = null;
        $this->finished_quantity = 0.0;
        $this->status = $status ?: 'released';
        $this->finish_date =null;

        $this->save();

        // Dispatch event
        event( new \App\Events\ProductionOrderUnfinished($this) );

        return true;
    }
    


    public function getMaxLineSortOrder()
    {
        // $this->load(['lines']);

        if ( $this->lines->count() )
            return $this->lines->max('line_sort_order');

        return 0;           // Or: return intval( $this->customershippingsliplines->max('line_sort_order') );
    }
    
    public function getNextLineSortOrder()
    {
        $inc = 10;

        return $this->getMaxLineSortOrder() + $inc;
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
    
    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse');
    }
    
    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }

    public function measureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'measure_unit_id');
    }
    
    public function productionorderlines()
    {
        return $this->hasMany('App\ProductionOrderLine', 'production_order_id')
                    ->orderBy('line_sort_order', 'ASC');
    }
    
    // Alias
    public function lines()
    {
        return $this->productionorderlines();
    }
    
    public function productionordertoollines()
    {
        return $this->hasMany('App\ProductionOrderToolLine', 'production_order_id');
    }
    
    // Alias
    public function tool_lines()
    {
        return $this->productionordertoollines();
    }

    /**
     * Get all of the Production Order Header's Stock Movements.
     */
    public function stockmovements()
    {
        return $this->morphMany( StockMovement::class, 'stockmovementable' );
    }

    public function document()
    {
       return $this->belongsTo(ProductionOrder::class, 'id');
    }

    public function lotitems()
    {
        return $this->morphMany('App\LotItem', 'lotable');
    }

    public function getLotsAttribute()
    {
        if (!$this->relationLoaded('lotitems') || !$this->lotitems->first()->relationLoaded('lot')) {
            $this->load('lotitems.lot');
        }

        return $this->lotitems->pluck('lot');       // ->collapse();
    }


    public function lotitem()
    {
        return $this->hasOne('App\LotItem', 'lotable_id', 'id')
                    ->where('lotable_type', ProductionOrder::class)->with('lot');
    }

    public function getLotAttribute()
    {
/*
        if (!$this->relationLoaded('lotitem')) {
            $this->load('lotitem.lot');
        }

        return $this->lotitem->lot;
*/
        // Better approach for testing: take last created lot
        return $this->lots->sortByDesc('id')->first();

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


    /*
    |--------------------------------------------------------------------------
    | Stock Movements
    |--------------------------------------------------------------------------
    */

    public function makeStockMovements( $params = [] )
    {
        // Let's rock!

        //
        // Finished product first
        //

        // Deal with Lots (ノಠ益ಠ)ノ彡┻━┻
        $lot = null;

        // Create Lot for finished product
        if ( Configuration::isTrue('ENABLE_LOTS') && $this->product->lot_tracking > 0 )
        {

            // Set some default...
            $finish_date = $params['finish_date'] ?? \Carbon\Carbon::now();
            $lot_reference = $params['lot_reference'] ?: Lot::generate( $finish_date, $this->product, $this->product->expiry_time);
            $expiry_at = Lot::getExpiryDate( $finish_date, $this->product->expiry_time );
            $finished_quantity = $params['finished_quantity'] ?? $this->planned_quantity;

            $warehouse_id = $params['warehouse_id'] ?? $this->warehouse_id;
            if ( ! $warehouse_id )
            {    
                $warehouse_id = Configuration::getInt('DEF_WAREHOUSE');
                $this->warehouse_id = $warehouse_id;
                $this->save();
            }

            $lot_params = [
                'reference' => $lot_reference,
                'product_id' => $this->product_id, 
    //            'combination_id' => ,
                'quantity_initial' => $finished_quantity, 
                'quantity' => $finished_quantity, 
                'measure_unit_id' => $this->product->measure_unit_id, 
    //            'package_measure_unit_id' => , 
    //            'pmu_conversion_rate' => ,
                'manufactured_at' => $finish_date, 
//                    'expiry_at' => $finish_date->addDays( $this->product->expiry_time ),
                'expiry_at' => $expiry_at,
                'notes' => 'Production Order: #'.$this->id,

                'warehouse_id' => $warehouse_id,
            ];
            

            // Time for "some magic"
            // Create Lot
            $lot = Lot::create($lot_params);

            $data = [
                'lot_id' => $lot->id,
                'is_reservation' => 0,
                'quantity' => $finished_quantity,
            ];

            $lot_item = LotItem::create( $data );
            $this->lotitems()->save($lot_item);

            // Cannot return this back: not good practice:
            // $success[] = l('Se ha creado un lote &#58&#58 (:id) ', ['id' => $lot->reference], 'layouts') . 
            //    'para el Producto: ['.$document->product_reference.'] '.$document->product_name;
        }

        // Production Order Header Stock Movement
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

                if ($lot)
                {
                    $lot->stockmovements()->save( $stockmovement );
                    $stockmovement->update(['lot_quantity_after_movement' => $stockmovement->quantity]);
                    $lot->update(['blocked' => 0]);
                }
            }

        //

        // Production Order Lines
        foreach ($this->lines as $line) {
            //
            // Only products, please!!!
            if ( ! ( $line->type == 'product' ) ) continue;
            if ( ! ( $line->product_id > 0 ) )         continue;

            if ( Configuration::isTrue('ENABLE_LOTS') && ($line->product->lot_tracking > 0)  )
            {
                $this->makeStockMovementsLineLots( $line );
                continue;
            }

            //
            $data = [
                    'date' => \Carbon\Carbon::now(),

//                    'stockmovementable_id' => $line->,
//                    'stockmovementable_type' => $line->,

                    'document_reference' => $this->document_reference,

//                    'quantity_before_movement' => $line->,
                    'quantity' => $line->real_quantity,
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
        if (0)
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



/* *********************************************************************************************** */

    
    public function makeStockMovementsLineLots( $line )
    {
        // Let's rock!
        foreach ($line->lotitems as $lotitem) {
            //
            $lot = $lotitem->lot;

            $data = [
                    'date' => \Carbon\Carbon::now(),

//                    'stockmovementable_id' => $line->,
//                    'stockmovementable_type' => $line->,

                    'document_reference' => $this->document_reference,

//                    'quantity_before_movement' => $line->,
                    'quantity' => $line->real_quantity,
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

                $lot_quantity_after_movement = $lot->quantity - $stockmovement->quantity;

                $lot->stockmovements()->save( $stockmovement );
                $stockmovement->update(['lot_quantity_after_movement' => $lot_quantity_after_movement]);
                $lot->update(['blocked' => 0, 'quantity' => $lot_quantity_after_movement]);

                $lotitem->update(['is_reservation' => 0]);
            }
        }

        return true;
    }




    /*
    |--------------------------------------------------------------------------
    | Document Lines Stuff
    |--------------------------------------------------------------------------
    */
    
    

    /**
     * Add Product to Production Order
     *
     *     'line_sort_order', 'type', 'product_id', 'combination_id', 'reference', 'name', bom_line_quantity', 'bom_quantity', 'required_quantity', 'real_quantity', 'measure_unit_id', 'warehouse_id'
     *
     */
    public function addProductLine( $product_id, $combination_id = null, $quantity = 1.0, $params = [] )
    {
        // Do the Mambo!
        // $line_type = 'product';

        // Product
        if ($combination_id>0) {
            $combination = Combination::with('product')->findOrFail(intval($combination_id));
            $product = $combination->product;
            $product->reference = $combination->reference;
            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = Product::findOrFail(intval($product_id));
        }

        $type = 'product';

        $reference  = $product->reference;
        $name = array_key_exists('name', $params) 
                            ? $params['name'] 
                            : $product->name;

        $measure_unit_id = array_key_exists('measure_unit_id', $params) 
                            ? $params['measure_unit_id'] 
                            : $product->measure_unit_id;

        $warehouse_id = array_key_exists('warehouse_id', $params) 
                            ? $params['warehouse_id'] 
                            : Configuration::get('DEF_WAREHOUSE');



        // Misc
        $line_sort_order = array_key_exists('line_sort_order', $params) 
                            ? $params['line_sort_order'] 
                            : $this->getNextLineSortOrder();

        $bom_line_quantity = array_key_exists('bom_line_quantity', $params) 
                            ? $params['bom_line_quantity'] 
                            : 0.0;

        $bom_quantity = array_key_exists('bom_quantity', $params) 
                            ? $params['bom_quantity'] 
                            : 0.0;

        $locked = array_key_exists('locked', $params) 
                            ? $params['locked'] 
                            : 0;


        // Build OrderLine Object
        $data = [
            'line_sort_order' => $line_sort_order,
            'type' => $type,

            'product_id' => $product->id,
            'reference' => $reference,
            'name' => $name,

            'bom_line_quantity' => $bom_line_quantity,
            'bom_quantity' => $bom_quantity,

            'required_quantity' => $quantity,
//            'real_quantity' => 0.0,

            'measure_unit_id' => $measure_unit_id,

            'warehouse_id' => $warehouse_id,
        ];


        // Finishing touches
        $document_line = ProductionOrderLine::create( $data );

        $this->lines()->save($document_line);


        // Good boy, bye then
        return $document_line;

    }

}
