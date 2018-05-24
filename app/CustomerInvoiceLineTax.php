<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerInvoiceLineTax extends Model {

	//


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function customerinvoiceline()
    {
       return $this->belongsTo('App\CustomerInvoiceLine', 'customer_invoice_line_id');
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
