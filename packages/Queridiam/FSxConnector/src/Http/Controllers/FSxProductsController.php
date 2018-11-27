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
        {
            $errors = [];

            if ( Configuration::isEmpty('FSOL_TCACFG') )
                $errors[] = l('No se ha encontrado un valor para la Tarifa por defecto, y no se puede importar los Precios.');
            if ( Configuration::isEmpty('FSOL_AUSCFG') )
                $errors[] = l('No se ha encontrado un valor para el Almacén por defecto, y no se puede importar el Stock.');

            return redirect()->route('fsxproducts.index')
                ->with('warning', l('Antes de importar Secciones, Familias y Artículos, debe cargar primero la Base de Datos de FactuSOLWeb.'))                
                ->with('error', $errors);
        }
        
        $warehouseList = \App\Warehouse::select('id', DB::raw("concat('[', alias, '] ', name) as full_name"))->pluck('full_name', 'id')->toArray();;

        $key_group = [];

        foreach ($this->conf_keys[$tab_index] as $key)
            $key_group[$key]= Configuration::get($key);
		
        // Set sensible defaults
        if ( isset($key_group['FSX_FSOL_AUSCFG_PEER']) && ( intval($key_group['FSX_FSOL_AUSCFG_PEER']) <= 0 ) ) 
            $key_group['FSX_FSOL_AUSCFG_PEER'] = Configuration::getInt('DEF_WAREHOUSE');

        // factusolweb.sql
        $fswebFile = Configuration::get('FSOL_CBDCFG').Configuration::get('FSOL_CBRCFG');

        if (file_exists($fswebFile)) {
            $fsw_date=date('Y-m-d H:i:s', filemtime($fswebFile));
            $fsw_alert = '';

            $file_fsol = detect_utf_encoding($fswebFile);
            if     ($file_fsol==1) $fsw_format='ISO-8859-1';
            elseif ($file_fsol==0) $fsw_format='UTF-8';
            else                   $fsw_format='desconocido';
        } else {
            $fsw_date='<span style="color: red; font-weight: bold">NO SE HA ENCONTRADO</span>';
            $fsw_alert = \App\Configuration::get('FSOL_CBRCFG').' :: '.$fsw_date;
            
            $fsw_format='';
        }

        return view('fsx_connector::fsx_products.index', compact('tab_index', 'key_group', 'warehouseList', 'fsw_date', 'fsw_alert', 'fsw_format'));
	}

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // Prepare Logger
        $logger = FSxFactuSOLWebSql::logger()->backTo( route('fsxproducts.index') );

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
        $logger = FSxProductImporter::logger()->backTo( route('fsxproducts.index').'?tab_index=7' );

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
