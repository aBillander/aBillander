<?php 

namespace App\Models;

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
                    'customer_shipping_slip_id',
    ];
    

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];

}
