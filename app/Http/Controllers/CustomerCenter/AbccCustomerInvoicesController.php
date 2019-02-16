<?php

namespace App\Http\Controllers\CustomerCenter;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\CustomerUser;
use App\Customer;
use App\CustomerInvoice;
use App\CustomerInvoiceLine;

use App\Events\CustomerInvoiceViewed;

use App\Configuration;
use App\Currency;

class AbccCustomerInvoicesController extends Controller
{
    protected $customer, $customerInvoice, $customerInvoiceLine;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, CustomerInvoice $customerInvoice, CustomerInvoiceLine $customerInvoiceLine)
    {
        // $this->middleware('auth:customer')->except('pdf');

        $this->customer            = $customer;
        $this->customerInvoice     = $customerInvoice;
        $this->customerInvoiceLine = $customerInvoiceLine;
    }

    public function index()
    {
        $customer_invoices = $this->customerInvoice->ofCustomer()      // Of Logged in Customer (see scope on Billable Trait)
//                            ->with('customer')
                            ->with('currency')
                            ->with('paymentmethod')
                            ->orderBy('id', 'desc');

        $customer_invoices = $customer_invoices->paginate( \App\Configuration::get('ABCC_ITEMS_PERPAGE') );

        $customer_invoices->setPath('invoices');

        return view('abcc.invoices.index', compact('customer_invoices'));
    }

    public function show($cinvoiceKey)
    {
/*        // Temporary
        return $this->pdf($cinvoiceKey, $request);

        $invoice = $this->invoiceRepository->findByUrlKey($cinvoiceKey);

        app()->setLocale($invoice->client->language);

        event(new InvoiceViewed($invoice));

        return view('client_center.invoices.public')
            ->with('invoice', $invoice)
            ->with('statuses', InvoiceStatuses::statuses())
            ->with('urlKey', $cinvoiceKey)
            ->with('merchants', MerchantProperties::setProperties(config('fi.merchant')))
            ->with('attachments', $invoice->clientAttachments);
*/

        $cinvoice = $this->customerInvoice
                            ->findByToken($invoiceKey)
                            ->with('customer')
                            ->with('invoicingAddress')
                            ->with('customerInvoiceLines')
                            ->with('currency')
                            ->firstOrFail();

        // $company = \App\Context::getContext()->company;
        $company = \App\Company::with('currency')->findOrFail( intval(Configuration::get('DEF_COMPANY')) );

        return view('abcc.invoices.show', compact('cinvoice', 'company'));
    }

    public function showPdf($cinvoiceKey, Request $request)
    {

        $customer      = Auth::user()->customer;

        $document = $this->customerInvoice
                            ->findByToken($cinvoiceKey)
                            ->where('customer_id', $customer->id)
                            ->with('currency')
                            ->with('paymentmethod')
                            ->with('template')
                            ->first();

        if (!$document) 
            return redirect()->route('abcc.invoices.index')
                    ->with('error', l('The record with id=:id does not exist', ['id' => $cinvoiceKey], 'layouts'));
        

        $company = \App\Context::getContext()->company;
/*
        event(new CustomerInvoiceViewed($cinvoice, 'customer_viewed_at'));
*/

        // Get Template
        $t = $document->template ?? 
             \App\Template::find( Configuration::getInt('DEF_CUSTOMER_INVOICE_TEMPLATE') );

        if ( !$t )
            return redirect()->route('abcc.invoices.index')
                ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->cinvoiceKey], 'layouts'));

        // $document->template = $t;

        $template = $t->getPath( 'CustomerInvoice' );


//        $template = 'customer_invoices.templates.' . $cinvoice->template->file_name;  // . '_dist';
        $paper = $t->paper;    // A4, letter
        $orientation = $t->orientation;    // 'portrait' or 'landscape'.
        
        // Catch for errors
        try{
                $pdf        = \PDF::loadView( $template, compact('document', 'company') )
                            ->setPaper( $paper, $orientation );
        }
        catch(\Exception $e){

                return redirect()->route('abcc.invoices.index')
                    ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->cinvoiceKey], 'layouts').$e->getMessage());
        }

        // PDF stuff ENDS

        $pdfName    = 'invoice_' . $document->secure_key . '_' . $document->document_date->format('Y-m-d');

        if ($request->has('screen')) return view($template, compact('document', 'company'));
        
        return  $pdf->stream();
        return  $pdf->download( $pdfName . '.pdf');
    }

    public function html($cinvoiceKey)
    {
        $invoice = $this->invoiceRepository->findByUrlKey($cinvoiceKey);

        return $invoice->html;
    }




    /*
    |--------------------------------------------------------------------------
    | Not CRUD stuff here
    |--------------------------------------------------------------------------
    */


/* ********************************************************************************************* */    


    /**
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function shippingslips($invoiceKey, Request $request)
    {
        

        $document = $this->customerInvoice
                            ->findByToken($invoiceKey)
//                            ->withCount('lines')
//                            ->with('customer')
//                            ->with('invoicingAddress')
//                            ->with('customerInvoiceLines')
//                            ->with('payments')
                            ->with('currency')
                            ->firstOrFail();

        return view('abcc.invoices._panel_shippingslips', compact('document') );
    }

    public function vouchers($invoiceKey, Request $request)
    {
        

        $document = $this->customerInvoice
                            ->findByToken($invoiceKey)
//                            ->with('customer')
//                            ->with('invoicingAddress')
//                            ->with('customerInvoiceLines')
                            ->with('payments')
                            ->with('currency')
                            ->firstOrFail();

        return view('abcc.invoices._panel_vouchers', compact('document') );
    }
}
