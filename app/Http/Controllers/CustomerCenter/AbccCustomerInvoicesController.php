<?php

namespace App\Http\Controllers\CustomerCenter;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Customer as Customer;
use App\CustomerInvoice as CustomerInvoice;
use App\CustomerInvoiceLine as CustomerInvoiceLine;

use App\Events\CustomerInvoiceViewed as CustomerInvoiceViewed;

use App\Configuration as Configuration;

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
        $this->middleware('auth:customer')->except('pdf');

        $this->customer            = $customer;
        $this->customerInvoice     = $customerInvoice;
        $this->customerInvoiceLine = $customerInvoiceLine;
    }

    public function index()
    {
        $customer_invoices = CustomerInvoice::ofCustomer()      // Of Logged in Customer (see scope on Customer Model)
//                            ->with('customer')
                            ->with('currency')
                            ->with('paymentmethod')
                            ->orderBy('id', 'desc')->get();

        return view('customer_center.invoices.index', compact('customer_invoices'));
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
                            ->where('secure_key', $cinvoiceKey)
                            ->with('customer')
                            ->with('invoicingAddress')
                            ->with('customerInvoiceLines')
                            ->with('currency')
                            ->firstOrFail();

        // $company = \App\Context::getContext()->company;
        $company = \App\Company::with('currency')->findOrFail( intval(Configuration::get('DEF_COMPANY')) );

        return view('customer_center.invoices.show', compact('cinvoice', 'company'));
    }

    public function pdf($cinvoiceKey, Request $request)
    {
        // $invoice = $this->invoiceRepository->findByUrlKey($cinvoiceKey);

        // Set up a temporay Context
        // $company = \App\Company::find( intval(Configuration::get('DEF_COMPANY')) );
        \App\Context::getContext()->company = \App\Company::find( intval(Configuration::get('DEF_COMPANY')) );
        $company = \App\Context::getContext()->company;
        \App\Context::getContext()->controller = 'customerinvoices';

        // PDF stuff
        try {
            $cinvoice = $this->customerInvoice
                            ->where('secure_key', $cinvoiceKey)
                            ->with('customer')
                            ->with('invoicingAddress')
                            ->with('customerInvoiceLines')
                            ->with('customerInvoiceLines.CustomerInvoiceLineTaxes')
                            ->with('currency')
                            ->with('paymentmethod')
                            ->with('template')
                            ->firstOrFail();

        } catch( \Exception $e ) {

            return l('Customer Invoice id=:id does not exist.', ['id' => $cinvoiceKey] );
        }

        event(new CustomerInvoiceViewed($cinvoice, 'customer_viewed_at'));

        $template = 'customer_invoices.templates.' . $cinvoice->template->file_name;  // . '_dist';
        $paper = $cinvoice->template->paper;    // A4, letter
        $orientation = $cinvoice->template->orientation;    // 'portrait' or 'landscape'.
        
        $pdf        = \PDF::loadView( $template, compact('cinvoice', 'company') )
                            ->setPaper( $paper, $orientation );
//      $pdf = \PDF::loadView('customer_invoices.templates.test', $data)->setPaper('a4', 'landscape');

        // PDF stuff ENDS

        $pdfName    = 'invoice_' . $cinvoice->secure_key . '_' . $cinvoice->document_date;

        if ($request->has('screen')) return view($template, compact('cinvoice', 'company'));
        
        return  $pdf->stream();
        return  $pdf->download( $pdfName . '.pdf');
    }

    public function html($cinvoiceKey)
    {
        $invoice = $this->invoiceRepository->findByUrlKey($cinvoiceKey);

        return $invoice->html;
    }
}
