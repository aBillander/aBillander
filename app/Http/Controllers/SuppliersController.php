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

class SuppliersController extends Controller {


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
 //                       ->filter( $request->all() )
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
		$supplier = Supplier::findOrFail($id);

		$this->validate($request, Supplier::$rules);

		$supplier->update($request->all());

		return redirect('suppliers')
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name'));
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

}