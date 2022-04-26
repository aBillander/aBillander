<?php

namespace App\Models;

class CustomerOrderLineTax extends BillableLineTax
{


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Needed (MayBe) by /WooConnect/src/WooOrderImporter.php
    public function customerorderline()
    {
       return $this->belongsTo(CustomerOrderLine::class, 'customer_order_line_id');
    }
    
}
