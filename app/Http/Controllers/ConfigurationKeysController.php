<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use \App\Configuration as Configuration;
use View;

class ConfigurationKeysController extends Controller {

   public $conf_keys = array();

   public function __construct()
   {

        $this->conf_keys = array(
                    1 => array(
                                'MARGIN_METHOD'   => Configuration::get('MARGIN_METHOD'),
                                'ALLOW_SALES_WITHOUT_STOCK' => Configuration::get('ALLOW_SALES_WITHOUT_STOCK'),
                                'ALLOW_SALES_RISK_EXCEEDED' => Configuration::get('ALLOW_SALES_RISK_EXCEEDED'),
                                'SUPPORT_CENTER_EMAIL'   => Configuration::get('SUPPORT_CENTER_EMAIL'),
                                'SUPPORT_CENTER_NAME'   => Configuration::get('SUPPORT_CENTER_NAME'),
                        ),
                    2 => array(
                                'DEF_COUNTRY_NAME'       => Configuration::get('DEF_COUNTRY_NAME'),
                                'DEF_ITEMS_PERPAGE'      => Configuration::get('DEF_ITEMS_PERPAGE'),
                                'DEF_ITEMS_PERAJAX'      => Configuration::get('DEF_ITEMS_PERAJAX'),
                                'DEF_LANGUAGE'           => Configuration::get('DEF_LANGUAGE'),
                                'DEF_CURRENCY'           => Configuration::get('DEF_CURRENCY'),
                                'DEF_PERCENT_DECIMALS'   => Configuration::get('DEF_PERCENT_DECIMALS'),
                                'DEF_OUTSTANDING_AMOUNT' => Configuration::get('DEF_OUTSTANDING_AMOUNT'),
                                'DEF_WAREHOUSE'          => Configuration::get('DEF_WAREHOUSE'),
                                'DEF_CARRIER'            => Configuration::get('DEF_CARRIER'),
                                'DEF_CUSTOMER_INVOICE_SEQUENCE' => Configuration::get('DEF_CUSTOMER_INVOICE_SEQUENCE'),
                                'DEF_CUSTOMER_INVOICE_TEMPLATE' => Configuration::get('DEF_CUSTOMER_INVOICE_TEMPLATE'),
                                'DEF_CUSTOMER_PAYMENT_METHOD'   => Configuration::get('DEF_CUSTOMER_PAYMENT_METHOD'),
                                'DEF_CUSTOMER_PAYMENT_DAY'   => Configuration::get('DEF_CUSTOMER_PAYMENT_DAY'),
                        ),
        );

   }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $conf_keys = array();
        $tab_index =   $request->has('tab_index')
                        ? intval($request->input('tab_index'))
                        : 1;
        
        // Check tab_index
        $tab_view = 'configuration_keys.'.'key_group_'.intval($tab_index);
        if (!View::exists($tab_view)) 
            return Redirect::to('404')->with('error', 'No se ha encontrado el Grupo de Claves de Configuración solicitado ('.intval($tab_index).')');

        $key_group = $this->conf_keys[$tab_index];

        return view( $tab_view, compact('tab_index', 'key_group') );
    }
	
	

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // die($request->input('tab_index'));

        $tab_index =   $request->has('tab_index')
                        ? intval($request->input('tab_index'))
                        : -1;
        
        $key_group = isset( $this->conf_keys[$tab_index] ) ?
                            $this->conf_keys[$tab_index]   :
                            null ;

        // Check tab_index
        if (!$key_group) 
            return redirect('404')->with('error', 'No se ha encontrado el Grupo de Claves de Configuración solicitado ('.intval($tab_index).')');

        foreach ($key_group as $key => $value) 
        {
            if ($request->has($key)) \App\Configuration::updateValue($key, $request->input($key), $html);
        }

        return redirect('configurationkeys?tab_index='.$tab_index)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => 0], 'layouts') );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        // 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        // 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        // 
    }

}