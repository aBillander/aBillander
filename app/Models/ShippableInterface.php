<?php

namespace App\Models;
    
interface ShippableInterface
{

    public function getShippingBillableAmount( $billing_type );

}