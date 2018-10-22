<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;

use App\CartLine;
use App\Product;

use App\Traits\ViewFormatterTrait;

class Cart extends Model
{

    use ViewFormatterTrait;

    //

    protected $dates = [
                        'date_prices_updated'
                        ];

    protected $fillable = [
    						'customer_user_id', 'customer_id', 'notes_from_customer', 
    						'total_items', 'total_currency_tax_excl', 'total_tax_excl', 
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
                'shipping_address_id' => $customer->shipping_address_id,
                'shipping_method_id' => $customer->shipping_method_id,
 //             'carrier_id',
                'currency_id' => $customer->currency_id,
                'payment_method_id' => $customer->payment_method_id,
            ]);

            \App\Context::getContext()->cart = $cart;

        });

    }
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public static function getCustomerCart()
    {
        if ( !Auth::guard('customer')->check() )
        	return null;

        // Get Customer Cart
        $customer = Auth::user()->customer;
        $cart = Cart::where('customer_id', $customer->id)->with('cartlines')->first();

        if ( $cart ) 
        {
        	// Update some values if customer data have changed -> cart data & cart line prices & stock
        } else {
        	// Create instance
        	$cart = Cart::create([
        		'customer_user_id' => Auth::user()->id,
        		'customer_id' => $customer->id,
        		'invoicing_address_id' => $customer->invoicing_address_id,
        		'shipping_address_id' => $customer->shipping_address_id,
        		'shipping_method_id' => $customer->shipping_method_id,
 //       		'carrier_id',
        		'currency_id' => $customer->currency_id,
        		'payment_method_id' => $customer->payment_method_id,
//                'date_prices_updated',
        	]);
        }

        return $cart;
    }


    public function add($product = null, $price = null, $quantity = 1.0)
    {
        // If $product is a 'prodduct_id', instantiate product, please.
        if ( is_numeric($product) ) 
        	$product = Product::find($product);

        if ($product == null) 
        	return null;

        if ($price === null) // Price can be 0.0!!!
        	$price = $product->price;

        // Allready in Cart?
        $line = $this->cartlines()->where('product_id', $product->id)->first();
        if ( $line )
        {
        	$line->quantity += $quantity;

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
	        	$line = CartLine::create([
	        		'line_sort_order' => 0,
	        		'product_id' => $product->id,
	//        		'combination_id' => $product->,
	        		'reference' => $product->reference, 
	        		'name' => $product->name, 
	        		'quantity' => $quantity, 
	        		'measure_unit_id' => $product->measure_unit_id,
	        		'unit_customer_price' => $price, 
	        		'tax_percent' => $product->tax->percent, 
	 //       		'cart_id' => $product->,
	        		'tax_id' => $product->tax_id,
	        	]);

	        	$this->cartlines()->save($line);
        	} 
        }

    }

    public function nbrItems()
    {
        return $this->cartlines()->count() . ' - ' . $this->persistance_left;
    }

    public function getPersistanceLeftAttribute()
    {
        $persistance = \App\Configuration::getInt('ABCC_CART_PERSISTANCE');
        $now = \Carbon\Carbon::now();

        $days = $this->date_prices_updated ? $now->diffInDays($this->date_prices_updated) : $persistance;

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
        return \App\Configuration::get('TAX_BASED_ON_SHIPPING_ADDRESS') ? 
            $this->shippingaddress()  : 
            $this->invoicingaddress() ;
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
                $tax = new \App\CustomerOrderLineTax();
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
}
