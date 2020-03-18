<?php

namespace App;
    
interface ShippableInterface
{

    public function getShippingBillableAmount( $billing_type );

}