<?php 

namespace Queridiam\FSxConnector\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use DB;

use Queridiam\FSxConnector\FSxTools;
use Queridiam\FSxConnector\FSxFactuSOLWebSql;
use Queridiam\FSxConnector\FSxProductImporter;

use App\Product;

use App\Configuration;

class FSxProductsController extends Controller 
{

   protected $product;

   public $conf_keys = [];

   public function __construct(Product $product)
   {
        $this->product = $product;

        $this->conf_keys = FSxTools::$_key_groups;
   }

	/**
	 * Display a listing of the resource.
	 * GET /something
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        $conf_keys = array();
        $tab_index =   $request->has('tab_index')
                        ? intval($request->input('tab_index'))
                        : 1;

        // Poor man check:
        if ( ! isset( $this->conf_keys[$tab_index] ) )
            return redirect()->route('fsxproducts.index');

        if ($tab_index != 1)
        if ( Configuration::isEmpty('FSOL_TCACFG') || Configuration::isEmpty('FSOL_AUSCFG') )
            return redirect()->route('fsxproducts.index')
                ->with('error', l('Antes de importar Secciones, Familias y Artículos, debe cargar primero la Base de Datos de FactuSOLWeb'));
        
        $warehouseList = \App\Warehouse::select('id', DB::raw("concat('[', alias, '] ', name) as full_name"))->pluck('full_name', 'id')->toArray();;

        $key_group = [];

        foreach ($this->conf_keys[$tab_index] as $key)
            $key_group[$key]= Configuration::get($key);
		
        // Set sensible defaults
        if ( isset($key_group['FSX_FSOL_AUSCFG_PEER']) && ( intval($key_group['FSX_FSOL_AUSCFG_PEER']) <= 0 ) ) 
            $key_group['FSX_FSOL_AUSCFG_PEER'] = Configuration::getInt('DEF_WAREHOUSE');

        return view('fsx_connector::fsx_products.index', compact('tab_index', 'key_group', 'warehouseList'));
	}

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // Prepare Logger
        $logger = FSxFactuSOLWebSql::logger();

        $logger->empty();
        $logger->start();

        FSxFactuSOLWebSql::readAndUpdate();

        $logger->stop();

        return redirect('activityloggers/'.$logger->id);

        return redirect()
                ->route('fsxproducts.index')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => '$product->id'], 'layouts') . $request->input('tab_index'));
    }


/* ********************************************************************************************* */   


    public function importProducts(Request $request)
    {
        // Save keys
        $tab_index =   $request->has('tab_index')
                        ? intval($request->input('tab_index'))
                        : -1;
        
        $key_group = isset( $this->conf_keys[$tab_index] ) ?
                            $this->conf_keys[$tab_index]   :
                            null ;

        // Check tab_index
        if (!$key_group) 
            return redirect('404');

        foreach ($key_group as $key) 
        {
            if ($request->has($key)) {

                // abi_r("-$key-");
                // abi_r($request->input($key));

                // Prevent NULL values
                $value = is_null( $request->input($key) ) ? '' : $request->input($key);

                Configuration::updateValue($key, $value);
            }
        }


        // Mambo time here on:
        // Prepare Logger
        $logger = FSxProductImporter::logger();

        $logger->empty();
        $logger->start();

        FSxProductImporter::process();

        $logger->stop();

        return redirect('activityloggers/'.$logger->id);


        return redirect()
                ->route('fsxproducts.index', ['tab_index' => $request->input('tab_index')])
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => '$product->id'], 'layouts') . $request->input('tab_index'));
    }

}
