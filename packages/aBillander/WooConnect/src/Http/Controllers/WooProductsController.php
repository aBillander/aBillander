<?php 

namespace aBillander\WooConnect\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;

use WooCommerce;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;

use \App\Product;
use \aBillander\WooConnect\WooProduct;

class WooProductsController extends Controller {


   protected $product;

   public function __construct(WooProduct $product)
   {
         $this->product = $product;
   }

	/**
	 * Display a listing of the resource.
	 * GET /wproducts
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		// 
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /wproducts/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /wproducts
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /wproducts/{id}
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
	 * GET /wproducts/{id}/edit
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
	 * PUT /wproducts/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /wproducts/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        //
	}


/* ********************************************************************************************* */   


	
	public function importProductList( $list )
	{
        // 
	}

	public function importProducts( Request $request )
	{

        return $this->importProductList( $request->input('wproducts', []) );
	} 


	public function import($id)
	{
		
        return $this->importProductList( [$id] );
	}


	public function fetch($id)
	{
		$product = WooProduct::fetch( $id );

		abi_r($product, true);
	}


	public function importProductImages()
	{
		// Route::get('wproducts/importProductImages'

		// En el Servidor se recuperaron las imagnes de la WooTienda, pero dió un gateway timeout => ¿Se podría hacer por partes (chuncks) para que no pase esto?

		// Products
		$list = Product::select('id', 'reference', 'name')->where('reference', '!=', '')->get();
		// $list = Product::select('id', 'reference', 'name')->where('reference', '4003')->get();		// ->where('reference', '!=', '');	// ->pluck('reference');

		foreach( $list as $p )
		{
			//
			$sku = $p->reference;

			$wp = WooProduct::fetch( $sku );

			// abi_r($wp, true);

			$images = $wp['images'] ?? [];

			if ( $images && count($images) )
			{
				// Initialize with something to show
				$img_src  = $images[0]['src']  ?? '';
				$img_name = $images[0]['name'] ?? '';
				$img_alt  = $images[0]['alt']  ?? '';

				foreach ($images as $image)
				{
					if ($image['position'] == 0)
					{
						$img_src  = $image['src'];
						$img_name = $image['name'];
						$img_alt  = $image['alt'];
						break;
					}
				}

				// Make the magic
				if( $img_src )
				{

			        $image = \App\Image::createForProductFromUrl($img_src, ['caption' => $p->name]);
					
			        $p->images()->save($image);

			        if ( $p->images()->count() == 1 )
			        	$p->setFeaturedImage( $image );

				}

			} else {

				$img_src = 'https://www.laextranatural.com/wp-content/plugins/woocommerce/assets/images/placeholder.png';
				
			}

			// abi_r($sku.' :: '.$img_src);
		}

		// die();

        return redirect('products')
                ->with('success', l('Some Product Images has been retrieved from WooCommerce Shop.'));
	}

}


/* ********************************************************************************************* */
