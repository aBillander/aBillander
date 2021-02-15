<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Configuration;
use App\Customer;
use App\CustomerInvoice;

use App\Traits\ViewFormatterTrait;
use App\Traits\DateFormFormatterTrait;

class AccountingCustomerInvoicesController extends Controller
{
    use ViewFormatterTrait;
    use DateFormFormatterTrait;
   
   protected $customer;
   protected $document;

   public function __construct(Customer $customer, CustomerInvoice $document)   // , CustomerInvoiceLine $document_line)
   {
//        parent::__construct();

//        $this->model_class = CustomerInvoice::class;

        $this->customer = $customer;
        $this->document = $document;
//        $this->document_line = $document_line;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );

        $invoice_from_id = $request->input('invoice_from_id', 0);
        $invoice_to_id   = $request->input('invoice_to_id'  , 0);

        $items_per_page = $request->input('items_per_page', Configuration::get('DEF_ITEMS_PERPAGE'));
        if ($items_per_page <= 0)
            $items_per_page = Configuration::get('DEF_ITEMS_PERPAGE');

        $documents = $this->document
                            ->filter( $request->all() )
                            ->where(function($query) use ($invoice_from_id)
                            {
                                if ($invoice_from_id > 0)
                                    $query->where( 'id', '>=', $invoice_from_id );
                            })
                            ->where(function($query) use ($invoice_to_id)
                            {
                                if ($invoice_to_id > 0)
                                    $query->where( 'id', '<=', $invoice_to_id );
                            })
                            ->with('customer')
                            ->with('currency')
                            ->with('paymentmethod')
//                            ->orderBy('document_date', 'desc')
                            ->orderBy('document_reference', 'desc');
// https://www.designcise.com/web/tutorial/how-to-order-null-values-first-or-last-in-mysql
//                            ->orderByRaw('document_reference IS NOT NULL, document_reference DESC');
//                          ->orderBy('id', 'desc');        // ->get();

        $documents = $documents->paginate( $items_per_page );

        $documents->setPath('customerinvoices');

        $statusList = CustomerInvoice::getStatusList();

        $payment_statusList = CustomerInvoice::getPaymentStatusList();

        return view('accounting.customer_invoices.index', compact('documents', 'statusList', 'payment_statusList'));
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




    protected function showPdf($id, Request $request)
    {
        // return $id;

        // PDF stuff
        try {
            $document = $this->document
                            ->with('customer')
//                            ->with('invoicingAddress')
//                            ->with('customerInvoiceLines')
//                            ->with('customerInvoiceLines.CustomerInvoiceLineTaxes')
                            ->with('currency')
                            ->with('paymentmethod')
                            ->with('customer.bankaccount')
                            ->with('template')
                            ->findOrFail($id);

        } catch(ModelNotFoundException $e) {

            return redirect()->back()
                     ->with('error', l('The record with id=:id does not exist', ['id' => $id], 'layouts'));
        }

        // abi_r($document->hasManyThrough('App\CustomerInvoiceLineTax', 'App\CustomerInvoiceLine'), true);

        // $company = \App\Company::find( intval(Configuration::get('DEF_COMPANY')) );
        $company = \App\Context::getContext()->company;

        // Template
        $t = $document->template ?? 
             \App\Template::find( Configuration::getInt('DEF_'.strtoupper( $this->getParentModelSnakeCase() ).'_TEMPLATE') );

        if ( !$t )
            return redirect()->back()
                ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts').'Document template not found.');


        $template = $t->getPath( 'customer_invoices' );

        $paper = $t->paper;    // A4, letter
        $orientation = $t->orientation;    // 'portrait' or 'landscape'.
        
        
        // Catch for errors
        try{
                $pdf        = \PDF::loadView( $template, compact('document', 'company') )
                            ->setPaper( $paper, $orientation );
        }
        catch(\Exception $e){

                abi_r($template);
                abi_r($e->getMessage(), true);

                // return redirect()->route('customerorders.show', $id)
                //    ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts').$e->getMessage());
        }

        // PDF stuff ENDS

        $pdfName    = 'customerinvoice'.'_'.$document->secure_key . '_' . $document->document_date->format('Y-m-d');
        
        // Lets try another strategy
        if ( $document->document_reference ) {
            //
            $sanitizer = new \App\FilenameSanitizer( $document->document_reference );

            $sanitizer->stripPhp()
                ->stripRiskyCharacters()
                ->stripIllegalFilesystemCharacters('_');
                
            $pdfName = $sanitizer->getFilename();
        } else {
            //
            $pdfName = 'customerinvoice'.'_'.'ID_' . (string) $document->id;
        }



        if ($request->has('screen')) return view($template, compact('document', 'company'));


        if ( $request->has('preview') ) 
        {
            //
        } else {
            // Dispatch event
//            $event_class = '\\App\\Events\\CustomerInvoicePosted';
//            event( new $event_class( $document ) );

            event( new \App\Events\CustomerInvoicePosted( $document ) );
        }
    

        return  $pdf->stream($pdfName . '.pdf');
        return  $pdf->download( $pdfName . '.pdf');
    }



    /*
    |--------------------------------------------------------------------------
    | Ajax Stuff
    |--------------------------------------------------------------------------
    */

    public function searchInvoice(Request $request)
    {
        $search = $request->term;

        // return response($request->term);

        // $search = 'otur';

        $invoices = CustomerInvoice::select('id', 'document_reference', 'total_tax_incl')
                                ->where(function($query) use ($search)
                                {
                                    $query->where  ( 'id',                 'LIKE', '%'.$search.'%' );
                                    $query->orWhere( 'document_reference', 'LIKE', '%'.$search.'%' );
                                })
//                                ->where('customer_id', $request->input('customer_id'))
//                                ->where('currency_id', $request->input('currency_id'))
//                                ->where('status', 'closed')
//                                ->where('total_tax_incl', '>', 0.0)
//                                ->toSql();
                                ->take( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) )
                                ->get();


//                                dd($products);

        return response( $invoices );
    }

}
