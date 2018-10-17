<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Customer;
use App\CustomerUser;
use App\Configuration;
use App\Language;

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
//		$users = $this->user->with('language')->orderBy('id', 'ASC')->get();

//		return view('users.index', compact('users'));
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createWithCustomer($customer_id)
    {
        
        return view('customer_orders.create', compact('sequenceList', 'customer_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $section = '#customeruser';
        $customer_id = $request->input('customer_id');

        if ( !$request->input('allow_abcc_access', 0) )
        	return redirect(route('customers.edit', $customer_id) . $section)
                ->with('warning', l('No action is taken &#58&#58 (:id) ', ['id' => $customer_id], 'layouts') );

		$this->validate($request, ['customer_id' => 'exists:customers,id']);

		$customer = $this->customer->with('address')->find($customer_id);

		$data = [
				'name' => '', 
				'email' => $customer->address->email, 
				'password' => \Hash::make( Configuration::get('ABCC_DEFAULT_PASSWORD') ), 
				'firstname' => $customer->address->firstname, 
				'lastname' => $customer->address->lastname, 
				'active' => 1, 
				'language_id' => $customer->language_id, 
				'customer_id' => $customer->id,
		];

		$customeruser = $this->customeruser->create($data);

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
        $section = '#customeruser';
        $customer_id = $request->input('customer_id');

		if ( $request->input('password') != '' ) {
			$this->validate( $request, CustomerUser::$rules );

			$password = \Hash::make($request->input('password'));
			$request->merge( ['password' => $password] );
			$customeruser->update($request->all());
		} else {
			$this->validate($request, array_except( CustomerUser::$rules, array('password')) );
			$customeruser->update($request->except(['password']));
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
        $customeruser->delete();

        return redirect('customerorders')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }

}
