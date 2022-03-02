<?php

namespace App\ShippingMethods;
    
interface ShippingMethodCalculatorInterface
{

    public function calculateCartCostPrice( \App\Cart $cart );

}