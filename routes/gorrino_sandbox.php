<?php

use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\Exports\ArrayExport;

use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;
// use Queridiam\WooCommerce\Facades\WooCommerce;

/*
|--------------------------------------------------------------------------
| Gorrino Web Routes "sandbox"
|--------------------------------------------------------------------------
|
| Quick & dirty!
|
*/


/* ********************************************************** */


Route::get('/auton', function () {
    // 
    $lines2 = \App\Models\SupplierShippingSlipLine::
                      where('product_id', 5)
                    ->whereHas('document', function($q)
                            {
                                $q->where('status', 'confirmed');
                            }
                    )
                    ->get();

    abi_r($lines2);
});



/* ********************************************************** */



Route::get('/excel', function () {
    // 

    $data = App\Models\User::all()->toArray();

    $styles = [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'italic' => true]],

            // Styling a specific cell by coordinate.
            'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            'C'  => ['font' => ['size' => 16]],
        ];

    $export = (new ArrayExport($data))->setStyles($styles)->setTitle('Categories');
    // same as: 
    // $export = new ArrayExport($data, $styles, $title);

    return Excel::download($export, 'users.xlsx', null, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
});


/* ********************************************************** */


Route::get('/woo', function () {
        $settings = [];

        // Get Configurations from WooCommerce Shop
        try {

            $groups = WooCommerce::get('settings'); // Array
        }

        catch( WooHttpClientException $e ) {

            /*
            $e->getMessage(); // Error message.

            $e->getRequest(); // Last request data.

            $e->getResponse(); // Last response data.
            */

            abi_r( $e->getMessage() );

        }

        abi_r($groups);
});


Route::get('/home0', function () {
    // 
    return view('home');
});


Route::get('/h1', function () {
    // 
    $user_home = Auth::user()->home_page;
    abi_r($user_home);
    abi_r((int) checkRoute( $user_home  ));


        if ( ! checkRoute( $user_home  ) ) 
        {
            $user_home =  App\Providers\RouteServiceProvider::USERS_HOME;
        }
});

Route::get('/soon', function () {
    // 
    return view('soon');
});


/* ********************************************************** */


Route::get('/invu', function () {
    $id=1182;
    $invu = \App\Models\CustomerInvoice::find($id);
    abi_r( $invu->leftAscriptions );
    abi_r( '**********************************' );
    abi_r( $invu->leftShippingSlips() );
});


Route::get('/langu', function () {
    // Changing The Default Language At Runtime
    App::setLocale('es');
    abi_r( l('Yes', 'layouts') );
});


/* ********************************************************** */




/* ********************************************************** */




/* ********************************************************** */

