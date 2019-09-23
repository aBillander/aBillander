<?php

namespace App\Http\Controllers\CustomerCenter;

use App\Configuration;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Category;
use App\Product;
use Illuminate\View\View;

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
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $customer_user = Auth::user();

        $category_id = $request->input('category_id', 0);
        // Not needed: $request->merge( ['category_id' => $category_id] );

        $parentId = 0;
        $breadcrumb = [];

        $categories = $this->category
            ->with('activechildren')
            //			->withCount('products')
            //			->IsPublished()
            ->IsActive()
            ->where('parent_id', '=', intval($parentId))
            ->orderBy('name', 'asc')
            ->get();

        if ($category_id > 0 && !$request->input('search_status', 0)) {

            $category = $categories->search(function ($item, $key) use ($category_id) {

                $cat = $item->activechildren;

                $c = $cat->search(function ($item, $key) use ($category_id) {
                    return $item->id == $category_id;
                });

                // Found?
                return $c !== false;
            });

            $parent = $categories->slice($category, 1)->first();
            $child = $parent->children->where('id', $category_id)->first();

            $breadcrumb = [$parent, $child];
        }

        $products = null;

        if ($customer_user) {
            $products = $this->product
                ->filter($request->all())
                ->Manufacturer($request->input('manufacturer_id', 0))
                //                             ->with('measureunit')
                ->with('combinations')
                //                             ->with('category')
                //                             ->with('tax')
                ->IsSaleable()
                ->IsAvailable()
                ->qualifyForCustomer($customer_user->customer_id, $customer_user->customer->currency->id)
                ->IsActive()
                ->orderBy('reference', 'asc');

            $products = $products->paginate(Configuration::get('ABCC_ITEMS_PERPAGE'));

            $this->appendInfoToProduct($products);

            $config['show_taxes'] = $customer_user->canDisplayPricesTaxInc();
            $config['enable_ecotaxes'] = Configuration::isTrue('ENABLE_ECOTAXES');

            $products->setPath('catalogue');     // Customize the URI used by the paginator
        }
        return view('abcc.catalogue.index', compact('category_id', 'categories', 'products', 'breadcrumb', 'config'));
    }


    /**
     * Append image info and price with and without taxes formatted
     * @param $products
     */
    private function appendInfoToProduct($products)
    {
        $products->map(function ($product) {
            // add the product image to each product here instead of doing it in the view
            $product->img = $product->getFeaturedImage();

            $product->price = $product->as_priceable(
                $product->getPriceByCustomer(
                    \Auth::user()->customer, 1,
                    \Auth::user()->customer->currency
                )->getPrice()
            );

            $product->price_tax_inc = $product->tax_percent = 0;
            $tax = $product->getTaxRules();

            if ($tax_data = $tax->first()) {
                $product->price_tax_inc = $product->as_priceable(
                    $tax_data->percent / 100 * $product->price +
                    $product->price
                );

                $product->tax_percent = (int)$tax_data->percent. '%';
            }
        });
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(Request $request)
    {
        // Get logged in user

        return 'OK';

        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '';

        $payment = null;

        return view('customer_vouchers.edit', compact('payment', 'back_route'));
    }


    /**
     * Display a listing of the resource :: New Products.
     *
     * @param Request $request
     * @return Response|Factory|View
     */
    public function newProducts(Request $request)
    {
        if (Configuration::isFalse('ABCC_ENABLE_NEW_PRODUCTS')) {
            return $this->index($request);
        }

        $customer_user = Auth::user();

        $category_id = $request->input('category_id', 0);
        // Not needed: $request->merge( ['category_id' => $category_id] );

        $parentId = 0;
        $breadcrumb = [];

        $categories = $this->category
            ->with('children')
            //			->withCount('products')
            ->where('parent_id', '=', intval($parentId))
            ->orderBy('name', 'asc')->get();

        if ($category_id > 0 && !$request->input('search_status', 0)) {
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
        }

        $products = null;

        if ($customer_user) {
            $products = $this->product
                //                                      ->filter( $request->all() )
                //                                      ->Manufacturer( $request->input( 'manufacturer_id', 0) )
                //                             ->with('measureunit')
                ->with('combinations')
                //                             ->with('category')
                //                             ->with('tax')
                ->IsSaleable()
                ->qualifyForCustomer($customer_user->customer_id, $customer_user->customer->currency->id)
                ->IsActive()
                ->IsNew()
                ->orderBy('reference', 'asc');

            $products = $products->paginate(Configuration::get('ABCC_ITEMS_PERPAGE'));

            $products->setPath('newproducts');     // Customize the URI used by the paginator
        }
        return view('abcc.catalogue.new_products', compact('category_id', 'categories', 'products', 'breadcrumb'));
    }


    public function getProductQuantityPricerules($i, Request $request)
    {
        $customer = Auth::user()->customer;

        $product = $this->product
            ->IsSaleable()
            ->IsAvailable()
            ->qualifyForCustomer($customer->id, $customer->currency_id)
            ->IsActive()
            ->find($i);

        // Check if Customer is allowed (Product is in Customer's price List)


        /*
                $customer_rules = \App\PriceRule::
                            // Currency
                              where( function($query) use ($customer) {
                                    if ($customer)
                                    {
                                        $query->where('currency_id', $customer->currency_id);
                                    }
                                } )
                            // Customer range
                            ->where( function($query) use ($customer) {
                                        $query->where('customer_id', $customer->id);
                                        if ($customer->customer_group_id)
                                            $query->orWhere('customer_group_id', $customer->customer_group_id);
                                } )
                            // Product range
                            ->where( function($query) use ($product) {
                                        $query->where('product_id', $product->id);
                                        if ($product->category_id)
                                            $query->orWhere('category_id',  $product->category_id);
                                } )
                            // Quantity range
                            ->where( 'from_quantity', '>', 1 )
                            // Date range
                            ->where( function($query){
                                        $now = \Carbon\Carbon::now()->startOfDay();
                                        $query->where( function($query) use ($now) {
                                            $query->where('date_from', null);
                                            $query->orWhere('date_from', '<=', $now);
                                        } );
                                        $query->where( function($query) use ($now) {
                                            $query->where('date_to', null);
                                            $query->orWhere('date_to', '>=', $now);
                                        } );
                                } )
                                        ->orderBy('from_quantity', 'ASC')
                                        ->get();


                // abi_r($customer_rules, true);
        */
        $customer_rules = $product ? $product->getQuantityPriceRules($customer) : collect([]);

        return view('abcc.catalogue._modal_pricerules_list', compact('product', 'customer_rules'));
    }

}
