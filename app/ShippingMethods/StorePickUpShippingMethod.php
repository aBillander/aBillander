<?php

namespace App\ShippingMethods;

use App\Configuration;
use App\Tax;
use App\ShippingMethod;

class StorePickUpShippingMethod extends ShippingMethod implements ShippingMethodCalculatorInterface
{

    public function calculateCartCostPrice( \App\Cart $cart )
    {
        $shipping_label = Configuration::get('ABCC_SHIPPING_LABEL');

        // $free_shipping  = Configuration::get('ABCC_FREE_SHIPPING_PRICE');
        // $state_42       = Configuration::get('ABCC_STATE_42_SHIPPING');
        // $country_1      = Configuration::get('ABCC_COUNTRY_1_SHIPPING');

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