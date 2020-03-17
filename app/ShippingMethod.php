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

        return $price = $method->calculateDocumentShippingCost( $shippable );

/*
        // laravel helper class_basename:
        $baseClass = class_basename(get_class( $shippable ));

        if ( $baseClass == 'Cart' )
            return $method->calculateCartCostPrice( $shippable );

        // Return Price Object
        return $price = $method->calculateCartCostPrice( $shippable );
*/
    }
    
    public function calculateDocumentShippingCost( $shippable )
    {
        // $shippable is either a Cart or Document (Quotation, Order, Slip or Invoice) object
        // that implements "Shippable Interface"

        $shipping_label = Configuration::get('ABCC_SHIPPING_LABEL');

        $free_shipping  = $this->free_shipping_from;

        $tax_id         = $this->tax_id;


        $tax = Tax::find($tax_id);

        $cost = 0.0;

        $billable_amount = $shippable->getShippingBillableAmount( $this->billing_type );

        // Now, perform calculations, Ho, ho, ho!

        // Free Shipping
        // ToDo: Global ABCC free shipping ???
        if ( 0 && $billable_amount >= $free_shipping ) 
            $cost = 0.0;
        else 
        {
            $address = $shippable->shippingaddress;

            $price = $this->getPriceByAddress( $address, $billable_amount );

            if ( $price == null )
                // No rule found => Out of range
                // ToDo: Manage this situation
                $cost = 0.0;
            else
                $cost = $price;

        }

        return [
                    'shipping_label' => $shipping_label,
                    'cost'           => $cost,
                    'tax'            => $tax,
            ];

    }

    public function getPriceByAddress( Address $address = null, $amount = 0.0 )
    {
        if ( $address == null ) $address = Context::getContext()->company->address;

        $rules = $this->rules;

        // [1] Postal Code
        $address_rule = $rules->where('country_id', $address->country_id)
                                ->where('postcode', $address->postcode)
                                ->where('from_amount', '<=', $amount)
                                ->sortByDesc('from_amount')
                                ->first();

        if ($address_rule) return $address_rule->price;

        // [2] State
        $address_rule = $rules->where('country_id', $address->country_id)
                                ->where('state_id', $address->state_id)
                                ->where('from_amount', '<=', $amount)
                                ->sortByDesc('from_amount')
                                ->first();

        if ($address_rule) return $address_rule->price;

        // [3] Country
        $address_rule = $rules->where('country_id', $address->country_id)
                                ->where('from_amount', '<=', $amount)
                                ->sortByDesc('from_amount')
                                ->first();

        if ($address_rule) return $address_rule->price;

        // [4] Universe
        $address_rule = $rules->filter(function ($value, $key) {
                                    return $value->country_id == null || $value->country_id == '' || $value->country_id == 0 ;
                                })
                                ->where('from_amount', '<=', $amount)
                                ->sortByDesc('from_amount')
                                ->first();

        if ($address_rule) return $address_rule->price;

        // Nothing found
        return null;
    }

    
    // To be deprecated...
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