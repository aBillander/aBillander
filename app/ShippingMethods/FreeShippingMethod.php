<?php

namespace App\ShippingMethods;

use App\Models\Configuration;
use App\Models\Tax;
use App\Models\ShippingMethod;
use App\Models\Cart;

class FreeShippingMethod extends ShippingMethod implements ShippingMethodCalculatorInterface
{

    public function calculateCartCostPrice( Cart $cart )
    {
        $shipping_label = Configuration::get('ABCC_SHIPPING_LABEL');

        $tax_id         = Configuration::get('ABCC_SHIPPING_TAX');


        $tax = Tax::find($tax_id);
        $tax_percent = $tax->percent;   // Naughty boy! Should consider cart invoicing address!

        $cost = 0.0;

        return [
                    'shipping_label' => $shipping_label,
                    'cost'           => $cost,
                    'tax'            => $tax,
            ];

    }

}