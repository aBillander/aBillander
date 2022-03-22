<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\ViewFormatterTrait;

use App\Exceptions\StockMovementException;

class StockMovement extends Model {

    use ViewFormatterTrait;
//    use SoftDeletes;

    protected $table = 'stock_movements';

    protected $price_in;        // Movement Price in Company Currency (for average price calculations)

    protected $dates = ['date', 'deleted_at'];

    protected $fillable = [ 'date', 'document_reference', 
                            'price', 'price_currency', 'currency_id', 'conversion_rate', 'quantity', 'measure_unit_id', 
                            'notes',
                            'product_id', 'combination_id', 'reference', 'name', 'warehouse_id', 'warehouse_counterpart_id', 'movement_type_id',

                            'lot_id', 'lot_quantity_after_movement', 

                            'user_id', 'inventorycode',
                           ];

//        'date' => 'required|date|date_format:YY-MM-DD',
//         See: https://es.stackoverflow.com/questions/57020/validaci%C3%B3n-de-formato-de-fecha-no-funciona-laravel-5-3
//        'document_reference' => 'required',   <- Stock adjustments & others do not need it!!

    public static $rules = [
            '10' => array(
                    'date' => 'required',
                    'price' => 'required|min:0',     // |not_in:0',
                    'currency_id' => 'exists:currencies,id',
                    'quantity' => 'required',                   //|not_in:0',
                    'product_id' => 'exists:products,id',
                    'combination_id' => 'sometimes|exists:combinations,id',
                    'warehouse_id' => 'exists:warehouses,id',
                    'movement_type_id' => 'required',
                    ),
            '12' => array(
                    'date' => 'required',
 //                   'price' => 'required',
 //                   'currency_id' => 'exists:currencies,id',
                    'quantity' => 'required',                   //|not_in:0',
                    'product_id' => 'exists:products,id',
                    'combination_id' => 'sometimes|exists:combinations,id',
                    'warehouse_id' => 'exists:warehouses,id',
                    'movement_type_id' => 'required',
                    ),
            '20' => array(
                    'date' => 'required',
                    'price' => 'required|numeric|min:0|not_in:0',
                    'currency_id' => 'exists:currencies,id',
                    'quantity' => 'required|numeric',                   //|not_in:0',
                    'conversion_rate' => 'required|numeric|min:0',
                    'product_id' => 'exists:products,id',
//                    'combination_id' => 'sometimes|exists:combinations,id',       // In fact, combination_id is CALCULATED in Controller
                    'warehouse_id' => 'exists:warehouses,id',
                    'movement_type_id' => 'required',
                    ),
            '21' => array(
                    'date' => 'required',
                    'price' => 'required|min:0|not_in:0',
                    'currency_id' => 'exists:currencies,id',
                    'quantity' => 'required|numeric',                   //|not_in:0',
                    'conversion_rate' => 'required|numeric|min:0',
                    'product_id' => 'exists:products,id',
                    'combination_id' => 'sometimes|exists:combinations,id',
                    'warehouse_id' => 'exists:warehouses,id',
                    'movement_type_id' => 'required',
                    ),
            '30' => array(
                    'date' => 'required',
                    'price' => 'required|min:0|not_in:0',
                    'currency_id' => 'exists:currencies,id',
                    'quantity' => 'required|numeric',                   //|not_in:0',
                    'conversion_rate' => 'required|numeric|min:0',
                    'product_id' => 'exists:products,id',
                    'combination_id' => 'sometimes|exists:combinations,id',
                    'warehouse_id' => 'exists:warehouses,id',
                    'movement_type_id' => 'required',
                    ),
            '31' => array(
                    'date' => 'required',
                    'price' => 'required|min:0|not_in:0',
                    'currency_id' => 'exists:currencies,id',
                    'quantity' => 'required|numeric',                   //|not_in:0',
                    'conversion_rate' => 'required|numeric|min:0',
                    'product_id' => 'exists:products,id',
                    'combination_id' => 'sometimes|exists:combinations,id',
                    'warehouse_id' => 'exists:warehouses,id',
                    'movement_type_id' => 'required',
                    ),
            '40' => array(
                    'date' => 'required',
 //                   'price' => 'required',
 //                   'currency_id' => 'exists:currencies,id',
                    'quantity' => 'required',                   //|not_in:0',
                    'product_id' => 'exists:products,id',
                    'combination_id' => 'sometimes|exists:combinations,id',
                    'warehouse_id' => 'exists:warehouses,id',
                    'warehouse_counterpart_id' => 'exists:warehouses,id',
                    'movement_type_id' => 'required',
                    ),
            '41' => array(
                    'date' => 'required',
   //                 'price' => 'required',
   //                 'currency_id' => 'exists:currencies,id',
                    'quantity' => 'required',                   //|not_in:0',
                    'product_id' => 'exists:products,id',
                    'combination_id' => 'sometimes|exists:combinations,id',
                    'warehouse_id' => 'exists:warehouses,id',
                    'warehouse_counterpart_id' => 'exists:warehouses,id',
                    'movement_type_id' => 'required',
                    ),
            '50' => array(
                    'date' => 'required',
 //                   'price' => 'required',
 //                   'currency_id' => 'exists:currencies,id',
                    'quantity' => 'required',                   //|not_in:0',
                    'product_id' => 'exists:products,id',
                    'combination_id' => 'sometimes|exists:combinations,id',
                    'warehouse_id' => 'exists:warehouses,id',
                    'movement_type_id' => 'required',
                    ),
            '51' => array(
                    'date' => 'required',
   //                 'price' => 'required',
   //                 'currency_id' => 'exists:currencies,id',
                    'quantity' => 'required',                   //|not_in:0',
                    'product_id' => 'exists:products,id',
                    'combination_id' => 'sometimes|exists:combinations,id',
                    'warehouse_id' => 'exists:warehouses,id',
                    'movement_type_id' => 'required',
                    ),
            '55' => array(
                    'date' => 'required',
   //                 'price' => 'required',
   //                 'currency_id' => 'exists:currencies,id',
                    'quantity' => 'required',                   //|not_in:0',
                    'product_id' => 'exists:products,id',
                    'combination_id' => 'sometimes|exists:combinations,id',
                    'warehouse_id' => 'exists:warehouses,id',
                    'movement_type_id' => 'required',
                    ),
    ];

