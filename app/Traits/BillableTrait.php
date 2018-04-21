<?php 

namespace App\Traits;

use Illuminate\Http\Request;

trait BillableTrait
{
    /**
    * Set form dates to billable (EDIT Method)
    */
    public function addFormDates( $form_dates = [], &$billable )
    {
        // Dates (cuen)
        foreach( $form_dates as $name ) {

            $billable->{$name.'_form'} = abi_toLocale_date_short( $billable->{$name} );
        }

        return true;
    }

    /**
    * Add dates to request (STORE & UPDATE methods)
    */
    public function mergeFormDates( $form_dates = [], Request &$request )
    {
        // Dates (cuen)
        $dates = [];

        foreach( $form_dates as $name ) {

            $dates[ $name ] = abi_fromLocale_date_short( $request->input($name.'_form') );
        }

        $request->merge( $dates );

        return true;
    }

}