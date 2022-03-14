<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;

use App\Events\CustomerRegistered;

use App\Customer;
use App\Address;
use App\CustomerUser;

class SalesRepRegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/abcc/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:customer');
    }

    public function showRegistrationForm()
    {
      $languages = \App\Language::orderBy('name')->get();

      $countryList = \App\Country::where('id', \App\Configuration::getInt('DEF_COUNTRY'))->orderby('name', 'asc')->pluck('name', 'id')->toArray();

      $stateList = \App\State::where('country_id', \App\Configuration::getInt('DEF_COUNTRY'))->orderby('name', 'asc')->pluck('name', 'id')->toArray();

      // ToDo: remember language using cookie :: echo Request::cookie('user_language');

      return view('auth.customer_register')->with(compact('languages', 'countryList', 'stateList'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // return redirect($this->redirectPath());

        $this->validator($request->all())->validate();

        // event(new Registered($user = $this->create($request->all())));

        // Create Customer
        $customer = $this->createCustomer($request);
        $customer = $customer->fresh();     // ->load('address');

        // Create CustomerUser con active=0
        // $customer = $this->customer->with('address')->find($customer_id);

        $data = [
                'name' => '', 
//                'email' => $customer->address->email, 
                'password' => \Hash::make( $request->input('password') ), 
                'firstname' => $request->input('address.firstname'), 
                'lastname' => $request->input('address.lastname'), 
                'active' => 0, 
                'language_id' => $customer->language_id, 
                'customer_id' => $customer->id,
        ];

        $request->merge( $data );

        // $customeruser = CustomerUser::create($request->all());
        // $customeruser = $customeruser->fresh();     // ->load('customer');

        event(new CustomerRegistered($customer));
/*
        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
*/
        return redirect($this->redirectPath())
                ->with('success', l('Your request has been sent. Check your email for further instructions.', 'layouts') . $request->input('name'));
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('customer');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
//            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customer_users',
//            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }



    /**
     * Create a new Customer instance.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function createCustomer(Request $request)
    {

        // Prepare address data
        $address = $request->input('address');

        $request->merge( ['outstanding_amount_allowed' => \App\Configuration::get('DEF_OUTSTANDING_AMOUNT')] );

        if ( !$request->input('address.name_commercial') ) {

            $request->merge( ['name_commercial' => $request->input('name_fiscal')] );
            $address['name_commercial'] = $request->input('name_fiscal');
        } else {

            $request->merge( ['name_commercial' => $request->input('address.name_commercial')] );
        }

//        $this->validate($request, Customer::$rules);

        $address['alias'] = l('Main Address', [],'addresses');
        $address['email'] = $request->input('email');

        $request->merge( ['address' => $address] );

//        $this->validate($request, Address::related_rules());

        if ( !$request->has('language_id') ) $request->merge( ['language_id' => \App\Configuration::get('DEF_LANGUAGE')] );

        if ( !$request->has('currency_id') ) $request->merge( ['currency_id' => \App\Configuration::get('DEF_CURRENCY')] );

        if ( !$request->has('payment_days') ) $request->merge( ['payment_days' => null] );

        // ToDO: put default accept einvoice in a configuration key
        
        $customer = Customer::create($request->all());

        $data = $request->input('address');

        $address = Address::create($data);
        $customer->addresses()->save($address);

        $customer->invoicing_address_id = $address->id;
        $customer->shipping_address_id  = $address->id;
        $customer->save();

        return $customer;

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Create a new CustomerUser instance.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function createUser(Request $request)
    {
        return ;
    }
}
