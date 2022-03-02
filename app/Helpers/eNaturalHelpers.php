<?php

/*
|--------------------------------------------------------------------------
| Helper functions.
|--------------------------------------------------------------------------
|
| ToDo: move to a proper location if necessary; Google this: 
| "laravel 5 where to put helpers".
|
*/
    

/*
|--------------------------------------------------------------------------
| Nice Prints
|--------------------------------------------------------------------------
*/

function xniceQuantity( $val = 0.0, $decimalPlaces = 0 )
{
    $data = floatval( $val );

    // Do formatting

    $data = number_format($data, $decimalPlaces, ',', '.');

    return $data;
}
