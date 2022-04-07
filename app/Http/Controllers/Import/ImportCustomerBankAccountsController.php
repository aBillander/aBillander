<?php

namespace App\Http\Controllers\Import;

use App\Helpers\Exports\ArrayExport;
use App\Helpers\Imports\ArrayImport;
use App\Http\Controllers\Controller;
use App\Models\ActivityLogger;
use App\Models\Address;
use App\Models\BankAccount;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\Customer;
use App\Models\State;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ImportCustomerBankAccountsController extends Controller
{
/*
   use BillableControllerTrait;

   protected $customer, $customerOrder, $customerOrderLine;

   public function __construct(Customer $customer, CustomerOrder $customerOrder, CustomerOrderLine $customerOrderLine)
   {
        $this->customer = $customer;
        $this->customerOrder = $customerOrder;
        $this->customerOrderLine = $customerOrderLine;
   }
*/
    public static $table = 'bank_accounts';

    public static $column_mask;		// Column fields (?)

//    public $entities = array();	// This controller is for ONE entity

    public $available_fields = array();

    public $required_fields = array();

//    public $cache_image_deleted = array();

    public static $default_values = array();
/*
    public static $validators = [];
*/
    public $separator = ';';

    public $multiple_value_separator = ',';


// //////////////////////////////////////////////////// //   

   protected $bankaccount;
   protected $customer;
   protected $address;

   public function __construct(BankAccount $bankaccount, Customer $customer, Address $address)
   {
        $this->bankaccount = $bankaccount;
        $this->customer = $customer;
        $this->address  = $address;
   }


    /**
     * Import a file of the resource.
     *
     * @return 
     */
    public function import()
    {
        return view('imports.customer_bank_accounts');
    }

    public function process(Request $request)
    {
        $extra_data = [
            'extension' => ( $request->file('data_file') ? 
                             $request->file('data_file')->getClientOriginalExtension() : 
                             null 
                            ),
        ];

        $rules = [
                'data_file' => 'required | max:8000',
                'extension' => 'in:csv,xlsx,xls,ods',
        ];

        $this->validate($request->merge( $extra_data ), $rules);


        // Start Logger
        $logger = ActivityLogger::setup( 'Import Customer Users', __METHOD__ )
                    ->backTo( route('customerbankaccounts.import') );


        $logger->empty();
        $logger->start();

        $file = $request->file('data_file')->getClientOriginalName();   // . '.' . $request->file('data_file')->getClientOriginalExtension();

        $logger->log("INFO", 'Se cargarán las Cuentas Bancarias desde el Fichero: <br /><span class="log-showoff-format">{file}</span> .', ['file' => $file]);



        $truncate = $request->input('truncate', 0);
        $params = ['simulate' => $request->input('simulate', 0)];

        // Truncate table
        if ( ! $params['simulate'] )
        if ( $truncate > 0 ) {

            $bankaccounts = $this->bankaccount
                        ->where('bank_accountable_type', Customer::class)
                        ->get();

            $nbr = $bankaccounts->count();
            
            $bankaccounts->delete();

            $logger->log("INFO", "Se han borrado todas las Cuentas Bancarias antes de la Importación. En total {nbr} Cuentas Bancarias.", ['nbr' => $nbr]);
        }


        try{
            
            $this->processFile( $request->file('data_file'), $logger, $params );

        }
        catch(\Exception $e){

                $logger->log("ERROR", "Se ha producido un error:<br />" . $e->getMessage());

        }


        $logger->stop();


        return redirect('activityloggers/'.$logger->id)
                ->with('success', l('Se han cargado las Cuentas Bancarias desde el Fichero: <strong>:file</strong> .', ['file' => $file]));
    }


    /**
     * Process a file of the resource.
     *
     * @return 
     */
    protected function processFile( $file, $logger, $params = [] )
    {
        // Get data as an array
        $worksheet = Excel::toCollection(new ArrayImport, $file);

        // abi_r($worksheet->first(), true);

        $reader = $worksheet->first();    // First sheet in worksheet


// Process reader STARTS

            if ( $params['simulate'] > 0 ) 
                $logger->log("WARNING", "Modo SIMULACION. Se mostrarán errores, pero no se cargará nada en la base de datos.");

            $i = 0;
            $i_ok = 0;
            $max_id = 1000;


            if(!empty($reader) && $reader->count()) {

                
                // BankAccount::unguard();        
                        

                foreach($reader as $row)
                {
                    // do stuff
                    if ($i > $max_id) break;

                    // Prepare data
                    $data = $row->toArray();

                    // abi_r($data);die();

                    $item = '[<span class="log-showoff-format">'.$data['bank_name'].'</span>] <span class="log-showoff-format">'.$data['customer_id'].'</span> '.$data['iban'];

                    // Some Poor Man checks:

                    // Customer
                    $customer = $this->customer->where( 'id', $data['customer_id'] ?? 0 )
                                                    ->with('bankaccount')
                                                    ->first();

                    if ( !$customer )
                    {
                        $logger->log("ERROR", "Cuenta Bancaria ".$item.":<br />" . "El Cliente NO existe. ".$data['id'].' / '.$data['CUSTOMER_NAME_COMMERCIAL']);

                        continue;
                    }

                    $data['customer_id'] = $customer->id;

                    // Bank Account
                    $bankaccount = $customer->bankaccount;

                    \DB::beginTransaction();
                    try {
                        if ( !($params['simulate'] > 0) ) 
                        {
                            if ( $bankaccount )
                            {
                                // Update
                                $bankaccount->update($data);
                            } else {
                                // Create
                                $bankaccount = BankAccount::create($data);
                                $customer->bankaccounts()->save($bankaccount);

                                $customer->bank_account_id = $bankaccount->id;
                                $customer->save();
                            }
                        }

                        $i_ok++;

                    }
                    catch(\Exception $e) {

                            \DB::rollback();

                            $logger->log("ERROR", "Se ha producido un error al procesar la Cuenta bancaria ".$item.":<br />" . $e->getMessage());

                    }

                    // If we reach here, then
                    // data is valid and working.
                    // Commit the queries!
                    \DB::commit();

                    $i++;

                }   // End foreach


                // BankAccount::reguard();


            } else {

                // No data in file
                $logger->log('WARNING', 'No se encontraton datos de Cuentas Bancarias en el fichero.');
            }

            $logger->log('INFO', 'Se han creado / actualizado {i} Cuentas Bancarias.', ['i' => $i_ok]);

            $logger->log('INFO', 'Se han procesado {i} Cuentas Bancarias.', ['i' => $i]);

// Process reader ENDS

    }


    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function export()
    {
        
        $bankaccounts = $this->bankaccount
                        ->with('customer')
                        ->where('bank_accountable_type', Customer::class)
                        ->orderBy('id', 'asc')
                        ->get();


        // Initialize the array which will be passed into the Excel generator.
        $data = [];  

        // Define the Excel spreadsheet headers
        $headers = [ 'id', 'name', 'bank_name', 
                     'ccc_entidad', 'ccc_oficina', 'ccc_control', 'ccc_cuenta', 'iban', 'swift', 

                     'suffix', 'creditorid', 'mandate_reference', 'mandate_date', 'notes', 
                    
                     'customer_id', 'CUSTOMER_NAME_COMMERCIAL'
        ];

        $data[] = $headers;

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($bankaccounts as $bankaccount) {
            // $data[] = $line->toArray();
            $row = [];
            foreach ($headers as $header)
            {
                $row[$header] = $bankaccount->{$header} ?? ( $bankaccount->bankaccountable->{$header} ?? '');
            }

            // abi_r($bankaccount->customer, true);

            $row['customer_id']  = $bankaccount->customer  ? $bankaccount->customer->id : '';
            $row['CUSTOMER_NAME_COMMERCIAL']  = $bankaccount->customer  ? $bankaccount->customer->name_commercial : '';

            $data[] = $row;
        }

        $styles = [];

        $columnFormats = [
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
        ];

        $sheetTitle = 'Customer Bank Accounts';

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }
}