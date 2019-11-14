<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon\Carbon;

use App\Traits\ViewFormatterTrait;

class Cart extends Model
{

    use ViewFormatterTrait;

    //

    protected $dates = [
                        'date_prices_updated'
                        ];

    protected $fillable = [
    						'customer_user_id', 'customer_id', 'notes_from_customer', 'total_items', 
                            'total_products_tax_incl', 'total_products_tax_excl', 'total_shipping_tax_incl', 'total_shipping_tax_excl', 
                            'document_discount_percent', 'document_discount_amount_tax_incl', 'document_discount_amount_tax_excl', 
                            'document_ppd_percent', 'document_ppd_amount_tax_incl', 'document_ppd_amount_tax_excl', 
                            'total_currency_tax_incl', 'total_currency_tax_excl', 'total_tax_incl', 'total_tax_excl', 
    						'invoicing_address_id', 'shipping_address_id', 'shipping_method_id', 'carrier_id',
    						'currency_id', 'payment_method_id',
    ];

    public static $rules = [
                            'customer_id' => 'exists:customers,id',
                            'invoicing_address_id' => '',
                            'shipping_address_id' => 'exists:addresses,id,addressable_id,{customer_id},addressable_type,App\Customer',
//                            'carrier_id'   => 'exists:carriers,id',
                            'currency_id' => 'exists:currencies,id',
//                            'payment_method_id' => 'exists:payment_methods,id',
               ];


    public static function boot()
    {
        parent::boot();

        static::creating(function($cart)
        {
            $cart->secure_key = md5(uniqid(rand(), true));
            
            if ( $cart->shippingmethod )
                $cart->carrier_id = $cart->shippingmethod->carrier_id;
        });

        static::saving(function($cart)
        {
            if ( $cart->shippingmethod )
                $cart->carrier_id = $cart->shippingmethod->carrier_id;
        });

        // https://laracasts.com/discuss/channels/general-discussion/deleting-related-models
        static::deleting(function ($cart)
        {
            // before delete() method call this
            foreach($cart->cartLines as $line) {
                $line->delete();
            }
        });

        static::deleted(function ()
        {
            // after delete() method call this
            if ( !Auth::guard('customer')->check() )
                return null;

            // Get Customer Cart
            $customer = Auth::user()->customer;

            // Create instance
            $cart = Cart::create([
                'customer_user_id' => Auth::user()->id,
                'customer_id' => $customer->id,
                'invoicing_address_id' => $customer->invoicing_address_id,
                'shipping_address_id' => Auth::user()->shippingaddress->id,
                'shipping_method_id' => $customer->shipping_method_id,
 //             'carrier_id',
                'currency_id' => $customer->currency_id,
                'payment_method_id' => $customer->payment_method_id,
//                'date_prices_updated',
                'document_discount_percent' => (float) $customer->document_percent,
                'document_ppd_percent' => (float) $customer->document_ppd_percent,
            ]);

            Context::getContext()->cart = $cart;

        });

    }
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public static function getCustomerUserCart()
    {
        if ( Auth::guard('customer')->check() )
        {

        // Get Customer Cart
        $customer = Auth::user()->customer;
        $cart = Cart::where('customer_id', $customer->id)->where('customer_user_id', Auth::user()->id)->with('cartlines')->first();

        if ( $cart ) 
        {
        	// Deletable lines
            $deletables = CartLine::where('cart_id', $cart->id)->doesntHave('product')->get();

            if ( $deletables->count() > 0 )
            {
                $deletables->each(function($deletable) {
                    $deletable->delete();
                });

                $cart = $cart->fresh();
            }

            // Check Shipping Address

            // To Do: Please: check if $shipping_address_id is within Auth::user()->getAllowedAddresses()
            // See CustomerCartController
            if ( $cart->cartlines->count() == 0 )
            {
                //
                $cart->update(['shipping_address_id' => Auth::user()->shippingaddress->id]);
            }


            // Update some values if customer data have changed -> cart data & cart line prices & stock
            if ( $cart->persistance_left <= 0 )
            {
                // Update Cart Prices
                $cart->updateLinePrices();

            }
        } else {
        	// Create instance
        	$cart = Cart::create([
        		'customer_user_id' => Auth::user()->id,
        		'customer_id' => $customer->id,
        		'invoicing_address_id' => $customer->invoicing_address_id,
                // Boot method takes care of this:
//        		'shipping_address_id' => $customer->shipping_address_id,
        		'shipping_method_id' => $customer->shipping_method_id,
 //       		'carrier_id',
        		'currency_id' => $customer->currency_id,
        		'payment_method_id' => $customer->payment_method_id,
//                'date_prices_updated',
                'document_discount_percent' => (float) $customer->document_percent,
                'document_ppd_percent' => (float) $customer->document_ppd_percent,
        	]);
        }

        return $cart;
        
        }

        return null;
    }


