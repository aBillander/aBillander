<?php

namespace App\Http\Controllers\HelferinTraits;

use App\Helpers\Calculator;
use App\Helpers\Exports\ArrayExport;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Customer;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

trait HelferinSalesTrait
{

    public function reportSalesNew(Request $request)
    {
        // return redirect()->route('helferin.home')
        //        ->with('error', 'No está autorizado a utilizar el formato "Por Documento".');
        

        /* ****************** */


        // Dates (cuen)
        $this->mergeFormDates( ['sales_date_from', 'sales_date_to'], $request );

        $date_from = $request->input('sales_date_from')
                     ? Carbon::createFromFormat('Y-m-d', $request->input('sales_date_from'))->startOfDay()
                     : null;
        
        $date_to   = $request->input('sales_date_to'  )
                     ? Carbon::createFromFormat('Y-m-d', $request->input('sales_date_to'  ))->endOfDay()
                     : null;

        //             abi_r($date_from.' - '.$date_to);die();

        $customer_id = $request->input('sales_customer_id', null);

/* * /
        $date_from = Carbon::createFromFormat('Y-m-d', '2023-03-01')->startOfDay();
        $date_to   = Carbon::createFromFormat('Y-m-d', '2023-03-31')->endOfDay();
        $customer_id = 12;
/ * */
        
        // abi_r($request->input('sales_customer_id', null));
        // abi_r($request->input('sales_date_from')); die();

        $sales_document_from = $request->input('sales_document_from', null);
        
        $sales_document_to   = $request->input('sales_document_to'  , null);

        $model = $request->input('sales_model', Configuration::get('RECENT_SALES_CLASS'));

        $models = $this->models;
        if ( !in_array($model, $models) )
            $model = Configuration::get('RECENT_SALES_CLASS');
        $class = '\\App\\Models\\'.$model;
        $table = \Str::snake(\Str::plural($model));
        $route = str_replace('_', '', $table);

        $grouped = $request->input('sales_grouped', 1);
        
        // Customers that have purchases within filters. Lets see:
        $customers =  Customer::when($customer_id>0, function ($query) use ($customer_id) {

                            $query->where('id', $customer_id);
                    })
                    ->whereHas($route, function ($query) use ($date_from, $date_to, $sales_document_from, $sales_document_to) {

                            if ( $date_from )
                                $query->where('document_date', '>=', $date_from);

                            if ( $date_to )
                                $query->where('document_date', '<=', $date_to  );


                            if ( (int) $sales_document_from > 0 )
                                $query->where('id', '>=', $sales_document_from);

                            if ( (int) $sales_document_to   > 0 )
                                $query->where('id', '<=', $sales_document_to  );
                    })
                    ->get();


// Nice! Lets move on and retrieve Documents
foreach ($customers as $customer) {
        # code...

        $documents = $class::where('customer_id', $customer->id)
                    ->where( function ($query) use ($date_from, $date_to, $sales_document_from, $sales_document_to) {

                            if ( $date_from )
                                $query->where('document_date', '>=', $date_from);

                            if ( $date_to )
                                $query->where('document_date', '<=', $date_to  );


                            if ( (int) $sales_document_from > 0 )
                                $query->where('id', '>=', $sales_document_from);

                            if ( (int) $sales_document_to   > 0 )
                                $query->where('id', '<=', $sales_document_to  );
                    } )
                    ->get();
        
        // Initialize
        $customer->model = $model;
        $customer->nbr_documents = $documents->count();

        $customer->total_taget_revenue = 0.0;
        $customer->total_revenue       = 0.0;
        $customer->total_revenue_with_discount  = 0.0;

        $customer->total_cost_price = 0.0;
        $customer->margin_amount    = 0.0;

        $customer->total_commission  = 0.0;
        $customer->margin_two_amount = 0.0;

        $customer->grand_total = 0.0;       // Including taxes && RAEE (ecotax)
//        $customer-> = 0.0;

        $customer->nbr_documents = $documents->count();

        // Lets peep under the hood:
        foreach ($documents as $document) {
            # code...
            $customer->total_taget_revenue   += $document->getTotalTargetRevenue();
            $customer->total_revenue         += $document->getTotalRevenue();
            $customer->total_revenue_with_discount += $document->getTotalRevenueWithDiscount();

            $customer->total_cost_price      += $document->getTotalCostPrice();
//          $customer->margin_percent        += $document->marginPercent();
            $customer->margin_amount         += $document->marginAmount();

            $customer->total_commission      += $document->getSalesRepCommission();
//          $customer->margin_two_percent    += $document->marginTwoPercent();
            $customer->margin_two_amount     += $document->marginTwoAmount();

            $customer->grand_total += $document->total_tax_incl;

/*
            $document->calculateProfit();

            $customer->products_cost   += $document->products_cost;
            $customer->products_ecotax += $document->products_ecotax;
            $customer->products_price  += $document->products_price;
            $customer->products_final_price       += $document->products_final_price;
            $customer->document_products_discount += $document->document_products_discount;
            $customer->document_commission += $document->total_commission;
            $customer->products_total += ($document->products_final_price - $document->products_ecotax - $document->document_products_discount - $document->total_commission);
            $customer->products_profit += $document->products_profit - $document->total_commission;

            $customer->grand_total += $document->total_tax_incl;
*/


            /*
            abi_r($document->id);
            abi_r($document->products_cost.' - '.$customer->products_cost);
            abi_r($document->products_ecotax.' - '.$customer->products_ecotax);
            abi_r($document->products_price.' - '.$customer->products_price);
            abi_r($document->products_final_price.' - '.$customer->products_final_price);
            abi_r($document->document_products_discount.' - '.$customer->document_products_discount);
            abi_r($document->products_profit.' - '.$customer->products_profit);

            abi_r('*********');
            */
        }

        // abi_r($documents->count());
        // abi_r($customers, true);

}

        // Lets get dirty!!
        // See: https://laraveldaily.com/laravel-excel-export-formatting-and-styling-cells/


        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        if ( $request->input('sales_date_from_form') && $request->input('sales_date_to_form') )
        {
            $ribbon = 'entre ' . $request->input('sales_date_from_form') . ' y ' . $request->input('sales_date_to_form');

        } else

        if ( !$request->input('sales_date_from_form') && $request->input('sales_date_to_form') )
        {
            $ribbon = 'hasta ' . $request->input('sales_date_to_form');

        } else

        if ( $request->input('sales_date_from_form') && !$request->input('sales_date_to_form') )
        {
            $ribbon = 'desde ' . $request->input('sales_date_from_form');

        } else

        if ( !$request->input('sales_date_from_form') && !$request->input('sales_date_to_form') )
        {
            $ribbon = 'todas';

        }

        $ribbon = 'fecha ' . $ribbon;

        $ribbon1  = ( $sales_document_from ? ' desde ' . $sales_document_from : '' );
        $ribbon1 .= ( $sales_document_to   ? ' hasta ' . $sales_document_from : '' );

        // Sheet Header Report Data
        $data[] = [Context::getContext()->company->name_fiscal];
        $data[] = ['Rentabilidad de Clientes por Documento ('.l($model).') ' . $ribbon1 . ', y ' . $ribbon, '', '', '', '', '', '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['Cliente', '', 'Agente', 'Operaciones', 'Valor', 'Ventas', 'Neto', 'Coste', 'Margen 1 (%)', 'Margen Neto', 'Comisión', '%Rent', 'Beneficio', 'Ranking Vtas. %', 'Ranking Benef. %', 'Ventas con Impuestos', 'ID', 'Referencia Externa'];

        $data[] = $header_names;

        // Convert each member of the returned collection into an array,
        // and append it to the data array.

        $total_taget_revenue = $customers->sum('total_taget_revenue');
        $total_revenue = $customers->sum('total_revenue');
        $total_revenue_with_discount = $customers->sum('total_revenue_with_discount');
        $total_cost_price = $customers->sum('total_cost_price');
        $margin_amount = $customers->sum('margin_amount');

        $total_commission = $customers->sum('total_commission');
        $margin_two_amount = $customers->sum('margin_two_amount');
        $total = $customers->sum('total_revenue_with_discount');
        $total_profit = $margin_two_amount;

        foreach ($customers as $customer) 
        {
                $row = [];
                $row[] = (string) $customer->reference_accounting;
                $row[] = $customer->name_regular;
                $row[] = $customer->sales_rep_id > 0 
                                ? '['.$customer->sales_rep_id.'] '.$customer->salesrep->alias
                                : '';
                $row[] = $customer->nbr_documents;
                $row[] = $customer->total_taget_revenue * 1.0;
                $row[] = $customer->total_revenue * 1.0;
                $row[] = $customer->total_revenue_with_discount * 1.0;

                $row[] = $customer->total_cost_price * 1.0;
                $row[] = Calculator::margin( 
                                        $customer->total_cost_price, 
                                        $customer->total_revenue_with_discount
                               ) * 1.0;
                $row[] = $customer->margin_amount * 1.0;

                $row[] = $customer->total_commission * 1.0;
                $row[] = Calculator::margin( $customer->total_cost_price, 
                                   $customer->total_revenue_with_discount - $customer->total_commission
                               );
                $row[] = $customer->margin_two_amount;

/*
                $row[] = Calculator::margin( $customer->products_cost, $customer->products_total, $customer->currency ) * 1.0;
                $row[] = $customer->products_profit * 1.0;
*/
                $row[] = abi_safe_division( $customer->total_revenue_with_discount, $total ) * 100.0;
                $row[] = abi_safe_division( $customer->margin_two_amount, $total_profit ) * 100.0;
                $row[] = $customer->grand_total * 1.0;

                $row[] = (string) $customer->id;
                $row[] = (string) $customer->reference_external;
    
                $data[] = $row;

        }

        // Totals
        $data[] = [''];

        $data[] = ['', '', '', 'Total:', $total_taget_revenue * 1.0, $total_revenue * 1.0, $total_revenue_with_discount*1.0, $total_cost_price * 1.00, Calculator::margin( 
                                        $total_cost_price, 
                                        $total_revenue_with_discount
                               ) * 1.0, $margin_amount * 1.0, $total_commission * 1.00, Calculator::margin( $total_cost_price, $total_revenue_with_discount - $total_commission, Context::getContext()->company->currency ) * 1.0, $margin_two_amount ];

//        $i = count($data);


        $n = count($data);
        $m = $n;    //  - 3;
        
        $styles = [
            'A4:P4'    => ['font' => ['bold' => true]],
//            "C$n:C$n"  => ['font' => ['bold' => true, 'italic' => true]],
            "D$m:M$n"  => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
            'A' => NumberFormat::FORMAT_TEXT,
//            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'D' => NumberFormat::FORMAT_NUMBER,
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
        ];

        $merges = ['A1:F1', 'A2:F2'];

        $sheetTitle = 'Rentabilidad Docs ' . l($model);

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }


/* 

    ************************************************* 

*/

    public function reportSales(Request $request)
    {
        if ( $request->input('sales_report_layout') == 'documents' )
            return $this->reportSalesNew($request);

        /* ****************** */


        // Dates (cuen)
        $this->mergeFormDates( ['sales_date_from', 'sales_date_to'], $request );

        $date_from = $request->input('sales_date_from')
                     ? Carbon::createFromFormat('Y-m-d', $request->input('sales_date_from'))->startOfDay()
                     : null;
        
        $date_to   = $request->input('sales_date_to'  )
                     ? Carbon::createFromFormat('Y-m-d', $request->input('sales_date_to'  ))->endOfDay()
                     : null;

        //             abi_r($date_from.' - '.$date_to);die();

        $customer_id = $request->input('sales_customer_id', null);

        $sales_document_from = $request->input('sales_document_from', null);
        
        $sales_document_to   = $request->input('sales_document_to'  , null);

        $model = $request->input('sales_model', Configuration::get('RECENT_SALES_CLASS'));

        $models = $this->models;
        if ( !in_array($model, $models) )
            $model = Configuration::get('RECENT_SALES_CLASS');
        $class = '\\App\\Models\\'.$model;
        $table = \Str::snake(\Str::plural($model));
        $route = str_replace('_', '', $table);

        $grouped = $request->input('sales_grouped', 1);
        
        // Customers that have purchases within filters. Lets see:
        $customers =  Customer::when($customer_id>0, function ($query) use ($customer_id) {

                            $query->where('id', $customer_id);
                    })
                    ->whereHas($route, function ($query) use ($date_from, $date_to, $sales_document_from, $sales_document_to) {

                            if ( $date_from )
                                $query->where('document_date', '>=', $date_from);

                            if ( $date_to )
                                $query->where('document_date', '<=', $date_to  );


                            if ( (int) $sales_document_from > 0 )
                                $query->where('id', '>=', $sales_document_from);

                            if ( (int) $sales_document_to   > 0 )
                                $query->where('id', '<=', $sales_document_to  );
                    })
                    ->get();


// Nice! Lets move on and retrieve Documents
foreach ($customers as $customer) {
        # code...
        // Initialize
        $customer->model = $model;
        $customer->nbr_documents   = 0.0;
        $customer->products_cost   = 0.0;
        $customer->products_ecotax = 0.0;
        $customer->products_price  = 0.0;
        $customer->products_final_price       = 0.0;
        $customer->document_products_discount = 0.0;
        $customer->products_total = 0.0;
        $customer->products_profit = 0.0;
        $customer->grand_total = 0.0;       // Including taxes && RAEE
//        $customer-> = 0.0;

        $documents = $class::where('customer_id', $customer->id)
                    ->where( function ($query) use ($date_from, $date_to, $sales_document_from, $sales_document_to) {

                            if ( $date_from )
                                $query->where('document_date', '>=', $date_from);

                            if ( $date_to )
                                $query->where('document_date', '<=', $date_to  );


                            if ( (int) $sales_document_from > 0 )
                                $query->where('id', '>=', $sales_document_from);

                            if ( (int) $sales_document_to   > 0 )
                                $query->where('id', '<=', $sales_document_to  );
                    } )
                    ->get();

        $customer->nbr_documents = $documents->count();

        // Lets peep under the hood:
        foreach ($documents as $document) {
            # code...
            $document->calculateProfit();

            $customer->products_cost   += $document->products_cost;
            $customer->products_ecotax += $document->products_ecotax;
            $customer->products_price  += $document->products_price;
            $customer->products_final_price       += $document->products_final_price;
            $customer->document_products_discount += $document->document_products_discount;
            $customer->document_commission += $document->total_commission;
            $customer->products_total += ($document->products_final_price - $document->products_ecotax - $document->document_products_discount - $document->total_commission);
            $customer->products_profit += $document->products_profit - $document->total_commission;

            $customer->grand_total += $document->total_tax_incl;


            /*
            abi_r($document->id);
            abi_r($document->products_cost.' - '.$customer->products_cost);
            abi_r($document->products_ecotax.' - '.$customer->products_ecotax);
            abi_r($document->products_price.' - '.$customer->products_price);
            abi_r($document->products_final_price.' - '.$customer->products_final_price);
            abi_r($document->document_products_discount.' - '.$customer->document_products_discount);
            abi_r($document->products_profit.' - '.$customer->products_profit);

            abi_r('*********');
            */
        }


        // abi_r($documents->count());
        // abi_r($customers, true);

}

        // Lets get dirty!!
        // See: https://laraveldaily.com/laravel-excel-export-formatting-and-styling-cells/


        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        if ( $request->input('sales_date_from_form') && $request->input('sales_date_to_form') )
        {
            $ribbon = 'entre ' . $request->input('sales_date_from_form') . ' y ' . $request->input('sales_date_to_form');

        } else

        if ( !$request->input('sales_date_from_form') && $request->input('sales_date_to_form') )
        {
            $ribbon = 'hasta ' . $request->input('sales_date_to_form');

        } else

        if ( $request->input('sales_date_from_form') && !$request->input('sales_date_to_form') )
        {
            $ribbon = 'desde ' . $request->input('sales_date_from_form');

        } else

        if ( !$request->input('sales_date_from_form') && !$request->input('sales_date_to_form') )
        {
            $ribbon = 'todas';

        }

        $ribbon = 'fecha ' . $ribbon;

        $ribbon1  = ( $sales_document_from ? ' desde ' . $sales_document_from : '' );
        $ribbon1 .= ( $sales_document_to   ? ' hasta ' . $sales_document_from : '' );

        // Sheet Header Report Data
        $data[] = [Context::getContext()->company->name_fiscal];
        $data[] = ['Rentabilidad de Clientes ('.l($model).') ' . $ribbon1 . ', y ' . $ribbon, '', '', '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['Cliente', '', 'Agente', 'Operaciones', 'Valor', 'Ventas', '%Desc.', 'Coste', 'Comisión', '%Rent', 'Beneficio', 'Ranking Vtas. %', 'Ranking Benef. %', 'Ventas con Impuestos', 'ID', 'Referencia Externa'];

        $data[] = $header_names;

        // Convert each member of the returned collection into an array,
        // and append it to the data array.

        $total_price = $customers->sum('products_price');
        $total_cost = $customers->sum('products_cost');
        $total_commission = $customers->sum('document_commission');
        $total_profit = $customers->sum('products_profit');
        $total =  $total_cost + $total_profit;

        foreach ($customers as $customer) 
        {
                $row = [];
                $row[] = (string) $customer->reference_accounting;
                $row[] = $customer->name_regular;
                $row[] = $customer->sales_rep_id > 0 
                                ? '['.$customer->sales_rep_id.'] '.$customer->salesrep->alias
                                : '';
                $row[] = $customer->nbr_documents;
                $row[] = $customer->products_price * 1.0;
                $row[] = $customer->products_total * 1.0;
                $row[] = $customer->products_price != 0.0
                            ? 100.0 * ($customer->products_price - $customer->products_total) / $customer->products_price
                            : 0.0;
                $row[] = $customer->products_cost * 1.0;
                $row[] = $customer->document_commission * 1.0;
                $row[] = Calculator::margin( $customer->products_cost, $customer->products_total, $customer->currency ) * 1.0;
                $row[] = $customer->products_profit * 1.0;
                $row[] = abi_safe_division( $customer->products_cost + $customer->products_profit, $total ) * 100.0;
                $row[] = abi_safe_division($customer->products_profit, $total_profit) * 100.0;

                $row[] = $customer->grand_total * 1.0;

                $row[] = (string) $customer->id;
                $row[] = (string) $customer->reference_external;
    
                $data[] = $row;

        }

        // Totals
        $data[] = [''];
        $r = ($total_price != 0.0) ? 100.0 * ($total_price - $total) / $total_price : '';
        $data[] = ['', '', '', 'Total:', $total_price * 1.0, $total * 1.0, $r, $total_cost * 1.00, $total_commission * 1.00, Calculator::margin( $total_cost, $total, Context::getContext()->company->currency ) * 1.0, $total_profit ];

//        $i = count($data);


        $n = count($data);
        $m = $n;    //  - 3;
        
        $styles = [
            'A4:N4'    => ['font' => ['bold' => true]],
//            "C$n:C$n"  => ['font' => ['bold' => true, 'italic' => true]],
            "D$m:K$n"  => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
            'A' => NumberFormat::FORMAT_TEXT,
//            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'D' => NumberFormat::FORMAT_NUMBER_00,
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
        ];

        $merges = ['A1:F1', 'A2:F2'];

        $sheetTitle = 'Rentabilidad ' . l($model);

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }

}