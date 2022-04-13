<?php

namespace App\Http\Controllers;

use App\Http\Controllers\HelferinTraits\JenniferBankOrdersTrait;
use App\Http\Controllers\HelferinTraits\JenniferCustomerInvoicesA3Trait;
use App\Http\Controllers\HelferinTraits\JenniferCustomersBalanceTrait;
use App\Http\Controllers\HelferinTraits\JenniferInventoryTrait;
use App\Http\Controllers\HelferinTraits\JenniferInvoicesTrait;
use App\Http\Controllers\HelferinTraits\JenniferModelo347Trait;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Customer;
use App\Models\CustomerInvoice;
use App\Models\CustomerOrder;
use App\Models\CustomerShippingSlip;
use App\Models\Product;
use App\Models\Tax;
use App\Models\TaxRule;
use App\Traits\DateFormFormatterTrait;
use Excel;
use Illuminate\Http\Request;

class JenniferController extends Controller
{
    use JenniferInvoicesTrait;
    use JenniferBankOrdersTrait;
    use JenniferInventoryTrait;
    use JenniferCustomersBalanceTrait;
    use JenniferModelo347Trait;
    use JenniferCustomerInvoicesA3Trait;
   
   use DateFormFormatterTrait;

   private $invoices_report_formatList;
   private $valuation_methodList;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web');

        $this->invoices_report_formatList = [
                    'compact' => 'Compacto',
                    'loose' => 'Amplio',
                    'a3' => 'A3 Contabilidad',
        ];

        $this->mod347_claveList = [
                    'A' => 'Proveedores',
                    'B' => 'Clientes',
        ];

        $this->valuation_methodList = [
                    'cost_average_on_date' => 'Precio Medio en la Fecha',
                    'cost_average_current' => 'Precio Medio Actual',
                    'cost_price_on_date' => 'Precio de Coste en la Fecha',
                    'cost_price_current' => 'Precio de Coste Actual',
        ];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $valuation_methodList = $this->valuation_methodList;

        $invoices_report_formatList = $this->invoices_report_formatList;

        $mod347_claveList = $this->mod347_claveList;

        return view('jennifer.home', compact('valuation_methodList', 'invoices_report_formatList', 'mod347_claveList'));
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
