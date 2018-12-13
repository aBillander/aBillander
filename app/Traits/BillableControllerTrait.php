<?php 

namespace App\Traits;

use Illuminate\Http\Request;

trait BillableControllerTrait
{
    /**
    * Gather handy Controller vars (useful when passing to views)
    */
    public function modelVars()
    {
        // 

        return [
                'model' => $this->model,
                'model_snake_case' => $this->model_snake_case,
                'model_path' => $this->model_path,
                'view_path' => $this->view_path,
        ];
    }

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