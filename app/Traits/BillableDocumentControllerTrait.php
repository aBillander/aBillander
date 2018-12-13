<?php 

namespace App\Traits;

use Illuminate\Http\Request;

trait BillableDocumentControllerTrait
{


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
                            ->with('template')
                            ->findOrFail($id);

        } catch(ModelNotFoundException $e) {

            return redirect('customerorders')
                     ->with('error', l('The record with id=:id does not exist', ['id' => $id], 'layouts'));
        }

        // abi_r($document->hasManyThrough('App\CustomerInvoiceLineTax', 'App\CustomerInvoiceLine'), true);

        // $company = \App\Company::find( intval(Configuration::get('DEF_COMPANY')) );
        $company = \App\Context::getContext()->company;

        // Template impersonation
        $template = \App\Template::create(['file_name'=>'shipping_slip', 'paper'=>'A4', 'orientation'=>'portrait']);
        $document->template = $template;

        $template->delete();

        $template = 'customer_orders.templates.' . $document->template->file_name;  // . '_dist';
        $paper = $document->template->paper;    // A4, letter
        $orientation = $document->template->orientation;    // 'portrait' or 'landscape'.
        
        $pdf        = \PDF::loadView( $template, compact('document', 'company') )
                            ->setPaper( $paper, $orientation );
//      $pdf = \PDF::loadView('customer_invoices.templates.test', $data)->setPaper('a4', 'landscape');

        // PDF stuff ENDS

        $pdfName    = 'invoice_' . $document->secure_key . '_' . $document->document_date->format('Y-m-d');

        if ($request->has('screen')) return view($template, compact('document', 'company'));
        
        return  $pdf->stream();
        return  $pdf->download( $pdfName . '.pdf');
    }


    public function sendemail( Request $request )
    {
        $id = $request->input('invoice_id');

        // PDF stuff
        try {
            $document = $this->document
                            ->with('customer')
                            ->with('invoicingAddress')
                            ->with('customerInvoiceLines')
                            ->with('customerInvoiceLines.CustomerInvoiceLineTaxes')
                            ->with('currency')
                            ->with('paymentmethod')
                            ->with('template')
                            ->findOrFail($id);

        } catch(ModelNotFoundException $e) {

            return redirect('customers.index')
                     ->with('error', 'La Factura de Cliente id='.$id.' no existe.');
            // return Redirect::to('invoice')->with('message', trans('invoice.access_denied'));
        }

        // $company = \App\Company::find( intval(Configuration::get('DEF_COMPANY')) );
        $company = \App\Context::getContext()->company;

        $template = 'customer_invoices.templates.' . $document->template->file_name;
        $paper = $document->template->paper;    // A4, letter
        $orientation = $document->template->orientation;    // 'portrait' or 'landscape'.
        
        $pdf        = \PDF::loadView( $template, compact('document', 'company') )
//                          ->setPaper( $paper )
//                          ->setOrientation( $orientation );
                            ->setPaper( $paper, $orientation );
        // PDF stuff ENDS

        // MAIL stuff
        try {

            $pdfName    = 'invoice_' . $document->secure_key . '_' . $document->document_date->format('Y-m-d');

            $pathToFile     = storage_path() . '/pdf/' . $pdfName .'.pdf';
            $pdf->save($pathToFile);

            $template_vars = array(
                'company'       => $company,
                'invoice_num'   => $document->number,
                'invoice_date'  => abi_date_short($document->document_date),
                'invoice_total' => $document->as_money('total_tax_incl'),
                'custom_body'   => $request->input('email_body'),
                );

            $data = array(
                'from'     => $company->address->email,
                'fromName' => $company->name_fiscal,
                'to'       => $document->customer->address->email,
                'toName'   => $document->customer->name_fiscal,
                'subject'  => $request->input('email_subject'),
                );

            

            // http://belardesign.com/2013/09/11/how-to-smtp-for-mailing-in-laravel/
            \Mail::send('emails.customerinvoice.default', $template_vars, function($message) use ($data, $pathToFile)
            {
                $message->from($data['from'], $data['fromName']);

                $message->to( $data['to'], $data['toName'] )->bcc( $data['from'] )->subject( $data['subject'] );    // Will send blind copy to sender!
                
                $message->attach($pathToFile);

            }); 
            
            unlink($pathToFile);

        } catch(\Exception $e) {

            return redirect()->back()->with('error', 'La Factura '.$document->number.' no se pudo enviar al Cliente');
        }
        // MAIL stuff ENDS
        

        return redirect()->back()->with('success', 'La Factura '.$document->number.' se envió correctamente al Cliente');
    }



/* ********************************************************************************************* */    



    
    public function showPdfInvoice($id, Request $request)
    {

        // die($id);

        try {
            $document = $this->document
                            ->with('customer')
//                            ->with('invoicingAddress')
//                            ->with('customerInvoiceLines')
//                            ->with('customerInvoiceLines.CustomerInvoiceLineTaxes')
                            ->with('currency')
                            ->with('paymentmethod')
                            ->with('template')
                            ->findOrFail($id);

        } catch(ModelNotFoundException $e) {

            return redirect('customerorders')
                     ->with('error', l('The record with id=:id does not exist', ['id' => $id], 'layouts'));
        }
        

        $company = \App\Context::getContext()->company;

        // Get Template
        $t = $document->template ?? 
             \App\Template::find( Configuration::getInt('DEF_CUSTOMER_INVOICE_TEMPLATE') );

        if ( !$t )
            return redirect()->route('customerorders.show', $id)
                ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts').'Document template not found.');

        // $document->template = $t;

        $template = $t->getPath( 'CustomerInvoice' );

        $paper = $t->paper;    // A4, letter
        $orientation = $t->orientation;    // 'portrait' or 'landscape'.
        
        // Catch for errors
        try{
                $pdf        = \PDF::loadView( $template, compact('document', 'company') )
                            ->setPaper( $paper, $orientation );
        }
        catch(\Exception $e){

                abi_r($e->getMessage(), true);

                // return redirect()->route('customerorders.show', $id)
                //    ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts').$e->getMessage());
        }

        // PDF stuff ENDS

        $pdfName    = 'invoice_' . $document->secure_key . '_' . $document->document_date->format('Y-m-d');

        if ($request->has('screen')) return view($template, compact('document', 'company'));
        
        return  $pdf->stream();
        return  $pdf->download( $pdfName . '.pdf');


        return redirect()->route('abcc.orders.index')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
    }


}