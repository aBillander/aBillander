<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\ViewFormatterTrait;

use \Lang as Lang;

class StockMovement extends Model {

    use ViewFormatterTrait;
    use SoftDeletes;

    protected $price_in;        // Movement Price in Company Currency (for average price calculations)

    protected $dates = ['date', 'deleted_at'];

    protected $fillable = [ 'date', 'document_reference', 'price', 'currency_id', 'conversion_rate', 
                            'quantity', 'measure_unit_id', 
                            'notes',
                            'product_id', 'combination_id', 'reference', 'name', 'warehouse_id', 'warehouse_counterpart_id', 'movement_type_id',

                            'user_id', 'inventorycode',
                           ];

//        'date' => 'required|date|date_format:YY-MM-DD',
//         See: https://es.stackoverflow.com/questions/57020/validaci%C3%B3n-de-formato-de-fecha-no-funciona-laravel-5-3
//        'document_reference' => 'required',   <- Stock adjustments & others do not need it!!

    public static $rules = [
            '10' => array(
                    'date' => 'required',
                    'price' => 'required|min:0|not_in:0',
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
                    'price' => 'required|min:0|not_in:0',
                    'currency_id' => 'exists:currencies,id',
                    'quantity' => 'required',                   //|not_in:0',
                    'product_id' => 'exists:products,id',
//                    'combination_id' => 'sometimes|exists:combinations,id',       // In fact, combination_id is CALCULATED in Controller
                    'warehouse_id' => 'exists:warehouses,id',
                    'movement_type_id' => 'required',
                    ),
            '21' => array(
                    'date' => 'required',
                    'price' => 'required|min:0|not_in:0',
                    'currency_id' => 'exists:currencies,id',
                    'quantity' => 'required',                   //|not_in:0',
                    'product_id' => 'exists:products,id',
                    'combination_id' => 'sometimes|exists:combinations,id',
                    'warehouse_id' => 'exists:warehouses,id',
                    'movement_type_id' => 'required',
                    ),
            '30' => array(
                    'date' => 'required',
                    'price' => 'required|min:0|not_in:0',
                    'currency_id' => 'exists:currencies,id',
                    'quantity' => 'required',                   //|not_in:0',
                    'product_id' => 'exists:products,id',
                    'combination_id' => 'sometimes|exists:combinations,id',
                    'warehouse_id' => 'exists:warehouses,id',
                    'movement_type_id' => 'required',
                    ),
            '31' => array(
                    'date' => 'required',
                    'price' => 'required|min:0|not_in:0',
                    'currency_id' => 'exists:currencies,id',
                    'quantity' => 'required',                   //|not_in:0',
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
	const PURCHASE_ORDER       = 20;
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
    
    public static function stockmovementList()
    {
        $list = array();

        foreach (self::validTypes() as $t){

            $list[$t] = $t.' - '.Lang::get('appmultilang.'.$t);

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


            static $segment;

            if ($segment) return $segment;

            $str = $this->stockmovementable_type;   // Maybe $this->stockmovementable_type = '' or NULL
            if ( !$str ) return $segment = '';

            $segments = array_reverse(explode('\\', $str));

            return $segment = str_plural(strtolower($segments[0]));
    }

    public function getStockmovementableDocumentRoute()
    {
            static $segment;

            if ($segment) return $segment;

            $str = $this->stockmovementable_type;
            if ( !$str ) return $segment = '';

            $segments = array_reverse(explode('\\', $str));


            // Last segment
            $str = substr( $segments[0], 0, -strlen('Line') );

            return $segment = str_plural(strtolower($str));
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | Stock movement fulfillment (perform stock movements)
    |--------------------------------------------------------------------------
    */

    public static function createAndProcess( $data = [] )
    {
        // 
        $movement = StockMovement::create( $data );

        if ( $movement->process() ) return $movement;   // ->fresh();  Need this???

        // If process fails, then delete
        $movement->delete();

        return null;
    }

    public function process()
    {
        // $price_in;
        // Price 4 Cost average calculations
        $this->price_in = $this->price/$this->conversion_rate;


        // Product & Combination;
        // Update Cpmbination
        $this->load(['product', 'combination']);


        $list = self::stockmovementList();
        $method = 'process_'.$this->movement_type_id;
        
        if ( isset($list[$this->movement_type_id]) && method_exists($this, $method) ) 
            return $this->{$method}();
        else
            return false;
    }

    public function process_stock()
    {
        return false;
    }

    public function process_price()
    {
        return false;
    }


    // INITIAL_STOCK
    public function process_10()
    {
        // Update Product
        $product = $this->product;

        if ( $product->getStockByWarehouse( $this->warehouse_id ) > 0.0 ) return false;
        
        $quantity_onhand = $this->quantity;
        $this->quantity_before_movement = 0.0;
        $this->quantity_after_movement = $quantity_onhand;

        if ($this->quantity_before_movement == $this->quantity_after_movement)
        {
            // Nothing said about cost price
            return false;
        }
        $this->save();

        // Average price stuff
        if ( !($this->combination_id > 0) ) {
            $product->cost_average = $this->price_in;
            $product->last_purchase_price = 0.0;
        }

        // All warehouses
        $product->quantity_onhand += $quantity_onhand;
        $product->save();

        // Update Cpmbination
        if ($this->combination_id > 0) {
            $combination = $this->combination;
            $quantity_onhand = $this->quantity;

            // Average price stuff
            $combination->cost_average = $this->price_in;
            $combination->last_purchase_price = 0.0;

            $combination->quantity_onhand += $quantity_onhand;
            $combination->save();
        }

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

        return $this;
    }

    // ADJUSTMENT
    public function process_12()
    {
        // Update Product
        $product = $this->product;

        $quantity_onhand = $this->quantity;
        $this->quantity_before_movement = $product->getStockByWarehouse( $this->warehouse_id );
        $this->quantity_after_movement = $quantity_onhand;

        if ($this->quantity_before_movement == $this->quantity_after_movement)
        {
            // Nothing said about cost price
            return false;
        }

        if ($this->price === null) 
        {
            $this->price = ($this->combination_id > 0) ? $combination->cost_average : $product->cost_average;
            $this->price_in = $this->price;
        }
        $this->save();

        // Average price stuff
        if ( !($this->combination_id > 0) ) {
            $product->cost_average = $this->price_in;
        }

        // Update Cpmbination
        if ($this->combination_id > 0) {
            $combination = $this->combination;
            $quantity_onhand = $this->quantity;

            // Average price stuff
            $combination->cost_average = $this->price_in;
        }

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

        $product->quantity_onhand = $product->getStock();
        $product->save();

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

            $combination->quantity_onhand = $combination->getStock();
            $combination->save();
        }

        return $this;
    }

    // PURCHASE_ORDER
    public function process_20()
    {
        // Price 4 Cost average calculations
        if ( $this->currency_id != \App\Context::getContext()->currency->id ) {
            $currency = \App\Currency::find($this->currency_id);
            $conversion_rate = $currency->conversion_rate;
            $this->price_in = $this->price*$conversion_rate;
        } else
            $this->price_in = $this->price;

        // Update Product
        $product = \App\Product::find($this->product_id);
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

        // Update Cpmbination
        if ($this->combination_id > 0) {
            $combination = \App\Combination::find($this->combination_id);
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
        if ( $this->currency_id != \App\Context::getContext()->currency->id ) {
            $currency = \App\Currency::find($this->currency_id);
            $conversion_rate = $currency->conversion_rate;
            $this->price_in = $this->price*$conversion_rate;
        } else
            $this->price_in = $this->price;

        // Update Product
        $product = \App\Product::find($this->product_id);
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

        // Update Cpmbination
        if ($this->combination_id > 0) {
            $combination = \App\Combination::find($this->combination_id);
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

        $product->quantity_onhand = $product->getStock();
        $product->save();

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

        // Update Cpmbination
        if ($this->combination_id > 0) {
            $combination = $this->combination;
            $quantity_onhand = $combination->quantity_onhand - $this->quantity;

            // Average price stuff - Not needed!

            $combination->quantity_onhand = $combination->getStock();
            $combination->save();
        }

        return $this;
    }

    // SALE_RETURN
    public function process_31()
    {
        if ( $this->currency_id != \App\Context::getContext()->currency->id ) {
            $currency = \App\Currency::find($this->currency_id);
            $conversion_rate = $currency->conversion_rate;
            $this->price_in = $this->price*$conversion_rate;
        } else
            $this->price_in = $this->price;

        // Update Product
        $product = \App\Product::find($this->product_id);
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

        // Update Cpmbination
        if ($this->combination_id > 0) {
            $combination = \App\Combination::find($this->combination_id);
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
        $product = \App\Product::find($this->product_id);
        $quantity_onhand = $product->quantity_onhand - $this->quantity;

        // Average price stuff - Not needed!

        $product->quantity_onhand = $quantity_onhand;
        $product->save();

        // Update Cpmbination
        if ($this->combination_id > 0) {
            $combination = \App\Combination::find($this->combination_id);
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
        $product = \App\Product::find($this->product_id);
        $quantity_onhand = $product->quantity_onhand + $this->quantity;

        // Average price stuff - Not needed!

        $product->quantity_onhand = $quantity_onhand;
        $product->save();

        // Update Cpmbination
        if ($this->combination_id > 0) {
            $combination = \App\Combination::find($this->combination_id);
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
        $product = \App\Product::find($this->product_id);
        $quantity_onhand = $product->quantity_onhand - $this->quantity;

        // Average price stuff - Not needed!

        $product->quantity_onhand = $quantity_onhand;
        $product->save();

        // Update Cpmbination
        if ($this->combination_id > 0) {
            $combination = \App\Combination::find($this->combination_id);
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
        if ( $this->currency_id != \App\Context::getContext()->currency->id ) {
            $currency = \App\Currency::find($this->currency_id);
            $conversion_rate = $currency->conversion_rate;
            $this->price_in = $this->price*$conversion_rate;
        } else
            $this->price_in = $this->price;

        // Update Product
        $product = \App\Product::find($this->product_id);
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

        // Update Cpmbination
        if ($this->combination_id > 0) {
            $combination = \App\Combination::find($this->combination_id);
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
        $product = \App\Product::find($this->product_id);
        $quantity_onhand = $product->quantity_onhand + $this->quantity;

        // Average price stuff - Not needed!

        $product->quantity_onhand = $quantity_onhand;
        $product->save();

        // Update Cpmbination
        if ($this->combination_id > 0) {
            $combination = \App\Combination::find($this->combination_id);
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
        $product = \App\Product::find($this->product_id);
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

        // Update Cpmbination
        if ($this->combination_id > 0) {
            $combination = \App\Combination::find($this->combination_id);
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
    

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
    
    public function combination()
    {
        return $this->belongsTo('App\Combination');
    }
	
    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse');
	}
    
	public function movementtype()
    {
        return $this->belongsTo('MovementType');
	}
    
	public function user()
    {
        return $this->belongsTo('App\User');
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

        if ( isset($params['name']) && trim($params['name']) !== '' )
        {
            $query->where('name', 'LIKE', '%' . trim($params['name'] . '%'));
        }

        if ( isset($params['warehouse_id']) && $params['warehouse_id'] > 0 )
        {
            $query->where('warehouse_id', '=', $params['warehouse_id']);
        }

        return $query;
    }
}