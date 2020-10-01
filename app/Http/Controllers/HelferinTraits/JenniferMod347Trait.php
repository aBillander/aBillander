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

trait JenniferMod347Trait
{

    /**
     * ABC Customer Sales Report (ABC Analysis).
     *
     * @return Spread Sheet download
     */
    public function reportMod347(Request $request)
    {
        $mod347_year = $request->input('mod347_year') > 0 ? $request->input('mod347_year') : Carbon::now()->year;

        // abi_r((new Modelo347(2020, 1000))->getCustomers()->count());


        // Wanna dance, Honey Bunny?
        $mod347 = new Modelo347( $mod347_year );

        // All Suppliers. Lets see:
        $customers = $mod347->getSuppliers();

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

                $w = count($data[5+1]);

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

}