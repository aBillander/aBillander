<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ProductionSheet;

use App\Customer;
use App\Payment;
use App\PaymentType;

use App\Configuration;

class ProductionSheetVouchersController extends Controller
{
   protected $productionSheet;
   protected $payment;

   public function __construct(ProductionSheet $productionSheet, Payment $payment)
   {
        $this->productionSheet = $productionSheet;

        $this->payment = $payment;
   }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function vouchersIndex($id, Request $request)
    {
        // Maybe future use:
        $items_per_page = intval($request->input('items_per_page', Configuration::getInt('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page >= 0) ) 
            $items_per_page = Configuration::getInt('DEF_ITEMS_PERPAGE');

        $productionSheet = $this->productionSheet->findOrFail($id);

        $payments = $this->payment
                        ->whereHas('customerinvoice', function ($query) use ($id) {
                                $query->where('production_sheet_id', $id);
                            })
//                        ->filter( $request->all() )
                        ->with('paymenttype')
                        ->with('paymentable')
                        ->with('paymentable.customer')
//                        ->with('paymentable.customer.bankaccount')
                        ->where('payment_type', 'receivable')
//                        ->with('chequedetail')
//                        ->with('bankorder')
                        ->orderBy('payment_type_id', 'asc');      // ->get();

        $payments = $payments->paginate( $items_per_page );

        $payments->setPath($id);

        $statusList = Payment::getStatusList();

        $payment_typeList = PaymentType::orderby('name', 'desc')->pluck('name', 'id')->toArray();
/*

        $sdds = SepaDirectDebit::where('status', 'pending')->orWhere('status', 'confirmed')
                                ->orderBy('document_date', 'desc')->orderBy('id', 'desc')
                                ->get();

        // abi_r($sdds->pluck('document_reference', 'id')->toArray()); 

        $sddList = [];
        foreach ($sdds as $sdd) {
            # code...
            $sddList[$sdd->id] = $sdd->document_reference.' :: '.$sdd->status_name.' :: '.abi_date_short($sdd->document_date);
        }

*/


        return view('production_sheet_vouchers.index', compact('productionSheet', 'payments', 'statusList', 'payment_typeList', 'items_per_page'));       // , 'sddList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     *
     * Let' rock >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>.
     *
     */


    /**
     * Close Shipping Slips for a Production Sheet.
     * Prepare data for processCreateShippingSlips()
     *
     */
    public function payVouchers( Request $request )
    {
        // ProductionSheetsController
        $document_group = $request->input('document_group', []);

        if ( count( $document_group ) == 0 ) 
            return redirect()->back()
                ->with('warning', l('No records selected. ', 'layouts').l('No action is taken &#58&#58 (:id) ', ['id' => $request->production_sheet_id], 'layouts'));

        // abi_r($request->all(), true);

        // Set params for group
        $params = $request->only('production_sheet_id');

        // abi_r($params, true);

        return $this->processCloseInvoices( $document_group, $params );
    }

    
    /**
     * Close Shipping Slips after a list of Customer Shipping (id's).
     * Hard work is done here
     *
     */
    public function processCloseInvoices( $list, $params )
    {

//        1.- Recuperar los documntos
//        2.- Comprobar que están todos los de la lista ( comparando count() )

        try {

            $productionSheet = $this->productionSheet
                                ->findOrFail($params['production_sheet_id']);

            $documents = $this->document
                                ->where('production_sheet_id', $params['production_sheet_id'])
                                ->where('status', '<>', 'closed')
                                ->with('lines')
    //                            ->with('lines.linetaxes')
    //                            ->with('customer')
    //                            ->with('currency')
    //                            ->with('paymentmethod')
    //                            ->orderBy('document_date', 'asc')
    //                            ->orderBy('id', 'asc')
                                ->find( $list );
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return redirect()->back()
                    ->with('error', l('Some records in the list [ :id ] do not exist', ['id' => implode(', ', $list)], 'layouts'));
            
        }


//        3.- Close Documents

        $fails = [];

        foreach ($documents as $document)
        {
            # code...
            if ( !$document->close() )
                $fails[] = l('Unable to close this document &#58&#58 (:id) ', ['id' => $document->document_reference], 'layouts');
        }

        if (count($fails) > 0) {
            $result  = 'error';
            $message = $fails;
        } else {
            $result  = 'success';
            $message = l('These records have been successfully updated &#58&#58 (:id) ', ['id' => $params['production_sheet_id']], 'layouts');
        }

        // die();

        return redirect()->back()
//                ->route('productionsheet.orders', $params['production_sheet_id'])
                ->with($result, $message);

    }
}
