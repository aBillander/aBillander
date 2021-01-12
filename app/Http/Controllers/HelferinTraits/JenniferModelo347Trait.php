<?php 

namespace App\Http\Controllers\HelferinTraits;

use Illuminate\Http\Request;

use App\Configuration;

use App\Product;
use App\Customer;

use App\Modelo347;

use App\Tools;

use Carbon\Carbon;

use Excel;

trait JenniferModelo347Trait
{

    /**
     * Redirect to Model 347 dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index347(Request $request)
    {        
        $mod347_year = $request->input('mod347_year') > 0 ? $request->input('mod347_year') : Carbon::now()->year;

        return redirect()->route('jennifer.reports.index347.show', $mod347_year);
    }
        

    /**
     * Show Model 347 dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index347Show($mod347_year, Request $request)
    {        
        // $mod347_year = $request->input('mod347_year') > 0 ? $request->input('mod347_year') : Carbon::now()->year;
        
        // Wanna dance, Honey Bunny?
        $mod347 = new Modelo347( $mod347_year );

        // All Suppliers. Lets see:
        $suppliers = $mod347->getSuppliers();

        // All Customers. Lets see:
        $customers = $mod347->getCustomers();

        // Nice! Lets move on and retrieve Documents
        foreach ($customers as $customer) {

            $customer->yearly_sales = $mod347->getCustomerYearlySales($customer->id);

        }

        return view('jennifer.347.index', compact('mod347_year', 'customers', 'suppliers'));
    }


    /**
     * ABC Customer Sales Report (ABC Analysis).
     *
     * @return Spread Sheet download
     */
    public function reportModelo347(Request $request)
    {
        $mod347_year = $request->input('mod347_year') > 0 ? $request->input('mod347_year') : Carbon::now()->year;

        // abi_r((new Modelo347(2020, 1000))->getCustomers()->count());


        // Wanna dance, Honey Bunny?
        $mod347 = new Modelo347( $mod347_year );

        // All Suppliers. Lets see:
        $suppliers = $mod347->getSuppliers();

        // All Customers. Lets see:
        $customers = $mod347->getCustomers();


// Nice! Lets move on and retrieve Documents
foreach ($customers as $customer) {
        # code...
    $customer_id = $customer->id;

    // $customer->quarterly_sales = [];

    for ($quarter=1; $quarter <= 4 ; $quarter++) { 
        # code...
        // customer->quarterly_sales[$quarter] = $mod347->getCustomerQuarterlySales($customer_id, $quarter);
        $customer->{"Q$quarter"} = $mod347->getCustomerQuarterlySales($customer_id, $quarter);

        // abi_r($customer->{"Q$quarter"});
    }

    $customer->yearly_sales = $mod347->getCustomerYearlySales($customer_id);

    
    // abi_r($customer->yearly_sales);die();

}
// die();
// abi_r($customers, true);
        // Lets get dirty!!
        // See: https://laraveldaily.com/laravel-excel-export-formatting-and-styling-cells/


        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        // Sheet Header Report Data
        $data[] = [\App\Context::getContext()->company->name_fiscal];
        $data[] = ['Comprobación Acumulados 347', '', '', '', date('d M Y H:i:s')];
        $data[] = ['Ejercicio: '. $mod347_year];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['Clave', 'N.I.F.', 'Nombre', 'Cód. Postal', 'Municipio', 'Importe', 'Oper. Seguro', 'Arrendamiento',
                         'Trimeste 1', 'Trimeste 2', 'Trimeste 3', 'Trimeste 4', 
            ];

        $data[] = $header_names;

        // Convert each member of the returned collection into an array,
        // and append it to the data array.

        // Initialize (colmn) totals
        $total_A = 0.0;
        $total_B = $customers->sum('yearly_sales');
        $total =  $total_A + $total_B;

        foreach ($customers as $customer) 
        {
                $row = [];
                $row[] = 'B';
                $row[] = (string) $customer->identification;
                $row[] = (string) $customer->name_fiscal;
                $row[] = (string) $customer->address->postcode;
                $row[] = (string) $customer->address->city;
                $row[] = (float) $customer->yearly_sales;
                $row[] = '';
                $row[] = '';

                for ($quarter=1; $quarter <= 4 ; $quarter++) {
                    $row[] = (float) $customer->{"Q$quarter"};
                }

                $data[] = $row;

        }

        // Total
        $row = [];
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = 'Total Clave:';
        $row[] = (float) $total_B;
        
        $data[] = $row;
/*
        // Totals
        $data[] = [''];

        $row = [];
        $row[] = '';
        $row[] = '';
        $row[] = 'Total:';
        foreach ($list_of_years as $year) {
            $row[] = (float) $totals[$year];
        }
        $data[] = $row;
*/
        // abi_r($data, true);

//        $i = count($data);

        $sheetName = 'Acumulados 347';

        $nbr_years = 0;

        // Generate and return the spreadsheet
        Excel::create('Acumulados 347 - '.$mod347_year, function($excel) use ($sheetName, $data, $nbr_years) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data, $nbr_years) {
                
                $sheet->mergeCells('A1:C1');
                $sheet->mergeCells('A2:C2');
                $sheet->mergeCells('A3:C3');

                // $w = count($data[5+1]);

                $sheet->getStyle('A5:L5')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->setColumnFormat(array(
//                    'B' => 'dd/mm/yyyy',
//                    'C' => 'dd/mm/yyyy',
                    'A' => '@',
                    'F' => '0.00',
                    'I' => '0.00',
                    'J' => '0.00',
                    'K' => '0.00',
                    'L' => '0.00',

                ));
                
                $n = count($data);
                $m = $n;    //  - 3;
                $sheet->getStyle("E$m:F$n")->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');
    }




/* ********************************************************************************************* */    


    public function reportModelo347Customer( $mod347_year, $customer_id, Request $request )
    {
        $mod347 = new Modelo347( $mod347_year );

        // Attached file stuff
        $data = $mod347->getCustomerInvoicesAttachment($customer_id, true);
    }


/*
    public function sendemailWithInterface( $id, Request $request )
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
             \App\Template::find( Configuration::getInt('DEF_'.strtoupper( 'WarehouseShippingSlip' ).'_TEMPLATE') );

        if ( !$t )
            return redirect()->back()
                ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts').'Document template not found.');


        $template = $t->getPath( 'WarehouseShippingSlip' ); 

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

            $pdfName    = str_singular('warehouseshippingslips').'_'.$document->secure_key . '_' . $document->document_date->format('Y-m-d');

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
                $subject = l('warehouseshippingslips'.'.default.subject :num :date', [ 'num' => $document->number, 'date' => abi_date_short($document->document_date) ], 'emails');
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
            \Mail::send('emails.'.'warehouseshippingslips'.'.default', $template_vars, function($message) use ($data, $pathToFile)
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
*/


    public function sendemail( $mod347_year, $customer_id, Request $request )
    {
        // Wanna dance, Honey Bunny?
        $customer = Customer::findOrFail($customer_id);
        $mod347 = new Modelo347( $mod347_year );

        // Terms
        $terms = [];
        for ($i=1; $i <=4 ; $i++) { 
            # code..;
            $terms[$i] = $mod347->getCustomerQuarterlySales($customer_id, $i);
        }

        // Attached file stuff
        $attachment_data = $mod347->getCustomerInvoicesAttachment($customer_id);

        // MAIL stuff
        try {

            $pathToFile     = $attachment_data['full'];

            $subject = l('Informacion 347 de :year', [ 'year' => $mod347_year ]);

            $company = \App\Context::getContext()->company;

            $template_vars = array(
                'customer'   => $customer,
                'terms'  => $terms,
                'total'  => array_sum($terms),
                );

            // return view('jennifer.347.mail', $template_vars);

            $data = array(
                'from'     => $company->address->email,
                'fromName' => $company->name_fiscal,
                'to'       => $customer->address->email,
                'toName'   => $customer->name_fiscal,
                'subject'  => $subject,
                );

            

            // http://belardesign.com/2013/09/11/how-to-smtp-for-mailing-in-laravel/
            \Mail::send('jennifer.347.mail', $template_vars, function($message) use ($data, $pathToFile)
            {
                $message->from($data['from'], $data['fromName']);

                $message->to( $data['to'], $data['toName'] )->bcc( $data['from'] )->subject( $data['subject'] );    // Will send blind copy to sender!
                
                $message->attach($pathToFile);

            }); 
            
            unlink($pathToFile);

        } catch(\Exception $e) {

 //               abi_r($e->getMessage(), true);

            return redirect()->back()->with('error', l('Your Document could not be sent &#58&#58 (:id) ', ['id' => ''], 'layouts').'<br />'.$e->getMessage());
        }
        // MAIL stuff ENDS


        // Dispatch event
        // $event_class = '\\App\\Events\\'.str_singular($this->getParentClass()).'Emailed';
        // event( new $event_class( $document ) );
        

        return redirect()->back()->with('success', l('Your Document has been sent! &#58&#58 (:id) ', ['id' => ''], 'layouts'));
    }



/* ********************************************************************************************* */    


}