    public static $rules_adjustment = array(
        'date' => 'date',
//        'price' => 'required',
        'quantity' => 'required|min:0',
        'product_id' => 'exists:products,id',
        'combination_id' => 'sometimes|exists:combinations,id',
        'warehouse_id' => 'exists:warehouses,id',
//        'movement_type_id' => 'required',
    );


    public static function boot()
    {
        parent::boot();
/*
        static::creating(function($corder)
        {
            $corder->secure_key = md5(uniqid(rand(), true));
            
            if ( $corder->shippingmethod )
                $corder->carrier_id = $corder->shippingmethod->carrier_id;
        });
*/
        static::saving(function($record)
        {
            $record->user_id = \Auth::id();
        });

    }


    public static function validTypeRule()
    {
        $rules = array(
                        'movement_type_id' => 'required|in:'.implode(',', self::validTypes()),
                    );

        return $rules;
    }

    public static function getRules( $type )
    {
        $r1 = self::$rules[$type];

        $r2 = self::validTypeRule();

        return array_merge($r1,$r2);
    }
	
    
    /*
    |--------------------------------------------------------------------------
    | Stock movement types
    |--------------------------------------------------------------------------
    */

    const INITIAL_STOCK        = 10;
    const ADJUSTMENT           = 12;
	const PURCHASE_ORDER       = 20;          // 'App\\Services\\' . \Str::studly($user_selection) . 'Report';
	const PURCHASE_RETURN      = 21;
	const SALE_ORDER           = 30;
	const SALE_RETURN          = 31;
    const TRANSFER_OUT         = 40;
    const TRANSFER_IN          = 41;
	const MANUFACTURING_INPUT  = 50;
	const MANUFACTURING_RETURN = 51;
	const MANUFACTURING_OUTPUT = 55;
    
    public static function validTypes()
    {
        $list = array(

            self::INITIAL_STOCK,
            self::ADJUSTMENT,
            self::PURCHASE_ORDER,
            self::PURCHASE_RETURN,
            self::SALE_ORDER,
            self::SALE_RETURN,
            self::TRANSFER_OUT,
            self::TRANSFER_IN,
            self::MANUFACTURING_INPUT,
            self::MANUFACTURING_RETURN,
            self::MANUFACTURING_OUTPUT,

        );

        return $list;
    }
    
    public static function getClassByType( $type )
    {
        switch ( $type ) {
            case self::INITIAL_STOCK:
                # code...
            return '\\App\\StockMovements\\InitialStockStockMovement';
                break;
            case self::ADJUSTMENT:
                # code...
            return '\\App\\StockMovements\\AdjustmentStockMovement';
                break;

            
            case self::PURCHASE_ORDER:
                # code...
            return '\\App\\StockMovements\\PurchaseOrderStockMovement';
                break;
            case self::PURCHASE_RETURN:
                # code...
            return '\\App\\StockMovements\\PurchaseReturnStockMovement';
                break;

            
            case self::SALE_ORDER:
                # code...
            return '\\App\\StockMovements\\SaleOrderStockMovement';
                break;
            case self::SALE_RETURN:
                # code...
            return '\\App\\StockMovements\\SaleReturnStockMovement';
                break;

            
            case self::TRANSFER_OUT:
                # code...
            return '\\App\\StockMovements\\WarehouseOutputStockMovement';
                break;
            
            case self::TRANSFER_IN:
                # code...
            return '\\App\\StockMovements\\WarehouseInputStockMovement';
                break;

            
            case self::MANUFACTURING_INPUT:
                # code...
            return '\\App\\StockMovements\\ManufacturingInputStockMovement';
                break;
            case self::MANUFACTURING_RETURN:
                # code...
            return '\\App\\StockMovements\\ManufacturingReturnStockMovement';
                break;            
            case self::MANUFACTURING_OUTPUT:
                # code...
            return '\\App\\StockMovements\\ManufacturingOutputStockMovement';
                break;
            
            default:
                # code...
                break;
        }

        $list = array(

            self::INITIAL_STOCK,
            self::ADJUSTMENT,
            self::PURCHASE_ORDER,
            self::PURCHASE_RETURN,
            self::SALE_ORDER,
            self::SALE_RETURN,
            self::TRANSFER_OUT,
            self::TRANSFER_IN,
            self::MANUFACTURING_INPUT,
            self::MANUFACTURING_RETURN,
            self::MANUFACTURING_OUTPUT,

        );
    }
    
    public static function stockmovementList()
    {
        $list = array();

        foreach (self::validTypes() as $t){

            // $list[$t] = $t.' - '.Lang::get('appmultilang.'.$t);
            $list[$t] = $t.' - '.l($t, 'appmultilang');

        }

        return $list;
    }


    public static function getTypeList()
    {
            $list = [];
            foreach (self::validTypes() as $type) {
                $list[$type] = l($type, [], 'appmultilang');
            }

            return $list;
    }

    public static function getTypeName( $movement_type_id )
    {
            return l($movement_type_id, 'appmultilang');
    }

    public function getStockmovementableRoute()
    {
            $stub = $this->stockmovementable_type;


            // static $route;

            // if ($route) return $route;

            $str = $this->stockmovementable_type;   // Maybe $this->stockmovementable_type = '' or NULL
            if ( !$str ) return $route = '';

            $segments = array_reverse(explode('\\', $str));

            return $route = \Str::plural(strtolower($segments[0]));
    }

