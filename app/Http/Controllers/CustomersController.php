<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Validator;

use View, Mail;

use App\Customer;
use App\Address;
use App\BankAccount;
use App\CustomerOrder;
use App\CustomerOrderLine;
use App\CustomerInvoice;
use App\CustomerInvoiceLine;
use App\CustomerShippingSlip;
use App\CustomerShippingSlipLine;
use App\PriceRule;

use App\Configuration;

class CustomersController extends Controller {


   protected $customer, $address;

   public function __construct(Customer $customer, Address $address)
   {
        $this->customer = $customer;
        $this->address  = $address;
   }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $customers = $this->customer
                        ->filter( $request->all() )
                        ->with('address')
                        ->with('address.country')
                        ->with('address.state')
                        ->with('currency')
                        ->orderByRaw( 'ABS(`reference_external`) ASC' );
//                        ->orderBy('reference_external', 'asc');
//                        ->get();
        
        $customers = $customers->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        $customers->setPath('customers');     // Customize the URI used by the paginator

        return view('customers.index', compact('customers'));
        
    }
	

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $action = $request->input('nextAction', '');

        // Prepare address data
        $address = $request->input('address');

        $request->merge( ['outstanding_amount_allowed' => Configuration::get('DEF_OUTSTANDING_AMOUNT')] );

        if ( !$request->input('address.name_commercial') ) {

            $request->merge( ['name_commercial' => $request->input('name_fiscal')] );
            $address['name_commercial'] = $request->input('name_fiscal');
        } else {

            $request->merge( ['name_commercial' => $request->input('address.name_commercial')] );
        }

        $this->validate($request, Customer::$rules);

        $address['alias'] = l('Main Address', [],'addresses');

        $request->merge( ['address' => $address] );

        $this->validate($request, Address::related_rules());

        if ( !$request->has('currency_id') ) $request->merge( ['currency_id' => Configuration::get('DEF_CURRENCY')] );

        if ( !$request->has('payment_day') ) $request->merge( ['payment_day' => null] );

        if ( !$request->has('language_id') ) $request->merge( ['language_id' => Configuration::get('DEF_LANGUAGE')] );

        // ToDO: put default accept einvoice in a configuration key
        
        $customer = $this->customer->create($request->all());

        $data = $request->input('address');

        $address = $this->address->create($data);
        $customer->addresses()->save($address);

        $customer->invoicing_address_id = $address->id;
        $customer->shipping_address_id  = $address->id;
        $customer->save();

        if ($action == 'completeCustomerData')
            return redirect('customers/'.$customer->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $customer->id], 'layouts') . $request->input('name_fiscal'));
        else
            return redirect('customers')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $customer->id], 'layouts') . $request->input('name_fiscal'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return $this->edit($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $sequenceList = \App\Sequence::listFor( \App\CustomerInvoice::class );

        $customer = $this->customer->with('addresses', 'address', 'address.country', 'address.state')->findOrFail($id); 

        $aBook       = $customer->addresses;
        $mainAddressIndex = -1;
        $aBookCount = $aBook->count();

        if ( !($aBookCount>0) )
        {
            // Empty Addresss Book!
            // $aBook       = array();
            $mainAddress = 0;

            // Sanitize
            if ( !( $customer->invoicing_address_id ) OR !( $customer->shipping_address_id) ) {
                $customer->invoicing_address_id = 0;
                $customer->shipping_address_id  = 0;
                $customer->save();
            }

            // Issue Warning!
            return View::make('customers.edit', compact('customer', 'aBook', 'mainAddressIndex'))
                ->with('warning', l('You need one Address at list, for Customer (:id) :name', ['id' => $customer->id, 'name' => $customer->name_fiscal]));
        };

        if ( $aBookCount == 1 ) 
        {
            // Only 1 address in Addresss Book!
            $warning = array();
            $addr = $aBook->first();
            if( $customer->shipping_address_id != $addr->id)
            {
                $customer->shipping_address_id  = $addr->id;
                // $customer->save();
                $warning[] = l('Shipping Address has been updated for Customer (:id) :name', ['id' => $customer->id, 'name' => $customer->name_fiscal]);
            }
            if( $customer->invoicing_address_id != $addr->id)
            {
                $customer->invoicing_address_id  = $addr->id;
                // $customer->save();
                $warning[] = l('Main Address has been updated for Customer (:id) :name', ['id' => $customer->id, 'name' => $customer->name_fiscal]);
            }
            if ( $customer->isDirty() ) $customer->save();   // Model has changed

            $mainAddressIndex = 0;

            return View::make('customers.edit', compact('customer', 'aBook', 'mainAddressIndex', 'sequenceList'))
                ->with('warning', $warning);

        } else {
            // So far, so good => full stack Address Book!
            $warning = array();
            // Check Shpping Address
            if ( !$aBook->contains($customer->shipping_address_id) )
            {
                if ($customer->shipping_address_id != 0) 
                {
                    $customer->shipping_address_id  = 0;
                    $warning[] = l('Default Shipping Address has been updated for Customer (:id) :name', ['id' => $customer->id, 'name' => $customer->name_fiscal]);
                }
            }
            // Check Invoicing Address
            if ( !$aBook->contains($customer->invoicing_address_id) )
            {
                if ($customer->invoicing_address_id != 0) 
                {
                    $customer->invoicing_address_id  = 0;
                    $warning[] = l('You should set the Main Address for Customer (:id) :name', ['id' => $customer->id, 'name' => $customer->name_fiscal]);
                }
            }
            if ( $customer->isDirty() ) $customer->save();   // Model has changed

            $mainAddr = $customer->invoicing_address_id;

            // Get index for drop-down selector
            foreach ($aBook as $key => $value) {
                if ($mainAddr == $value->id) {
                    $mainAddressIndex = $key;
                    break;
                }
            }
            if ($mainAddressIndex < 0) ; // Issue warning!
        }

        // echo '<pre>'; print_r($aBook); echo '</pre>'; die();
        // echo '<pre>'; print_r($customer); echo '</pre>'; die();

//        abi_r($sequenceList1, true);

        return view('customers.edit', compact('customer', 'aBook', 'mainAddressIndex', 'sequenceList'))
                ->with('warning', $warning);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $action = $request->input('nextAction', '');

        $section =  $request->input('tab_name')     ? 
                    '#'.$request->input('tab_name') :
                    '';

        if ($section == '#addressbook')
        {
            $input = [];
            $input['invoicing_address_id'] = $request->input('invoicing_address_id', 0); // Should be in address Book
            $input['shipping_address_id']  = $request->input('shipping_address_id', 0);  // Should be in address Book or 0

            $rules['invoicing_address_id'] = 'exists:addresses,id,addressable_id,'.intval($id);
            if ($input['shipping_address_id']>0)
//                $rules['shipping_address_id'] = 'exists:addresses,id,addressable_type,\\App\\Customer|exists:addresses,id,addressable_id,'.intval($id);
                $rules['shipping_address_id'] = 'exists:addresses,id,addressable_id,'.intval($id);
            else
                $input['shipping_address_id'] = 0;

             $this->validate($request, $rules);

                $customer = $this->customer->find($id);
                $customer->update($input);

                return redirect(route('customers.edit', $id) . $section)
                    ->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name_commercial'));

        }

        if ($section == '#customeruser')
        {

            return redirect(route('customers.edit', $id) . $section)
                ->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name_commercial', ''));

        }

        
        $customer = $this->customer->with('address')->findOrFail($id);
        $address = $customer->address;

//        abi_r(Customer::$rules, true);

        $this->validate($request, Customer::$rules);
        
//        $this->validate($request, Address::related_rules());

        $request->merge( ['name_commercial' => $request->input('address.name_commercial')] );

        // $customer->update( array_merge($request->all(), ['name_commercial' => $request->input('address.name_commercial')] ) );
        $customer->update( $request->all() );
        if ( !$request->input('address.name_commercial') ) $request->merge( ['address.name_commercial' => $request->input('name_fiscal')] );
        // $data = $request->input('address');
        $data = $request->input('address') + ['country_id' => $address->country_id];     // Gorrino fix: field is disabled in view, so cero value is got in request (although address is not modified)
        $address->update($data);


        if ($action != 'completeCustomerData')
            return redirect('customers/'.$customer->id.'/edit'.$section)
                ->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name_commercial'));
        else
            return redirect('customers')
                ->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name_commercial'));

    }

    /**
     * Update Bank Account in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function updateBankAccount($id, Request $request)
    {

        $section = '#bankaccounts';

//        abi_r(Customer::$rules, true);

        $customer = $this->customer->with('bankaccount')->findOrFail($id);

        $bankaccount = $customer->bankaccount;

        $this->validate($request, BankAccount::$rules);

        if ( $bankaccount )
        {
            // Update
            $bankaccount->update($request->all());
        } else {
            // Create
            $bankaccount = BankAccount::create($request->all());
            $customer->bankaccounts()->save($bankaccount);

            $customer->bank_account_id = $bankaccount->id;
            $customer->save();
        }

        return redirect('customers/'.$customer->id.'/edit'.$section)
            ->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $customer->name_commercial);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $c = $this->customer->find($id);

        // Addresses
        $c->addresses()->delete();

        // Customer
        $c->delete();

        return redirect('customers')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }

    

    /**
     * Return a json list of records matching the provided query
     * @return json
     */
    public function ajaxCustomerSearch(Request $request)
    {
        $params = array();
        if (intval($request->input('name_commercial', ''))>0)
            $params['name_commercial'] = 1;
        
        return Customer::searchByNameAutocomplete($request->input('query'), $params);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrders($id, Request $request)
    {
        $items_per_page = intval($request->input('items_per_page', Configuration::get('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page >= 0) ) 
            $items_per_page = Configuration::get('DEF_ITEMS_PERPAGE');

        $customer_orders = CustomerOrder::where('customer_id', $id)
                            ->with('customer')
                            ->with('currency')
                            ->with('paymentmethod')
                            ->orderBy('document_date', 'desc');        // ->get();

        $customer_orders = $customer_orders->paginate( $items_per_page );     // Configuration::get('DEF_ITEMS_PERPAGE') );  // intval(Configuration::get('DEF_ITEMS_PERAJAX'))

        $customer_orders->setPath('customerorders');

        //return $items_per_page ;
        
        return view('customers._panel_orders_list', compact('customer_orders', 'items_per_page'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPriceRules($id, Request $request)
    {
        $items_per_page_pricerules = intval($request->input('items_per_page_pricerules', Configuration::get('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page_pricerules >= 0) ) 
            $items_per_page_pricerules = Configuration::get('DEF_ITEMS_PERPAGE');

        $customer_rules = PriceRule::where('customer_id', $id)
                                ->with('category')
                                ->with('product')
                                ->with('combination')
                                ->with('currency')
                                ->orderBy('product_id', 'ASC')
                                ->orderBy('customer_id', 'ASC')
                                ->orderBy('from_quantity', 'ASC');

        $customer_rules = $customer_rules->paginate( $items_per_page_pricerules );     // Configuration::get('DEF_ITEMS_PERPAGE') );  // intval(Configuration::get('DEF_ITEMS_PERAJAX'))

        $customer_rules->setPath('customerpricerules');

        //return $items_per_page ;
        
        return view('customers._panel_pricerules_list', compact('id', 'customer_rules', 'items_per_page_pricerules'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function productConsumption($id, $productid, Request $request)
    {
        $customer = $this->customer::findOrFail($id);

        $product = \App\Product::findOrFail($productid);

        $items_per_page = intval($request->input('items_per_page', Configuration::get('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page >= 0) ) 
            $items_per_page = Configuration::get('DEF_ITEMS_PERPAGE');

        // See: https://stackoverflow.com/questions/28913014/laravel-eloquent-search-on-fields-of-related-model
        $o_lines = CustomerOrderLine::where('product_id', $productid)
                            ->with('document')
//                            ->with(['currency' => function($q) {
//                                    $q->orderBy('document_date', 'desc');
//                                }])
                            ->whereHas('document', function($q) use ($id) {
                                    $q->where('customer_id', $id);
                                })
                            ->join('customer_orders', 'customer_order_lines.customer_order_id', '=', 'customer_orders.id')
                            ->select('customer_order_lines.*', 'customer_orders.document_date', \DB::raw('"customerorders" as route'))
                            ->orderBy('customer_orders.document_date', 'desc')
                            ->take( $items_per_page )
                            ->get();


        $s_lines = CustomerShippingSlipLine::where('product_id', $productid)
                            ->with('document')
//                            ->with(['currency' => function($q) {
//                                    $q->orderBy('document_date', 'desc');
//                                }])
                            ->whereHas('document', function($q) use ($id) {
                                    $q->where('customer_id', $id);
                                    $q->where('created_via', 'manual');
 //                                   $q->where('status', '!=', 'draft');
                                })
                            ->join('customer_shipping_slips', 'customer_shipping_slip_lines.customer_shipping_slip_id', '=', 'customer_shipping_slips.id')
                            ->select('customer_shipping_slip_lines.*', 'customer_shipping_slips.document_date', \DB::raw('"customershippingslips" as route'))
                            ->orderBy('customer_shipping_slips.document_date', 'desc')
                            ->take( $items_per_page )
                            ->get();


        $i_lines = CustomerInvoiceLine::where('product_id', $productid)
                            ->with('document')
//                            ->with(['currency' => function($q) {
//                                    $q->orderBy('document_date', 'desc');
//                                }])
                            ->whereHas('document', function($q) use ($id) {
                                    $q->where('customer_id', $id);
                                    $q->where('created_via', 'manual');
 //                                   $q->where('status', '!=', 'draft');
                                })
                            ->join('customer_invoices', 'customer_invoice_lines.customer_invoice_id', '=', 'customer_invoices.id')
                            ->select('customer_invoice_lines.*', 'customer_invoices.document_date', \DB::raw('"customerinvoices" as route'))
                            ->orderBy('customer_invoices.document_date', 'desc')
                            ->take( $items_per_page )
                            ->get();
        
        // See: https://stackoverflow.com/questions/23083572/merge-and-sort-two-eloquent-collections
        $lines1 = collect($o_lines);
        $lines2 = collect($s_lines);
        $lines3 = collect($i_lines);
/*
        This way you can just merge them, even when there are duplicate ID's.

        Eloquent returns an Eloquent Collection and collect() returns a normal Collection. 
        A 'normal' collection has different methods than the Eloquent Collection
*/

        $lines = $lines1->merge($lines2)->merge($lines3)->sortByDesc('document_date');

        return view('customers._modal_product_consumption', compact('customer', 'product', 'lines'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRecentSales($id, Request $request)
    {
        $customer = $this->customer::findOrFail($id);

        // return 'OK';

        $items_per_page_products = intval($request->input('items_per_page_products', Configuration::get('DEF_ITEMS_PERPAGE')));
        // return $items_per_page_products;



        if ( !($items_per_page_products >= 0) ) 
            $items_per_page_products = Configuration::get('DEF_ITEMS_PERPAGE');

        // See: https://stackoverflow.com/questions/28913014/laravel-eloquent-search-on-fields-of-related-model
        $o_lines = CustomerOrderLine::
                            with('document')
                            ->whereHas('product')
                            ->with('product')
//                            ->with(['currency' => function($q) {
//                                    $q->orderBy('document_date', 'desc');
//                                }])
                            ->whereHas('document', function($q) use ($id) {
                                    $q->where('customer_id', $id);
                                })
                            ->join('customer_orders', 'customer_order_lines.customer_order_id', '=', 'customer_orders.id')
                            ->select('customer_order_lines.*', 'customer_orders.document_date', \DB::raw('"customerorders" as route'))
                            ->orderBy('customer_orders.document_date', 'desc')
                            ->take( $items_per_page_products )
                            ->get();

/* */
        $s_lines = CustomerShippingSlipLine::
                            with('document')
                            ->whereHas('product')
                            ->with('product')
//                            ->with(['currency' => function($q) {
//                                    $q->orderBy('document_date', 'desc');
//                                }])
                            ->whereHas('document', function($q) use ($id) {
                                    $q->where('customer_id', $id);
                                })
                            ->join('customer_shipping_slips', 'customer_shipping_slip_lines.customer_shipping_slip_id', '=', 'customer_shipping_slips.id')
                            ->select('customer_shipping_slip_lines.*', 'customer_shipping_slips.document_date', \DB::raw('"customershippingslips" as route'))
                            ->orderBy('customer_shipping_slips.document_date', 'desc')
                            ->take( $items_per_page_products )
                            ->get();


        $i_lines = CustomerInvoiceLine::
                            with('document')
                            ->whereHas('product')
                            ->with('product')
//                            ->with(['currency' => function($q) {
//                                    $q->orderBy('document_date', 'desc');
//                                }])
                            ->whereHas('document', function($q) use ($id) {
                                    $q->where('customer_id', $id);
                                })
                            ->join('customer_invoices', 'customer_invoice_lines.customer_invoice_id', '=', 'customer_invoices.id')
                            ->select('customer_invoice_lines.*', 'customer_invoices.document_date', \DB::raw('"customerinvoices" as route'))
                            ->orderBy('customer_invoices.document_date', 'desc')
                            ->take( $items_per_page_products )
                            ->get();
/* */        
        // See: https://stackoverflow.com/questions/23083572/merge-and-sort-two-eloquent-collections
        $lines1 = collect($o_lines);
        $lines2 = collect($s_lines);
        $lines3 = collect($i_lines);
/*
        This way you can just merge them, even when there are duplicate ID's.

        Eloquent returns an Eloquent Collection and collect() returns a normal Collection. 
        A 'normal' collection has different methods than the Eloquent Collection
*/

        $lines = $lines1->merge($lines2)->merge($lines3)->sortByDesc('document_date');

        $lines = $lines->take( $items_per_page_products );

        return view('customers._panel_recent_sales', compact('lines', 'customer', 'items_per_page_products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function invite(Request $request)
    {
        
        // See: https://itsolutionstuff.com/post/laravel-5-ajax-request-validation-exampleexample.html
        $validator = Validator::make($request->all(), [

            'invitation_to_name' => 'required',
            'invitation_to_email' => 'required|email',
            'invitation_subject' => 'required',
            'invitation_message' => 'required',

        ]);


        if ( !$validator->passes() ) {

            return response()->json(['error'=>$validator->errors()->all()]);

        }


        // ToDo: validate if send to and send from fields are defined

        $body = $request->input('invitation_message');

        if ( !stripos( $body, '<br' ) ) $body = nl2br($body);

        // See ContactMessagesController
        try{
            $send = Mail::send('emails.'.\App\Context::getContext()->language->iso_code.'.invitation',
                // Data passing to view
                array(
                    'user_email'   => $request->input('invitation_from_email'),
                    'user_name'    => $request->input('invitation_from_name'),
                    'user_message' => $body,
                ), function($message) use ( $request )  // Message customization
            {
                $message->from(    config('mail.from.address'  ), config('mail.from.name'    ) );
                $message->replyTo( $request->input('invitation_from_email'), $request->input('invitation_from_name') );
                $message->to(      $request->input('invitation_to_email'  ), $request->input('invitation_to_name')   )->subject( $request->input('invitation_subject') );
            });
        }
        catch(\Exception $e){

                return response()->json(['error'=>[
                        l('There was an error. Your message could not be sent.', [], 'layouts'),
                        $e->getMessage()
                ]]);
        }
        

        return response()->json(['success'=>'Email sent.']);
    }

}