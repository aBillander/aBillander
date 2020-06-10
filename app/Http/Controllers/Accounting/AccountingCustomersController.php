<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Configuration;
use App\Customer;
use App\Address;
use App\BankAccount;
use App\CustomerInvoice;

class AccountingCustomersController extends Controller
{

   protected $customer, $address;

   public function __construct(Customer $customer, Address $address)  // , CustomerInvoice $document)   // , CustomerInvoiceLine $document_line)
   {
//        parent::__construct();

//        $this->model_class = CustomerInvoice::class;

        $this->customer = $customer;
        $this->address  = $address;
//        $this->document = $document;
//        $this->document_line = $document_line;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customers = $this->customer
                        ->filter( $request->all() )
                        ->with('address')
                        ->with('address.country')
                        ->with('address.state')
                        ->with('currency')
//                        ->orderByRaw( 'ABS(`reference_external`) ASC' );
                        ->orderBy('name_fiscal', 'asc');
//                        ->get();
        
        $customers = $customers->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        $customers->setPath('customers');     // Customize the URI used by the paginator

        $customer_groupList = [];

        return view('accounting.customers.index', compact('customers', 'customer_groupList'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sequenceList = \App\Sequence::listFor( \App\CustomerInvoice::class );

        $customer = $this->customer->with('addresses', 'address', 'address.country', 'address.state')->findOrFail($id); 

        $aBook       = $customer->addresses;
        $mainAddressIndex = -1;
        $aBookCount = $aBook->count();

        $warning = [];
/*
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
*/
        // echo '<pre>'; print_r($aBook); echo '</pre>'; die();
        // echo '<pre>'; print_r($customer); echo '</pre>'; die();

//        abi_r($sequenceList1, true);

        $invoices_templateList = \App\Template::listFor( \App\CustomerInvoice::class );
        $payment_methodList    = \App\PaymentMethod::orderby('name', 'desc')->pluck('name', 'id')->toArray();
        $currencyList          = \App\Currency::pluck('name', 'id')->toArray();
        $customer_groupList    = \App\CustomerGroup::pluck('name', 'id')->toArray();
        $price_listList = \App\PriceList::pluck('name', 'id')->toArray();
        $salesrepList = \App\SalesRep::pluck('alias', 'id')->toArray();
        $shipping_methodList = \App\ShippingMethod::pluck('name', 'id')->toArray();
        $orders_templateList = \App\Template::listFor( \App\CustomerOrder::class );
        $shipping_slips_templateList = \App\Template::listFor( \App\CustomerShippingSlip::class );

            $a=l('monthNames', [], 'appmultilang');

            $monthList = [];
            for($m=1; $m<=12; ++$m){
                $monthList[$m] = $a[$m-1];
            }

        return view('accounting.customers.edit', compact('customer', 'aBook', 'mainAddressIndex', 'sequenceList', 'invoices_templateList', 'payment_methodList', 'currencyList', 'customer_groupList', 'price_listList', 'salesrepList', 'shipping_methodList', 'orders_templateList', 'shipping_slips_templateList', 'monthList'))
                ->with('warning', $warning);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $action = $request->input('nextAction', '');

        $section =  $request->input('tab_name')     ? 
                    '#'.$request->input('tab_name') :
                    '';
// abi_r($request->all(), true);
        
        $customer = $this->customer->with('address')->findOrFail($id);
        // $address = $customer->address;

        // this->validate($request, Customer::$rules);

        // abi_r( ($section == '#accounting'));die();

        if ($section == '#accounting')
        if( ($accounting_id = $request->input('accounting_id', '')) != '' )
        {
            $customer->accounting_id = $accounting_id;
            $customer->save();
            
            return redirect( url()->previous() . '#accounting' )
                ->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $customer->name_fiscal);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