    public function getStockmovementableDocumentRoute()
    {
            // static $segment;

            // if ($segment) return $segment;

            $str = $this->stockmovementable_type;
            if ( !$str ) return $segment = '';

            $segments = array_reverse(explode('\\', $str));


            // Last segment
            // $str = substr( $segments[0], 0, -strlen('Line') );
            // Better approach:
            $str = substr( $segments[0], 0, strpos($segments[0], "Line") );

            if ( !$str ) 
                $str = $segments[0];

            $segment = strtolower($str);

            return \Str::plural($segment);
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | Stock movement fulfillment (perform stock movements)
    |--------------------------------------------------------------------------
    */

    public static function createAndProcess( $data = [] )
    {
        // Some checks first:
        // currency_id or DEF_CURRENCY
        // product_id
        // warehouse_id or DEF_WAREHOUSE
        // movement type
        $list = self::stockmovementList();
        $movement_type_id = $data['movement_type_id'];
        $method = 'process_'.$movement_type_id;
        
        if ( !( $movement_type_id && array_key_exists($movement_type_id, $list) && method_exists(__CLASS__, $method) ) )
            throw new StockMovementException('Stock Movement type not found');


        // Do the mambo!

        // https://laraveldaily.com/how-to-catch-handle-create-laravel-exceptions/
        // https://code.tutsplus.com/tutorials/exception-handling-in-laravel--cms-30210?ec_unit=translation-info-language
        // https://fideloper.com/laravel-database-transactions

        // Start transaction!
        \DB::beginTransaction();

        // https://laracasts.com/discuss/channels/general-discussion/multiple-services-implementing-same-interface-switching-at-runtime
        // if ( in_array($movement_type_id, [10, 12, 20, 21, 30, 31, 40, 41, 50, 51, 55]) )
        if (1)
        {
            $class = self::getClassByType( $movement_type_id );
            $movement = new $class;
            $movement->fill( $data );

            // abi_r($movement);die();
        }
        else
            $movement = StockMovement::create( $data );

        try {

            $movement->process();   // ->refresh();   // ->fresh();  Need this??? Without fresh() won't work

            // Could do here: \DB::commit();

        } catch (StockMovementException $exception) {
            // Rollback 
            \DB::rollback();

            // Maybe redirect or so
            // Throw exception instead
            throw $exception;

            // $movement->delete();
//            report($exception);
            // return back()->with('error', $exception->getMessage())->withInput();

        } catch (\Exception $e) {
            // Rollback 
            \DB::rollback();

            // I also catch a generic Exception, just in case any other exception other than a StockMovementException is thrown
            throw $e;

            // $movement->delete();
//            report($exception);
            // return back()->with('error', $exception->getMessage())->withInput();
        }

        // If we reach here, then
        // data is valid and working.
        // Commit the queries!
        \DB::commit();

        return $movement;
    }

    public function process()
    {
        // throw new \App\Exceptions\StockMovementException('Something Went Wrong => pedo!');


        // Deal with currency (enough to append sensible default currency, if not set)
        if ( !($currency = Currency::find($this->currency_id)) )
        {
            $currency = Context::getContext()->company->currency;

            $this->currency_id     = $currency->id;
            $this->conversion_rate = $currency->conversion_rate;

            $this->price_currency = $this->price;
        }

        if ( $this->currency_id == Context::getContext()->company->currency->id )
        {
            $this->conversion_rate = Context::getContext()->company->currency->conversion_rate;

            $this->price_currency = $this->price;
        }
        else {
            // Asume Currency vars (currency_id and conversion_rate) are set:
            $this->price = $this->price_currency / $this->conversion_rate;
        }


        // Product & Combination;
        // Update Combination
        $this->load(['product', 'combination', 'warehouse']);
        
        $method = 'process_'.$this->movement_type_id;
        return $this->{$method}();
    }

    public function prepareToProcess()
    {
        // throw new \App\Exceptions\StockMovementException('Something Went Wrong => pedo!');


        // Product & Combination;
        // Update Combination
        $this->load(['product', 'combination']);

        // Deal with measure Units (if needed) Maybe check measure Unit in Controller??
        if ( $this->measure_unit_id <= 0 ) $this->measure_unit_id = $this->product->measure_unit_id;


        // Deal with currency (enough to append sensible default currency, if not set)
        if ( !($currency = Currency::find($this->currency_id)) )
        {
            $currency = Context::getContext()->company->currency;

            $this->currency_id     = $currency->id;
            $this->conversion_rate = $currency->conversion_rate;

            // $this->price_currency = $this->price;
        }

        if ( $this->currency_id == Context::getContext()->company->currency->id )
        {
            $this->conversion_rate = Context::getContext()->company->currency->conversion_rate;

            // $this->price_currency = $this->price;
        }
        // else {
            // Asume Currency vars (currency_id and conversion_rate) are set:
            $this->price = $this->price_currency / $this->conversion_rate;
        // }


        // Deal with Warehouse
        if ( $this->warehouse_id <= 0 ) $this->warehouse_id = Configuration::getInt('DEF_WAREHOUSE');

    }

    public function process_stock()
    {
        return false;
    }

    public function process_price()
    {
        return false;
    }

    // FIFO: https://www.youtube.com/watch?v=oRSC4lgJzAY
    // PMP:  https://www.youtube.com/watch?v=_mlbjjBoclE


    // INITIAL_STOCK
    public function process_10()
    {
        // Update Product
        $product = $this->product;

        if ($this->price === null) 
        {
            $this->price = ($this->combination_id > 0) ? $combination->cost_price : $product->cost_price;
        }

        if ( $product->getStockByWarehouse( $this->warehouse_id ) > 0.0 ) 
            throw new StockMovementException( l('Cannot set Initial Stock because Product has already stock', 'stockmovements') );
        
        $quantity_onhand = $this->quantity;
        $this->quantity_before_movement = 0.0;
        $this->quantity_after_movement = $quantity_onhand;

        if ($this->quantity_before_movement == $this->quantity_after_movement)
        {
            // Nothing said about cost price
            // Nothing to do
            // return false;

            // Comment next line: Maybe I want to set stock=0.0 explicitly
            // throw new \App\Exceptions\StockMovementException('Cannot process movement because Quantity has not changed');
        }
        
        $this->save();

        // Average price stuff
//        if ( !($this->combination_id > 0) ) {
//            $product->cost_average = $this->price_in;
//            $product->last_purchase_price = 0.0;
//        }

        // All warehouses
        $product->quantity_onhand = $quantity_onhand;
        $product->save();

        // Update Combination
        if ($this->combination_id > 0) {
            $combination = $this->combination;
            $quantity_onhand = $this->quantity;

            // Average price stuff
//            $combination->cost_average = $this->price_in;
//            $combination->last_purchase_price = 0.0;

            $combination->quantity_onhand += $quantity_onhand;
            $combination->save();
        }

        // Update Product-Warehouse relationship (quantity)
        $product->setStockByWarehouse( $this->warehouse_id, $this->quantity );

// Old stuff:
/*
        // Update Product-Warehouse relationship (quantity)
        $whs = $product->warehouses;
        if ($whs->contains($this->warehouse_id)) {
            $wh = $product->warehouses()->get();
            $wh = $wh->find($this->warehouse_id);
            $quantity = $this->quantity;
            
            if ($quantity != 0) {
                $wh->pivot->quantity = $quantity;
                $wh->pivot->save(); }
            else {
                // Delete record ($quantity = 0)
                $product->warehouses()->detach($this->warehouse_id); }
        } else {
            if ($this->quantity != 0) 
                $product->warehouses()->attach($this->warehouse_id, array('quantity' => $this->quantity));
        }
*/

// Need refactor:
/*
        // Update Combination-Warehouse relationship (quantity)
        if ($this->combination_id > 0) {
            $whs = $combination->warehouses;
            if ($whs->contains($this->warehouse_id)) {
                $wh = $combination->warehouses()->get();
                $wh = $wh->find($this->warehouse_id);
                $quantity = $this->quantity;
                
                if ($quantity != 0) {
                    $wh->pivot->quantity = $quantity;
                    $wh->pivot->save(); }
                else {
                    // Delete record ($quantity = 0)
                    $combination->warehouses()->detach($this->warehouse_id); }
            } else {
                if ($this->quantity != 0) 
                    $combination->warehouses()->attach($this->warehouse_id, array('quantity' => $this->quantity));
            }
        }
*/
        return $this;
    }

    // ADJUSTMENT
    public function process_12()
    {
        // Update Product
        $product = $this->product;

        if ($this->price === null) 
        {
            $this->price = ($this->combination_id > 0) ? $combination->cost_price : $product->cost_price;
        }

        $quantity_onhand = $this->quantity;
        $this->quantity_before_movement = $product->getStockByWarehouse( $this->warehouse_id );
        $this->quantity_after_movement = $quantity_onhand;

        if ($this->quantity_before_movement == $this->quantity_after_movement)
        {
            // Nothing said about cost price
            // Nothing to do
            return false;
            // throw new StockMovementException( l('Cannot process Stock Movement because Quantity has not changed', 'stockmovements') );
        }
        $this->save();

        // Average price stuff
//        if ( !($this->combination_id > 0) ) {
//            $product->cost_average = $this->price_in;
//        }

        // All warehouses
        $product->quantity_onhand = $quantity_onhand;
        $product->save();

        // Update Combination
        if ($this->combination_id > 0) {
            $combination = $this->combination;
            $quantity_onhand = $this->quantity;

            // Average price stuff
//            $combination->cost_average = $this->price_in;

            $combination->quantity_onhand += $quantity_onhand;
            $combination->save();
        }

        // Update Product-Warehouse relationship (quantity)
        $product->setStockByWarehouse( $this->warehouse_id, $this->quantity );

        return $this;
    }

    // PURCHASE_ORDER
    public function process_20()
    {
        // Price 4 Cost average calculations
        if ( $this->currency_id != Context::getContext()->currency->id ) {
            $currency = Currency::find($this->currency_id);
            $conversion_rate = $currency->conversion_rate;
            $this->price_in = $this->price*$conversion_rate;
        } else
            $this->price_in = $this->price;

        // Update Product
        $product = Product::find($this->product_id);
        $quantity_onhand = $product->quantity_onhand + $this->quantity;
        $this->quantity_after_movement = $quantity_onhand;
        $this->save();

        // Average price stuff
        if ( !($this->combination_id > 0) ) {
            // $cost = $product->cost_average;
            $cost_average = ($product->quantity_onhand * $product->cost_average + $this->quantity * $this->price_in) / ($product->quantity_onhand + $this->quantity);

            $product->cost_average = $cost_average;
            $product->last_purchase_price = $this->price_in;
        }

        $product->quantity_onhand = $quantity_onhand;
        $product->save();

        // Update Combination
        if ($this->combination_id > 0) {
            $combination = Combination::find($this->combination_id);
            $quantity_onhand = $combination->quantity_onhand + $this->quantity;

            // Average price stuff
            // $cost = $combination->cost_average;
            $cost_average = ($combination->quantity_onhand * $combination->cost_average + $this->quantity * $this->price_in) / ($combination->quantity_onhand + $this->quantity);
            
            $combination->cost_average = $cost_average;
            $combination->last_purchase_price = $this->price_in;

            $combination->quantity_onhand = $quantity_onhand;
            $combination->save();
        }

        // Update Product-Warehouse relationship (quantity)
        $whs = $product->warehouses;
        if ($whs->contains($this->warehouse_id)) {
            $wh = $product->warehouses()->get();
            $wh = $wh->find($this->warehouse_id);
            $quantity = $wh->pivot->quantity + $this->quantity;
            
            if ($quantity != 0) {
                $wh->pivot->quantity = $quantity;
                $wh->pivot->save(); }
            else {
                // Delete record ($quantity = 0)
                $product->warehouses()->detach($this->warehouse_id); }
        } else {
            if ($this->quantity != 0) 
                $product->warehouses()->attach($this->warehouse_id, array('quantity' => $this->quantity));
        }

        // Update Combination-Warehouse relationship (quantity)
        if ($this->combination_id > 0) {
            $whs = $combination->warehouses;
            if ($whs->contains($this->warehouse_id)) {
                $wh = $combination->warehouses()->get();
                $wh = $wh->find($this->warehouse_id);
                $quantity = $wh->pivot->quantity + $this->quantity;
                
                if ($quantity != 0) {
                    $wh->pivot->quantity = $quantity;
                    $wh->pivot->save(); }
                else {
                    // Delete record ($quantity = 0)
                    $combination->warehouses()->detach($this->warehouse_id); }
            } else {
                if ($this->quantity != 0) 
                    $combination->warehouses()->attach($this->warehouse_id, array('quantity' => $this->quantity));
            }
        }
    }

    // PURCHASE_RETURN
    public function process_21()
    {
        if ( $this->currency_id != Context::getContext()->currency->id ) {
            $currency = Currency::find($this->currency_id);
            $conversion_rate = $currency->conversion_rate;
            $this->price_in = $this->price*$conversion_rate;
        } else
            $this->price_in = $this->price;

        // Update Product
        $product = Product::find($this->product_id);
        $quantity_onhand = $product->quantity_onhand - $this->quantity;

        // Average price stuff
        if ( !($this->combination_id > 0) ) {
            // $cost = $product->cost_average;
            $cost_average = ($product->quantity_onhand * $product->cost_average - $this->quantity * $this->price_in) / ($product->quantity_onhand - $this->quantity);

            $product->cost_average = $cost_average;
//            $product->last_purchase_price = $this->price_in;
        }

        $product->quantity_onhand = $quantity_onhand;
        $product->save();

        // Update Combination
        if ($this->combination_id > 0) {
            $combination = Combination::find($this->combination_id);
            $quantity_onhand = $combination->quantity_onhand - $this->quantity;

            // Average price stuff
            // $cost = $combination->cost_average;
            $cost_average = ($combination->quantity_onhand * $combination->cost_average - $this->quantity * $this->price_in) / ($combination->quantity_onhand - $this->quantity);
            
            $combination->cost_average = $cost_average;
//            $combination->last_purchase_price = $this->price_in;

            $combination->quantity_onhand = $quantity_onhand;
            $combination->save();
        }

        // Update Product-Warehouse relationship (quantity)
        $whs = $product->warehouses;
        if ($whs->contains($this->warehouse_id)) {
            $wh = $product->warehouses()->get();
            $wh = $wh->find($this->warehouse_id);
            $quantity = $wh->pivot->quantity - $this->quantity;
            
            if ($quantity != 0) {
                $wh->pivot->quantity = $quantity;
                $wh->pivot->save(); }
            else {
                // Delete record ($quantity = 0)
                $product->warehouses()->detach($this->warehouse_id); }
        } else {
            if ($this->quantity != 0) 
                // ?
                $product->warehouses()->attach($this->warehouse_id, array('quantity' => -$this->quantity));
        }

        // Update Combination-Warehouse relationship (quantity)
        if ($this->combination_id > 0) {
            $whs = $combination->warehouses;
            if ($whs->contains($this->warehouse_id)) {
                $wh = $combination->warehouses()->get();
                $wh = $wh->find($this->warehouse_id);
                $quantity = $wh->pivot->quantity - $this->quantity;
                
                if ($quantity != 0) {
                    $wh->pivot->quantity = $quantity;
                    $wh->pivot->save(); }
                else {
                    // Delete record ($quantity = 0)
                    $combination->warehouses()->detach($this->warehouse_id); }
            } else {
                if ($this->quantity != 0) 
                    // ?
                    $combination->warehouses()->attach($this->warehouse_id, array('quantity' => -$this->quantity));
            }
        }
    }

    // SALE_ORDER
    public function process_30()
    {
        // Update Product
        $product = $this->product;
        
        $quantity_onhand = $product->quantity_onhand - $this->quantity;
        $this->quantity_before_movement = $product->getStockByWarehouse( $this->warehouse_id );
        $this->quantity_after_movement = $this->quantity_before_movement - $this->quantity;
        $this->save();

        // Average price stuff - Not needed!

        // Update Product-Warehouse relationship (quantity)
        $product->setStockByWarehouse( $this->warehouse_id, $this->quantity_after_movement );

        $product->quantity_onhand = $quantity_onhand;       // $product->getStock();
        $product->save();

        return $this;
    }

    // SALE_RETURN
    public function process_31()
    {
        if ( $this->currency_id != Context::getContext()->currency->id ) {
            $currency = Currency::find($this->currency_id);
            $conversion_rate = $currency->conversion_rate;
            $this->price_in = $this->price*$conversion_rate;
        } else
            $this->price_in = $this->price;

        // Update Product
        $product = Product::find($this->product_id);
        $quantity_onhand = $product->quantity_onhand + $this->quantity;

        // Average price stuff
        if ( !($this->combination_id > 0) ) {
            // $cost = $product->cost_average;
            $cost_average = ($product->quantity_onhand * $product->cost_average + $this->quantity * $this->price_in) / ($product->quantity_onhand + $this->quantity);

            $product->cost_average = $cost_average;
//            $product->last_purchase_price = $this->price_in;
        }

        $product->quantity_onhand = $quantity_onhand;
        $product->save();

        // Update Combination
        if ($this->combination_id > 0) {
            $combination = Combination::find($this->combination_id);
            $quantity_onhand = $combination->quantity_onhand + $this->quantity;

            // Average price stuff
            // $cost = $combination->cost_average;
            $cost_average = ($combination->quantity_onhand * $combination->cost_average + $this->quantity * $this->price_in) / ($combination->quantity_onhand + $this->quantity);
            
            $combination->cost_average = $cost_average;
 //           $combination->last_purchase_price = $this->price_in;

            $combination->quantity_onhand = $quantity_onhand;
            $combination->save();
        }

        // Update Product-Warehouse relationship (quantity)
        $whs = $product->warehouses;
        if ($whs->contains($this->warehouse_id)) {
            $wh = $product->warehouses()->get();
            $wh = $wh->find($this->warehouse_id);
            $quantity = $wh->pivot->quantity + $this->quantity;
            
            if ($quantity != 0) {
                $wh->pivot->quantity = $quantity;
                $wh->pivot->save(); }
            else {
                // Delete record ($quantity = 0)
                $product->warehouses()->detach($this->warehouse_id); }
        } else {
            if ($this->quantity != 0) 
                $product->warehouses()->attach($this->warehouse_id, array('quantity' => $this->quantity));
        }

        // Update Combination-Warehouse relationship (quantity)
        if ($this->combination_id > 0) {
            $whs = $combination->warehouses;
            if ($whs->contains($this->warehouse_id)) {
                $wh = $combination->warehouses()->get();
                $wh = $wh->find($this->warehouse_id);
                $quantity = $wh->pivot->quantity + $this->quantity;
                
                if ($quantity != 0) {
                    $wh->pivot->quantity = $quantity;
                    $wh->pivot->save(); }
                else {
                    // Delete record ($quantity = 0)
                    $combination->warehouses()->detach($this->warehouse_id); }
            } else {
                if ($this->quantity != 0) 
                    $combination->warehouses()->attach($this->warehouse_id, array('quantity' => $this->quantity));
            }
        }
    }

    // TRANSFER_OUT
    public function process_40()
    {
        // Update Product
        $product = Product::find($this->product_id);
        $quantity_onhand = $product->quantity_onhand - $this->quantity;

        // Average price stuff - Not needed!

        $product->quantity_onhand = $quantity_onhand;
        $product->save();

        // Update Combination
        if ($this->combination_id > 0) {
            $combination = Combination::find($this->combination_id);
            $quantity_onhand = $combination->quantity_onhand - $this->quantity;

            // Average price stuff - Not needed!

            $combination->quantity_onhand = $quantity_onhand;
            $combination->save();
        }

        // Update Product-Warehouse relationship (quantity)
        $whs = $product->warehouses;
        if ($whs->contains($this->warehouse_id)) {
            $wh = $product->warehouses()->get();
            $wh = $wh->find($this->warehouse_id);
            $quantity = $wh->pivot->quantity - $this->quantity;
            
            if ($quantity != 0) {
                $wh->pivot->quantity = $quantity;
                $wh->pivot->save(); }
            else {
                // Delete record ($quantity = 0)
                $product->warehouses()->detach($this->warehouse_id); }
        } else {
            if ($this->quantity != 0) 
                $product->warehouses()->attach($this->warehouse_id, array('quantity' => $this->quantity));
        }

        // Update Combination-Warehouse relationship (quantity)
        if ($this->combination_id > 0) {
            $whs = $combination->warehouses;
            if ($whs->contains($this->warehouse_id)) {
                $wh = $combination->warehouses()->get();
                $wh = $wh->find($this->warehouse_id);
                $quantity = $wh->pivot->quantity - $this->quantity;
                
                if ($quantity != 0) {
                    $wh->pivot->quantity = $quantity;
                    $wh->pivot->save(); }
                else {
                    // Delete record ($quantity = 0)
                    $combination->warehouses()->detach($this->warehouse_id); }
            } else {
                if ($this->quantity != 0) 
                    $combination->warehouses()->attach($this->warehouse_id, array('quantity' => $this->quantity));
            }
        }
    }

    // TRANSFER_IN
    public function process_41()
    {
        // Update Product
        $product = Product::find($this->product_id);
        $quantity_onhand = $product->quantity_onhand + $this->quantity;

        // Average price stuff - Not needed!

        $product->quantity_onhand = $quantity_onhand;
        $product->save();

        // Update Combination
        if ($this->combination_id > 0) {
            $combination = Combination::find($this->combination_id);
            $quantity_onhand = $combination->quantity_onhand + $this->quantity;

            // Average price stuff - Not needed!

            $combination->quantity_onhand = $quantity_onhand;
            $combination->save();
        }

        // Update Product-Warehouse relationship (quantity)
        $whs = $product->warehouses;
        if ($whs->contains($this->warehouse_id)) {
            $wh = $product->warehouses()->get();
            $wh = $wh->find($this->warehouse_id);
            $quantity = $wh->pivot->quantity + $this->quantity;
            
            if ($quantity != 0) {
                $wh->pivot->quantity = $quantity;
                $wh->pivot->save(); }
            else {
                // Delete record ($quantity = 0)
                $product->warehouses()->detach($this->warehouse_id); }
        } else {
            if ($this->quantity != 0) 
                $product->warehouses()->attach($this->warehouse_id, array('quantity' => $this->quantity));
        }

        // Update Combination-Warehouse relationship (quantity)
        if ($this->combination_id > 0) {
            $whs = $combination->warehouses;
            if ($whs->contains($this->warehouse_id)) {
                $wh = $combination->warehouses()->get();
                $wh = $wh->find($this->warehouse_id);
                $quantity = $wh->pivot->quantity + $this->quantity;
                
                if ($quantity != 0) {
                    $wh->pivot->quantity = $quantity;
                    $wh->pivot->save(); }
                else {
                    // Delete record ($quantity = 0)
                    $combination->warehouses()->detach($this->warehouse_id); }
            } else {
                if ($this->quantity != 0) 
                    $combination->warehouses()->attach($this->warehouse_id, array('quantity' => $this->quantity));
            }
        }
    }

    // MANUFACTURING_INPUT
    public function process_50()
    {
        // Update Stock Movement
 //       $this->price = $product->cost_average;
        $this->price = $product->cost_price;
        $this->save();

        // Update Product
        $product = Product::find($this->product_id);
        $quantity_onhand = $product->quantity_onhand - $this->quantity;

        // Average price stuff - Not needed!

        $product->quantity_onhand = $quantity_onhand;
        $product->save();

        // Update Combination
        if ($this->combination_id > 0) {
            $combination = Combination::find($this->combination_id);
            $quantity_onhand = $combination->quantity_onhand - $this->quantity;

            // Average price stuff - Not needed!

            $combination->quantity_onhand = $quantity_onhand;
            $combination->save();
        }

        // Update Product-Warehouse relationship (quantity)
        $whs = $product->warehouses;
        if ($whs->contains($this->warehouse_id)) {
            $wh = $product->warehouses()->get();
            $wh = $wh->find($this->warehouse_id);
            $quantity = $wh->pivot->quantity - $this->quantity;
            
            if ($quantity != 0) {
                $wh->pivot->quantity = $quantity;
                $wh->pivot->save(); }
            else {
                // Delete record ($quantity = 0)
                $product->warehouses()->detach($this->warehouse_id); }
        } else {
            if ($this->quantity != 0) 
                $product->warehouses()->attach($this->warehouse_id, array('quantity' => $this->quantity));
        }

        // Update Combination-Warehouse relationship (quantity)
        if ($this->combination_id > 0) {
            $whs = $combination->warehouses;
            if ($whs->contains($this->warehouse_id)) {
                $wh = $combination->warehouses()->get();
                $wh = $wh->find($this->warehouse_id);
                $quantity = $wh->pivot->quantity - $this->quantity;
                
                if ($quantity != 0) {
                    $wh->pivot->quantity = $quantity;
                    $wh->pivot->save(); }
                else {
                    // Delete record ($quantity = 0)
                    $combination->warehouses()->detach($this->warehouse_id); }
            } else {
                if ($this->quantity != 0) 
                    $combination->warehouses()->attach($this->warehouse_id, array('quantity' => $this->quantity));
            }
        }
    }

    // MANUFACTURING_RETURN
    public function process_51()
    {
        if ( $this->currency_id != Context::getContext()->currency->id ) {
            $currency = Currency::find($this->currency_id);
            $conversion_rate = $currency->conversion_rate;
            $this->price_in = $this->price*$conversion_rate;
        } else
            $this->price_in = $this->price;

        // Update Product
        $product = Product::find($this->product_id);
        $quantity_onhand = $product->quantity_onhand + $this->quantity;

        // Average price stuff
        if ( !($this->combination_id > 0) ) {
            // $cost = $product->cost_average;
            $cost_average = ($product->quantity_onhand * $product->cost_average + $this->quantity * $this->price_in) / ($product->quantity_onhand + $this->quantity);

            $product->cost_average = $cost_average;
//            $product->last_purchase_price = $this->price_in;
        }

        $product->quantity_onhand = $quantity_onhand;
        $product->save();

        // Update Combination
        if ($this->combination_id > 0) {
            $combination = Combination::find($this->combination_id);
            $quantity_onhand = $combination->quantity_onhand + $this->quantity;

            // Average price stuff
            // $cost = $combination->cost_average;
            $cost_average = ($combination->quantity_onhand * $combination->cost_average + $this->quantity * $this->price_in) / ($combination->quantity_onhand + $this->quantity);
            
            $combination->cost_average = $cost_average;
//            $combination->last_purchase_price = $this->price_in;

            $combination->quantity_onhand = $quantity_onhand;
            $combination->save();
        }

        // Update Product-Warehouse relationship (quantity)
        $whs = $product->warehouses;
        if ($whs->contains($this->warehouse_id)) {
            $wh = $product->warehouses()->get();
            $wh = $wh->find($this->warehouse_id);
            $quantity = $wh->pivot->quantity + $this->quantity;
            
            if ($quantity != 0) {
                $wh->pivot->quantity = $quantity;
                $wh->pivot->save(); }
            else {
                // Delete record ($quantity = 0)
                $product->warehouses()->detach($this->warehouse_id); }
        } else {
            if ($this->quantity != 0) 
                $product->warehouses()->attach($this->warehouse_id, array('quantity' => $this->quantity));
        }

        // Update Combination-Warehouse relationship (quantity)
        if ($this->combination_id > 0) {
            $whs = $combination->warehouses;
            if ($whs->contains($this->warehouse_id)) {
                $wh = $combination->warehouses()->get();
                $wh = $wh->find($this->warehouse_id);
                $quantity = $wh->pivot->quantity + $this->quantity;
                
                if ($quantity != 0) {
                    $wh->pivot->quantity = $quantity;
                    $wh->pivot->save(); }
                else {
                    // Delete record ($quantity = 0)
                    $combination->warehouses()->detach($this->warehouse_id); }
            } else {
                if ($this->quantity != 0) 
                    $combination->warehouses()->attach($this->warehouse_id, array('quantity' => $this->quantity));
            }
        }
    }

    // MANUFACTURING_OUTPUT
    public function process_55()
    {
        // Update Stock Movement
 //       $this->price = $product->cost_average;
        $this->price = $product->cost_price;
        $this->save();

        // Update Product
        $product = Product::find($this->product_id);
        $quantity_onhand = $product->quantity_onhand + $this->quantity;

        // Average price stuff - Not needed!

        $product->quantity_onhand = $quantity_onhand;
        $product->save();

        // Update Combination
        if ($this->combination_id > 0) {
            $combination = Combination::find($this->combination_id);
            $quantity_onhand = $combination->quantity_onhand + $this->quantity;

            // Average price stuff - Not needed!

            $combination->quantity_onhand = $quantity_onhand;
            $combination->save();
        }

        // Update Product-Warehouse relationship (quantity)
        $whs = $product->warehouses;
        if ($whs->contains($this->warehouse_id)) {
            $wh = $product->warehouses()->get();
            $wh = $wh->find($this->warehouse_id);
            $quantity = $wh->pivot->quantity + $this->quantity;
            
            if ($quantity != 0) {
                $wh->pivot->quantity = $quantity;
                $wh->pivot->save(); }
            else {
                // Delete record ($quantity = 0)
                $product->warehouses()->detach($this->warehouse_id); }
        } else {
            if ($this->quantity != 0) 
                $product->warehouses()->attach($this->warehouse_id, array('quantity' => $this->quantity));
        }

        // Update Combination-Warehouse relationship (quantity)
        if ($this->combination_id > 0) {
            $whs = $combination->warehouses;
            if ($whs->contains($this->warehouse_id)) {
                $wh = $combination->warehouses()->get();
                $wh = $wh->find($this->warehouse_id);
                $quantity = $wh->pivot->quantity + $this->quantity;
                
                if ($quantity != 0) {
                    $wh->pivot->quantity = $quantity;
                    $wh->pivot->save(); }
                else {
                    // Delete record ($quantity = 0)
                    $combination->warehouses()->detach($this->warehouse_id); }
            } else {
                if ($this->quantity != 0) 
                    $combination->warehouses()->attach($this->warehouse_id, array('quantity' => $this->quantity));
            }
        }
    }
    
    // Used in StockAdjustmentsController->store
    public function fulfill()
    {
        // Update Product
        $product = Product::find($this->product_id);
        $quantity_onhand = $product->quantity_onhand + $this->quantity;

        // Average price stuff
        if ( !($this->combination_id > 0) ) {
            // $cost = $product->cost_average;
            $cost_average = ($product->quantity_onhand * $product->cost_average + $this->quantity * $this->price) / ($product->quantity_onhand + $this->quantity);

            $product->cost_average = $cost_average;
            $product->last_purchase_price = $this->price;
        }

        $product->quantity_onhand = $quantity_onhand;
        $product->save();

        // Update Combination
        if ($this->combination_id > 0) {
            $combination = Combination::find($this->combination_id);
            $quantity_onhand = $combination->quantity_onhand + $this->quantity;

            // Average price stuff
            // $cost = $combination->cost_average;
            $cost_average = ($combination->quantity_onhand * $combination->cost_average + $this->quantity * $this->price) / ($combination->quantity_onhand + $this->quantity);
            
            $combination->cost_average = $cost_average;
            $combination->last_purchase_price = $this->price;

            $combination->quantity_onhand = $quantity_onhand;
            $combination->save();
        }

        // Update Product-Warehouse relationship (quantity)
        $whs = $product->warehouses;
        if ($whs->contains($this->warehouse_id)) {
            $wh = $product->warehouses()->get();
            $wh = $wh->find($this->warehouse_id);
            $quantity = $wh->pivot->quantity + $this->quantity;
            
            if ($quantity != 0) {
                $wh->pivot->quantity = $quantity;
                $wh->pivot->save(); }
            else {
                // Delete record ($quantity = 0)
                $product->warehouses()->detach($this->warehouse_id); }
        } else {
            if ($this->quantity != 0) 
                $product->warehouses()->attach($this->warehouse_id, array('quantity' => $this->quantity));
        }

        // Update Combination-Warehouse relationship (quantity)
        if ($this->combination_id > 0) {
            $whs = $combination->warehouses;
            if ($whs->contains($this->warehouse_id)) {
                $wh = $combination->warehouses()->get();
                $wh = $wh->find($this->warehouse_id);
                $quantity = $wh->pivot->quantity + $this->quantity;
                
                if ($quantity != 0) {
                    $wh->pivot->quantity = $quantity;
                    $wh->pivot->save(); }
                else {
                    // Delete record ($quantity = 0)
                    $combination->warehouses()->detach($this->warehouse_id); }
            } else {
                if ($this->quantity != 0) 
                    $combination->warehouses()->attach($this->warehouse_id, array('quantity' => $this->quantity));
            }
        }
    }

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get all of the owning stockmovementable models.
     */
    public function stockmovementable()
    {
        return $this->morphTo();
    }

    public function measureunit()
    {
        return $this->belongsTo(MeasureUnit::class, 'measure_unit_id');
    }
    

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function combination()
    {
        return $this->belongsTo(Combination::class);
    }
    
    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }
    
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    
	public function movementtype()
    {
        return $this->belongsTo('MovementType');
	}
    
