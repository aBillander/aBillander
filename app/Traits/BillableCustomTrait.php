<?php 

namespace App\Traits;

trait BillableCustomTrait
{
	
    /**
     * Get the fillable attributes for the model.
     *
     * https://gist.github.com/JordanDalton/f952b053ef188e8750177bf0260ce166
     *
     * @return array
     */
    public function getFillable()
    {
        return array_merge(parent::getFillable(), $this->document_fillable);
    }

    
    private function documenttotals_rounding_custom()
    {
        return $this->documenttotals_rounding_none();
    }

}