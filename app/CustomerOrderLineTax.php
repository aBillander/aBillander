<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerOrderLineTax extends Model
{

	//


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function customerorderline()
    {
       return $this->belongsTo('App\CustomerOrderLine', 'customer_order_line_id');
    }

    public function tax()
    {
       return $this->belongsTo('App\Tax', 'tax_id');
    }

    public function taxrule()
    {
       return $this->belongsTo('App\TaxRule', 'tax_rule_id');
    }
}
