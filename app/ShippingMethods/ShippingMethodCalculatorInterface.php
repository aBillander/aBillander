<?php

namespace App\ShippingMethods;

use App\Models\Cart;
    
interface ShippingMethodCalculatorInterface
{

    public function calculateCartCostPrice( Cart $cart );

}