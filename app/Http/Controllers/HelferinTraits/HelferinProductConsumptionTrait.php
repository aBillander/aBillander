<?php 

namespace App\Http\Controllers\HelferinTraits;

use App\Helpers\Exports\ArrayExport;
use App\Models\Context;
use App\Models\Customer;
use App\Models\CustomerShippingSlipLine;
use App\Models\Product;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

trait HelferinProductConsumptionTrait
{

    public function reportConsumption(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['consumption_date_from', 'consumption_date_to'], $request );

        $date_from = $request->input('consumption_date_from')
                     ? Carbon::createFromFormat('Y-m-d', $request->input('consumption_date_from'))->startOfDay()
                     : null;
        
        $date_to   = $request->input('consumption_date_to'  )
                     ? Carbon::createFromFormat('Y-m-d', $request->input('consumption_date_to'  ))->endOfDay()
                     : null;

        //             abi_r($date_from.' - '.$date_to);die();

        $customer_id = $request->input('consumption_customer_id', null);

        $model = 'CustomerShippingSlip';

        $document_reference_date = 'close_date';		// 'document_date'
        
        // Products that were sent to Customers within filters. Lets see:
        $products = Product::select('id', 'name', 'reference', 'measure_unit_id')->with('measureunit')->orderBy('reference', 'asc')->get();

        foreach ($products as $product) {
        	# code...
        	$product->consumption = CustomerShippingSlipLine::
	                      where('line_type', 'product')
	                    ->where('product_id', $product->id)
	                    ->whereHas('document', function ($query) use ($customer_id, $date_from, $date_to, $document_reference_date) {

	                            // Closed Documents only
	                    		$query->where($document_reference_date, '!=', null);

	                            if ( $customer_id )
	                                $query->where('customer_id', $customer_id);

	                            if ( $date_from )
	                                $query->where($document_reference_date, '>=', $date_from);
	                            
	                            if ( $date_to )
	                                $query->where($document_reference_date, '<=', $date_to);
	                    })
	                    ->sum('quantity');
        }
        
        // https://github.com/Maatwebsite/Laravel-Excel/issues/2161



        // Lets get dirty!!

        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        if ( $request->input('consumption_date_from_form') && $request->input('consumption_date_to_form') )
        {
            $ribbon = 'entre ' . $request->input('consumption_date_from_form') . ' y ' . $request->input('consumption_date_to_form');

        } else

        if ( !$request->input('consumption_date_from_form') && $request->input('consumption_date_to_form') )
        {
            $ribbon = 'hasta ' . $request->input('consumption_date_to_form');

        } else

        if ( $request->input('consumption_date_from_form') && !$request->input('consumption_date_to_form') )
        {
            $ribbon = 'desde ' . $request->input('consumption_date_from_form');

        } else

        if ( !$request->input('consumption_date_from_form') && !$request->input('consumption_date_to_form') )
        {
            $ribbon = 'todas';

        }

        $ribbon = 'fecha ' . $ribbon;

        $customer_label = (int) $customer_id > 0
        				? Customer::findOrFail($customer_id)->name_regular
        				: 'todos';

        // Sheet Header Report Data
        $data[] = [Context::getContext()->company->name_fiscal];
        $data[] = ['Consumo de Productos ' . $ribbon, '', '', date('d M Y H:i:s')];
        $data[] = [''];
        $data[] = ['Cliente:', $customer_label];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['Referencia', 'Nombre', 'Consumo', 'Unidad'];

        $data[] = $header_names;

        // Convert each member of the returned collection into an array,
        // and append it to the data array.

        foreach ($products as $product) 
        {
                $row = [];
                $row[] = (string) $product->reference;
                $row[] = $product->name;
                $row[] = $product->consumption * 1.0;	// abi_amount($product->consumption, $product->measureunit->decimalPlaces) * 1;
                $row[] = $product->measureunit->sign;
    
                $data[] = $row;

        }


        $styles = [
            'A6:D6'    => ['font' => ['bold' => true]],
//            "C$n:C$n"  => ['font' => ['bold' => true, 'italic' => true]],
        ];

        $columnFormats = [
            'A' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_NUMBER,
//            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];

        $merges = ['A1:C1', 'A2:C2'];

        $sheetTitle = 'Consumo entre Fechas';

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }

}