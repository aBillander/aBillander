<?php 

namespace Queridiam\FSxConnector;

use App\Customer;
use App\Address;
use App\CustomerOrder;
use App\CustomerOrderLine;
use App\CustomerOrderLineTax;
use App\Product;
use App\Combination;
use App\Tax;

use App\Configuration;

use Queridiam\FSxConnector\FSxTools;

// use \aBillander\WooConnect\WooOrder;

use App\Traits\LoggableTrait;

class FSxOrderExporter {

    use LoggableTrait;

  	protected $abi_order;
    protected $run_status = true;			// So far, so good. Can continue export
  	protected $customer_download = false;	// Should I download the Customer?
    protected $dest_clientes;
    protected $dest_pedidos;

  	protected $raw_data = [];

  	protected $info     = [];
  	protected $customer = [];
  	protected $delivery = [];
  	protected $billing  = [];
  	protected $products = [];
  	protected $other    = [];    // Handy array to manage non Product costs

  //	protected $next_sort_order = 0;

  	// Logger to send messages
  	protected $logger;
  	protected $log_has_errors = false;

    public function __construct ($order_id = null, CustomerOrder $order = null)
    {
        // Get logger
        // $this->log = $rwsConnectorLog;

        $this->abi_order = $order;

        $this->run_status = true;
        $this->customer_download = false;     // ¿Debe descargarse el Cliente?


     	$this->dest_clientes = Configuration::get('FSOL_CBDCFG').Configuration::get('FSOL_CCLCFG');	// Destination folders
     	$this->dest_pedidos  = Configuration::get('FSOL_CBDCFG').Configuration::get('FSOL_CPVCFG');	// 


        // Start Logger
        $this->logger = self::loggerSetup( 'Descargar Pedidos y Clientes a FactuSOL' );


        // Get order data (if order exists!)
        if ( intval($order_id) ) {
            // set the product
            // $this->fill_in_data( intval($order_id) );

            // fill it, parse it and save it
            // $this->populate_data();
            
            // $this->import();  // The whole process

	          $this->fill_in_data( intval($order_id) );
            // return true

        } else {
            // $this->logMessage( 'ERROR', 'La descarga de Pedidos a FactuSOL está en desarrollo.' );
            $this->logMessage( 'ERROR', 'El número de Pedido <b>"'.$order_id.'"</b>no es válido.' );
            $this->run_status = false;
        }
    }

    
    /**
     *   Data retriever & Transformer
     */
    public function fill_in_data($order_id = null)
    {
    	// Get $order_id data...
        $this->abi_order = CustomerOrder::
        					  with('customer')
        					->with('invoicingaddress')
        					->with('shippingaddress')
        					->find( $order_id );

        $order = $this->abi_order;
        if (!$order) {
            $this->logError( 'Se ha intentado recuperar el Pedido número <b>"'.$order_id.'"</b> y no existe.' );
            $this->run_status = false;
            return $this->run_status;
        }

        if ($order->export_date) {
            $this->logError( 'El Pedido número <b>"'.$order_id.'"</b> ya se descargó el <b>"'.$order->export_date.'"</b>.' );
            $this->run_status = false;
            return $this->run_status;
        }

        $customer = $order->customer;
        // $cid_customer = $customer->id;
        $cid_customer = $customer->reference_external;
        if ( !$cid_customer ) $this->customer_download = true;

		$addressInvoice  = $order->invoicingaddress;
		$addressDelivery = $order->shippingaddress;

		if ($order->invoicing_address_id != $order->shipping_address_id) 
		{
			// Igual debería descargarse el cliente
      if ( Configuration::get('FSX_DLOAD_CUSTOMER_SHIPPING_ADDRESS') > 0 ) $this->customer_download = true;
		}

        $products  = $order->customerorderlines->filter(function($line) {
		    	return ($line->line_type == 'product') || ($line->line_type == 'service');
			  });
        $discounts = $order->customerorderlines->where('line_type', '=', 'discount');
//        $coupons   = $order->get_items( 'coupon' );
//        $fees      = $order->get_items( 'fee' );        // Future use
        $shipping = $order->customerorderlines->where('line_type', '=', 'shipping')->first();


        // Shipping

        $this->other['ot_shipping'] =  [];

        if ($shipping)
        $this->other['ot_shipping'] = array(
                    'id' => $shipping->product_id,
                    'reference' => $shipping->reference,
                    'name' => $shipping->name,
                    'qty' => $shipping->quantity,

                    'unit_customer_final_price' => $shipping->unit_customer_final_price,
                    'discount_percent' => $shipping->discount_percent,
                    'unit_final_price' => $shipping->unit_final_price,
                    'unit_final_price_tax_inc' => $shipping->unit_final_price_tax_inc,

                    'total_tax_incl' => $shipping->total_tax_incl,
                    'total_tax_excl' => $shipping->total_tax_excl,

                    'tax_id' => $shipping->tax_id,
                    'sales_equalization' => $shipping->sales_equalization,

//                    'tax_rate' => $tax_rate,
                    'allow_tax' => '0'     // Tax not included
                );
        
        // Coupons & Discounts
        $this->other['ot_discount'] = [];

        // Fees
        $this->other['ot_fees'][] = [];

        // Order Info
        $reference = $order->document_reference;
        if ( $order->created_via == 'webshop' ) $reference = $order->reference;

        $this->info = array(
                      'orders_id' => $order->id,
                      'document_reference' => $order->document_reference,
                      'reference' => $order->reference,
                      'created_via' => $order->created_via,
//                      'currency' => $order->get_order_currency(),
//                      'currency_value' => $order->conversion_rate,
//                      'payment_title' => $order->payment_method_title,
                      'payment_method' => $order->payment_method_id,
//                      'shipping_title' => $order->get_shipping_method(),
//                      'order_status' => $order->get_status(),
                      'date_purchased' => $order->document_date,
//                      'last_modified' => $order->modified_date,
                      'comments' => $order->notes_from_customer,  // ISO-8859-1 charset does not contain the EURO sign, therefor the EURO sign will be converted into a question mark character '?'. In order to convert properly UTF8 data with EURO sign you must use: iconv("UTF-8", "CP1252", $data)

                      'id_customer' => $order->customer_id,
                      'id_address_delivery' => $addressDelivery->id,
                      'id_address_invoice' => $addressInvoice->id,
//                      'id_carrier' => '',

                      'docpago' => '',
                          );

        $this->customer = array(
                      'name' => $addressInvoice->firstname . ' ' . $addressInvoice->lastname, 
                      'ID' => $customer->id,    // User ID who the order belongs to. 0 for guests.
//                      'wooID' => $woo_customer,
                      'csID' => $cid_customer,
                      'is_new' => $customer->reference_external > 0 ? false : true,
                      'customer_user' => $addressInvoice->email,  // $user->data->user_login,
                      'vat_id' => $customer->identification,
                      'email_address' => $addressInvoice->email,
                      
                      'company' => $addressInvoice->name_commercial,
                      'street_address' => $addressInvoice->address1 . ' ' . $addressInvoice->address2,
                      'city' => $addressInvoice->city,
                      'postcode' => $addressInvoice->postcode,
                      'state' => $addressInvoice->state->name,
                      'country' => $addressInvoice->country->name,
					 
                      'telephone' => $addressInvoice->phone,
                      'mobile' => $addressInvoice->phone_mobile,
                      'fax' => $addressInvoice->fax,
                          );


        $this->delivery = array(
                      'name' => $addressDelivery->firstname . ' ' . $addressDelivery->lastname,
                      'company' => $addressDelivery->name_commercial,
                      'street_address' => $addressDelivery->address1 . ' ' . $addressDelivery->address2,
                      'city' => $addressDelivery->city,
                      'postcode' => $addressDelivery->postcode,
                      'state' => $addressDelivery->state->name,
                      'country' => $addressDelivery->country,

                      'telephone' => $addressDelivery->phone,
                      'mobile' => $addressDelivery->phone_mobile,
                      'fax' => $addressDelivery->fax,
							  );
        
        // Order Lines
        foreach ($products as $item)
        {
        	// 

        	$this->products[] = array(
                    'qty' => $item->quantity,
                    'name' => $item->name,
                    'id' => $item->product_id,
                    'reference' => $item->reference,

                    'unit_customer_final_price' => $item->unit_customer_final_price,
                    'discount_percent' => $item->discount_percent,
                    'unit_final_price' => $item->unit_final_price,
                    'unit_final_price_tax_inc' => $item->unit_final_price_tax_inc,

                    'total_tax_excl' => $item->total_tax_excl,
                    'total_tax_incl' => $item->total_tax_incl,

                    'tax_id' => $item->tax_id,
                    'sales_equalization' => $item->sales_equalization,

//                    'tax_rate' => $tax_rate,
                    'allow_tax' => '0',     // Tax not included
                    
                    'product_id' => $item->product_id,
                    'product_variation_id' => $item->combination_id,
                        );
        }

        // Wait a minute:

        $this->other['ot_shipping_address'] =  [];
        
        if ($order->invoicing_address_id != $order->shipping_address_id) 
        {
          // Add Shipping Address as a line in Order
          if ( Configuration::get('FSX_SHIPPING_ADDRESS_AS_LINE') > 0 )
          {
              //
              // Shipping Address Impersonator
              $address_name =  $addressDelivery->address1 . 
                              ($addressDelivery->address2 ? ' ' . $addressDelivery->address2 : '' ) . ',' .
                               $addressDelivery->postcode . ' ' .
                               $addressDelivery->city . ',' .

                               $addressDelivery->phone;

              $this->other['ot_shipping_address'] = array(
                          'id' => 0,
                          'reference' => 'ENTREGA EN',
                          'name' => $address_name,
                          'qty' => 1,

                          'unit_customer_final_price' => 0.0,
                          'discount_percent' => 0.0,
                          'unit_final_price' => 0.0,
                          'unit_final_price_tax_inc' => 0.0,

                          'total_tax_incl' => 0.0,
                          'total_tax_excl' => 0.0,

                          'tax_id' => Configuration::get('FSOL_IMPUESTO_DIRECTO_TIPO_4'),     // Exento
                          'sales_equalization' => 0,

      //                    'tax_rate' => $tax_rate,
                          'allow_tax' => '0'     // Tax not included
                      );
              
          }
        }

        // OK. Let's move on!
        return $this->run_status;
    }
   


