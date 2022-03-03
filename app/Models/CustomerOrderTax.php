<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerOrderTax extends CustomerOrderLineTax // Model
{

	public $customer_order_id;


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function getCustomerorderAttribute()
    {
       return CustomerOrder::find($this->customer_order_id);
    }

/* already in parent class
    public function tax()
    {
       return $this->belongsTo('App\Tax', 'tax_id');
    }

    public function taxrule()
    {
       return $this->belongsTo('App\TaxRule', 'tax_rule_id');
    }
*/
}
