<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Customer;
use App\SalesRepUser;
use App\Configuration;
use App\Language;

use Mail;

class SalesRepUsersController extends Controller
{
    //

   protected $salesrepuser, $salesrep;

   public function __construct(SalesRepUser $salesrepuser, Customer $customer)
   {
        $this->salesrepuser = $salesrepuser;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $section = '#salesrepuser';
        $customer_id = $request->input('customer_id');

        if ( !$request->input('allow_abcc_access', 0) )
        	return redirect(route('customers.edit', $customer_id) . $section)
                ->with('warning', l('No action is taken &#58&#58 (:id) ', ['id' => $customer_id], 'layouts') );

        $customer = $this->customer->with('address')->find($customer_id);

        // Check unique
        if ( $this->customeruser->where('email', $customer->address->email)->first() )
            return redirect(route('customers.edit', $customer_id) . $section)
                ->with('error', l('Duplicate email address &#58&#58 (:id) ', ['id' => $customer->address->email], 'layouts') );

        $this->validate($request, ['customer_id' => 'exists:customers,id', 'email' => 'email|unique:customer_users,email']);

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

        // Check existence
        if ( $customeruser = $this->customeruser->where('email', $customer->address->email)->withTrashed()->first() )
        {
            $customeruser->active=1;
            $customeruser->deleted_at=null;    // or $customeruser->restore(); 
            $customeruser->save();
        }
		else
            $customeruser = $this->customeruser->create($data);
        // Notify SalesRep
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
                'from'     => \App\Configuration::get('ABCC_EMAIL'),         // config('mail.from.address'  ),
                'fromName' => \App\Configuration::get('ABCC_EMAIL_NAME'),    // config('mail.from.name'    ),
                'to'       => $customer->address->email,         // $cinvoice->customer->address->email,
                'toName'   => $customer->name_fiscal,    // $cinvoice->customer->name_fiscal,
                'subject'  => l(' :_> Confirmación de acceso al Centro de Clientes de :company', ['company' => \App\Context::getcontext()->company->name_fiscal]),
                );

            

            $send = Mail::send('emails.'.\App\Context::getContext()->language->iso_code.'.invitation_confirmation', $template_vars, function($message) use ($data)
            {
                $message->from($data['from'], $data['fromName']);

                $message->to( $data['to'], $data['toName'] )->bcc( $data['from'] )->subject( $data['subject'] );    // Will send blind copy to sender!

            }); 

        } catch(\Exception $e) {

             abi_r($e->getMessage());

/*            return redirect()->route('abcc.orders.index')
                    ->with('error', l('There was an error. Your message could not be sent.', [], 'layouts').'<br />'.
                        $e->getMessage());
*/
            // return false;
        }
        // MAIL stuff ENDS


		return redirect(route('customers.edit', $customer_id) . $section)
				->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $customeruser->id], 'layouts') . $customeruser->getFullName());

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function show(SalesRepUser $customeruser)
    {
//        return $this->edit( $customeruser );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function edit(SalesRepUser $customeruser)
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
    public function update(Request $request, SalesRepUser $customeruser)
    {
        $section = '#customeruser';
        $customer_id = $request->input('customer_id');

        $vrules = array(
            'email'       => 'required|email',
            'password'    => array('required', 'min:6', 'max:32'),
        );

        if ( isset($vrules['email']) ) $vrules['email'] = 'email|unique:customer_users,email' . ','. $customeruser->id.',id';  // Unique

		if ( $request->input('password') != '' ) {
			$this->validate( $request, $vrules );

			$password = \Hash::make($request->input('password'));
			$request->merge( ['password' => $password] );
			$customeruser->update($request->all());
		} else {
			$this->validate($request, array_except( $vrules, array('password')) );
			$customeruser->update($request->except(['password']));
		}

        if ( ! $request->input('active') )
        {
            $customeruser->delete();

            return redirect(route('customers.edit', $customer_id) . $section)
                    ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $customer_id], 'layouts'));
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
    public function destroy(SalesRepUser $customeruser)
    {
        $customeruser->delete();

        return redirect('customerorders')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
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
}