    public function tell_run_status() {
      return $this->run_status;
    }

    public function customer_must_download() {
      return $this->customer_download;
    }

    public function delivery_is_new_address() {
      return ( $this->info['id_address_delivery'] != $this->info['id_address_invoice'] );
    }




    private function _fsol_customer_code( $abi_customer_id, $codbase = 0 ) 
    {
		$fsol_codcli = intval($codbase) + intval($abi_customer_id);
	    return $fsol_codcli;
    }

    private function _prepare_fsol_codcli() {

		// Recuperar el Código de Cliente FactuSol
		if ( $this->customer['csID'] && ( $this->customer['csID'] > 0 ) ) { 

      // No calculation needed

			// $this->logInfo(sprintf( 'El Cliente <span style="color: green; font-weight: bold">(%s) %s</span> del Pedido %s ya está en FactuSOL (%s). No se ha creado un fichero para descargarlo.', $this->customer['ID'], ($this->customer['company']?$this->customer['company']:$this->customer['name']), $this->info['orders_id'], $this->customer['csID'] ));
		} else {

			// Calculate Código de Cliente FactuSol

			switch ( $this->info['created_via'] ) {
				case 'webshop':
					# code...
					$codbase = Configuration::get('FSOL_WEB_CUSTOMER_CODE_BASE');;
					break;
				
				case 'manual':
					# code...
					$codbase = Configuration::get('FSOL_ABI_CUSTOMER_CODE_BASE');;
					break;
				
				default:
					# code...
					$codbase = 0;
					break;
			}

			$fsol_codcli = $this->_fsol_customer_code( $this->customer['ID'], $codbase );

			$this->customer['csID'] = $fsol_codcli;
			// $this->customer_download=true;
		}

		return $this->customer['csID'];
    }

