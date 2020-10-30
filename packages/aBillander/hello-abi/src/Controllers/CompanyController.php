<?php

namespace aBillander\Installer\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use App\Http\Requests;
use App\Company;
use App\Warehouse;
use App\Address;
use App\User;
use App\Configuration;

class CompanyController extends Controller
{
    protected $company;
    protected $address;
    protected $user;

    public function __construct(Company $company, Address $address, Warehouse $warehouse, User $user)
    {
        $this->company = $company;
        $this->address = $address;
        $this->warehouse = $warehouse;
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

        // Company
        $company = $this->company->create($request->all());

        $data = $request->input('address');

        $address = $this->address->create($data);
        $company->addresses()->save($address);

        // Another step forward: default Warehouse
        $warehouse = $this->warehouse->create([
            'name'      => $company->name_commercial,
            'alias'     => $address->alias,
            'active'    => '1' ,
        ]);
        $addressw = $this->address->create($data);  // Same Address
        $warehouse->addresses()->save($addressw);

        // User
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
        // Configuration is not loaded so far...
        $confs = Configuration::where('name', 'like', 'DEF_%')->get();

        $infos = [

                [ 'name' => 'DEF_COMPANY',   'value' => $company->id          ],
                [ 'name' => 'DEF_CURRENCY',  'value' => $company->currency_id ],
                [ 'name' => 'DEF_LANGUAGE',  'value' => $company->language_id ],
                [ 'name' => 'DEF_COUNTRY',   'value' => $address->country_id  ],
                [ 'name' => 'DEF_WAREHOUSE', 'value' => $warehouse->id        ],

        ];

        foreach ($infos as $info) {
            # code...
            $conf = $confs->where('name', $info['name'])->first();

            if ($conf)
            {
                $conf->value = $info['value'];
                $conf->save();

            } else {
                $newConfig = Configuration::create($info);
                // Or:
                // Configuration::updateValue( $info['name'], $info['value'] );
            }
        }

        return redirect()->route('installer::done')->with('install-finished');
    }

}
