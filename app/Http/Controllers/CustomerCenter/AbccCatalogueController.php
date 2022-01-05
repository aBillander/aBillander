<?php 

namespace App\Http\Controllers\CustomerCenter;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Configuration;
use App\Customer;
use App\Category;
use App\Product;

class AbccCatalogueController extends Controller
{


   protected $category;
   protected $product;

   public function __construct(Category $category, Product $product)
   {
        $this->middleware('auth:customer');

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
    	$customer_user = Auth::user();
		
		$category_id = $request->input('category_id', 0);
		// Not needed: $request->merge( ['category_id' => $category_id] );

		$parentId=0;
		$breadcrumb = [];

		$categories = $this->category
			->with('activechildren')
//			->withCount('products')
			->IsPublished()
			->IsActive()
			->where('parent_id', '=', intval($parentId))
			->orderBy('position', 'asc')->get();

		$categories_ids = $categories->pluck('activechildren')->flatten()->pluck('id');
		// Would be better? ->
		// $categories_ids = $categories->activechildren->pluck('id');

		if ($category_id>0 && !$request->input('search_status', 0)) {
			//
			// abi_r($categories, true);

			$category = $categories->search(function ($item, $key) use ($category_id) {
			    
			    $cat = $item->activechildren;

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
                                      ->filter( $request->all() )
                                      ->Manufacturer( $request->input( 'manufacturer_id', 0) )
         //                             ->with('measureunit')
        							  ->with('combinations')                                  
         //                             ->with('category')
         //                             ->with('tax')
	                                  ->IsSaleable()
	                                  ->IsAvailable()
	                                  ->whereIn('category_id', $categories_ids)
	                                  ->qualifyForCustomer( $customer_user->customer_id, $customer_user->customer->currency->id)
                                      ->IsActive()
                                      ->IsPublished()
                                      ->orderBy('name', 'asc')
                                      ;

                // abi_toSQL($products); die();

                                    //  abi_r($products->get());

                $products = $products->paginate( Configuration::get('ABCC_ITEMS_PERPAGE') );

	            // $this->appendInfosToProducts($products, $customer_user->customer);

	            $vparams = [];

	            $vparams['display_with_taxes'] = $customer_user->canDisplayPricesTaxInc() > 0 ? '_with_taxes' : '';
	            $vparams['enable_ecotaxes']    = Configuration::isTrue('ENABLE_ECOTAXES');

                $products->setPath('catalogue');     // Customize the URI used by the paginator
        }        
        return view('abcc.catalogue.index', compact('category_id', 'categories', 'products', 'breadcrumb', 'vparams'));
	}


    /**
     * Append image info and price with and without taxes formatted
     *
     * @param              $products
     * @param Customer     $customer
     */
    private function appendInfosToProducts($products, Customer $customer)
    {
        $products->map(function (Product $product) use ($customer) {
            // add the product image to each product here instead of doing it in the view
            $product->img = $product->getFeaturedImage();

            $product->price =
                $product->getPriceByCustomer(
                    \Auth::user()->customer, 1,
                    \Auth::user()->customer->currency
                )->getPrice()
            ;

            $product->price_tax_inc = 0;
            $product->tax_percent = 0;
            $tax = $product->getTaxRules($customer->address, $customer);

            // TODO. Several tax lines are possible?
            // should check if this is a rule_type sales tax?
            if ($tax_data = $tax->first()) {
                $product->price_tax_inc = $product->as_priceable(
                    $tax_data->percent / 100 * $product->price +
                    $product->price
                );

                $product->tax_percent = $product->as_percentable($tax_data->percent, 1);
            }
        });
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
			->orderBy('position', 'asc')->get();

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
                                      ->IsPublished()
                                      ->IsNew()
                                      ->orderBy('reference', 'asc');

                $products = $products->paginate( Configuration::get('ABCC_ITEMS_PERPAGE') );

                $products->setPath('newproducts');     // Customize the URI used by the paginator
        }        
        return view('abcc.catalogue.new_products', compact('category_id', 'categories', 'products', 'breadcrumb'));
	}


    public function getProductQuantityPricerules($i, Request $request)
    {
        $customer = Auth::user()->customer;
        $currency = $customer->currency;

        $product = $this->product
                              ->IsSaleable()
                              ->IsAvailable()
                              ->qualifyForCustomer( $customer->id, $customer->currency_id)
                              ->IsActive()
                              ->IsPublished()
                              ->find($i);
        // abi_r($product, true);

        // Check if Customer is allowed (Product is in Customer's price List)
        $customer_price = $product ? $product->getPriceByCustomerPriceList( $customer, 1, $customer->currency ) : null;

        $customer_rules = ($product && $customer_price) ? $product->getPriceRulesByCustomer( $customer ) : collect([]);

        return view('abcc.catalogue._modal_pricerules_list', compact('product', 'customer_rules', 'customer_price', 'currency', 'customer'));
    }


    public function getProduct($i, Request $request)
    {
        $customer = Auth::user()->customer;
        $currency = $customer->currency;

        $product = $this->product
                              ->with('images')
                              ->IsSaleable()
                              ->IsAvailable()
                              ->qualifyForCustomer( $customer->id, $customer->currency_id)
                              ->IsActive()
                              ->IsPublished()
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
        // To do: move this nasty code to view (grrrr!)
        foreach ($images as $k => $image) {
        	// code...
        	$flaf = '';
        	if ( !$active && $image->is_featured )
        	{
        		$active = true;
        		$flaf = 'active';
        	}
        	$carousel .= '
    <div class="item '.$flaf.'">
     <img class="img-responsive" src="'.\URL::to( \App\Image::pathProducts() . $image->getImageFolder() . $image->filename . '-large_default' . '.' . $image->extension ).'" alt="'."(".$image->filename.") ".$image->caption.'" style="padding-bottom: 80px;">
      <div class="carousel-caption" style="background-color: #aea79f; left: 5%; right: 5%;">
        '."(".$image->filename.") ".$image->caption.'
      </div>
    </div>
        	';
        }

        $data = [];

        if ($product)
        {
        	$data = [
        		'title' => $product->name,
        		'content' => nl2p($product->description_short) . '<br />' . nl2p($product->description),
        		'href' => \URL::to( \App\Image::pathProducts() . $img->getImageFolder() . $img->filename . '-large_default' . '.' . $img->extension ),
        		'caption' => "(".$img->filename.") ".$img->caption,

        		'carousel' => $carousel,

        		'nbr_images' => $images->count(),
        	];
        }

        return response()->json( $data );




        // Check if Customer is allowed (Product is in Customer's price List)
        $customer_price = $product ? $product->getPriceByCustomerPriceList( $customer, 1, $customer->currency ) : null;

        $customer_rules = ($product && $customer_price) ? $product->getPriceRulesByCustomer( $customer ) : collect([]);

        return view('abcc.catalogue._modal_pricerules_list', compact('product', 'customer_rules', 'customer_price', 'currency', 'customer'));
    }
}
