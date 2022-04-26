<?php

namespace App\Models;

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
        return $this->belongsTo(CustomerOrder::class, 'customer_order_id');
    }
    
    // Needed by /WooConnect/src/WooOrderImporter.php
    public function customerorderlinetaxes()
    {
        return $this->hasMany(CustomerOrderLineTax::class, 'customer_order_line_id');
    }
}
