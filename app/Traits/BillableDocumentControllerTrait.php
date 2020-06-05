<?php 

namespace App\Traits;

use Illuminate\Http\Request;

// use App\PDFMerger;
use App\Configuration;

trait BillableDocumentControllerTrait
{


    protected function showBulkPdf(Request $request)
    {
        //
        // Get Document IDs & constraints
        $document_list = $request->input('document_group', []);

        if ( count( $document_list ) == 0 ) 
            return redirect()->back()
                ->with('warning', l('No records selected. ', 'layouts').l('No action is taken &#58&#58 (:id) ', ['id' => ''], 'layouts'));
        
        // Dates (cuen)
        $this->mergeFormDates( ['document_date'], $request );
        $request->merge( ['invoice_date' => $request->input('document_date')] );   // According to $rules_createinvoice

        // $rules = $this->document::$rules_createinvoice;

        // $this->validate($request, $rules);


        //
        // Get Documents
        try {

            $documents = $this->document
                            ->with('customer')
//                            ->with('invoicingAddress')
//                            ->with('customerInvoiceLines')
//                            ->with('customerInvoiceLines.CustomerInvoiceLineTaxes')
                            ->with('currency')
                            ->with('paymentmethod')
                            ->with('customer.bankaccount')
                            ->with('template')
                                ->findOrFail( $document_list );
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return redirect()->back()
                    ->with('error', l('Some records in the list [ :id ] do not exist', ['id' => implode(', ', $document_list)], 'layouts'));
            
        }

        // return $request->toArray();

        //
        // Do some house-keeping
        $storage_folder = $this->getParentClass().'/';
        $currents =  \Storage::files($storage_folder);
        // Empty folder
        \Storage::delete( $currents );


        //
        // Loop through Documents

        // $company = \App\Company::find( intval(Configuration::get('DEF_COMPANY')) );
        $company = \App\Context::getContext()->company;
        $names = [];

        foreach ($documents as $document) {
            # code...

                // Template
                $t = $document->template ?? 
                    \App\Template::find( $document->customer->getInvoiceTemplateId() );
                    // \App\Template::find( Configuration::getInt('DEF_'.strtoupper( $this->getParentModelSnakeCase() ).'_TEMPLATE') );

                if ( !$t )
                    return redirect()->back()
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

                $pdfName    = str_singular($this->getParentClassLowerCase()).'_'.$document->secure_key . '_' . $document->document_date->format('Y-m-d').'.pdf';

                // if ($request->has('screen')) return view($template, compact('document', 'company'));

                $file_content = $pdf->output();
                \Storage::put($storage_folder.$pdfName, $file_content);

                $names[] = $pdfName;


                if ( $request->has('preview') || 1 ) 
                {
                    //
                } else {
                    // Dispatch event
                    // $event_class = '\\App\\Events\\'.str_singular($this->getParentClass()).'Printed';
                    // event( new $event_class( $document ) );
                }
        }


        //
        // It is time to merge Documents
        // include '../../Helpers/PDFMerger.php';

        $documents_path = storage_path().'/app/'.$storage_folder;
        $merged_pdf = new \PDFMerger;

        foreach ($names as $name) {
            # code...
            // $content = \Storage::get($storage_folder.$name);

            $merged_pdf->addPDF($documents_path.$name, 'all');

        }

        // Ta-chan!!
        $merged_pdf->merge('browser', 'samplepdfs/TEST2.pdf'); //REPLACE 'file' (first argument) WITH 'browser', 'download', 'string', or 'file' for output options. You do not need to give a file path for browser, string, or download - just the name.
    
die();
        return  $pdf->stream();
        return  $pdf->download( $pdfName );
    }



/* ********************************************************************************************* */    


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
            $pdfName = str_singular($this->getParentClassLowerCase()).'_'.'ID_' . (string) $document->id;
        }


        if ($request->has('screen')) return view($template, compact('document', 'company'));


        if ( $request->has('preview') ) 
        {
            //
        } else {
            // Dispatch event
            $event_class = '\\App\\Events\\'.str_singular($this->getParentClass()).'Printed';
            event( new $event_class( $document ) );
        }
    

        return  $pdf->stream($pdfName . '.pdf');
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

        $document->close();

        if ( $document->status != 'closed' )
            return redirect()->back()
                ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts').'Document is NOT closed.');



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
        $event_class = '\\App\\Events\\'.str_singular($this->getParentClass()).'Emailed';
        event( new $event_class( $document ) );
        

        return redirect()->back()->with('success', l('Your Document has been sent! &#58&#58 (:id) ', ['id' => $document->number], 'layouts'));
    }



/* ********************************************************************************************* */    


}