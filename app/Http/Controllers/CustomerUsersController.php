<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Customer;
use App\CustomerUser;
use App\Configuration;
use App\Language;

use Mail;

class CustomerUsersController extends Controller
{
    //

   protected $customeruser, $customer;

   public function __construct(CustomerUser $customeruser, Customer $customer)
   {
        $this->customeruser = $customeruser;
        $this->customer = $customer;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//      $users = $this->user->with('language')->orderBy('id', 'ASC')->get();

//      return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
        
//        return view('customer_orders.create', compact('sequenceList'));
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $section = '#customerusers';
        $customer_id = $request->input('customer_id');

        $customer = $this->customer->with('address')->find($customer_id);

        // Check unique
/*
        if ( $this->customeruser->where('email', $request->input('email'))->first() )
            return redirect(route('customers.edit', $customer_id) . $section)
                ->with('error', l('Duplicate email address &#58&#58 (:id) ', ['id' => $request->input('email')], 'layouts') );
*/

        // https://hdtuto.com/article/ajax-validation-in-laravel-57-example
        // https://itsolutionstuff.com/post/laravel-5-ajax-request-validation-exampleexample.html
        // https://www.youtube.com/watch?v=3mYs2rTjg1s

        /// $this->validate($request, CustomerUser::$rules);
        $validator = Validator::make($request->all(), CustomerUser::$rules);

    

        if ( !$validator->passes() ) {

            return response()->json(['error'=>$validator->errors()->all()]);

        }

        // Do move on!
        
        $password = \Hash::make($request->input('password'));
        $request->merge( ['password' => $password] );
        
        $request->merge( ['language_id' => $request->input('language_id', $customer->language_id)] );

        if (
                $request->input('enable_min_order') || 
                (( $request->input('enable_min_order') == -1 ) && Configuration::isTrue('ABCC_ENABLE_MIN_ORDER'))
        )
            ;
        else
            $request->merge( ['min_order_value' => 0.0] );

        $customeruser = $this->customeruser->create($request->all());

        // Notify Customer
        // 
        // $customer = $this->customeruser->customer;


        // MAIL stuff
        if ( $request->input('notify_customer', 0) )
        try {

            $template_vars = array(
                'customer'   => $customer,
//                'custom_body'   => $request->input('email_body'),
                );

            $data = array(
                'from'     => config('mail.from.address'),         // Configuration::get('ABCC_EMAIL'),
                'fromName' => config('mail.from.name'   ),    // Configuration::get('ABCC_EMAIL_NAME'),
                'to'       => $customeruser->email,         // $cinvoice->customer->address->email,
                'toName'   => $customeruser->full_name,    // $cinvoice->customer->name_fiscal,
                'subject'  => l(' :_> Confirmación de acceso al Centro de Clientes de :company', ['company' => \App\Context::getcontext()->company->name_fiscal]),
                );

            

            $send = Mail::send('emails.'.\App\Context::getContext()->language->iso_code.'.invitation_confirmation', $template_vars, function($message) use ($data)
            {
                $message->from($data['from'], $data['fromName']);

                $message->to( $data['to'], $data['toName'] )->bcc( $data['from'] )->subject( $data['subject'] );    // Will send blind copy to sender!

            }); 

        } catch(\Exception $e) {

             // abi_r($e->getMessage());
            return response()->json([ 'error' => $e->getMessage() ]);

/*            return redirect()->route('abcc.orders.index')
                    ->with('error', l('There was an error. Your message could not be sent.', [], 'layouts').'<br />'.
                        $e->getMessage());
*/
            // return false;
        }
        // MAIL stuff ENDS

        if($request->ajax()){

            return response()->json( [
                'success' => 'OK',
                'msg' => 'OK',
                'data' => $customeruser->toArray()
            ] );

        }


        return redirect(route('customers.edit', $customer_id) . $section)
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $customeruser->id], 'layouts') . $customeruser->getFullName());

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerUser $customeruser)
    {
//        return $this->edit( $customeruser );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerUser $customeruser)
    {
//        return view('customer_orders.edit', compact('customeruser'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerUser $customeruser)
    {
        $section = '#customerusers';
        $customer_id = $request->input('customer_id');

        if (
                $request->input('enable_min_order') || 
                (( $request->input('enable_min_order') == -1 ) && Configuration::isTrue('ABCC_ENABLE_MIN_ORDER'))
        )
            ;
        else
            $request->merge( ['min_order_value' => 0.0] );

        
        $vrules = CustomerUser::$rules;

        if ( isset($vrules['email']) ) $vrules['email'] .= ','. $customeruser->id.',id';  // Unique

        if ( $request->input('password') != '' ) {

            $validator = Validator::make($request->all(), $vrules);
            if ( !$validator->passes() ) {

                return response()->json(['error'=>$validator->errors()->all()]);

            }

            $password = \Hash::make($request->input('password'));
            $request->merge( ['password' => $password] );

            $customeruser->update($request->all());

        } else {

            $validator = Validator::make($request->all(), array_except( $vrules, array('password')));
            if ( !$validator->passes() ) {

                return response()->json(['error'=>$validator->errors()->all()]);

            }

            $customeruser->update($request->except(['password']));
        }


        if($request->ajax()){

            return response()->json( [
                'success' => 'OK',
                'msg' => 'OK',
                'data' => $customeruser->toArray()
            ] );

        }

        return redirect(route('customers.edit', $customer_id) . $section)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $customeruser->id], 'layouts') . $customeruser->getFullName());

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerUser $customeruser)
    {
        $section = '#customerusers';
        $name = $customeruser->getFullName();

        $customeruser->delete();

        return redirect(url()->previous() . $section) // redirect()->to( url()->previous() . $section )
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $name], 'layouts'));
    }



    /**
     * Extra Stuff.
     *
     * 
     */  


    public function impersonate($id)
    {
        
        \Auth()->guard('customer')->loginUsingId($id);

        return redirect()->route('customer.dashboard');
    }


    public function getCart($id)
    {
        
        $customer_user = $this->customeruser->with('cart', 'cart.cartlines')->findOrFail($id);

        return view('customers._panel_cart_lines', ['user' => $customer_user, 'cart' => $customer_user->cart]);
    }

    public function getUser($id, Request $request)
    {
        $customer_user = $this->customeruser->findOrFail($id);

            $data = [];

        return response()->json( ['user' => $customer_user] );
        
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
                            'display' => Configuration::get('PRICES_ENTERED_WITH_TAX') ? 
                                        $price->getPriceWithTax() : $price->getPrice(),
                            'price_is_tax_inc' => $price->price_is_tax_inc,  
//                            'price_obj' => $price,
                            ],
    
                'unit_customer_price' => [ 
                            'tax_exc' => $customer_price->getPrice(), 
                            'tax_inc' => $customer_price->getPriceWithTax(),
                            'display' => Configuration::get('PRICES_ENTERED_WITH_TAX') ? 
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
}
