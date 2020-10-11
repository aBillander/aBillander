<?php 

namespace App;

use Excel;

use Carbon\Carbon;

class Modelo347 {

    // https://www.youtube.com/watch?v=JzoYPB77WaY

    public $year;
    public $max_amount;
	
    public function __construct( $year = 0, $max_amount = 3005.06 )
    {
        $this->year = $year > 0 ? $year : Carbon::now()->year;
        $this->max_amount = $max_amount;
    }

    
    /*
    |--------------------------------------------------------------------------
    | Methods - Customers
    |--------------------------------------------------------------------------
    */

    public function getCustomers( $params = [] )
    {
        $customer_id = array_key_exists('customer_id', $params)
                            ? $params['customer_id'] 
                            : 0;

        $year = $this->year;
        $max_amount = $this->max_amount;

        $customers = Customer::
                          with('address')
                        ->whereHas('customerinvoices', function ($query) use ( $year, $max_amount, $customer_id ) {

                                // Closed Documents only
                                $query->where('status', 'closed');

                                if ( $customer_id > 0 )
                                    $query->where('customer_id', $customer_id);

                                // Date range
                                $query->whereYear('document_date', $year);

                                // Modelo 347 condition
                                $query->havingRaw('SUM(total_tax_incl) > ?', [$max_amount]);
                        })
                        ->get();
        
        return $customers;
    }

    public function getCustomerQuarterlySales($customer_id = null, $quarter = null) 
    {
        if ( ($customer_id == null) || ($quarter == null) )
            return 0.0;

        $year = $this->year;        
        $month_from = 3 * $quarter - 2;
        $month_to   = 3 * $quarter;
        // abi_r($year);abi_r($month_from );abi_r($month_to);die();
        $date_from = Carbon::create($year, $month_from, 1)->startOfDay();
        $date_to   = Carbon::create($year, $month_to  , 1)->endOfMonth()->endOfDay();

        $sales = CustomerInvoice::
                        // Closed Documents only
                          where('status', 'closed')
                        // Customer
                        ->when($customer_id>0, function ($query) use ($customer_id) {

                                $query->where('customer_id', $customer_id);
                        })
                        // Date range
                        ->where('document_date', '>=', $date_from)
                        ->where('document_date', '<=', $date_to  )
                        // Final result
                        ->sum('total_tax_incl');

        return $sales;
    }

    public function getCustomerYearlySales($customer_id = null) 
    {
        if ( $customer_id == null )
            return 0.0;

        $year = $this->year;        
        $month_from = 1;
        $month_to   = 12;
        // abi_r($year);abi_r($month_from );abi_r($month_to);die();
        $date_from = Carbon::create($year, $month_from, 1)->startOfDay();
        $date_to   = Carbon::create($year, $month_to  , 1)->endOfMonth()->endOfDay();

        $sales = CustomerInvoice::
                        // Closed Documents only
                          where('status', 'closed')
                        // Customer
                        ->when($customer_id>0, function ($query) use ($customer_id) {

                                $query->where('customer_id', $customer_id);
                        })
                        // Date range
                        ->whereYear('document_date', $year )
                        // Final result
                        ->sum('total_tax_incl');

        return $sales;
    }

