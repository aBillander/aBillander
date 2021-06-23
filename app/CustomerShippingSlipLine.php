<?php

namespace App;

class CustomerShippingSlipLine extends BillableLine
{

    /**
     * The fillable properties for this model.
     *
     * @var array
     *
     * 
     */
    protected $line_fillable = [
    ];
    

    public static $rules = [
//        'product_id'    => 'required',
    ];
    
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function customershippingslip()
    {
        return $this->belongsTo('App\CustomerShippingSlip', 'customer_shipping_slip_id');
    }
    
}
