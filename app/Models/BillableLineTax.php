<?php

namespace App\Models;

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
       return $this->belongsTo(Tax::class, 'tax_id');
    }

    public function taxrule()
    {
       return $this->belongsTo(TaxRule::class, 'tax_rule_id');
    }
}