    public function addLine($product_id = null, $combination_id = null, $quantity = 1.0)
    {

        $customer_user = Auth::user();  // Don't trust: $request->input('customer_id')

        if ( !$customer_user ) 
            return response( null );

        // Do the Mambo!
        // Product
        if ($combination_id>0) {
            $combination = Combination::with('product')->with('product.tax')->find(intval($combination_id));
            $product = $combination->product;
            $product->reference = $combination->reference;
            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = Product::with('tax')->find(intval($product_id));
        }

        // Is there a Price for this Customer?
        if (!$product) return false;    // redirect()->route('abcc.cart')->with('error', 'No se pudo añadir el producto porque no se encontró.');

        // Let's check quantity
        $quantity = ($quantity > 0.0) ? $quantity : 1.0;

        // Product allready in Cart?
        $line = $this->cartlines()->where('product_id', $product->id)->first();
        if ( $line )
        {
            // Need this to apply price rules properly

            // Quantity
            $quantity += $line->quantity;

            // Remove line (we dont need it, as we are going to add a new one)
            $line->delete();

            if ( $quantity <= 0 )
                return null;       // No point to continue here
        }

        // Tax percent (sum of all applicable tax rules)
        $tax_rates = $this->getTaxPercent($product);    // Array

        // Tax percent (sum of all applicable tax rules)
        $tax_percent = $tax_rates['all'];
        // Tax percent (sum of all 'sales_equalization' applicable tax rules)
        $tax_se_percent = $tax_rates['sales_equalization'];

        $cart =  $this; // Context::getContext()->cart;

        // Get Customer Price based on Price List
        $customer = $cart->customer;
        $currency = $cart->currency;
        $customer_price = $product->getPriceByCustomerPriceList( $customer, $quantity, $currency );

        // Is there a Price for this Customer?
        if (!$customer_price) return false;    // return redirect()->route('abcc.cart')->with('error', 'No se pudo añadir el producto porque no está en su tarifa.');      // Product not allowed for this Customer

        // Still with me? Lets check Price rules with type='price'
        $customer_final_price = $product->getPriceByCustomerPriceRules( $customer, $quantity, $currency );
        if ( !$customer_final_price )
            $customer_final_price = clone $customer_price;  // No price Rules available

        $unit_customer_price       = $customer_price->getPrice();
        $unit_customer_final_price = $customer_final_price->getPrice();


        // Still one thing left: rule_type = 'promo'
        $promo_rule = $customer->getExtraQuantityRule( $product, $currency );

        // abi_r($customer->getPriceRules( $product, $currency ), true);

        if ($promo_rule)
        {
            $extra_quantity = floor( $quantity / $promo_rule->from_quantity ) * $promo_rule->extra_quantity;
            $extra_quantity_label = $promo_rule->name;
        } else {
            $extra_quantity = 0.0;
            $extra_quantity_label = '';
        }

        // Totals
        $total_tax_excl = $quantity * $unit_customer_final_price;
        $total_tax_incl = $total_tax_excl * (1.0+$tax_percent/100.0);


        // Yearning to being here? Me too!

        // New line
        if( $this->isEmpty() ) 
        {
            $this->date_prices_updated = Carbon::now();
            $this->save();
        }

        $line = CartLine::create([
            'line_sort_order' => 0,
            'line_type' => 'product',

            'product_id' => $product->id,
//              'combination_id' => $product->,
            'reference' => $product->reference, 
            'name' => $product->name, 

            'quantity' => $quantity, 
            'extra_quantity' => $extra_quantity,
            'extra_quantity_label' => $extra_quantity_label,
            'measure_unit_id' => $product->measure_unit_id,

            'unit_customer_price'       => $unit_customer_price,
            'unit_customer_final_price' => $unit_customer_final_price,
            'sales_equalization' => $customer->sales_equalization,
            'total_tax_incl' => $total_tax_incl,
            'total_tax_excl' => $total_tax_excl, 

            'tax_percent'         => $tax_percent,
            'tax_se_percent'      => $tax_se_percent,
//             'cart_id' => $product->,
            'tax_id' => $product->tax_id,
        ]);

        $this->cartlines()->save($line);

        $this->makeTotals();

        return $line;
    }


