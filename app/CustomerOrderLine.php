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
    
}
