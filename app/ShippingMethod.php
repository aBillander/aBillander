<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingMethod extends Model {

    use SoftDeletes;

    public static $types = [
            'basic',
            'multiservice',
        ];

    public static $billing_types = [
            'price',
            'weight',
        ];

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'alias', 'webshop_id', 'class_name', 'carrier_id', 'active',
                           'type', 'transit_time', 'billing_type', 'free_shipping_from', 'tax_id', 'position', 
             ];
    
    // 
    // ShippingMethod CRUD is only available if 'type' == 'basic'
    // 

    public static $rules = array(
        'name'         => 'required|min:2|max:64',
        'alias'        => 'required|min:2|max:16',
        'tax_id'       => 'exists:taxes,id',
        'carrier_id'   => 'sometimes|nullable|exists:carriers,id',
    	);
    

    public static function boot()
    {
        parent::boot();

        static::deleted(function($method)
        {
            $method->servicelines()->delete(); // Not calling the events on each child
        });
    }



    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public static function getBillingTypeList()
    {
            $list = [];
            foreach (static::$billing_types as $billing_type) {
                $list[$billing_type] = l(get_called_class().'.'.$billing_type, [], 'appmultilang');
            }

            return $list;
    }

    public function getBillingTypeNameAttribute( )
    {
            return l(get_called_class().'.'.$this->billing_type, [], 'appmultilang');
    }

    public static function getBillingTypeName( $billing_type )
    {
            return l(get_called_class().'.'.$billing_type, [], 'appmultilang');
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function tax()
    {
        return $this->belongsTo('App\Tax', 'tax_id');
    }

    public function carrier()
    {
        return $this->belongsTo('App\Carrier', 'carrier_id');
    }
    
    public function customerorders()
    {
        return $this->hasMany('App\CustomerOrder');
    }
    

    public function services()
    {
        return $this->hasMany('App\ShippingMethodService', 'shipping_method_id');
    }
    

    public function servicelines()
    {
        // Only if 'type' == 'basic'
        return $this->morphMany('App\ShippingMethodServiceLine', 'tabulable');
    }

    // Alias
    public function rules()
    {
        // Only if 'type' == 'basic'
        return $this->servicelines();
    }



    /*
    |--------------------------------------------------------------------------
    | Cost Calculations
    |--------------------------------------------------------------------------
    */

    public static function costPriceCalculator( $method, $shippable )
    {
        // $shippable is either a Cart or Document (Quotation, Order, Slip or Invoice) object

        // laravel helper class_basename:
        $baseClass = class_basename(get_class( $shippable ));

        if ( $baseClass == 'Cart' )
            return $method->calculateCartCostPrice( $shippable );

        // Return Price Object
        return $price = $method->calculateCartCostPrice( $shippable );
    }
    
    public function calculateCartCostPrice( Cart $cart )
    {
        // Instantiate calculator
        $class = $this->class_name;

        if ( !class_exists($class) )
        {
            return [
                        'shipping_label' => $class,
                        'cost'           => 0.0,
                        'tax'            => Tax::find( Configuration::get('DEF_TAX') ),
                ];
        }

        $calculator = new $class ();

        return $calculator->calculateCartCostPrice( $cart );

    }


}