    // Need update according to public function addLine($product_id = null, $combination_id = null, $quantity = 1.0)
    public function addLineByAdmin($product_id = null, $combination_id = null, $quantity = 1.0)
    {
        // Do the Mambo!
        // Product
        if ($combination_id>0) {
            $combination = Combination::with('product')->with('product.tax')->find(intval($combination_id));
            $product = $combination->product;
            $product->reference = $combination->reference;
            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = Product::with('tax')->find(intval($product_id));
        }

        // Is there a Price for this Customer?
        if (!$product) return false;    // redirect()->route('abcc.cart')->with('error', 'No se pudo añadir el producto porque no se encontró.');

        $quantity > 0 ?: 1.0;

        $cart = $this;

        // Get Customer Price
        $customer = $cart->customer;
        $currency = $cart->currency;
        $customer_price = $product->getPriceByCustomer( $customer, $quantity, $currency );

        // Is there a Price for this Customer?
        if (!$customer_price) return false;    // return redirect()->route('abcc.cart')->with('error', 'No se pudo añadir el producto porque no está en su tarifa.');      // Product not allowed for this Customer

        $tax_percent = $product->tax->percent;

        $customer_price->applyTaxPercent( $tax_percent );
        $unit_customer_price = $customer_price->getPrice();

        return $cart->add($product, $unit_customer_price, $quantity);
    }


    public function add($product = null, Price $price = null, $quantity = 1.0)
    {
        // If $product is a 'prodduct_id', instantiate product, please.
        if ( is_numeric($product) ) 
        	$product = Product::find($product);

        if ($product == null) 
        	return null;

        if ($price === null) { // Price can be 0.0!!!
            $unit_customer_price = $product->price;
            $tax_percent         = $product->tax->percent;
        } else {
            $unit_customer_price = $price->getPrice();
            $tax_percent         = $price->tax_percent;
        }

        // Allready in Cart?
        $line = $this->cartlines()->where('product_id', $product->id)->first();
        if ( $line )
        {
        	// Keep line price
            #
            # Not so fast, boy. If quantity changes, a price rule based on quantity may apply
            #

            // Quantity
            $line->quantity += $quantity;
            // update tax in case the customer data has changed (not needed, I think)
            $line->tax_percent = $tax_percent;

        	if ( $line->quantity <= 0 )
        	{
        		// Remove line
        		$line->delete();
        	} else {
        		// Save line
        		$line->save();
        	} 
        } else {

        	if ( $quantity > 0 )
        	{
        		// New line
                if( $this->isEmpty() ) 
                {
                    $this->date_prices_updated = Carbon::now();
                    $this->save();
                }

	        	$line = CartLine::create([
	        		'line_sort_order' => 0,
	        		'product_id' => $product->id,
	//        		'combination_id' => $product->,
	        		'reference' => $product->reference, 
	        		'name' => $product->name, 
	        		'quantity' => $quantity, 
	        		'measure_unit_id' => $product->measure_unit_id,
                    'unit_customer_price' => $unit_customer_price,
                    'tax_percent'         => $tax_percent,
	 //       		'cart_id' => $product->,
	        		'tax_id' => $product->tax_id,
	        	]);

	        	$this->cartlines()->save($line);
        	} 
        }

        return $line;
    }

    public function updateLinePrices($byAdmin = false)
    {
        // Update prices or remove from cart
        foreach ($this->cartlines as $line) {
            # code...

            $product_id     = $line->product_id;
            $combination_id = $line->combination_id;
            $quantity       = $line->quantity;

            // Remove line
            $line->delete();

            // Recreate
            if ($byAdmin)
                $newline = $this->addLineByAdmin($product_id, $combination_id, $quantity);
            else
                $newline = $this->addLine($product_id, $combination_id, $quantity);
        }

        $this->date_prices_updated = Carbon::now();
        $this->save();

        return true;
    }

    public function updateLinePricesByAdmin()
    {
        return $this->updateLinePrices(true);
    }



    public function nbrItems()
    {
        switch ( Configuration::get('ABCC_NBR_ITEMS_IS_QUANTITY') )
        {
            case 'quantity':
                # code...
                return $this->quantity;
                break;
            
            case 'items':
                # code...
                return $this->cartlines()->count(); // . ' - ' . $this->persistance_left;
                break;
            
            case 'value':
                # code...
                return Currency::viewMoneyWithSign($this->amount, $this->currency);
                break;
            
            default:
                # code...
                return '';
                break;
        }

        if ( Configuration::isTrue('ABCC_NBR_ITEMS_IS_QUANTITY') ) 
            return $this->quantity;

        else
            return $this->cartlines()->count(); // . ' - ' . $this->persistance_left;
    }