    private function _fsol_customer_filename($abi_customer = '') {
      if ($abi_customer) $fsol_clifile = strtolower( str_replace("@", "_", $abi_customer) ) . '.txt';
      else               $fsol_clifile = "cliente_" . $this->customer['csID']               . '.txt';
      return $fsol_clifile;
    }

    private function _fsol_order_filename($abi_order_id) {
      $fsol_order_series=intval( Configuration::get('FSOL_SPCCFG') );

      $fsol_pedfile  = 'pedidofactusolweb' . strval($fsol_order_series*1000000 + intval( $abi_order_id )) . '.txt';
      return $fsol_pedfile;
    }



    private function _check_destination_folders() {
      $go_ahead=true;
      if (!is_writable( $this->dest_clientes )) { // ERROR!
	$this->logError(sprintf( 'No se pueden descargar Clientes. La carpeta destino no tiene permisos de escritura (%s).' , $this->dest_clientes ));
	$go_ahead=false;
      }
      if (!is_writable( $this->dest_pedidos )) { // ERROR!
	$this->logError(sprintf( 'No se pueden descargar Pedidos. La carpeta destino no tiene permisos de escritura (%s).' , $this->dest_pedidos ));
	$go_ahead=false;
      }
      return $go_ahead;
    }

//*
//* **********  Common functions END *********************************** */
//*
    

/* ********************************************************************************************* */   

