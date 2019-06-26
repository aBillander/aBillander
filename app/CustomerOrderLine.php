<?php

namespace App;

class CustomerOrderLine extends BillableLine
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
    
    public function customerorder()
    {
        return $this->belongsTo('App\CustomerOrder', 'customer_order_id');
    }
    
    // Needed by /WooConnect/src/WooOrderImporter.php
    public function customerorderlinetaxes()
    {
        return $this->hasMany('App\CustomerOrderLineTax', 'customer_order_line_id');
    }
}
