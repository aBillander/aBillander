<?php 

namespace App\Http\Controllers\CustomerCenter;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Customer;
use App\CustomerUser;
use App\Product;
use App\Cart;
use App\CartLine;

use App\Configuration;

use App\Traits\BillableControllerTrait;

class AbccCustomerCartController extends Controller 
{

   use BillableControllerTrait;

   protected $customer_user;
   protected $customer, $cart, $cartLine, $product;

   public function __construct(Cart $cart, CartLine $cartLine, Product $product)
   {
        $this->middleware('auth:customer');

// https://stackoverflow.com/questions/43000880/how-to-get-logged-in-user-in-a-laravel-controller-constructor

 //       $this->customer_user = Auth::user();
 //       $this->customer = Auth::user(); // ->customer;

 //        abi_r($this->customer_user, true);

        $this->cart = $cart;
        $this->cartLine = $cartLine;
        $this->product = $product;
   }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $this->customer_user = Auth::user();
        $this->customer      = Auth::user()->customer;

//		 abi_r($this->customer->name_fiscal);die();

		$cart = Cart::getCustomerCart();

        return view('abcc.cart.index', compact('cart'));
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
		// 
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
	 * Groovy Cart mechanism.
	 *
	 * 
	 */

    // Deprecated
    public function addItem(Request $request, $id)
    {
        $cart = Cart::getCustomerCart();

        $product = $this->product->find($id);

        // Is there a Price for this Customer?
        if (!$product) redirect()->route('abcc.cart')->with('error', 'No se pudo añadir el producto porque no se encontró.');

        $quantity = floatval( $request->input('quantity', 1.0) );
        $quantity = ($quantity > 0.0) ? $quantity : 1.0;

        // Get Customer Price
        $customer = $cart->customer;
        $currency = $cart->currency;
        $customer_price = $product->getPriceByCustomer( $customer, $quantity, $cart->currency );

        // Is there a Price for this Customer?
        if (!$customer_price) return redirect()->route('abcc.cart')->with('error', 'No se pudo añadir el producto porque no está en su tarifa.');      // Product not allowed for this Customer

        $tax_percent = $product->tax->percent;

        $customer_price->applyTaxPercent( $tax_percent );
        $unit_customer_price = $customer_price->getPrice();

        $cart->add($product, $unit_customer_price, $quantity);

        $cart->load('cartlines');

        // abi_r($cart);die();

        return redirect()->route('abcc.cart')->with('success', 'Se ha añadido el producto.');
    }

    public function removeLine($id){
        Cart::remove($id);
        return back(); // will keep same page
    }

    public function updateLine(Request $request, $id)
    {
        $qty = $request->qty;
              $proId = $request->proId;
           $rowId = $request->rowId;
            Cart::update($rowId,$qty); // for update
            $cartItems = Cart::content(); // display all new data of cart
            return view('cart.upCart', compact('cartItems'))->with('status', 'cart updated');
            /*  $products = products::find($proId);
              $stock = $products->stock;
              if($qty<$stock){
                  $msg = 'Cart is updated';
                 Cart::update($id,$request->qty);
                 return back()->with('status',$msg);
              }else{
                   $msg = 'Please check your qty is more than product stock';
                    return back()->with('error',$msg);
              }        */

	}



/* ********************************************************************************************* */    


// https://stackoverflow.com/questions/39812203/cloning-model-with-hasmany-related-models-in-laravel-5-3


/* ********************************************************************************************* */  



    /**
     * AJAX Stuff.
     *
     * 
     */  

    public function searchProduct(Request $request)
    {

    	$customer_user = Auth::user();	// Don't trust: $request->input('customer_id')

    	if ( !$customer_user ) 
    		return response( null );

        $search = $request->term;

        $products = \App\Product::select('id', 'name', 'reference', 'measure_unit_id')
                                ->where(   'name',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference', 'LIKE', '%'.$search.'%' )
                                ->orWhere( 'ean13',     'LIKE', '%'.$search.'%' )
                                ->IsSaleable()
                                ->IsAvailable()
                                ->qualifyForCustomer( $customer_user->customer_id, $request->input('currency_id') )
//                                ->with('measureunit')
//                                ->toSql();
                                ->get( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) );


//                                dd($products);

        return response( $products );
    }

