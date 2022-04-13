<?php

namespace App\Http\Controllers;

use App\Helpers\Tools;
use App\Http\Controllers\HelferinTraits\ReportsABCCustomerSalesTrait;
use App\Http\Controllers\HelferinTraits\ReportsABCProductSalesTrait;
use App\Http\Controllers\HelferinTraits\ReportsCategorySalesTrait;
use App\Http\Controllers\HelferinTraits\ReportsCustomerSalesTrait;
use App\Http\Controllers\HelferinTraits\ReportsCustomerServicesTrait;
use App\Http\Controllers\HelferinTraits\ReportsProductSalesTrait;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Customer;
use App\Models\CustomerInvoice;
use App\Models\CustomerOrder;
use App\Models\CustomerShippingSlip;
use App\Models\Product;
use App\Traits\DateFormFormatterTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
   
   use ReportsProductSalesTrait;
   use ReportsCustomerSalesTrait;
   use ReportsCustomerServicesTrait;
   use ReportsCategorySalesTrait;
   use ReportsABCProductSalesTrait;
   use ReportsABCCustomerSalesTrait;

   use DateFormFormatterTrait;

   protected $models = ['CustomerOrder', 'CustomerShippingSlip', 'CustomerInvoice'];

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
        $models = $this->models;

        $modelList = [];
        foreach ($models as $model) {
            # code...
            $modelList[$model] = l($model);
        }

        $default_model = Configuration::get('RECENT_SALES_CLASS');

        // https://laracasts.com/discuss/channels/laravel/how-to-compile-a-blade-template-from-any-folder-other-than-resourcesviews
        // view()->addNamespace('theme', resource_path('theme'));

/*

// get view factory, eg.: view() / View:: / app('view')

// let's add /app/custom_views via namespace
view()->addNamespace('my_views', app_path('custom_views'));
// then:
view('my_views::some.view.name') // /app/custom_views/some/view/name.blade.php

// OR via path
view()->addLocation(app_path('cutom_views'));
// then:
view('some.view.name') // search in /app/views first, then custom locations

*/
        $selectorMonthList = Tools::selectorMonthList();

        $current = [];
        $current['month'] = Carbon::now()->month;       //  Or: now()->month || Or: (int) Carbon::now()->format('m'); (Carbon extends PHP date format)
        $current['year'] = Carbon::now()->year;

        $selectorNumberYearsList = [0 => 0, 1 => 1, 2 => 2, 3 => 3];

        return view('reports.home', compact('modelList', 'default_model', 'selectorMonthList', 'current', 'selectorNumberYearsList'));
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

            $customers = Customer::where(   'name_fiscal',      'LIKE', '%'.$search.'%' )
                                    ->orWhere( 'name_commercial',      'LIKE', '%'.$search.'%' )
                                    ->orWhere( 'identification', 'LIKE', '%'.$search.'%' )
//                                    ->with('currency')
//                                    ->with('addresses')
                                    ->take( intval(Configuration::get('DEF_ITEMS_PERAJAX')) )
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

        $products = Product::select('id', 'name', 'reference', 'measure_unit_id')
                                ->where(   'name',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference', 'LIKE', '%'.$search.'%' )
//                                ->IsSaleable()
//                                ->qualifyForCustomer( $request->input('customer_id'), $request->input('currency_id') )
//                                ->IsActive()
//                                ->with('measureunit')
//                                ->toSql();
                                ->take( intval(Configuration::get('DEF_ITEMS_PERAJAX')) )
                                ->get();


//                                dd($products);

        return response( $products );
    }


    public function searchCustomerOrder(Request $request)
    {
        $search = $request->term;

        $documents = CustomerOrder::select('id', 'document_reference', 'document_date', 'reference_external')
                                ->where(   'id',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'document_reference', 'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference_external', 'LIKE', '%'.$search.'%' )
                                ->orWhere( 'document_date', 'LIKE', '%'.$search.'%' )
                                ->orderBy('document_date', 'DESC')
                                ->orderBy('id', 'ASC')
//                                ->toSql();
                                ->take( intval(Configuration::get('DEF_ITEMS_PERAJAX')) )
                                ->get();


//                                dd($products);

        return response( $documents );
    }


    public function searchCustomerShippingSlip(Request $request)
    {
        $search = $request->term;

        $documents = CustomerShippingSlip::select('id', 'document_reference', 'document_date', 'reference_external')
                                ->where(   'id',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'document_reference', 'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference_external', 'LIKE', '%'.$search.'%' )
                                ->orWhere( 'document_date', 'LIKE', '%'.$search.'%' )
                                ->orderBy('document_date', 'DESC')
                                ->orderBy('id', 'ASC')
//                                ->toSql();
                                ->take( intval(Configuration::get('DEF_ITEMS_PERAJAX')) )
                                ->get();


//                                dd($products);

        return response( $documents );
    }


    public function searchCustomerInvoice(Request $request)
    {
        $search = $request->term;

        $documents = CustomerInvoice::select('id', 'document_reference', 'document_date', 'reference_external')
                                ->where(   'id',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'document_reference', 'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference_external', 'LIKE', '%'.$search.'%' )
                                ->orWhere( 'document_date', 'LIKE', '%'.$search.'%' )
                                ->orderBy('document_date', 'DESC')
                                ->orderBy('id', 'ASC')
//                                ->toSql();
                                ->take( intval(Configuration::get('DEF_ITEMS_PERAJAX')) )
                                ->get();


//                                dd($products);

        return response( $documents );
    }
}
