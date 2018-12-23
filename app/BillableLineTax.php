<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;
use App\Traits\BillableLineTaxTrait;

class BillableLineTax extends Model
{
    use ViewFormatterTrait;
    use BillableLineTaxTrait;

	//


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function documentline()
    {
       return $this->belongsTo($this->getParentClassName(), $this->getParentClassSnakeCase().'_id');
    }

    // Alias
    public function line()
    {
       return $this->documentline();
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
