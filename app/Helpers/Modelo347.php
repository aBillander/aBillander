<?php

namespace App\Helpers;

use App\Helpers\Exports\ArrayExport;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Customer;
use App\Models\CustomerInvoice;
use App\Models\Supplier;
use App\Models\SupplierInvoice;
use App\Models\Tax;
use App\Models\TaxRule;
use Carbon\Carbon;
use Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

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
        $data[] = [Context::getContext()->company->name_fiscal];
        $data[] = ['Comprobación Acumulados 347 :: Año: '.$this->year, '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = ['Facturas del Cliente: ' . $ribbon];
        $data[] = [''];

        // All Taxes
        $alltaxes = Tax::get()->sortByDesc('percent');
        $alltax_rules = TaxRule::get();


        // Define the Excel spreadsheet headers
        $header_names = ['Número', 'Fecha', 'Trimestre', 'Estado', 'Base', 'IVA', 'Rec', 'Total'];

        // Add more headers
        foreach ( $alltaxes as $alltax )
        {
            $header_names[] = 'Base IVA '.$alltax->percent;
            $header_names[] = 'IVA '.$alltax->percent;
            $header_names[] = 'RE '.$alltax->equalization_percent;
        }

        $data[] = $header_names;

        $sub_totals = [];
        
        foreach ($documents as $document) {
            $row = [];
            $row[] = $document->document_reference;
//            $row[] = abi_date_short($document->document_date);
            $row[] = Date::dateTimeToExcel($document->document_date);
            $row[] = 'T'.$document->document_date->quarter;
            $row[] = $document->payment_status_name;
            $row[] = $document->total_tax_excl * 1.0;
            $row[] = 0.0;
            $row[] = 0.0;
            $row[] = $document->total_tax_incl * 1.0;

            $i = count($data);
            // $data[] = $row;

            // Taxes breakout
            $totals = $document->totals();

            foreach ( $alltaxes as $alltax )
            {
                if ( !( $total = $totals->where('tax_id', $alltax->id)->first() ) ) 
                {
                    // Empty Group
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';

                    continue;
                }
                
                $iva = $total['tax_lines']->where('tax_rule_type', 'sales')->first();
                $re  = $total['tax_lines']->where('tax_rule_type', 'sales_equalization')->first();

                $row[] = $iva->taxable_base * 1.0;
                $row[] = $iva->total_line_tax * 1.0;
                $row[] = optional($re)->total_line_tax ?? 0.0;
    
                // $data[] = $row;

                $row[6-1] += $iva->total_line_tax;
                $row[7-1] += optional($re)->total_line_tax ?? 0.0;

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

            $data[] = $row;

        }   // Document loop ends here



        // Totals
        $data[] = [''];
        $base = $iva = $re = 0.0;
        foreach ($sub_totals as $value) {
            # code...
            $data[] = ['', '', '', $value['percent'] / 100.0, $value['base'] * 1.0, $value['iva'] * 1.0, $value['re'] * 1.0];
            $base += $value['base'];
            $iva += $value['iva'];
            $re += $value['re'];
        }

        $data[] = [''];
        $data[] = ['', '', '', 'Total:', $base * 1.0, $iva * 1.0, $re * 1.0, ($base + $iva + $re) * 1.0];
        $data[] = [''];


        $columns_nbr = count($header_names);
        $collection = collect($data);

        foreach (['T1', 'T2', 'T3', 'T4'] as $t) {
            // code...
            $filtered = $collection->filter(function ($value, $key) use ($t) {
                return isset($value[2]) && ($value[2] == $t);
            });

            $row = ['', '', '', 'Total '.$t.':'];

            for ($i=4; $i < $columns_nbr; $i++) { 
                // code...
                $total_col = $filtered->reduce(function ($carry, $item) use ($i) {
                    return $carry + (float) $item[$i];
                }, 0.0);

                $row[$i] = (float) $total_col;
            }

            $data[] = $row;
        }



        $n = count($data);
        $m = $n - 3 - 4;

        $styles = [
            'A5:Q5'    => ['font' => ['bold' => true]],
            "D$m:K$n"  => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
//            'A' => NumberFormat::FORMAT_TEXT,
                    'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
                    'D' => NumberFormat::FORMAT_PERCENTAGE_00,
                    'E' => NumberFormat::FORMAT_NUMBER_00,
                    'F' => NumberFormat::FORMAT_NUMBER_00,
                    'G' => NumberFormat::FORMAT_NUMBER_00,
                    'H' => NumberFormat::FORMAT_NUMBER_00,
                    'I' => NumberFormat::FORMAT_NUMBER_00,
                    'J' => NumberFormat::FORMAT_NUMBER_00,
                    'K' => NumberFormat::FORMAT_NUMBER_00,
                    'L' => NumberFormat::FORMAT_NUMBER_00,
                    'M' => NumberFormat::FORMAT_NUMBER_00,
                    'N' => NumberFormat::FORMAT_NUMBER_00,
                    'O' => NumberFormat::FORMAT_NUMBER_00,
                    'P' => NumberFormat::FORMAT_NUMBER_00,
                    'Q' => NumberFormat::FORMAT_NUMBER_00,
        ];

        $merges = ['A1:D1', 'A2:D2', 'A3:D3'];

        $sheetTitle = 'Facturas 347';

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $company = Context::getContext()->company;

        $sheetFileName = '347 CLIENTES '.$this->year.' - '.str_replace(['.', ','], '', strtoupper($company->name_fiscal) );

        // Generate and return the spreadsheet
        if ($download == true)
        {
            return Excel::download($export, $sheetFileName.'.xlsx');

        } else {
            //
            $pathToFile     = storage_path() . '/exports/' . $fileName .'.xlsx';// die($pathToFile);

            // return storage information
            $storage_data = Excel::store($export, $pathToFile);
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

        return $sheetFileName;
    }


    /**
     * Create and store file to be attached to email that will be send to customer.
     *
     * Same as previous, but taxes breakdown are in rows below Customer Invoice
     *
     * @return full path to file in storage
     */
    public function getCustomerInvoicesAttachmentCompact($customer_id = null, $download = false) 
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
        $data[] = [Context::getContext()->company->name_fiscal];
        $data[] = ['Comprobación Acumulados 347 :: Año: '.$this->year, '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = ['Facturas del Cliente: ' . $ribbon];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['Número', 'Fecha', 'Trimestre', 'Estado', '', 'Base', 'IVA', 'Rec', 'Total'];

        $data[] = $header_names;

        // Convert each member of the returned collection into an array,
        // and append it to the data array.
        $alltaxes = Tax::get()->sortByDesc('percent');
        $alltax_rules = TaxRule::get();

        $sub_totals = [];
        
        foreach ($documents as $document) {
            $row = [];
            $row[] = $document->document_reference;
            $row[] = Date::dateTimeToExcel($document->document_date);
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


        $n = count($data);
        $m = $n - 3;

        $styles = [
            'A4:I4'    => ['font' => ['bold' => true]],
            "E$m:I$n"  => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
//            'A' => NumberFormat::FORMAT_TEXT,
                    'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
                    'E' => NumberFormat::FORMAT_PERCENTAGE_00,
                    'F' => NumberFormat::FORMAT_NUMBER_00,
                    'G' => NumberFormat::FORMAT_NUMBER_00,
                    'H' => NumberFormat::FORMAT_NUMBER_00,
                    'I' => NumberFormat::FORMAT_NUMBER_00,
        ];

        $merges = ['A1:D1', 'A2:D2', 'A3:D3'];

        $sheetTitle = 'Facturas 347';

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $company = Context::getContext()->company;

        $sheetFileName = '347 CLIENTES '.$this->year.' - '.str_replace(['.', ','], '', strtoupper($company->name_fiscal) );

        // Generate and return the spreadsheet
        if ($download == true)
        {
            return Excel::download($export, $sheetFileName.'.xlsx');

        } else {
            //
            $pathToFile     = storage_path() . '/exports/' . $fileName .'.xlsx';// die($pathToFile);

            // return storage information
            $storage_data = Excel::store($export, $pathToFile);
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

        return $sheetFileName;
    }

    
    /*
    |--------------------------------------------------------------------------
    | Methods - Suplliers
    |--------------------------------------------------------------------------
    */

    public function getSuppliers( $params = [] )
    {
        $supplier_id = array_key_exists('supplier_id', $params)
                            ? $params['supplier_id'] 
                            : 0;

        $year = $this->year;
        $max_amount = $this->max_amount;

        $suppliers = Supplier::
                          with('address')
                        ->whereHas('supplierinvoices', function ($query) use ( $year, $max_amount, $supplier_id ) {

                                // Closed Documents only
                                $query->where('status', 'closed');

                                if ( $supplier_id > 0 )
                                    $query->where('supplier_id', $supplier_id);

                                // Date range
                                $query->whereYear('document_date', $year);

                                // Modelo 347 condition
                                $query->havingRaw('SUM(total_tax_incl) > ?', [$max_amount]);
                        })
                        ->get();
        
        return $suppliers;
    }

    public function getSupplierQuarterlySales($supplier_id = null, $quarter = null) 
    {
        if ( ($supplier_id == null) || ($quarter == null) )
            return 0.0;

        $year = $this->year;        
        $month_from = 3 * $quarter - 2;
        $month_to   = 3 * $quarter;
        // abi_r($year);abi_r($month_from );abi_r($month_to);die();
        $date_from = Carbon::create($year, $month_from, 1)->startOfDay();
        $date_to   = Carbon::create($year, $month_to  , 1)->endOfMonth()->endOfDay();

        $sales = SupplierInvoice::
                        // Closed Documents only
                          where('status', 'closed')
                        // Supplier
                        ->when($supplier_id>0, function ($query) use ($supplier_id) {

                                $query->where('supplier_id', $supplier_id);
                        })
                        // Date range
                        ->where('document_date', '>=', $date_from)
                        ->where('document_date', '<=', $date_to  )
                        // Final result
                        ->sum('total_tax_incl');

        return $sales;
    }

    public function getSupplierYearlySales($supplier_id = null) 
    {
        if ( $supplier_id == null )
            return 0.0;

        $year = $this->year;        
        $month_from = 1;
        $month_to   = 12;
        // abi_r($year);abi_r($month_from );abi_r($month_to);die();
        $date_from = Carbon::create($year, $month_from, 1)->startOfDay();
        $date_to   = Carbon::create($year, $month_to  , 1)->endOfMonth()->endOfDay();

        $sales = SupplierInvoice::
                        // Closed Documents only
                          where('status', 'closed')
                        // Supplier
                        ->when($supplier_id>0, function ($query) use ($supplier_id) {

                                $query->where('supplier_id', $supplier_id);
                        })
                        // Date range
                        ->whereYear('document_date', $year )
                        // Final result
                        ->sum('total_tax_incl');

        return $sales;
    }

    /**
     * Create and store file to be attached to email that will be send to supplier.
     *
     * @return full path to file in storage
     */
    public function getSupplierInvoicesAttachment($supplier_id = null, $download = false) 
    {
        $supplier = Supplier::findOrFail($supplier_id);

        $documents = SupplierInvoice::
                        // Closed Documents only
                          where('status', 'closed')
                        // Supplier
                        ->where('supplier_id', $supplier_id)
                        // Date range
                        ->whereYear('document_date', $this->year )
                        // Final result
                        ->orderBy('document_date', 'asc')
                        ->get();
        
        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        $ribbon = '['.$supplier->identification.'] '.$supplier->name_fiscal;

        // Sheet Header Report Data
        $data[] = [Context::getContext()->company->name_fiscal];
        $data[] = ['Comprobación Acumulados 347 :: Año: '.$this->year, '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = ['Facturas del Proveedor: ' . $ribbon];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['Número', 'Fecha', 'Trimestre', 'Estado', '', 'Base', 'IVA', 'Rec', 'Total'];

        $data[] = $header_names;

        // Convert each member of the returned collection into an array,
        // and append it to the data array.
        $alltaxes = Tax::get()->sortByDesc('percent');
        $alltax_rules = TaxRule::get();

        $sub_totals = [];
        
        foreach ($documents as $document) {
            $row = [];
            $row[] = $document->document_reference;
            $row[] = Date::dateTimeToExcel($document->document_date);
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


        $n = count($data);
        $m = $n - 3;

        $styles = [
            'A4:I4'    => ['font' => ['bold' => true]],
            "E$m:I$n"  => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
//            'A' => NumberFormat::FORMAT_TEXT,
                    'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
                    'E' => NumberFormat::FORMAT_PERCENTAGE_00,
                    'F' => NumberFormat::FORMAT_NUMBER_00,
                    'G' => NumberFormat::FORMAT_NUMBER_00,
                    'H' => NumberFormat::FORMAT_NUMBER_00,
                    'I' => NumberFormat::FORMAT_NUMBER_00,
        ];

        $merges = ['A1:D1', 'A2:D2', 'A3:D3'];

        $sheetTitle = 'Facturas 347';

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $company = Context::getContext()->company;

        $sheetFileName = '347 PROVEEDORES '.$this->year.' - '.str_replace(['.', ','], '', strtoupper($company->name_fiscal) );

        // Generate and return the spreadsheet
        if ($download == true)
        {
            return Excel::download($export, $sheetFileName.'.xlsx');

        } else {
            //
            $pathToFile     = storage_path() . '/exports/' . $fileName .'.xlsx';// die($pathToFile);

            // return storage information
            $storage_data = Excel::store($export, $pathToFile);
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

        return $sheetFileName;
    }
}