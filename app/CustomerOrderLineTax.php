<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;
use App\Traits\BillableLineTaxTrait;

class CustomerOrderLineTax extends Model
{

    use ViewFormatterTrait;
    use BillableLineTaxTrait;

	//


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function customerorderline()
    {
       // return $this->belongsTo('App\CustomerOrderLine', 'customer_order_line_id');

       return $this->documentline();
    }
    
}
