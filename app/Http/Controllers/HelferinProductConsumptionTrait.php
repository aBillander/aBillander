<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;
use App\Customer;

use App\CustomerShippingSlipLine;

use Excel;

trait HelferinProductConsumptionTrait
{

    public function reportConsumption(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['consumption_date_from', 'consumption_date_to'], $request );

        $date_from = $request->input('consumption_date_from')
                     ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('consumption_date_from'))->startOfDay()
                     : null;
        
        $date_to   = $request->input('consumption_date_to'  )
                     ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('consumption_date_to'  ))->endOfDay()
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
        				: '';

        // Sheet Header Report Data
        $data[] = [\App\Context::getContext()->company->name_fiscal];
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

        $sheetName = 'Consumo';

        // Generate and return the spreadsheet
        Excel::create('Consumo entre Fechas', function($excel) use ($sheetName, $data) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data) {
                
                $sheet->mergeCells('A1:C1');
                $sheet->mergeCells('A2:C2');

                $sheet->getStyle('A6:D6')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->setColumnFormat(array(
//                    'B' => 'dd/mm/yyyy',
//                    'C' => 'dd/mm/yyyy',
                    'A' => '@',
//                    'C' => '0.00',
                    'C' => '0',

                ));

                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');


        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts'));

    }

}