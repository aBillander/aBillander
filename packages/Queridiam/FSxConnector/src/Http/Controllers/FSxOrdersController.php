<?php 

namespace Queridiam\FSxConnector\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use App\Customer;
use App\CustomerOrder;
use App\CustomerOrderLine;

use App\Configuration;

class FSxOrdersController extends Controller 
{

   protected $customer, $customerOrder, $customerOrderLine;

   public function __construct(Customer $customer, CustomerOrder $customerOrder, CustomerOrderLine $customerOrderLine)
   {
        $this->customer = $customer;
        $this->customerOrder = $customerOrder;
        $this->customerOrderLine = $customerOrderLine;
   }

	/**
	 * Display a listing of the resource.
	 * GET /something
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		return view('fsx_connector::fsx_orders.index');
	}


/* ********************************************************************************************* */   


	
	public function exportOrderList( $list )
	{
        // ProductionSheetsController
        if ( count( $list ) == 0 ) 
            return redirect()->route('worders.index')
                ->with('warning', l('No se ha seleccionado ningún Pedido, y no se ha realizado ninguna acción.'));


        // Prepare Logger
        $logger = \Queridiam\FSxConnector\FSxOrderExporter::logger();

        $logger->empty();
        $logger->start();


        // Initialize counters
        $i_ok=$i_nok=0;
        $i_all=$i_ok+$i_nok;

        // Do the Mambo!
        foreach ( $list as $oID ) 
        {
        	$logger->log("INFO", 'Se descargará el Pedido: <span class="log-showoff-format">{oid}</span> .', ['oid' => $oID]);

        	$exporter = \Queridiam\FSxConnector\FSxOrderExporter::processOrder( $oID );

            if ( $exporter->tell_run_status() ) {
                $i_ok++;
            } else {
                $i_nok++;
            }
        }

        $i_all=$i_ok+$i_nok;

        $logger->logInfo('Resumen de Pedidos:<br /><b>'.$i_all.'</b> Pedidos procesados.<br /> - <b>'.$i_ok.'</b> descargados.<br /> - <b>'.$i_nok.'</b> NO descargados.');

        $logger->stop();

        // Last minute stuff
        // Do not log this, since figures may change after log is created
        
            $dest_clientes = Configuration::get('FSOL_CBDCFG').Configuration::get('FSOL_CCLCFG');

            $dest_pedidos  = Configuration::get('FSOL_CBDCFG').Configuration::get('FSOL_CPVCFG');
            
            // Calculate last minute stuff!
            $anyClient = count(File::files( $dest_clientes ));
            
            $anyOrder  = count(File::files( $dest_pedidos ));


        return redirect('activityloggers/'.$logger->id)
                ->with('info', [ 
                    l('Hay <b>:anyClient</b> ficheros en la Carpeta de descarga de <b>Clientes</b>. Debe importarlos a FactuSOL, o borrarlos.', ['anyClient' => $anyClient]),
                    l('Hay <b>:anyOrder</b> ficheros en la Carpeta de descarga de <b>Pedidos</b>. Debe importarlos a FactuSOL, o borrarlos.', ['anyOrder' => $anyOrder])
                        ] );
    	}

    	public function exportOrders( Request $request )
    	{

            return $this->exportOrderList( $request->input('worders', []) );
    	} 


        public function export($id)
        {
            // return $id;
            
            return $this->exportOrderList( [$id] );
        }


        public function deleteCustomerFiles()
        {
            // 
            $dest_clientes = Configuration::get('FSOL_CBDCFG').Configuration::get('FSOL_CCLCFG');
            
            File::cleanDirectory( $dest_clientes );
            
            return redirect('customerorders')
                    ->with('success', l('Se ha vaciado la Carpeta de descarga de Clientes.'));
        }

        public function deleteOrderFiles()
        {
            // 
            $dest_pedidos  = Configuration::get('FSOL_CBDCFG').Configuration::get('FSOL_CPVCFG');
            
            File::cleanDirectory( $dest_pedidos );
            
            return redirect('customerorders')
                    ->with('success', l('Se ha vaciado la Carpeta de descarga de Pedidos.'));
        }

}
