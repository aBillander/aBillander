<?php 

namespace App;

class CustomerInvoiceLine extends BillableLine
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
    

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];

}
