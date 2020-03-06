<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingMethod extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'alias', 'webshop_id', 'class_name', 'carrier_id', 'active'];

    public static $rules = array(
        'name'         => 'required|min:2|max:64',
        'alias'         => 'required|min:2|max:16',
        'carrier_id'   => 'sometimes|nullable|exists:carriers,id',
    	);

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function carrier()
    {
        return $this->belongsTo('App\Carrier', 'carrier_id');
    }
    
    public function customerorders()
    {
        return $this->hasMany('App\CustomerOrder');
    }
    

    public function shippingmethodtable()
    {
        return $this->hasOne('App\ShippingMethodTable', 'shipping_method_table_id');
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