	public function user()
    {
        return $this->belongsTo(User::class);
	}


    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */


    public function scopeFilter($query, $params)
    {

        if ($params['date_from'])
            // if ( isset($params['date_to']) && trim($params['date_to']) != '' )
        {
            $query->where('date', '>=', $params['date_from'].' 00:00:00');
        }

        if ($params['date_to'])
        {
            $query->where('date', '<=', $params['date_to']  .' 23:59:59');
        }


        if ( isset($params['reference']) && trim($params['reference']) !== '' )
        {
            $query->where('reference', 'LIKE', '%' . trim($params['reference']) . '%');
            // $query->orWhere('combinations.reference', 'LIKE', '%' . trim($params['reference'] . '%'));
/*
            // Moved from controller
            $reference = $params['reference'];
            $query->orWhereHas('combinations', function($q) use ($reference)
                                {
                                    // http://stackoverflow.com/questions/20801859/laravel-eloquent-filter-by-column-of-relationship
                                    $q->where('reference', 'LIKE', '%' . $reference . '%');
                                }
            );  // ToDo: if name is supplied, shows records that match reference but do not match name (due to orWhere condition)
*/
        }


        if ( isset($params['document_reference']) && trim($params['document_reference']) !== '' )
        {
            $query->where('document_reference', 'LIKE', '%' . trim($params['document_reference']) . '%');
        }
        

        if ( isset($params['name']) && trim($params['name']) !== '' )
        {
            $query->where('name', 'LIKE', '%' . trim($params['name'] . '%'));
        }

        if ( isset($params['warehouse_id']) && $params['warehouse_id'] > 0 )
        {
            $query->where('warehouse_id', '=', $params['warehouse_id']);
        }

        if ( isset($params['movement_type_id']) && $params['movement_type_id'] > 0 )
        {
            $query->where('movement_type_id', '=', $params['movement_type_id']);
        }

        if ( isset($params['lot_id']) && $params['lot_id'] > 0 )
        {
            $query->where('lot_id', '=', $params['lot_id']);
        }

        if ( isset($params['lot_reference']) && $params['lot_reference'] !== '' )
        {
            $lot_reference = $params['lot_reference'];

            $query->whereHas('lot', function($q) use ($lot_reference) 
            {
                $q->where('reference', 'LIKE', '%' . $lot_reference . '%');

            });
        }

        return $query;
    }
}