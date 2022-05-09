<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\Customer;
use App\Models\CustomerInvoice;
use App\Models\CustomerInvoiceLine;
use App\Models\CustomerInvoiceLineTax;
use App\Models\CustomerShippingSlip;
use App\Models\CustomerShippingSlipLine;
use App\Models\ActivityLogger;
use App\Models\Sequence;
use App\Models\Template;
use App\Traits\DateFormFormatterTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CustomerShippingSlipsInvoicerController
{

   use DateFormFormatterTrait;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $statusList = CustomerInvoice::getStatusList();
        // $order_statusList = Document::getStatusList();

        // Make sense:
        // unset($statusList['confirmed']);
        // unset($statusList['closed']);
        unset($statusList['canceled']);

        $logger = ActivityLogger::setup( 'Automatic Invoice Customer Shipping Slips', 'App\Http\Controllers\CustomerShippingSlipsInvoicerController::process' )
                    ->backTo( route('customershippingslips.invoicer.create') );

        $logger->empty();

        return view('customer_shipping_slips_invoicer.create', compact('statusList', 'logger'));
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function process(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['invoicer_date_from', 'invoicer_date_to', 'invoicer_date'], $request );

        // abi_r($request->All());

        // Carbon Objects:
        $date_from = $request->input('invoicer_date_from')
                     ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('invoicer_date_from'))->startOfDay()
                     : null;
        
        $date_to   = $request->input('invoicer_date_to'  )
                     ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('invoicer_date_to'  ))->endOfDay()
                     : null;
        
        $document_date = $request->input('invoicer_date'  )
                     ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('invoicer_date'  ))->startOfDay()
                     : abi_date_full( \Carbon\Carbon::now()->startOfDay() );

        // abi_r($date_from);
        // abi_r($date_to);
        // abi_r($date);


        $customer_id = $request->input('invoicer_customer_id', null);

        $group_by_customer = $request->input('invoicer_group_by_customer', 1);

        $group_by_shipping_address = $request->input('invoicer_group_by_shipping_address', 0);

        $status = $request->input('invoicer_status', 'draft');

        $testing = $request->input('testing', '0');


        // Calculate Document Range
        $documents = CustomerShippingSlip::select('id', 'document_date', 'customer_id', 'status', 'invoiced_at')
                    ->where( function ($query) use ($date_from, $date_to, $customer_id) {

                            if ( $date_from )
                                $query->where('document_date', '>=', $date_from);

                            if ( $date_to )
                                $query->where('document_date', '<=', $date_to  );

                            if ( $customer_id )
                                $query->where('customer_id', $customer_id  );


                            $query->where('status', 'closed');

                            $query->where('invoiced_at', null  );

                            $query->where('is_invoiceable', '>', 0);           // Skip not invoiceable shipping slips
                    } )
                    ->whereHas('customer', function($query)  {

                            $query->where('automatic_invoice', '>', 0);        // Skip not automatically invoiceable scustomers

                    })
                    ->get();

        // abi_r($documents);die();

        // Group by Customer
        $customers = $documents->unique('customer_id')->pluck('customer_id')->all();

        // abi_r(">>> ".count($customers));


        // Start Logger
        $logger = ActivityLogger::setup( 'Automatic Invoice Customer Shipping Slips', __METHOD__ )
                    ->backTo( route('customershippingslips.invoicer.create') );        // 'Import Products :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')


        $logger->empty();
        $logger->start();

        $logger->log("INFO", 'Se facturarán los Albaranes desde / hasta: <span class="log-showoff-format">{range}</span> .', ['range' => ($date_from ?? '-').' / '.($date_to ?? '-')]);

        $logger->log("INFO", 'Se facturarán los Albaranes de los Clientes: <span class="log-showoff-format">{customers}</span> .', ['customers' => implode(', ', $customers)]);

        $logger->log("INFO", 'Se facturarán los Albaranes: <span class="log-showoff-format">{customers}</span> .', ['customers' => implode(', ', $documents->pluck('id')->all())]);

        $logger->log("INFO", 'Se facturarán un total de <span class="log-showoff-format">{nbr}</span> Albaranes de los Clientes.', ['nbr' => $documents->count()]);

        $logger->log("INFO", 'La Factura se creará con fecha <span class="log-showoff-format">{date}</span> y estado <span class="log-showoff-format">{status}</span> .', ['date' => $document_date, 'status' => $status]);

        $logger->log("INFO", 'Opciones. Agrupar por Cliente: <span class="log-showoff-format">{group_by_customer}</span> Agrupar por Dirección de Envío: <span class="log-showoff-format">{group_by_shipping_address}</span> .', ['group_by_customer' => $group_by_customer, 'group_by_shipping_address' => $group_by_shipping_address]);



        foreach ($customers as $customer_id) {        	
	            # code...
	            // Select Documents
	            $documents_by_customer = $documents->where('customer_id', $customer_id)->pluck('id')->all();

	            // abi_r($customer_id);
	            // abi_r($documents_by_customer);
	            // abi_r("/* ******************************************************************************** */");

	            $params = [
	            		'group_by_customer' => $group_by_customer, 
	            		'group_by_shipping_address' => $group_by_shipping_address, 
	            		'document_date' => $document_date, 
	            		'status' => $status,
                        'testing' => $testing,

                        'logger' => $logger,
	            ];

                $logger->log("INFO", 'Comienza la facturación de los Albaranes del Cliente: <span class="log-showoff-format">{customer}</span> .', ['customer' => $customer_id]);

	            CustomerShippingSlip::invoiceDocumentList( $documents_by_customer, $params );
        }



        $logger->stop();


        return redirect('activityloggers/'.$logger->id)
                ->with('success', l('Se han facturado los Albaranes seleccionados <strong>:file</strong> .', ['file' => '']));



        // return redirect()->back()
        //        ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => ''], 'layouts'));

    }

}
