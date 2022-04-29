<?php 

namespace App\Http\Controllers\SalesRepCenter;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Configuration;
use App\Models\Image;
use App\Models\SalesRepUser;
use App\Models\SalesRep;
use Illuminate\Support\Facades\Auth;

use App\Models\Category;
use App\Models\Product;

class AbsrcCatalogueController extends Controller
{

   protected $category;
   protected $product;

   public function __construct(Category $category, Product $product)
   {
        $this->middleware('auth:salesrep');

        $this->category = $category;
        $this->product = $product;
   }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
    	$salesrep_user = Auth::user();
		
		$category_id = $request->input('category_id', 0);
		// Not needed: $request->merge( ['category_id' => $category_id] );

		$parentId=0;
		$breadcrumb = [];

		$categories = $this->category
			->with('children')
//			->withCount('products')
			->where('parent_id', '=', intval($parentId))
			->orderBy('name', 'asc')->get();

		if ($category_id>0 && !$request->input('search_status', 0)) {
			//
			// abi_r($categories, true);

			$category = $categories->search(function ($item, $key) use ($category_id) {
			    
			    $cat = $item->children;

			    $c = $cat->search(function ($item, $key) use ($category_id) {
				    // abi_r($item->id.' - '.$category_id);

				    return $item->id == $category_id;
				});

			    // Found?
			    return $c !== false;
			});

			$parent = $categories->slice($category, 1)->first();
			$child = $parent->children->where('id', $category_id)->first();

			$breadcrumb = [$parent, $child];

			// abi_r($parent->name.' / '.$child->name, true);
		}

		$products = null;

		if ( $salesrep_user ) {
                $products = $this->product
                                      ->filter( $request->all() )
                                      ->Manufacturer( $request->input( 'manufacturer_id', 0) )
         //                             ->with('measureunit')
        							  ->with('combinations')                                  
         //                             ->with('category')
         //                             ->with('tax')
	                                  ->IsSaleable()
	                                  ->IsAvailable()
//	                                  ->qualifyForCustomer( $customer_user->customer_id, $customer_user->customer->currency->id)
                                      ->IsActive()
                                      ->orderBy('reference', 'asc');

                // abi_toSQL($products);

                                    //  abi_r($products->get());

                $products = $products->paginate( Configuration::get('ABSRC_ITEMS_PERPAGE') );

                $products->setPath('catalogue');     // Customize the URI used by the paginator
        }
        
        return view('absrc.catalogue.index', compact('category_id', 'categories', 'products', 'breadcrumb'));
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
	public function store()
	{
		//
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
	public function edit(Request $request)
	{
		// Get logged in user

		return 'OK';

		$back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;
		
		$payment = null;
		
		return view('customer_vouchers.edit', compact('payment', 'back_route'));
	}

	/**
	 * Update the specified resource in storage.
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
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


	/**
	 * Display a listing of the resource :: New Products.
	 *
	 * @return Response
	 */
	public function newProducts(Request $request)
	{
    	if( Configuration::isFalse('ABCC_ENABLE_NEW_PRODUCTS') )
    		return $this->index($request);


    	$customer_user = Auth::user();
		
		$category_id = $request->input('category_id', 0);
		// Not needed: $request->merge( ['category_id' => $category_id] );

		$parentId=0;
		$breadcrumb = [];

		$categories = $this->category
			->with('children')
//			->withCount('products')
			->where('parent_id', '=', intval($parentId))
			->orderBy('name', 'asc')->get();

		if ($category_id>0 && !$request->input('search_status', 0)) {
			//
			// abi_r($categories, true);

			$category = $categories->search(function ($item, $key) use ($category_id) {
			    
			    $cat = $item->children;

			    $c = $cat->search(function ($item, $key) use ($category_id) {
				    // abi_r($item->id.' - '.$category_id);

				    return $item->id == $category_id;
				});

			    // Found?
			    return $c !== false;
			});

			$parent = $categories->slice($category, 1)->first();
			$child = $parent->children->where('id', $category_id)->first();

			$breadcrumb = [$parent, $child];

			// abi_r($parent->name.' / '.$child->name, true);
		}

		$products = null;

		if ( $customer_user ) {
                $products = $this->product
//                                      ->filter( $request->all() )
//                                      ->Manufacturer( $request->input( 'manufacturer_id', 0) )
         //                             ->with('measureunit')
        							  ->with('combinations')                                  
         //                             ->with('category')
         //                             ->with('tax')
	                                  ->IsSaleable()
	                                  ->qualifyForCustomer( $customer_user->customer_id, $customer_user->customer->currency->id)
                                      ->IsActive()
                                      ->IsNew()
                                      ->orderBy('reference', 'asc');

                $products = $products->paginate( Configuration::get('ABCC_ITEMS_PERPAGE') );

                $products->setPath('new');     // Customize the URI used by the paginator
        }        
        return view('abcc.catalogue.new_products', compact('category_id', 'categories', 'products', 'breadcrumb'));
	}


    public function getProduct($i, Request $request)
    {
        $salesrep_user = Auth::user();

        $product = $this->product
                              ->with('images')
                              ->IsSaleable()
                              ->IsAvailable()
 //                             ->qualifyForCustomer( $customer->id, $customer->currency_id)
                              ->IsActive()
                              ->find($i);
        
        // abi_r($product, true);
        $images = $product->images;
        // ($product->images, true);
        $img = $product->getFeaturedImage();

        if ( $images->count() == 0 )
        {	
        	$img->is_featured = 1;
            $images->push($img);
        }

        $carousel = '';

        $active = false;
        foreach ($images as $k => $image) {
        	// code...
        	$flaf = '';
        	if ( !$active && $image->is_featured )
        	{
        		$active = true;
        		$flaf = 'active';
        	}
        	$src = \URL::to( Image::pathProducts() . $image->getImageFolder() . $image->filename . '-large_default' . '.' . $image->extension );
        	$alt = "(".$image->filename.") ".$image->caption;
        	$caption = "(".$image->filename.") ".$image->caption;

        	$carousel .= view('absrc.catalogue._modal_view_product_image_chunck', compact('flaf', 'src', 'alt', 'caption'));
        }

        $data = [];

        if ($product)
        {
        	$data = [
        		'title' => $product->name,
        		'content' => nl2p($product->description_short) . '<br />' . nl2p($product->description),
        		'href' => \URL::to( Image::pathProducts() . $img->getImageFolder() . $img->filename . '-large_default' . '.' . $img->extension ),
        		'caption' => "(".$img->filename.") ".$img->caption,

        		'carousel' => $carousel,

        		'nbr_images' => $images->count(),
        	];
        }

        return response()->json( $data );
    }
}
