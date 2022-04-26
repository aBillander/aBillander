<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Models\PriceRule;
use App\Models\Context;

// php artisan make:rule SupplierPriceListLineQuantity

class PriceRuleDuplicated implements Rule
{
    private $customer_id;
    private $customer_group_id;
    private $product_id;
    private $currency_id;
    private $measure_unit_id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($customer_id = null, $customer_group_id = null, $product_id = null, $currency_id = null, $measure_unit_id = null)
    {
        $this->customer_id = $customer_id;
        $this->customer_group_id = $customer_group_id;
        $this->product_id  = $product_id;
        $this->currency_id = $currency_id ?: Context::getContext()->currency->id;
        $this->measure_unit_id = $measure_unit_id;
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
        $customer_id = $this->customer_id;

        return ! PriceRule::
                              where('customer_id'       , $this->customer_id )
                            ->where('customer_group_id' , $this->customer_group_id )
                            ->where('product_id'        , $this->product_id )
                            ->where('currency_id'       , $this->currency_id)
                            ->where('measure_unit_id'   , $this->measure_unit_id)
  //                          ->where('from_quantity', $value)
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
            'This Price List Line for the specified Currency and Measure Unit already exists.',
            'validationrules'
        );
    }
}
