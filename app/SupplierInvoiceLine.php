<?php

namespace App;

class SupplierInvoiceLine extends BillableLine
{

    /**
     * The fillable properties for this model.
     *
     * @var array
     *
     * 
     */
    protected $line_fillable = [
        'unit_supplier_price', 'unit_supplier_final_price', 'unit_customer_final_price_tax_inc',
    ];
    

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];

}
