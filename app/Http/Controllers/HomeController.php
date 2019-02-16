<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        return view('home.home');
    }


/* ********************************************************************************************* */  


    /**
     * AJAX Stuff.
     *
     * 
     */

    /**
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function searchCustomer(Request $request)
    {
//        $term  = $request->has('term')  ? $request->input('term')  : null ;
//        $query = $request->has('query') ? $request->input('query') : $term;


        if ($request->has('term'))
        {
            $search = $request->term;

            $customers = \App\Customer::where(   'name_fiscal',      'LIKE', '%'.$search.'%' )
                                    ->orWhere( 'name_commercial',      'LIKE', '%'.$search.'%' )
                                    ->orWhere( 'identification', 'LIKE', '%'.$search.'%' )
//                                    ->with('currency')
//                                    ->with('addresses')
                                    ->take( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) )
                                    ->get();

//            return $customers;
//            return Product::searchByNameAutocomplete($query, $onhand_only);
//            return Product::searchByNameAutocomplete($request->input('query'), $onhand_only);
//            response( $customers );
//            return json_encode( $customers );
            return response()->json( $customers );
        }

        // Otherwise, die silently
        return json_encode( [ 'query' => '', 'suggestions' => [] ] );
        
    }


    public function searchProduct(Request $request)
    {
        $search = $request->term;

        $products = \App\Product::select('id', 'name', 'reference', 'measure_unit_id')
                                ->where(   'name',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference', 'LIKE', '%'.$search.'%' )
//                                ->IsSaleable()
//                                ->qualifyForCustomer( $request->input('customer_id'), $request->input('currency_id') )
//                                ->with('measureunit')
//                                ->toSql();
                                ->get( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) );


//                                dd($products);

        return response( $products );
    }
}
