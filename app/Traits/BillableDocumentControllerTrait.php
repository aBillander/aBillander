<?php 

namespace App\Traits;

use Illuminate\Http\Request;

use App\Configuration;

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
            return redirect()->route('customerorders.show', $id)
                ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts').'Document template not found.');


        $template = $t->getPath( $this->getParentModelSnakeCase() );

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

        $pdfName    = str_singular($this->getParentClassLowerCase()).'_'.$document->secure_key . '_' . $document->document_date->format('Y-m-d');

        if ($request->has('screen')) return view($template, compact('document', 'company'));


        if ( $request->has('preview') ) 
        {
            //
        } else {
            // Dispatch event
            event( new \App\Events\CustomerInvoicePrinted( $document ) );
        }
    

        return  $pdf->stream();
        return  $pdf->download( $pdfName . '.pdf');
    }


    public function sendemail( $id, Request $request )
    {
        // $id = $request->input('id');

        // abi_r($id);die();

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

            return redirect()->back()
                     ->with('error', l('The record with id=:id does not exist', ['id' => $id], 'layouts'));
            // return Redirect::to('invoice')->with('message', trans('invoice.access_denied'));
        }

        // $company = \App\Company::find( intval(Configuration::get('DEF_COMPANY')) );
        $company = \App\Context::getContext()->company;

        // Template
        // $seq_id = $this->sequence_id > 0 ? $this->sequence_id : Configuration::get('DEF_'.strtoupper( $this->getClassSnakeCase() ).'_SEQUENCE');
        $t = $document->template ?? 
             \App\Template::find( Configuration::getInt('DEF_'.strtoupper( $this->getParentModelSnakeCase() ).'_TEMPLATE') );

        if ( !$t )
            return redirect()->back()
                ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts').'Document template not found.');


        $template = $t->getPath( $this->getParentModelSnakeCase() ); 

        $paper = $t->paper;    // A4, letter
        $orientation = $t->orientation;    // 'portrait' or 'landscape'.


        // Catch for errors
        try{
                $pdf        = \PDF::loadView( $template, compact('document', 'company') )
//                          ->setPaper( $paper )
//                          ->setOrientation( $orientation );
                            ->setPaper( $paper, $orientation );
        }
        catch(\Exception $e){

//                abi_r($template);
//                abi_r($e->getMessage(), true);

                return redirect()->back()
                    ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts').'<br />'.$e->getMessage());
        }

        // PDF stuff ENDS

        // MAIL stuff
        try {

            $pdfName    = str_singular($this->getParentClassLowerCase()).'_'.$document->secure_key . '_' . $document->document_date->format('Y-m-d');

            $pathToFile     = storage_path() . '/pdf/' . $pdfName .'.pdf';// die($pathToFile);
            $pdf->save($pathToFile);

            if ($request->isMethod('post'))
            {
                // ... this is POST method (call from popup)
                $subject = $request->input('email_subject');
            }
            if ($request->isMethod('get'))
            {
                // ... this is GET method (call from button)
                $subject = l($this->getParentClassLowerCase().'.default.subject :num :date', [ 'num' => $document->number, 'date' => abi_date_short($document->document_date) ], 'emails');
            }

            $template_vars = array(
                'company'       => $company,
                'invoice_num'   => $document->number,
                'invoice_date'  => abi_date_short($document->document_date),
                'invoice_total' => $document->as_money('total_tax_incl'),
                'custom_body'   => $request->input('email_body', ''),
//                'custom_subject' => $request->input('email_subject'),
                );

            $data = array(
                'from'     => $company->address->email,
                'fromName' => $company->name_fiscal,
                'to'       => $document->customer->address->email,
                'toName'   => $document->customer->name_fiscal,
                'subject'  => $subject,
                );

            

            // http://belardesign.com/2013/09/11/how-to-smtp-for-mailing-in-laravel/
            \Mail::send('emails.'.$this->getParentClassLowerCase().'.default', $template_vars, function($message) use ($data, $pathToFile)
            {
                $message->from($data['from'], $data['fromName']);

                $message->to( $data['to'], $data['toName'] )->bcc( $data['from'] )->subject( $data['subject'] );    // Will send blind copy to sender!
                
                $message->attach($pathToFile);

            }); 
            
            unlink($pathToFile);

        } catch(\Exception $e) {

 //               abi_r($e->getMessage(), true);

            return redirect()->back()->with('error', l('Your Document could not be sent &#58&#58 (:id) ', ['id' => $document->number], 'layouts').'<br />'.$e->getMessage());
        }
        // MAIL stuff ENDS


        // Dispatch event
        event( new \App\Events\CustomerInvoiceEmailed($document) );
        

        return redirect()->back()->with('success', l('Your Document has been sent! &#58&#58 (:id) ', ['id' => $document->number], 'layouts'));
    }



/* ********************************************************************************************* */    


}