    /**
     * Create and store file to be attached to email that will be send to customer.
     *
     * @return full path to file in storage
     */
    public function getCustomerInvoicesAttachment($customer_id = null, $download = false) 
    {
        $customer = Customer::findOrFail($customer_id);

        $documents = CustomerInvoice::
                        // Closed Documents only
                          where('status', 'closed')
                        // Customer
                        ->where('customer_id', $customer_id)
                        // Date range
                        ->whereYear('document_date', $this->year )
                        // Final result
                        ->orderBy('document_date', 'asc')
                        ->get();
        
        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        $ribbon = '['.$customer->identification.'] '.$customer->name_fiscal;

        // Sheet Header Report Data
        $data[] = [\App\Context::getContext()->company->name_fiscal];
        $data[] = ['Comprobación Acumulados 347 :: Año: '.$this->year, '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = ['Facturas del Cliente: ' . $ribbon];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['Número', 'Fecha', 'Trimestre', 'Estado', '', 'Base', 'IVA', 'Rec', 'Total'];

        $data[] = $header_names;

        // Convert each member of the returned collection into an array,
        // and append it to the data array.
        $alltaxes = \App\Tax::get()->sortByDesc('percent');
        $alltax_rules = \App\TaxRule::get();

        $sub_totals = [];
        
        foreach ($documents as $document) {
            $row = [];
            $row[] = $document->document_reference;
            $row[] = abi_date_short($document->document_date);
            $row[] = 'T'.$document->document_date->quarter;
            $row[] = $document->payment_status_name;
            $row[] = '';    // $document->paymentmethod->name;
            $row[] = $document->total_tax_excl * 1.0;
            $row[] = 0.0;
            $row[] = 0.0;
            $row[] = $document->total_tax_incl * 1.0;

            $i = count($data);
            $data[] = $row;

            // Taxes breakout
            $totals = $document->totals();

            foreach ( $alltaxes as $alltax )
            {
                if ( !( $total = $totals->where('tax_id', $alltax->id)->first() ) ) continue;
                
                $iva = $total['tax_lines']->where('tax_rule_type', 'sales')->first();
                $re  = $total['tax_lines']->where('tax_rule_type', 'sales_equalization')->first();

                $row = [];
                $row[] = '';
                $row[] = '';
                $row[] = '';
                $row[] = '';
                $row[] = $alltax->percent / 100.0;
                $row[] = $iva->taxable_base * 1.0;
                $row[] = $iva->total_line_tax * 1.0;
                $row[] = optional($re)->total_line_tax ?? 0.0;
                $row[] = '';
    
                $data[] = $row;

                $data[$i][6] += $iva->total_line_tax;
                $data[$i][7] += optional($re)->total_line_tax ?? 0.0;

                if ( array_key_exists($alltax->id, $sub_totals) )
                {
                    $sub_totals[$alltax->id]['base']    += $iva->taxable_base;
                    $sub_totals[$alltax->id]['iva']     += $iva->total_line_tax;
                    $sub_totals[$alltax->id]['re']      += optional($re)->total_line_tax ?? 0.0;
                } else {
                    $sub_totals[$alltax->id] = [];
                    $sub_totals[$alltax->id]['percent'] = $alltax->percent;
                    $sub_totals[$alltax->id]['base']    = $iva->taxable_base;
                    $sub_totals[$alltax->id]['iva']     = $iva->total_line_tax;
                    $sub_totals[$alltax->id]['re']      = optional($re)->total_line_tax ?? 0.0;
                }

                // abi_r($sub_totals);
            }

// abi_r('************************************');
        }

//        die();

        // Totals
        $data[] = [''];
        $base = $iva = $re = 0.0;
        foreach ($sub_totals as $value) {
            # code...
            $data[] = ['', '', '', '', $value['percent'] / 100.0, $value['base'] * 1.0, $value['iva'] * 1.0, $value['re'] * 1.0];
            $base += $value['base'];
            $iva += $value['iva'];
            $re += $value['re'];
        }

        $data[] = [''];
        $data[] = ['', '', '', '', 'Total:', $base * 1.0, $iva * 1.0, $re * 1.0, ($base + $iva + $re) * 1.0];

        
        $company = Context::getContext()->company;

        $fileName    = '347 CLIENTES '.$this->year.' - '.str_replace(['.', ','], '', strtoupper($company->name_fiscal) );
        
        $sheetName = 'Facturas 347';

        // Generate and return the spreadsheet
        $theSheet = Excel::create($fileName, function($excel) use ($sheetName, $data) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data) {
                
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:D3');
                
                $sheet->getStyle('A4:I4')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->setColumnFormat(array(
                    'B' => 'dd/mm/yyyy',
                    'E' => '0.00%',
                    'F' => '0.00',
                    'G' => '0.00',
                    'H' => '0.00',
                    'I' => '0.00',
//                    'F' => '@',
                ));
                
                $n = count($data);
                $m = $n - 3;
                $sheet->getStyle("E$m:I$n")->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->fromArray($data, null, 'A1', false, false);
            });

        });

        if ($download == true)
        {
            $theSheet->download('xlsx');

        } else {
            //
            $pathToFile     = storage_path() . '/exports/' . $fileName .'.xlsx';// die($pathToFile);

            // https://docs.laravel-excel.com/2.1/export/store.html
            $storage_data = $theSheet->store('xlsx', storage_path('exports'), true);    // return storage information
/*
Key     Explanation
full    Full path with filename
path    Path without filename
file    Filename
title   File title
ext     File extension
*/
            return $storage_data;
        }





        // Final touches
        

        return $fileName;
    }

    
    /*
    |--------------------------------------------------------------------------
    | Methods - Suplliers
    |--------------------------------------------------------------------------
    */

    public function getSuppliers( ) 
    {
        $suppliers = collect([]);

        return $suppliers;
    }
}