<?php

namespace App\ShippingMethods;

use App\Configuration;
use App\Tax;
use App\ShippingMethod;

class TransportAgencyShippingMethod extends ShippingMethod implements ShippingMethodCalculatorInterface
{

    public function calculateCartCostPrice( \App\Cart $cart )
    {
        $shipping_label = Configuration::get('ABCC_SHIPPING_LABEL');

        $free_shipping  = Configuration::get('ABCC_FREE_SHIPPING_PRICE');
        $state_42       = Configuration::get('ABCC_STATE_42_SHIPPING');
        $country_1      = Configuration::get('ABCC_COUNTRY_1_SHIPPING');

        $tax_id         = Configuration::get('ABCC_SHIPPING_TAX');


        $tax = Tax::find($tax_id);
        $tax_percent = $tax->percent;   // Naughty boy! Should consider cart invoicing address!

        $line_products = $cart->cartlines->where('line_type', 'product');

        $total_products_tax_excl = $cart->amount;   // $line_products->sum('total_tax_excl');

        // Now, perform calculations
        // To Do: Improve this procedure
        $address = $cart->shippingaddress;

        $cost = $country_1; // Start here. No Country other than Spain

        if ( $address->state_id == 42 ) $cost = $state_42;      // Sevilla

        // Free Shipping
        if ( $total_products_tax_excl >= $free_shipping ) $cost = 0.0;

        return [
                    'shipping_label' => $shipping_label,
                    'cost'           => $cost,
                    'tax'            => $tax,
            ];

    }

}