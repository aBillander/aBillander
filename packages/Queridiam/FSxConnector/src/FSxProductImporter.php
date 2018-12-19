<?php 

namespace Queridiam\FSxConnector;

use App\Category;
use App\Product;
use App\Combination;
use App\Tax;

use App\Configuration;
use \App\StockMovement;

use Queridiam\FSxConnector\FSxTools;
use Queridiam\FSxConnector\Seccion;
use Queridiam\FSxConnector\Familia;
use Queridiam\FSxConnector\Articulo;
use Queridiam\FSxConnector\Stock;
use Queridiam\FSxConnector\Tarifa;

// use \aBillander\WooConnect\WooOrder;

use App\Traits\LoggableTrait;

class FSxProductImporter {

    use LoggableTrait;

    
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */
    
    public function __construct()
    {
        // Start Logger
        $this->logger = self::loggerSetup( 'Actualizar el Catálogo desde la Base de Datos de FactuSOLWeb' );

    }

    
    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    
    public static function process( Request $request = null )
    {
        $processor = new static();

        $processor->logInfo('Se ctualizará el Catálogo de aBillander desde la Base de Datos de FactuSOLWeb.');

        // Actualizar el Catálogo
        if ( Configuration::isTrue('FSX_LOAD_ARTICULOS_STOCK_ALL') || Configuration::isTrue('FSX_LOAD_ARTICULOS_PRIZE_ALL') )
        	$processor->processCatalogoUpdate();

        // Cargar el Catálogo (Nuevos)
        if ( Configuration::isTrue('FSX_LOAD_ARTICULOS') )
        	$processor->processCatalogo();

        // Desactivar no encontrados
        if ( Configuration::isTrue('FSX_PROD_ABI_ONLY_DEACTIVATE') )
            $processor->processNotFound();

        // So far, so GOOD!
        Configuration::updateValue('FSX_CATALOGUE_LAST_RUN_DATE', \Carbon\Carbon::now()->format('Y-m-d H:i:s'));

        return true;
    } 


/* ********************************************************************************************* */

    
    public function processCatalogoUpdate()
    {
        // 
        $this->processSecciones();

        $this->processFamilias();

        // 
        $art_query = Articulo::whereHas('product')->with('stock')->get();

        if ($arts = $art_query->count()) {

        	$this->logInfo('Comienza la actualización de Artículos.');

        	foreach ($art_query AS $articulo) {

    			$data = [];

    			if ( Configuration::isTrue('FSX_LOAD_ARTICULOS_STOCK_ALL') ) {
    				//
/*
                    $data += [
                        'quantity_onhand' => $articulo->USTART > 0 ?: 0.0, 
                    ];

                    // Ahhh! 'quantity_onhand' is NOT fillable
                    $articulo->product->quantity_onhand = $articulo->USTART > 0 ? $articulo->USTART : 0.0;

                    $p = $articulo->product->update( $data );
*/
                    // Prepare stock movement
                    $currency =  \App\Context::getContext()->currency;
                    $product = $articulo->product;
                    $datem = \Carbon\Carbon::now()->toDateString();

                    $data = [
                        'date' => $datem,
                        'document_reference' => 'FSWeb '.$datem,
//                        'price' => ,
                        'currency_id' => $currency->id,
                        'conversion_rate' => $currency->conversion_rate,
                        'quantity' => $articulo->USTART > 0 ? $articulo->USTART : 0.0,
//                        'notes' => ,
                        'product_id' => $product->id,
                        'combination_id' => null,
                        'reference' => $product->reference,
                        'name' => $product->name,
                        'warehouse_id' => Configuration::get('FSX_FSOL_AUSCFG_PEER'),
//                        'warehouse_counterpart_id' => ,
                        'movement_type_id' => StockMovement::ADJUSTMENT,
                    ];

                    $stockmovement = StockMovement::createAndProcess( $data );
    			}

    			if ( Configuration::isTrue('FSX_LOAD_ARTICULOS_PRIZE_ALL') ) {
    				//
    				$price = $articulo->precio();

    				$data += [
	        			'price' => $price->getPrice(), 
	        			'price_tax_inc' => $price->getPriceWithTax(), 
	        		];

	        		$p = $articulo->product->update( $data );
    			}

//                if ( count($data) )
//                    $p = $articulo->product->update( $data );

        	}

        	$this->logInfo('Se han actualizado :nbr Producto(s).', ['nbr' => $arts]);

        } else
        	$this->logInfo('No hay Productos para actualizar.');
    }