    public function getProduct(Request $request)
    {
        
        // Request data
        $product_id      = $request->input('product_id');
        $combination_id  = $request->input('combination_id');
        $customer_id     = $request->input('customer_id');
        $currency_id     = $request->input('currency_id', \App\Context::getContext()->currency->id);
//        $currency_conversion_rate = $request->input('currency_conversion_rate', $currency->conversion_rate);

 //       return response()->json( [ $product_id, $combination_id, $customer_id, $currency_id ] );

        // Do the Mambo!
        // Product
        if ($combination_id>0) {
            $combination = \App\Combination::with('product')->with('product.tax')->findOrFail(intval($combination_id));
            $product = $combination->product;
            $product->reference = $combination->reference;
            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = \App\Product::with('tax')->findOrFail(intval($product_id));
        }

        // Customer
        $customer = \App\Customer::findOrFail(intval($customer_id));
        
        // Currency
        $currency = \App\Currency::findOrFail(intval($currency_id));
        $currency->conversion_rate = $request->input('conversion_rate', $currency->conversion_rate);

        // Tax
        $tax = $product->tax;
        $taxing_address = \App\Address::findOrFail($request->input('taxing_address_id'));
        $tax_percent = $tax->getTaxPercent( $taxing_address );

        $price = $product->getPrice();
        if ( $price->currency->id != $currency->id ) {
            $price = $price->convert( $currency );
        }

        // Calculate price per $customer_id now!
        $customer_price = $product->getPriceByCustomer( $customer, 1, $currency );
//        $tax_percent = $tax->percent;               // Accessor: $tax->getPercentAttribute()
//        $price->applyTaxPercent( $tax_percent );

        if ($customer_price) 
        {
            $customer_price->applyTaxPercentToPrice($tax_percent);        
    
            $data = [
                'product_id' => $product->id,
                'combination_id' => $combination_id,
                'reference' => $product->reference,
                'name' => $product->name,
                'cost_price' => $product->cost_price,
                'unit_price' => [ 
                            'tax_exc' => $price->getPrice(), 
                            'tax_inc' => $price->getPriceWithTax(),
                            'display' => \App\Configuration::get('PRICES_ENTERED_WITH_TAX') ? 
                                        $price->getPriceWithTax() : $price->getPrice(),
                            'price_is_tax_inc' => $price->price_is_tax_inc,  
//                            'price_obj' => $price,
                            ],
    
                'unit_customer_price' => [ 
                            'tax_exc' => $customer_price->getPrice(), 
                            'tax_inc' => $customer_price->getPriceWithTax(),
                            'display' => \App\Configuration::get('PRICES_ENTERED_WITH_TAX') ? 
                                        $customer_price->getPriceWithTax() : $customer_price->getPrice(),
                            'price_is_tax_inc' => $customer_price->price_is_tax_inc,  
//                            'price_obj' => $customer_price,
                            ],
    
                'tax_percent' => $tax_percent,
                'tax_id' => $product->tax_id,
                'tax_label' => $tax->name." (".$tax->as_percentable($tax->percent)."%)",
                'customer_id' => $customer_id,
                'currency' => $currency,
    
                'measure_unit_id' => $product->measure_unit_id,
                'quantity_decimal_places' => $product->quantity_decimal_places,
                'reorder_point'      => $product->reorder_point, 
                'quantity_onhand'    => $product->quantity_onhand, 
                'quantity_onorder'   => $product->quantity_onorder, 
                'quantity_allocated' => $product->quantity_allocated, 
                'blocked' => $product->blocked, 
                'active'  => $product->active, 
            ];
        } else
            $data = [];

        return response()->json( $data );
    }

    public function add(Request $request)
    {
        // return response()->json(['order_id' => $order_id] + $request->all());

        $product_id      = $request->input('product_id');
        $combination_id  = $request->input('combination_id', 0);

        $quantity = floatval( $request->input('quantity', 1.0) );

        $cart = Cart::getCustomerCart();

        $line = $cart->addLine($product_id, $combination_id, $quantity);

        // Refresh Cart
        $cart = Cart::getCustomerCart();


        if ($line)
            return response()->json( [
                    'msg' => 'OK',
                    'cart_nbr_items' => $cart->nbrItems(),
     //               'data' => $cart_line->toArray()
            ] );
        else
            return response()->json( [
                    'msg' => 'ERROR',
     //               'data' => $cart_line->toArray()
            ] );
    }


    public function updateLineQuantity(Request $request)
    {

        $line_id = $request->input('line_id', 0);

    	if ( !$line_id ) 
    		return response( null );

        $quantity = floatval( $request->input('quantity', 1.0) );
        $quantity >= 0 ?: 1.0;

    	$customer_user = Auth::user();	// Don't trust: $request->input('customer_id')

    	if ( !$customer_user ) 
    		return response( null );

        $cart =  \App\Context::getContext()->cart;

        // Get line
        $line = $cart->cartlines()->where('id', $line_id)->first();

        if ($quantity>0)
        {
            $line->update(['quantity' => $quantity]);
            // $cart->touch(); //  protected $touches = ['cart']; 
        }
        else
        	$line->delete();


        // Now, update Order Totals
        // $order->makeTotals();



        return response()->json( [
                'msg' => 'OK',
                'data' => [$line_id, $quantity]
        ] );
    }



    public function getCartLines()
    {
        $this->customer_user = Auth::user();
        $this->customer      = Auth::user()->customer;

//		 abi_r($this->customer->name_fiscal);die();

//		$cart = Cart::getCustomerCart();
		$cart = \App\Context::getContext()->cart;

        return view('abcc.cart._panel_cart_lines', compact('cart'));
    }

    public function getCartTotal()
    {
        $order = $this->customerOrder
//                        ->with('customerorderlines')
//                        ->with('customerorderlines.product')
                        ->findOrFail($id);

        return view('customer_orders._panel_customer_order_total', compact('order'));
    }


    public function deleteCartLine($line_id)
    {

        $order_line = $this->cartLine
                        ->findOrFail($line_id);

        $order_line->delete();

        // Now, update Order Totals
        // $order->makeTotals();

        return response()->json( [
                'msg' => 'OK',
                'data' => $line_id
        ] );
    }

}
