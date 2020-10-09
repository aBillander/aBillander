<?php

namespace aBillander\Installer\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use App\Http\Requests;
use App\Company;
use App\Address;
use App\User;
use App\Configuration;

class CompanyController extends Controller
{
    protected $company;
    protected $address;
    protected $user;

    public function __construct(Company $company, Address $address, User $user)
    {
        $this->company = $company;
        $this->address = $address;
        $this->user    = $user;
    }

    /**
     * Display the installer company page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $currencyList = \App\Currency::pluck('name', 'id')->toArray();
        $languageList = \App\Language::pluck('name', 'id')->toArray();
        $countryList  = \App\Country::orderby('name', 'asc')->pluck('name', 'id')->toArray();

        return view('installer::company', compact('currencyList', 'languageList', 'countryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->company::$rules);
        $request->validate($this->address::related_rules());

        $request->merge(['notes' => $request->input('address.notes'), 'name_commercial' => $request->input('address.name_commercial')]);

        $company = $this->company->create($request->all());

        $data = $request->input('address');

        $address = $this->address->create($data);
        $company->addresses()->save($address);

        $user_data = [
            'email'     => $address->email ,
            'password'  => Hash::make( $request->input('password') ) ,
            'name'      => 'wasp' ,
            'home_page' => '/' ,
            'firstname' => 'Admin' ,
            'lastname'  => 'Billanderin' ,
            'is_admin'  => '1' ,
            'active'    => '1' ,
            'language_id' => $company->language_id ,
        ];

        $user = $this->user->create($user_data);

        // Time for some defaults!
        Configuration::updateValue( 'DEF_COMPANY',  $company->id          );
        Configuration::updateValue( 'DEF_CURRENCY', $company->currency_id );
        Configuration::updateValue( 'DEF_LANGUAGE', $company->language_id );
        Configuration::updateValue( 'DEF_COUNTRY',  $address->country_id  );

        return redirect()->route('installer::done')->with('install-finished');
    }

}
