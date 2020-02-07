<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\SupplierPriceListLine;
use App\Context;

// php artisan make:rule SupplierPriceListLineQuantity

class SupplierPriceListLineDuplicated implements Rule
{
    private $supplier_id;
    private $product_id;
    private $currency_id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($supplier_id = null, $product_id = null, $currency_id = null)
    {
        $this->supplier_id = $supplier_id;
        $this->product_id  = $product_id;
        $this->currency_id = $currency_id ?: Context::getContext()->currency->id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return ! SupplierPriceListLine::
                              where('supplier_id', $this->supplier_id)
                            ->where('product_id' , $this->product_id )
                            ->where('currency_id', $this->currency_id)
                            ->where('from_quantity', $value)
  //                          ->orderBy('from_quantity', 'asc')
                            ->exists();

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return l(
            'This Price List Line already exists.',
            'validationrules'
        );
    }
}
