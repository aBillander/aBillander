<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerOrderLine extends Model
{

//    protected $dates = ['due_date'];
	
    protected $fillable = [ 'product_id', 'woo_product_id', 'woo_variation_id', 
    						'reference', 'name', 'quantity', 'customer_order_id'
                          ];

    public static $rules = array(
    	'product_id'    => 'required',
    	);
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function customerorder()
    {
        return $this->belongsTo('App\CustomerOrder');
    }

    public function product()
    {
       return $this->belongsTo('App\Product');
    }
}
