<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Validator;

use View, Mail;

use App\Supplier;
use App\Address;
use App\BankAccount;

use App\Configuration;

use App\Traits\ModelAttachmentControllerTrait;

class SuppliersController extends Controller
{

   use ModelAttachmentControllerTrait;

   protected $supplier, $address;

   public function __construct(Supplier $supplier, Address $address)
   {
        $this->supplier = $supplier;
        $this->address  = $address;
   }
    /**
     * Display a listing of the resource.
	 * GET /suppliers
     *
     * @return Response
     */

    public function index(Request $request)
    {
        $suppliers = $this->supplier
                        ->filter( $request->all() )
                        ->with('address')
                        ->with('address.country')
                        ->with('address.state')
                        ->with('currency')
//                        ->orderByRaw( 'ABS(`reference_external`) ASC' );
                        ->orderBy('name_fiscal', 'asc');
        
        $suppliers = $suppliers->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        $suppliers->setPath('suppliers');     // Customize the URI used by the paginator

        return view('suppliers.index', compact('suppliers'));
        
    }

	/**
	 * Show the form for creating a new resource.
	 * GET /suppliers/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('suppliers.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /suppliers
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $action = $request->input('nextAction', '');

        // Prepare address data
        $address = $request->input('address');

        if ( !$request->input('address.name_commercial') ) {

            $request->merge( ['name_commercial' => $request->input('name_fiscal')] );
            $address['name_commercial'] = $request->input('name_fiscal');
        } else {

            $request->merge( ['name_commercial' => $request->input('address.name_commercial')] );
        }

        $this->validate($request, Supplier::$rules);

        $address['alias'] = l('Main Address', [],'addresses');

        $request->merge( ['address' => $address] );

        $this->validate($request, Address::related_rules());

        if ( !$request->has('currency_id') ) $request->merge( ['currency_id' => Configuration::get('DEF_CURRENCY')] );

        if ( !$request->has('payment_days') ) $request->merge( ['payment_days' => null] );

        if ( !$request->has('language_id') ) $request->merge( ['language_id' => Configuration::get('DEF_LANGUAGE')] );

        // ToDO: put default accept einvoice in a configuration key
        
        $supplier = $this->supplier->create($request->all());

        $data = $request->input('address');

        $address = $this->address->create($data);
        $supplier->addresses()->save($address);

        $supplier->invoicing_address_id = $address->id;
        $supplier->save();

        if ($action == 'completeSupplierData')
            return redirect('suppliers/'.$supplier->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $supplier->id], 'layouts') . $request->input('name_fiscal'));
        else
            return redirect('suppliers')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $supplier->id], 'layouts') . $request->input('name_fiscal'));
    }

	/**
	 * Display the specified resource.
	 * GET /suppliers/{id}
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
	 * GET /suppliers/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $sequenceList = \App\Sequence::listFor( \App\SupplierInvoice::class );

		$supplier = $this->supplier->with('addresses', 'address')->findOrFail($id);

        $aBook       = $supplier->addresses;
        $mainAddressIndex = $supplier->invoicing_address_id;

		return view('suppliers.edit', compact('supplier', 'aBook', 'mainAddressIndex', 'sequenceList'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /suppliers/{id}
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
//                $rules['shipping_address_id'] = 'exists:addresses,id,addressable_type,\\App\\Supplier|exists:addresses,id,addressable_id,'.intval($id);
                $rules['shipping_address_id'] = 'exists:addresses,id,addressable_id,'.intval($id);
            else
                $input['shipping_address_id'] = 0;

             $this->validate($request, $rules);

                $supplier = $this->supplier->find($id);
                $supplier->update($input);

                return redirect(route('suppliers.edit', $id) . $section)
                    ->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name_commercial'));

        }

        
        $supplier = $this->supplier->with('address')->findOrFail($id);
        $address = $supplier->address;

//        abi_r(Supplier::$rules, true);

        $this->validate($request, Supplier::$rules);
        
//        $this->validate($request, Address::related_rules());

        $request->merge( ['name_commercial' => $request->input('address.name_commercial')] );

        // $supplier->update( array_merge($request->all(), ['name_commercial' => $request->input('address.name_commercial')] ) );
        $supplier->update( $request->all() );
        if ( !$request->input('address.name_commercial') ) $request->merge( ['address.name_commercial' => $request->input('name_fiscal')] );
        // $data = $request->input('address');
        $data = $request->input('address') + ['country_id' => $address->country_id];     // Gorrino fix: field is disabled in view, so cero value is got in request (although address is not modified)
        $address->update($data);


        if ($action != 'completeSupplierData')
            return redirect('suppliers/'.$supplier->id.'/edit'.$section)
                ->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name_commercial'));
        else
            return redirect('suppliers')
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

//        abi_r(Supplier::$rules, true);

        $supplier = $this->supplier->with('bankaccount')->findOrFail($id);

        $bankaccount = $supplier->bankaccount;

        $this->validate($request, BankAccount::$rules);

        if ( $bankaccount )
        {
            // Update
            $bankaccount->update($request->all());
        } else {
            // Create
            $bankaccount = BankAccount::create($request->all());
            $supplier->bankaccounts()->save($bankaccount);

            $supplier->bank_account_id = $bankaccount->id;
            $supplier->save();
        }

        return redirect('suppliers/'.$supplier->id.'/edit'.$section)
            ->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $supplier->name_regular);

    }

	/**
	 * Remove the specified resource from storage.
	 * DELETE /suppliers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $s = $this->supplier->findOrFail($id);

        // Addresses
        $s->addresses()->delete();

        // Supplier
        $s->delete();

        return redirect('suppliers')
				->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }

    

    /**
     * Return a json list of records matching the provided query
     * @return json
     */
    public function ajaxSupplierSearch(Request $request)
    {
        $params = array();
        if (intval($request->input('name_commercial', ''))>0)
            $params['name_commercial'] = 1;
        
        return Supplier::searchByNameAutocomplete($request->input('query'), $params);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProducts($id, Request $request)
    {
        $supplier = $this->supplier->with('products')->findOrFail($id);
        $products = $supplier->products;

        // return 'OK';

        $items_per_page_products = intval($request->input('items_per_page_products', Configuration::get('DEF_ITEMS_PERPAGE')));
        // return $items_per_page_products;


        if ( !($items_per_page_products >= 0) ) 
            $items_per_page_products = Configuration::get('DEF_ITEMS_PERPAGE');

//        $products = $supplier->products->take($items_per_page_products);

        

        return view('suppliers._panel_products_list', compact('products', 'supplier', 'items_per_page_products'));
    }
}