    function export_order_customer( $output = 'file' ) {

    if ( Configuration::get('FSX_FORCE_CUSTOMERS_DOWNLOAD') > 0 ) $this->customer_download = true;

    if ( !$this->customer_download ) {
      
        $this->logInfo(sprintf( 'El Cliente <span style="color: green; font-weight: bold">(%s) %s</span> del Pedido %s ya está en FactuSOL (%s), y NO se ha creado un fichero para descargarlo.', $this->customer['ID'], ($this->customer['company']?$this->customer['company']:$this->customer['name']), $this->info['orders_id'], $this->customer['csID'] ));

        return ;
    }

    $this->_prepare_fsol_codcli();
        
    if ( !isset($this->info['orders_id']) || ($this->info['orders_id'] < 1)) { $this->run_status = false; }
    if ($this->run_status == false) return ;
    $err = 0;

        $fsol_clifile = $this->_fsol_customer_filename( $this->customer['email_address'] );
        if (file_exists( $this->dest_clientes . $fsol_clifile )) { // ERROR!
            $this->logError(sprintf( 'El fichero de Cliente <span style="color: green; font-weight: bold">%s</span> ya existe. El Pedido %s no se descargará.' , $fsol_clifile,  $this->info['orders_id']));
            // continue;
            $this->run_status = false;
            return ;
        }

        // Calculate Código de Cliente FactuSOL -> 
        $fsol_codcli = $this->customer['csID'];


    // Calculate Customer values
    $fsweb_user = array();

$fsweb_user['CUWCLI']  = $this->customer['customer_user'];  // onagrosan
$fsweb_user['CAWCLI']  = 'unknown';                     // nredondo
$fsweb_user['SNOMCFG'] = ($this->customer['company']?$this->customer['company']:$this->customer['name']);   // José I. 
$fsweb_user['SDOMCFG'] = $this->customer['street_address']; // Mar de Aral 7
$fsweb_user['SCPOCFG'] = $this->customer['postcode'];       // 28033
$fsweb_user['SPOBCFG'] = $this->customer['city'];       // Madrid
$fsweb_user['SPROCFG'] = $this->customer['state'];      // Madrid
$fsweb_user['SNIFCFG'] = $this->customer['vat_id'];     // 50811875e
$fsweb_user['STELCFG'] = $this->customer['telephone'];      // 913825704
$fsweb_user['SFAXCFG'] = $this->delivery['fax'];                    // (Fax)
$fsweb_user['SMOVCFG'] = $this->customer['mobile'];                 // 618646400
// 
$fsweb_user['SEMACFG'] = $this->customer['email_address'];  // noname@none.com
$fsweb_user['SPCOCFG'] = $this->customer['name'];       // Mari Complejines
$fsweb_user['SNENCFG'] = ($this->delivery['company']?$this->delivery['company']:$this->delivery['name']);   // Muelle 22
$fsweb_user['SDENCFG'] = $this->delivery['street_address']; // Polígano Coslada
$fsweb_user['SCPECFG'] = $this->delivery['postcode'];       // 28009
$fsweb_user['SPOECFG'] = $this->delivery['city'];       // Coslada
$fsweb_user['SPRECFG'] = $this->delivery['state'];      // Madrid
$fsweb_user['SPCECFG'] = $this->delivery['name'];       // Almacenero
$fsweb_user['STCECFG'] = $this->delivery['telephone']?:$this->customer['telephone'];       // 911234567
$fsweb_user['SFPACFG'] = '';                   // COD
$fsweb_user['Terminado'] = 'Alta Cliente';

// Según fsweb/confaltacliente.php
        //hay que crear el archivo con el usuario y los datos. y la sentencia SQL
        //recorro toda la matriz de campos rellenos
        $campos = '';
        $valores = '';
        $cadena = '';
        foreach($fsweb_user as $nombre_var => $valor_var) {
            $cadena .= $nombre_var . ":" . $valor_var . "\n";
            if($nombre_var != 'Terminado' and substr($nombre_var, (strlen($nombre_var)-3)) != 'CFG') {
                $campos .= $nombre_var . ',';
                $valores .= "'" . $valor_var . "',";
            }
        }
        //escribo el usuario en el archivo de texto.
        $cadena = $fsol_codcli . "\ \n" . $cadena;
        if ($output == 'file') {
            $fp = fopen( $this->dest_clientes .  $fsol_clifile,'w');
            $err = fwrite($fp, utf8_decode($cadena));
            fclose($fp);
        } else {
        	$err = 0;
        	abi_r($fsol_clifile);
        	abi_r($cadena);
        }

        if ($err != -1) {
            if ( $this->customer['is_new'] )
            {
                if ($output == 'file')
                $this->abi_order->customer->update(['reference_external' => $this->customer['csID']]);
            }
            
            $this->logInfo(sprintf( 'Se ha creado un fichero de Cliente <span style="color: green; font-weight: bold">(%s) %s</span> para el Pedido %s.', $fsol_codcli, $fsweb_user['SNOMCFG'], $this->info['orders_id'] ));
        } else {
            $this->run_status = false;
            $this->logError(sprintf( 'No se ha creado un fichero de Cliente <span style="color: green; font-weight: bold">(%s) %s</span> porque se ha producido un error al crear el archivo. El Pedido <b>%s</b> no se descargará.', $fsol_codcli, $fsweb_user['SNOMCFG'], $this->info['orders_id'] ));

        }
      
    }  //  export_order_customer() ENDS

/*  *******************************************************************************************************  */

