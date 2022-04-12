<?php 

namespace App\Http\Controllers\HelferinTraits;

use App\Helpers\Exports\ArrayExport;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Customer;
use App\Models\Product;
use App\Helpers\Modelo347;
use App\Helpers\Tools;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

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

        $mod347_clave = strtoupper($request->input('mod347_clave')) == 'A' ? 'A' : 'B';

        return redirect()->route('jennifer.reports.index347.show', [$mod347_year, 'mod347_clave' => $mod347_clave]);
    }
        

    /**
     * Show Model 347 dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index347Show($mod347_year, Request $request)
    {        
        // $mod347_year = $request->input('mod347_year') > 0 ? $request->input('mod347_year') : Carbon::now()->year;

        $mod347_clave = strtoupper($request->input('mod347_clave')) == 'A' ? 'A' : 'B';
        
        // Wanna dance, Honey Bunny?
        $mod347 = new Modelo347( $mod347_year );

        // All Suppliers. Lets see:
        $suppliers = $mod347->getSuppliers();

        // All Customers. Lets see:
        $customers = $mod347->getCustomers();

        // Nice! Lets move on and retrieve Documents
        foreach ($suppliers as $supplier) {

            $supplier->yearly_sales = $mod347->getSupplierYearlySales($supplier->id);

        }

        foreach ($customers as $customer) {

            $customer->yearly_sales = $mod347->getCustomerYearlySales($customer->id);

        }

        return view('jennifer.347.'.$mod347_clave.'_index', compact('mod347_year', 'customers', 'suppliers'));
    }


    /**
     * ABC Customer Sales Report (ABC Analysis).
     *
     * @return Spread Sheet download
     */
    public function reportModelo347($mod347_year = 0, Request $request)
    {
        $mod347_year = $mod347_year > 0 ? $mod347_year : Carbon::now()->year;

        // abi_r((new Modelo347(2020, 1000))->getCustomers()->count());


        // Wanna dance, Honey Bunny?
        $mod347 = new Modelo347( $mod347_year );

        // All Suppliers. Lets see:
        $suppliers = $mod347->getSuppliers();

        // All Customers. Lets see:
        $customers = $mod347->getCustomers();


        // Nice! Lets move on and retrieve Documents

// Suppliers
foreach ($suppliers as $supplier) {
        # code...
    $supplier_id = $supplier->id;

    // $supplier->quarterly_sales = [];

    for ($quarter=1; $quarter <= 4 ; $quarter++) { 
        # code...
        // supplier->quarterly_sales[$quarter] = $mod347->getSupplierQuarterlySales($supplier_id, $quarter);
        $supplier->{"Q$quarter"} = $mod347->getSupplierQuarterlySales($supplier_id, $quarter);

        // abi_r($supplier->{"Q$quarter"});
    }

    $supplier->yearly_sales = $mod347->getSupplierYearlySales($supplier_id);

    
    // abi_r($supplier->yearly_sales);die();

}

// Customers
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
        $data[] = [Context::getContext()->company->name_fiscal];
        $data[] = ['Comprobación Acumulados 347', '', '', '', date('d M Y H:i:s')];
        $data[] = ['Ejercicio: '. $mod347_year];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['Clave', 'N.I.F.', 'Nombre', 'Cód. Postal', 'Municipio', 'Importe', 'Oper. Seguro', 'Arrendamiento',
                         'Trimeste 1', 'Trimeste 2', 'Trimeste 3', 'Trimeste 4', 
            ];

        // Initialize (colmn) totals
        $total_A = $suppliers->sum('yearly_sales');
        $total_B = $customers->sum('yearly_sales');
        $total =  $total_A + $total_B;
        $suppliers_count = $suppliers->count();

        // Suppliers
        $data[] = $header_names;

        foreach ($suppliers as $supplier) 
        {
                $row = [];
                $row[] = 'A';
                $row[] = (string) $supplier->identification;
                $row[] = (string) $supplier->name_fiscal;
                $row[] = (string) $supplier->address->postcode;
                $row[] = (string) $supplier->address->city;
                $row[] = (float) $supplier->yearly_sales;
                $row[] = '';
                $row[] = '';

                for ($quarter=1; $quarter <= 4 ; $quarter++) {
                    $row[] = (float) $supplier->{"Q$quarter"};
                }

                $data[] = $row;

        }

        // Total
        $row = [];
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = 'Total Clave A:';
        $row[] = (float) $total_A;
        
        $data[] = $row;

        // Separator

        $data[] = [''];


        // Customers
        $data[] = $header_names;

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
        $row[] = 'Total Clave B:';
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

        $nbr_years = 0;


        $n = 5+1+$suppliers_count+2;

        $n1 = 5+1+$suppliers_count;
        $m1 = $n1;    //  - 3;

        $n2 = count($data);
        $m2 = $n2;    //  - 3;

        $styles = [
            'A5:L5'    => ['font' => ['bold' => true]],
            "A$n:L$n"  => ['font' => ['bold' => true]],
            "E$m1:F$n1"  => ['font' => ['bold' => true]],
            "E$m2:F$n2"  => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
            'A' => NumberFormat::FORMAT_TEXT,
//            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_NUMBER_00,
            'J' => NumberFormat::FORMAT_NUMBER_00,
            'K' => NumberFormat::FORMAT_NUMBER_00,
            'L' => NumberFormat::FORMAT_NUMBER_00,
        ];

        $merges = ['A1:C1', 'A2:C2', 'A3:C3'];

        $sheetTitle = 'Acumulados 347';

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $sheetFileName = 'Acumulados 347 - '.$mod347_year;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }




/* ********************************************************************************************* */    


    public function reportModelo347Customer( $mod347_year, $customer_id, Request $request )
    {
        $mod347 = new Modelo347( $mod347_year );

        // Attached file stuff
        return $mod347->getCustomerInvoicesAttachment($customer_id, true);
    }   


    public function reportModelo347Supplier( $mod347_year, $supplier_id, Request $request )
    {
        $mod347 = new Modelo347( $mod347_year );

        // Attached file stuff
        return $mod347->getSupplierInvoicesAttachment($supplier_id, true);
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

            $pdfName    = \Str::singular('warehouseshippingslips').'_'.$document->secure_key . '_' . $document->document_date->format('Y-m-d');

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
        $event_class = '\\App\\Events\\'.\Str::singular($this->getParentClass()).'Emailed';
        event( new $event_class( $document ) );
        

        return redirect()->back()->with('success', l('Your Document has been sent! &#58&#58 (:id) ', ['id' => $document->number], 'layouts'));
    }
*/


    public function reportModelo347Email( $mod347_year, $customer_id, Request $request )
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

            $company = Context::getContext()->company;

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
        // $event_class = '\\App\\Events\\'.\Str::singular($this->getParentClass()).'Emailed';
        // event( new $event_class( $document ) );
        

        return redirect()->back()->with('success', l('Your Document has been sent! &#58&#58 (:id) ', ['id' => ''], 'layouts'));
    }



/* ********************************************************************************************* */    


}