    public function processCatalogo()
    {
        // 
        $this->processSecciones();

        $this->processFamilias();

        $this->processArticulos();


    }

    public function processSecciones()
    {
        // 
        if ( Configuration::isTrue('FSX_LOAD_FAMILIAS_TO_ROOT') 
          || Configuration::isFalse('ALLOW_PRODUCT_SUBCATEGORIES') 
        ) {
				// Nothing to do here ;)
        		$this->logInfo('No se cargan las Secciones por la configuración actual.');
        		return ;
        }

        $sec_query = Seccion::doesntHave('category')->get();

        if ($secs = $sec_query->count()) {

        	$this->logInfo('Comienza la carga de Secciones.');

        	foreach ($sec_query AS $seccion) {

        		$data = [
        			'name' => $seccion->DESSEC,
 //       			'position',
 //       			'publish_to_web',
 //       			'webshop_id',
        			'reference_external' => $seccion->CODSEC,
 //       			'is_root',
 //       			'active',
//        			'parent_id'
        		];

        		Category::create( $data );

        	}

        	$this->logInfo('Se han creado :nbr Categoría(s) nueva(s).', ['nbr' => $secs]);

        } else
        	$this->logInfo('No hay Secciones nuevas.');
    }

    public function processFamilias()
    {
        // 
        $fam_query = Familia::doesntHave('category')->with('seccion.category')->get();

        if ($fams = $fam_query->count()) {

        	$this->logInfo('Comienza la carga de Familias.');

        	foreach ($fam_query AS $familia) {

        		$data = [
        			'name' => $familia->DESFAM,
 //       			'position',
 //       			'publish_to_web',
 //       			'webshop_id',
        			'reference_external' => $familia->CODFAM,
 //       			'is_root',
 //       			'active',
//        			'parent_id'
        		];

        		if ( Configuration::isTrue('ALLOW_PRODUCT_SUBCATEGORIES') )
        			if ($familia->seccion->category)
        				$data['parent_id'] = $familia->seccion->category->id;
        			else
        				$this->logWarning('La Familia [:codfam] :desfam se ha cargado en la raíz del Catálogo porque no existe la Categoría padre.', ['codfam' => $familia->CODFAM, 'desfam' => $familia->DESFAM,]);

        		Category::create( $data );

        	}

        	$this->logInfo('Se han creado :nbr Categoría(s) nueva(s).', ['nbr' => $fams]);

        } else
        	$this->logInfo('No hay Familias nuevas.');
    }