    public function export_order( $output = 'file' ) {

    if ( !isset($this->info['orders_id']) || ($this->info['orders_id'] < 1)) { $this->run_status = false; }
    if ($this->run_status == false) return ;

// Calculate Order values
        $fsol_order_series=Configuration::get('FSOL_SPCCFG');
        $fsweb_order = array();
        $fsweb_order_products = array();
        $fsweb_order_footer = array();
        $net1pcl = 0;
        $net2pcl = 0;
        $net3pcl = 0;
        $net4pcl = 0;
        $iiva1pcl = 0;
        $iiva2pcl = 0;
        $iiva3pcl = 0;
        $iiva4pcl = 0;

        $irec1pcl = 0;
        $irec2pcl = 0;
        $irec3pcl = 0;
        $irec4pcl = 0;

// Según function escribearchivo($serie, $numero, $pagos)  de fsweb/confirmacion.php
	    $fsol_pedfile =  $this->_fsol_order_filename( $this->info['orders_id'] );
	    if(file_exists($this->dest_pedidos . $fsol_pedfile)) { // ERROR!  'Este pedido ya se finalizó anteriormente.'
	        $this->logError(sprintf( 'El fichero <span style="color: green; font-weight: bold">%s</span> ya existe. El Pedido <b>%s</b> no se ha descargado.', $fsol_pedfile, $this->info['orders_id'] ));
	        $this->run_status = false;
	        return ;
	    } 

// Product loop here

for ($i=0; $i<count($this->products); $i++) {  // Order Products loop STARTS
$products = $this->products[$i]; 

// Manage Product Description
// ^-- U Kidding?
$order_articulo_fsol = $products['name'];


// 
$fsol_tipo_iva = FSxTools::getTipoIVA($products['tax_id']);
if ($fsol_tipo_iva<0) { // ERROR!
    $this->logError(sprintf( 'El Producto <span style="color: green; font-weight: bold">(%s) %s</span> tiene un Impuesto <b>[%s%%]</b> que no se ha hallado correspondencia en FactuSOL. El Pedido <b>%s</b> no se descargará.', $products['id'], $products['name'], $products['tax_rate'], $this->info['orders_id'] ));
    $this->run_status = false;
    continue;
}

$fsol_has_rec = $products['sales_equalization'];

$fsweb_order_products[$i]['TIPLPC'] = $fsol_order_series; // :8
$fsweb_order_products[$i]['CODLPC'] = $this->info['orders_id']; // :6
$fsweb_order_products[$i]['POSLPC'] = $i+1; // :1
$fsweb_order_products[$i]['ARTLPC'] = $products['reference']; // :010FAGESF
$fsweb_order_products[$i]['DESLPC'] = $order_articulo_fsol; // :Fagodio Esforulante de 10x10

$fsweb_order_products[$i]['CANLPC'] = $products['qty']; // :1.0000
$fsweb_order_products[$i]['DT1LPC'] = $products['discount_percent']; // :2   Descuento
$fsweb_order_products[$i]['PRELPC'] = $products['unit_customer_final_price']; // :110.0000
$fsweb_order_products[$i]['TOTLPC'] = $products['total_tax_excl']; // :107.8000 (había descuento!)
$fsweb_order_products[$i]['IVALPC'] = $fsol_tipo_iva; // :0   Tipo de IVA 0,1,2,3 (16/7/4/0)
$fsweb_order_products[$i]['IINLPC'] = $products['allow_tax']; // :0   IVA NO incluido en el precio

$price_no_tax = $fsweb_order_products[$i]['TOTLPC'];
$net = 'net'  . ($fsol_tipo_iva+1) . 'pcl';
$iva = 'iiva' . ($fsol_tipo_iva+1) . 'pcl';
$$net += $price_no_tax;

$line_tax = $products['total_tax_incl'] - $products['total_tax_excl'];
if ($fsol_has_rec) {
    $rec = 'irec' . ($fsol_tipo_iva+1) . 'pcl';

    $piva = floatval( Configuration::get('FSOL_PIV' . ($fsol_tipo_iva+1) . 'CFG') );
    $prec = floatval( Configuration::get('FSOL_PRE' . ($fsol_tipo_iva+1) . 'CFG') );
    $riva = $piva / ($piva + $prec) ;
    $rrec = $prec / ($piva + $prec) ;

    $$iva += $line_tax * $riva;
    $$rec += $line_tax * $rrec;
} else {
    $$iva += $line_tax;
}

}  // Order Products loop ENDS

if ($this->run_status==false) return ;

//
// Portes ot_shipping STARTS
// 
if ( isset($this->other['ot_shipping']['name'])
      &&  ($this->other['ot_shipping']['total_tax_excl']>0)
      &&  ($this->other['ot_shipping']['total_tax_incl']  >0) ) {

// 
$fsol_tax_id = $this->other['ot_shipping']['tax_id'];  
$fsol_tipo_iva = FSxTools::getTipoIVA($fsol_tax_id);
if ($fsol_tipo_iva<0) { // ERROR!
    $this->logError(sprintf( 'El Coste de Envío del Pedido <span style="color: green; font-weight: bold">%s</span> tiene un Impuesto <b>[%s]</b> que no se ha hallado correspondencia en FactuSOL. El Pedido <b>%s</b> no se descargará.', $this->info['orders_id'], $fsol_tax_id, $this->info['orders_id'] ));
    $this->run_status = false;
    return ;
}

$fsol_has_rec = $this->other['ot_shipping']['sales_equalization'];

$price_no_tax = $this->other['ot_shipping']['total_tax_excl'];

$fsweb_order_products[$i]['TIPLPC'] = $fsol_order_series; // :8
$fsweb_order_products[$i]['CODLPC'] = $this->info['orders_id']; // :6
$fsweb_order_products[$i]['POSLPC'] = $i+1; // :1
$fsweb_order_products[$i]['ARTLPC'] = ''; // :010FAGESF
$fsweb_order_products[$i]['DESLPC'] = $this->other['ot_shipping']['name']; // :Fagodio Esforulante de 10x10
$fsweb_order_products[$i]['CANLPC'] = $this->other['ot_shipping']['qty']; // :1.0000
$fsweb_order_products[$i]['DT1LPC'] = '0'; // :2   Descuento
$fsweb_order_products[$i]['PRELPC'] = $price_no_tax; // :110.0000
$fsweb_order_products[$i]['TOTLPC'] = $price_no_tax; // :107.8000 (había descuento!)
$fsweb_order_products[$i]['IVALPC'] = $fsol_tipo_iva; // :0   Tipo de IVA 0,1,2 (16/7/4)
$fsweb_order_products[$i]['IINLPC'] = $this->other['ot_shipping']['allow_tax']; // :0   IVA NO incluido en el precio

// $price_no_tax = $this->other['ot_shipping']['value'];
$net = 'net'  . ($fsol_tipo_iva+1) . 'pcl';// echo "<br>".$net;
$iva = 'iiva' . ($fsol_tipo_iva+1) . 'pcl';// echo "<br>".$iva.$products['tax'];
$$net += $price_no_tax;

$line_tax = $this->other['ot_shipping']['total_tax_incl'] - $this->other['ot_shipping']['total_tax_excl'];
if ($fsol_has_rec) {
    $rec = 'irec' . ($fsol_tipo_iva+1) . 'pcl';

    $piva = floatval( Configuration::get('FSOL_PIV' . ($fsol_tipo_iva+1) . 'CFG') );
    $prec = floatval( Configuration::get('FSOL_PRE' . ($fsol_tipo_iva+1) . 'CFG') );
    $riva = $piva / ($piva + $prec) ;
    $rrec = $prec / ($piva + $prec) ;

    $$iva += $line_tax * $riva;
    $$rec += $line_tax * $rrec;
} else {
    $$iva += $line_tax;
}

$i += 1;  // Ready for next item (if any)
}
//  
// Portes ot_shipping ENDS
//


//
// Delivery Address ot_shipping_address STARTS
// 
if ( isset($this->other['ot_shipping_address']['name']) ) {

// 
$fsol_tax_id = $this->other['ot_shipping_address']['tax_id'];  
$fsol_tipo_iva = FSxTools::getTipoIVA($fsol_tax_id);
if ($fsol_tipo_iva<0) { // ERROR!
    $this->logError(sprintf( 'El Coste de Envío del Pedido <span style="color: green; font-weight: bold">%s</span> tiene un Impuesto <b>[%s]</b> que no se ha hallado correspondencia en FactuSOL. El Pedido <b>%s</b> no se descargará.', $this->info['orders_id'], $fsol_tax_id, $this->info['orders_id'] ));
    $this->run_status = false;
    return ;
}

$fsol_has_rec = $this->other['ot_shipping_address']['sales_equalization'];

$price_no_tax = $this->other['ot_shipping_address']['total_tax_excl'];

$fsweb_order_products[$i]['TIPLPC'] = $fsol_order_series; // :8
$fsweb_order_products[$i]['CODLPC'] = $this->info['orders_id']; // :6
$fsweb_order_products[$i]['POSLPC'] = $i+1; // :1
$fsweb_order_products[$i]['ARTLPC'] = ''; // :010FAGESF
$fsweb_order_products[$i]['DESLPC'] = $this->other['ot_shipping_address']['name']; // :Fagodio Esforulante de 10x10
$fsweb_order_products[$i]['CANLPC'] = $this->other['ot_shipping_address']['qty']; // :1.0000
$fsweb_order_products[$i]['DT1LPC'] = '0'; // :2   Descuento
$fsweb_order_products[$i]['PRELPC'] = $price_no_tax; // :110.0000
$fsweb_order_products[$i]['TOTLPC'] = $price_no_tax; // :107.8000 (había descuento!)
$fsweb_order_products[$i]['IVALPC'] = $fsol_tipo_iva; // :0   Tipo de IVA 0,1,2 (16/7/4)
$fsweb_order_products[$i]['IINLPC'] = $this->other['ot_shipping_address']['allow_tax']; // :0   IVA NO incluido en el precio

// $price_no_tax = $this->other['ot_shipping_address']['value'];
$net = 'net'  . ($fsol_tipo_iva+1) . 'pcl';// echo "<br>".$net;
$iva = 'iiva' . ($fsol_tipo_iva+1) . 'pcl';// echo "<br>".$iva.$products['tax'];
$$net += $price_no_tax;

$line_tax = $this->other['ot_shipping_address']['total_tax_incl'] - $this->other['ot_shipping_address']['total_tax_excl'];
if ($fsol_has_rec) {
    $rec = 'irec' . ($fsol_tipo_iva+1) . 'pcl';

    $piva = floatval( Configuration::get('FSOL_PIV' . ($fsol_tipo_iva+1) . 'CFG') );
    $prec = floatval( Configuration::get('FSOL_PRE' . ($fsol_tipo_iva+1) . 'CFG') );
    $riva = $piva / ($piva + $prec) ;
    $rrec = $prec / ($piva + $prec) ;

    $$iva += $line_tax * $riva;
    $$rec += $line_tax * $rrec;
} else {
    $$iva += $line_tax;
}

$i += 1;  // Ready for next item (if any)
}
//  
// Delivery Address ot_shipping_address ENDS
//



// Order body here:
$order_delivery_flag = $this->delivery_is_new_address();

$fsweb_order['TIPPCL'] = $fsol_order_series; // :8   Serie de Pedidos
$fsweb_order['CODPCL'] = $this->info['orders_id']; // :26   Hasta 6 posiciones
$fsweb_order['REFPCL'] = $this->info['created_via'] == 'webshop' ? $this->info['reference'] : 'ABI '.$this->info['orders_id']; // : Referencia varchar(12)
$fsweb_order['FECPCL'] = substr($this->info['date_purchased'], 0, 10).' 00:00:00'; // :2008-11-25 00:00:00
$fsweb_order['AGEPCL'] = '0'; // :0   Agente
$fsweb_order['CLIPCL'] = $this->customer['csID']; // :2   Cliente FSOL
$fsweb_order['DIRPCL'] = '1'; // ( $order_delivery_flag ? '0' : '1' ); // :1
$fsweb_order['TIVPCL'] = '0'; // :0   Con IVA
$fsweb_order['REQPCL'] = '0'; // :0   Sin RE
$fsweb_order['ALMPCL'] = ''; // :

$fsweb_order['NET1PCL'] = $net1pcl; // :107.8000
$fsweb_order['NET2PCL'] = $net2pcl; // :196.0000
$fsweb_order['NET3PCL'] = $net3pcl; // :49.0000
$fsweb_order['NET4PCL'] = $net4pcl; // :0.0000

$fsweb_order['PDTO1PCL'] = '0'; // :0.0000
$fsweb_order['PDTO2PCL'] = '0'; // :0.0000
$fsweb_order['PDTO3PCL'] = '0'; // :0.0000
// $fsweb_order['PDTO4PCL'] = '0'; // :0.0000
$fsweb_order['IDTO1PCL'] = '0'; // :0.0000
$fsweb_order['IDTO2PCL'] = '0'; // :0.0000
$fsweb_order['IDTO3PCL'] = '0'; // :0.0000
// $fsweb_order['IDTO4PCL'] = '0'; // :0.0000
$fsweb_order['PPPA1PCL'] = '0'; // :0.0000
$fsweb_order['PPPA2PCL'] = '0'; // :0.0000
$fsweb_order['PPPA3PCL'] = '0'; // :0.0000
// $fsweb_order['PPPA4PCL'] = '0'; // :0.0000
$fsweb_order['IPPA1PCL'] = '0'; // :0.0000
$fsweb_order['IPPA2PCL'] = '0'; // :0.0000
$fsweb_order['IPPA3PCL'] = '0'; // :0.0000
// $fsweb_order['IPPA4PCL'] = '0'; // :0.0000
$fsweb_order['PFIN1PCL'] = '0'; // :0.0000
$fsweb_order['PFIN2PCL'] = '0'; // :0.0000
$fsweb_order['PFIN3PCL'] = '0'; // :0.0000
// $fsweb_order['PFIN4PCL'] = '0'; // :0.0000
$fsweb_order['IFIN1PCL'] = '0'; // :0.0000
$fsweb_order['IFIN2PCL'] = '0'; // :0.0000
$fsweb_order['IFIN3PCL'] = '0'; // :0.0000
// $fsweb_order['IFIN4PCL'] = '0'; // :0.0000

$fsweb_order['BAS1PCL'] = $net1pcl; // :107.8000
$fsweb_order['BAS2PCL'] = $net2pcl; // :196.0000
$fsweb_order['BAS3PCL'] = $net3pcl; // :49.0000
// $fsweb_order['BAS4PCL'] = $net4pcl; // :0.0000
$fsweb_order['PIVA1PCL'] = Configuration::get('FSOL_PIV1CFG'); // :16.0000
$fsweb_order['PIVA2PCL'] = Configuration::get('FSOL_PIV2CFG'); // :7.0000
$fsweb_order['PIVA3PCL'] = Configuration::get('FSOL_PIV3CFG'); // :4.0000
// $fsweb_order['PIVA4PCL'] = 0.0000;       // :Exento!
$fsweb_order['IIVA1PCL'] = $iiva1pcl; // :17.2500
$fsweb_order['IIVA2PCL'] = $iiva2pcl; // :13.7200
$fsweb_order['IIVA3PCL'] = $iiva3pcl; // :1.9600
// $fsweb_order['IIVA4PCL'] = $iiva4pcl; // :0.0 Always!

$fsweb_order['PREC1PCL'] = Configuration::get('FSOL_PRE1CFG'); // :4.0000
$fsweb_order['PREC2PCL'] = Configuration::get('FSOL_PRE2CFG'); // :1.0000
$fsweb_order['PREC3PCL'] = Configuration::get('FSOL_PRE3CFG'); // :0.5000
$fsweb_order['IREC1PCL'] = $irec1pcl; // :0.0000
$fsweb_order['IREC2PCL'] = $irec2pcl; // :0.0000
$fsweb_order['IREC3PCL'] = $irec3pcl; // :0.0000
// $fsweb_order['IREC4PCL'] = $irec4pcl; // :0.0000 Always!

$fsweb_order['TOTPCL']  = $fsweb_order['BAS1PCL']  + $fsweb_order['BAS2PCL']  + $fsweb_order['BAS3PCL']  + $net4pcl; // :385.7300
$fsweb_order['TOTPCL'] += $fsweb_order['IIVA1PCL'] + $fsweb_order['IIVA2PCL'] + $fsweb_order['IIVA3PCL'] + 0.0;
$fsweb_order['TOTPCL'] += $fsweb_order['IREC1PCL'] + $fsweb_order['IREC2PCL'] + $fsweb_order['IREC3PCL'] + 0.0;

$fsol_forma_pago = FSxTools::getCodigoFormaDePago( $this->info['payment_method'] );
if ( !$fsol_forma_pago ) $this->logWarning(sprintf( 'La Forma de Pago del Pedido <span style="color: green; font-weight: bold">%s</span> (%s) no se ha hallado correspondencia en FactuSOL. Deberá ponerla manualmente en FactuSOL.', $this->info['orders_id'], $this->info['payment_method']));
$fsweb_order['FOPPCL'] = $fsol_forma_pago; // :COD

$fsweb_order['OB1PCL'] = substr( str_replace(array("\n", "\r"), '', $this->info['comments'] ), 0, 50); // :Es DT1CLI un 2%   varchar(50)
$fsweb_order['OB2PCL'] = $order_delivery_flag ? 'Compruebe la Dirección de Entrega.' : '' ; // :Dirección "1"   varchar(50)
$fsweb_order['PPOPCL'] = ''; // :   Pedido por  varchar(40)
$fsweb_order['ESTPCL'] = '0'; // :0   Estado: Pendiente

// Tax exempt
$fsweb_order['NET4PCL'] = $net4pcl; // :0.0000
$fsweb_order['PDTO4PCL'] = '0'; // :0.0000
$fsweb_order['IDTO4PCL'] = '0'; // :0.0000
$fsweb_order['PPP4PCL'] = '0'; // :0.0000
$fsweb_order['IPP4PCL'] = '0'; // :0.0000
$fsweb_order['PFIN4PCL'] = '0'; // :0.0000
$fsweb_order['IFIN4PCL'] = '0'; // :0.0000
$fsweb_order['BAS4PCL'] = $net4pcl; // :0.0000

$fsweb_order_footer['DOCPAGO'] = ''; // :
// $fsweb_order_footer['STATUS'] = 'OK'; // :OK  OJO!! no añade "\n"

// 
// Write to file 
// Según function escribearchivo($serie, $numero, $pagos)  de fsweb/confirmacion.php

$order='';
$products='';
$footer='';

        foreach($fsweb_order as $nombre_var => $valor_var) {
            $order .= $nombre_var . ":" . $valor_var . "\n";
        }
        for ($i=0; $i<count($fsweb_order_products); $i++) {
          foreach($fsweb_order_products[$i] as $nombre_var => $valor_var) {
              $products .= $nombre_var . ":" . $valor_var . "\n";
          }
        }
        foreach($fsweb_order_footer as $nombre_var => $valor_var) {
            $footer .= $nombre_var . ":" . $valor_var . "\n";
        }

        $linea  = $order.$products.$footer;
        $linea .= 'STATUS:OK';

        if ($output == 'file') {
            $fp = fopen($this->dest_pedidos . $fsol_pedfile, 'w');

            $err = fwrite($fp, utf8_decode($linea));
            fclose($fp);
        } else {
          $err = 0;
          abi_r($fsol_pedfile);
          abi_r($linea);
        }

        if ($err != -1) {
            if ($output == 'file')
            {    
                $this->abi_order->export_date = \Carbon\Carbon::now();
                $this->abi_order->save();
            }

            $this->logInfo(sprintf( 'El Pedido <b>%s</b> se ha descargado correctamente.', $this->info['orders_id'] ));
        }else{ 
            $this->run_status = false;
            $this->logError(sprintf( 'No se ha podido crear un fichero de Pedido %s. El Pedido <b>%s</b> no se ha descargado.', $this->info['orders_id'] )); 
        }  




	}	//   public function export_order() 


/*  *******************************************************************************************************  */

    

/* ********************************************************************************************* */   

/* ********************************************************************************************* */   


 
    public static function processOrder( $order_id = null ) 
    {
        $exporter = new static($order_id);
        if ( !$exporter->tell_run_status() ) 
        {
        	$exporter->logMessage("ERROR", l('Order number <span class="log-showoff-format">{oid}</span> could not be loaded.'), ['oid' => $order_id]);

        	return $exporter;
        }

        // Mambo!
        $output = 'screen';
        $output = 'file';

        $exporter->export_order_customer( $output );
        if ( !$exporter->tell_run_status() ) 
        {
          $exporter->logMessage("ERROR", l('El Pedido <span class="log-showoff-format">:oid</span> no se descargará.'), ['oid' => $order_id]);

          return $exporter;
        }

        $exporter->export_order( $output );
        if ( !$exporter->tell_run_status() ) 
        {
          $exporter->logMessage("ERROR", l('Order number <span class="log-showoff-format">{oid}</span> could not be loaded.'), ['oid' => $order_id]);

          // Delete Customer file? -> No! Customer order is in place no matter import errors...

          return $exporter;
        }

        // die();

        // So far, so good! Bye, then!
        return $exporter;
    }

    
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    
    // public function importCustomer()


/* ********************************************************************************************* */   



}