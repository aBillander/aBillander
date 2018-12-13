<?php

namespace App;

use \App\CustomerShippingSlipLineTax;

class CustomerShippingSlipLine extends BillableLine
{
	
//    protected $fillable = [ 'product_id', 'woo_product_id', 'woo_variation_id', 
//    						'reference', 'name', 'quantity', 'customer_shipping_slip_id'
//                          ];

    protected $fillable = ['line_sort_order', 'line_type', 
                    'product_id', 'combination_id', 'reference', 'name', 'quantity', 'measure_unit_id',
                    'cost_price', 'unit_price', 'unit_customer_price', 
                    'prices_entered_with_tax',
                    'unit_customer_final_price', 'unit_customer_final_price_tax_inc', 
                    'unit_final_price', 'unit_final_price_tax_inc', 
                    'sales_equalization', 'discount_percent', 'discount_amount_tax_incl', 'discount_amount_tax_excl', 
                    'total_tax_incl', 'total_tax_excl', // Not fillable? For sure: NOT. Totals are calculated after ALL taxes are set. BUT handy fillable when importing order!!!
                    'tax_percent', 'commission_percent', 'notes', 'locked',
 //                 'customer_shipping_slip_id',
                    'tax_id', 'sales_rep_id',
    ];

    public static $rules = [
//        'product_id'    => 'required',
    ];
    
}