    public function isEmpty()
    {
        return !$this->cartlines()->count();
    }

    public function getPersistanceLeftAttribute()
    {
        $persistance = Configuration::getInt('ABCC_CART_PERSISTANCE');
        $now = Carbon::now();

        $days = $this->date_prices_updated ? $persistance - $now->diffInDays($this->date_prices_updated) : $persistance;

        // $days = 1;

        return $days;
    }

    public function getQuantityAttribute() 
    {
        return (int) $this->cartlines->sum('quantity');
    }

    public function getAmountAttribute() 
    {
        $a = $this->cartlines;

        $s = $a->sum(function ($line) {
                return $line->quantity * $line->unit_customer_price;
            });

        return $s;
    }
    

    /*
    |--------------------------------------------------------------------------
    | Calculations
    |--------------------------------------------------------------------------
    */

    public function makeTotals()
    {
        $cart = Cart::getCustomerUserCart();

        $line_products = $cart->cartlines->where('line_type', 'product');
        $line_shipping = $cart->cartlines->where('line_type', 'shipping')->first();

        if ( !$line_products ) return ;

        $total_products_tax_excl = $line_products->sum('total_tax_excl');
        $total_products_tax_incl = $line_products->sum('total_tax_incl');

        if ( $line_shipping )
        {
            $total_shipping_tax_excl = $line_shipping->total_tax_excl;
            $total_shipping_tax_incl = $line_shipping->total_tax_incl;
        } else {
            $total_shipping_tax_excl = 0.0;
            $total_shipping_tax_incl = 0.0;
        }

        $sub_tax_excl = $total_products_tax_excl + $total_shipping_tax_excl;
        $sub_tax_incl = $total_products_tax_incl + $total_shipping_tax_incl;

        $document_discount_amount_tax_excl = $sub_tax_excl * $cart->document_discount_percent / 100.0;
        $document_discount_amount_tax_incl = $sub_tax_incl * $cart->document_discount_percent / 100.0;

        $document_ppd_amount_tax_excl = ($sub_tax_excl - $document_discount_amount_tax_excl) * $cart->document_ppd_percent / 100.0;
        $document_ppd_amount_tax_incl = ($sub_tax_incl - $document_discount_amount_tax_incl) * $cart->document_ppd_percent / 100.0;

        $total_tax_excl = $sub_tax_excl - $document_discount_amount_tax_excl - $document_ppd_amount_tax_excl;
        $total_tax_incl = $sub_tax_incl - $document_discount_amount_tax_incl - $document_ppd_amount_tax_incl;

        $this->update( compact('total_products_tax_excl', 'total_products_tax_incl', 
                               'total_shipping_tax_excl', 'total_shipping_tax_incl', 
                               'document_discount_amount_tax_excl', 'document_discount_amount_tax_incl', 
                               'document_ppd_amount_tax_excl', 'document_ppd_amount_tax_incl', 
                               'total_tax_excl', 'total_tax_incl') );

        return $cart;
    }
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
//    {
//        return $this->belongsTo('App\User');
//    }

//    public function customeruser()
    {
        return $this->belongsTo('App\CustomerUser', 'customer_user_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public function shippingmethod()
    {
        return $this->belongsTo('App\ShippingMethod', 'shipping_method_id');
    }

    public function carrier()
    {
        return $this->belongsTo('App\Carrier');
    }

    public function paymentmethod()
    {
        return $this->belongsTo('App\PaymentMethod', 'payment_method_id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function invoicingaddress()
    {
        return $this->belongsTo('App\Address', 'invoicing_address_id');
    }

    // Alias function
    public function billingaddress()
    {
        return $this->invoicingaddress();
    }

    public function shippingaddress()
    {
        return $this->belongsTo('App\Address', 'shipping_address_id');
    }

    public function taxingaddress()
    {
        return Configuration::get('TAX_BASED_ON_SHIPPING_ADDRESS') ? 
            $this->shippingaddress  : 
            $this->invoicingaddress ;
    }

    
    public function cartlines()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->hasMany('App\CartLine')->orderBy('line_sort_order', 'ASC');
    }

    // Alias
    public function documentlines()
    {
        return $this->cartlines();
    }
/*    
    public function customerorderlinetaxes()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->hasManyThrough('App\CustomerOrderLineTax', 'App\CustomerOrderLine');
    }

    public function customerordertaxes()
    {
        $taxes = [];
        $tax_lines = $this->customerorderlinetaxes;


        foreach ($tax_lines as $line) {

            if ( isset($taxes[$line->tax_rule_id]) ) {
                $taxes[$line->tax_rule_id]->taxable_base   += $line->taxable_base;
                $taxes[$line->tax_rule_id]->total_line_tax += $line->total_line_tax;
            } else {
                $tax = new CustomerOrderLineTax();
                $tax->percent        = $line->percent;
                $tax->taxable_base   = $line->taxable_base; 
                $tax->total_line_tax = $line->total_line_tax;

                $taxes[$line->tax_rule_id] = $tax;
            }
        }

        return collect($taxes)->sortByDesc('percent')->values()->all();
    }
    
    // Alias
    public function documenttaxes()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->customerordertaxes();
    }
*/


    /**
     * @param      $product
     * @param      $customer
     * @return mixed
     */
    public function getTaxPercent($product)
    {
        // get the tax percent checking the taxing address,
        // while using product tax as backup data
        $address = $this->taxingaddress();

        // if the customer has que sales_equalization enabled,
        // we need to set the product's sales_equalization to 1 to use it
        if ($product->procurement_type != 'none') {
            $product->sales_equalization = 1;
        } else {
            // Case “Product” is a service, and  Sales Equalization does not apply
            $product->sales_equalization = 0;
        }

        $tax = $product->getTaxRules($address, $this->customer);

        if (empty($tax)) {
            // Kind of "wied", isnt it?
            return $product->tax->percent;
        }

        // get the sum of the percents in case there are more than one
        return [
            'all'                => $tax->sum('percent'),
            'sales'              => $tax->where('rule_type', '=', 'sales')->sum('percent'),
            'sales_equalization' => $tax->where('rule_type', '=', 'sales_equalization')->sum('percent'),
        ];
    }

    /**
     * From the cart lines, calculate taxes total, discounts and order total
     *
     */
    public function calculateTotals()
    {
        $customer = $this->customer;
        $taxes = 0;
        
        $this->cartlines->map(function ($line) use (&$taxes, $customer) {
            $line->img = $line->product->getFeaturedImage();
            $line->tax = $line->tax_percent / 100 *
                         $line->unit_customer_price * $line->quantity;
            $line->price_without_taxes = $line->unit_customer_price * $line->quantity;
            $line->price_with_taxes = $line->tax + $line->price_without_taxes;

            if ($line->product->hasApplicableQuantityPriceRules($line->quantity, $customer)) {

                $rule = $line->product->getQuantityPriceRules($customer)->first();

                if ($rule->rule_type === 'promo') {
                    $line->product->has_extra_item_applied = true;
                    $line->product->extra_item_qty = $rule->extra_quantity;
                } else {
                    $line->product->has_price_rule_applied = true;
                    $line->product->previous_price = $line->product->getPrice()->price;
                }
            }

            $taxes += $line->tax;
        });


        if ($customer->sales_equalization) {
            $taxes_se = 0;
            foreach ($this->cartlines as $line) {
                $line->product->sales_equalization = 1;
                $rules = $customer->getTaxRules($line->product);
                $se_percent = $rules->sum('percent');

                $taxes_se += $se_percent / 100 * $line->unit_customer_price * $line->quantity;
            }
            $this->taxes_se = $this->as_priceable($taxes_se);
            $this->total_taxes = $this->as_priceable($taxes - $taxes_se);
        } else {
            $this->total_taxes = $this->as_priceable($taxes);
        }

        $amount_with_taxes = $this->amount + $this->total_taxes;

        $discount1 = $amount_with_taxes * $this->customer->discount_percent / 100.0;
        $discount2 = ($amount_with_taxes - $discount1) * $this->customer->discount_ppd_percent / 100.0;

        $this->discounts_applied = $discount1 > 0 || $discount2 > 0;

        // Re-thinking needed after this point:
        $this->discount1 = $this->as_priceable($discount1);
        $this->discount2 = $this->as_priceable($discount2);

        $this->total_products = $this->as_priceable($this->amount) - $this->discount1 - $this->discount2;
        $this->total_price = $this->as_priceable($amount_with_taxes) - $this->discount1 - $this->discount2;
    }
}
