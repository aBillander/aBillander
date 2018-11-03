<?php 

namespace App\Traits;

trait BillableCustomTrait
{
    
    private function documenttotals_rounding_custom()
    {
        return $this->documenttotals_rounding_none();
    }

}