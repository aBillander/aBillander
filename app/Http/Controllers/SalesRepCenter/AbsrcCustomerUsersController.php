<?php

namespace App\Http\Controllers\SalesRepCenter;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Customer;
use App\CustomerUser;
use App\Configuration;
use App\Language;

use Auth;

use Mail;

class AbsrcCustomerUsersController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $salesrep = Auth::user()->salesrep;

        // ToDo: check customer ownership

        $section = '#customeruser';
        $customer_id = $request->input('customer_id');
/*
        if ( !$request->input('allow_abcc_access', 0) )
        	return redirect(route('absrc.customers.edit', $customer_id) . $section)
                ->with('warning', l('No action is taken &#58&#58 (:id) ', ['id' => $customer_id], 'layouts') );
*/
        $customer = $this->customer->with('address')->find($customer_id);

        // Check unique
/*
        if ( $this->customeruser->where('email', $customer->address->email)->first() )
            return redirect(route('absrc.customers.edit', $customer_id) . $section)
                ->with('error', l('Duplicate email address &#58&#58 (:id) ', ['id' => $customer->address->email], 'layouts') );
*/


        $def_lang = $customer->language_id > 0 ? $customer->language_id : Configuration::get('DEF_LANGUAGE');
        $language_id = $request->input('language_id', $def_lang) > 0 ? $request->input('language_id', $def_lang) : $def_lang;
        $request->merge( ['language_id' => $language_id] );

        // $this->validate($request, ['customer_id' => 'exists:customers,id', 'email' => 'email|unique:customer_users,email']);                
        $validator = Validator::make($request->all(), CustomerUser::$rules);

    

        if ( !$validator->passes() ) {

            return response()->json(['error'=>$validator->errors()->all()]);

        }

        // Do move on!
        
        $password = \Hash::make($request->input('password'));
        $request->merge( ['password' => $password] );
        
        // $request->merge( ['language_id' => $request->input('language_id', $customer->language_id)] );

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


		return redirect(route('absrc.customers.edit', $customer_id) . $section)
				->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $customeruser->id], 'layouts') . $customeruser->getFullName());

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerUser $customeruser)
    {
//        return $this->edit( $customeruser );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerOrder  $customerorder
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
     * @param  \App\Models\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerUser $customeruser)
    {
        $salesrep = Auth::user()->salesrep;

        $section = '#customeruser';
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

            $validator = Validator::make($request->all(), \Arr::except( $vrules, array('password')));
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

		return redirect(route('absrc.customers.edit', $customer_id) . $section)
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $customeruser->id], 'layouts') . $customeruser->getFullName());

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerOrder  $customerorder
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerUser $customeruser)
    {
        $section = '#customerusers';
        $name = $customeruser->getFullName();

        $customeruser->delete();

        return redirect(url()->previous() . $section)
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
        // $salesrep = Auth::user()->salesrep;
        
        $customer_user = $this->customeruser->with('cart', 'cart.cartlines')->findOrFail($id);

        return view('absrc.customers._panel_cart_lines', ['user' => $customer_user, 'cart' => $customer_user->cart]);
    }

    public function getUser($id, Request $request)
    {
        $customer_user = $this->customeruser->findOrFail($id);

        return response()->json( ['user' => $customer_user] );
    }
}
