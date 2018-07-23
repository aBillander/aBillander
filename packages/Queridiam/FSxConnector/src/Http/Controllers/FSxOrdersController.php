<?php 

namespace Queridiam\FSxConnector\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

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
        $logger = \aBillander\WooConnect\WooOrderImporter::logger();

        $logger->empty();
        $logger->start();

        // Do the Mambo!
        foreach ( $list as $oID ) 
        {
        	$logger->log("INFO", 'Se descargará el Pedido: <span class="log-showoff-format">{oid}</span> .', ['oid' => $oID]);

        	$exporter = \aBillander\WooConnect\WooOrderImporter::processOrder( $oID );
        }

        $logger->stop();

        return redirect('activityloggers/'.$logger->id)
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $logger->id], 'layouts'));
	}

	public function exportOrders( Request $request )
	{

        return $this->exportOrderList( $request->input('worders', []) );
	} 


	public function export($id)
	{
		return $id;
		
        return $this->exportOrderList( [$id] );
	}

}