    public function processArticulos()
    {
        // 
        $art_query = Articulo::doesntHave('product')->with('familia.category')->with('stock')->get();

        if ($arts = $art_query->count()) {

        	$this->logInfo('Comienza la carga de Artículos.');

            $measure_unit = \App\MeasureUnit::find( Configuration::get('DEF_MEASURE_UNIT_FOR_PRODUCTS') );

        	foreach ($art_query AS $articulo) {

    			// if ($articulo->CODART != 'BURGUER002') continue;

    			$data = [];

    			if ($articulo->familia->category)
    				$category_id = $articulo->familia->category->id;
    			else {
    			    $this->logError('El Artículo [:codart] :desart NO se ha cargado porque no existe la Categoría padre.', ['codart' => $articulo->CODART, 'desart' => $articulo->DESART,]);

    			    continue;
    			}

                // Manufacturer
                $manufacturer = \App\Manufacturer::firstOrCreate(array('name' => $articulo->CP1ART));

    			$price = $articulo->precio();

        		$data = [
        			'product_type' => 'simple', 
        			'procurement_type' => 'purchase', 
        			'name' => $articulo->DESART, 
        			'reference' => $articulo->CODART, 
        			'ean13' => $articulo->EANART, 
        			'description' => $articulo->DEWART,
        			'description_short' => '',

        			'quantity_decimal_places' => $measure_unit->decimalPlaces,
 //       			'manufacturing_batch_size' => '',

        			'quantity_onhand' => $articulo->USTART > 0 ?: 0.0, 
//        			'quantity_onorder' => '', 
//        			'quantity_allocated' => '', 
//        			'quantity_onorder_mfg' => '', 
//        			'quantity_allocated_mfg' => '', 
        			'reorder_point' => $articulo->stock->MINSTO, 
        			'maximum_stock' => $articulo->stock->MAXSTO, 

        			'price' => $price->getPrice(), 
        			'price_tax_inc' => $price->getPriceWithTax(), 
//        			'last_purchase_price' => '', 
//        			'cost_price' => '', 
//        			'cost_average' => '', 
//        			'supplier_reference' => '', 
//        			'supply_lead_time' => '',

/*        			'location' => '', 
        			'width' => '', 
        			'height' => '', 
        			'depth' => '', 
        			'weight' => '', 
*/
        			'notes' => '',
        			'stock_control' => 1,
//        			'phantom_assembly' => '',
//        			'publish_to_web' => '',
//        			'blocked' => '',
        			'active' => Configuration::get('FSX_LOAD_ARTICULOS_ACTIVE'),

        			'tax_id' => FSxTools::translate_tivart($articulo->TIVART),
        			'measure_unit_id' => $measure_unit->id,
        			'category_id' => $category_id,
//        			'main_supplier_id' => '',
                    'manufacturer_id' => $manufacturer->id,
//        			'work_center_id' => '',
//        			'route_notes' => '',
        		];

        		$p = Product::create( $data );

        		// Load image...
                if ($articulo->IMGART)
                {
                    try {

                        $img_path = Configuration::get('FSOL_CBDCFG').Configuration::get('FSOL_CIACFG').$articulo->IMGART;

                        $image = \App\Image::createForProductFromPath($img_path, ['caption' => $p->name, 'is_featured'=> 1]);
                        
                        if ( $image )
                            $p->images()->save($image);
                        else
                            $this->logError('El Artículo [:codart] :desart NO se ha cpodido cargar la Imagen (:img_path), porque: :img_error ', ['codart' => $articulo->CODART, 'desart' => $articulo->DESART, 'img_path' => $img_path, 'img_error' => 'No se encontró la imagen '.$articulo->IMGART]);
                        
                    } catch (\Exception $e) {

                        $this->logError('El Artículo [:codart] :desart NO se ha cpodido cargar la Imagen (:img_path), porque: :img_error ', ['codart' => $articulo->CODART, 'desart' => $articulo->DESART, 'img_path' => $img_path, 'img_error' => $e->getMessage()]);                        
                    }
                }

        		// ToDo: Set Stock as Stock Count (stock adjustment)

        	}

        	$this->logInfo('Se han creado :nbr Producto(s) nuevo(s).', ['nbr' => $arts]);

        } else
        	$this->logInfo('No hay Artículos nuevos.');
    }


    public function processNotFound()
    {
        // 
        $art_query = FSxProduct::doesntHave('articulo')->get();

        if ($arts = $art_query->count()) {

            $this->logInfo('Comienza la desactivación de Productos.');

            $i=0;
            foreach ($art_query AS $product) {

                if (!$product->active) continue;
                
                $product->update(['active' => 0]);

                $this->logWarning('Se ha desactivado el Producto: (:id) [:ref] :name', ['id' => $product->id, 'ref' => $product->ref, 'name' => $product->name, ]);

                $i++;
            }

            $this->logInfo('Se han desactivado :nbr Producto(s) de :tot no encontrado(s).', ['nbr' => $i, 'tot' => $arts]);

        } else
            $this->logInfo('No hay Productos para desactivar.');
    }

}


class FSxProduct extends Product
{
    protected $table = 'products';

    protected $guarded = ['id'];

    protected $primaryKey = 'id';

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function articulo()
    {
        return $this->hasOne(Articulo::class, 'CODART', 'reference');
    }
    

}