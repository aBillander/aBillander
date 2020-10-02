<?php

namespace aBillander\Installer\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Company;
use App\Address;
use App\Configuration;

class CompanyController extends Controller
{
    protected $company;
    protected $address;

    public function __construct(Company $company, Address $address)
    {
        $this->company = $company;
        $this->address = $address;
    }

    /**
     * Display the installer company page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('installer::company');
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

        return redirect()->route('installer::done')->with('install-finished');
    }

}
