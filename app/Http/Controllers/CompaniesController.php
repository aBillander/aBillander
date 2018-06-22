<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Company as Company;
use App\Address as Address;
use App\Country as Country;
use App\Configuration as Configuration; 
use \iImage as iImage;
use View;

class CompaniesController extends Controller {


   protected $company;
   protected $address;

   public function __construct(Company $company, Address $address)
   {
        $this->company = $company;
        $this->address = $address;
   }
	/**
	 * Display a listing of the resource.
	 * GET /companies
	 *
	 * @return Response
	 */
	public function index()
	{
		if ( intval(Configuration::get('DEF_COMPANY')) > 0 ) 
			return $this->edit(intval(Configuration::get('DEF_COMPANY')));
		else
			return $this->create();
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /companies/create
	 *
	 * @return Response
	 */
	public function create()
	{
 //       return View::make('companies.create');

        if ( Configuration::get('DEF_COMPANY') > 0 ) {
        	// Company already created
        	return $this->edit(intval(Configuration::get('DEF_COMPANY')));
        } else {
        
            return View::make('companies.create');
        }
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /companies
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, $this->company::$rules);
		$this->validate($request, $this->address::related_rules());

		$request->merge(['notes' => $request->input('address.notes'), 'name_commercial' => $request->input('address.name_commercial')]);

		$company = $this->company->create( $request->all() );

		$data = $request->input('address');
//		$data['notes'] = '';
		$address = $this->address->create( $data );
		$company->addresses()->save($address);

		Configuration::updateValue('DEF_COMPANY', $this->company->id);

		return redirect('companies/'.$company->id.'/edit')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $company->id], 'layouts') . $request->input('name_fiscal'));
	}

	/**
	 * Display the specified resource.
	 * GET /companies/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /companies/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $company = $this->company->with('address')->with('currency')->findOrFail( intval($id) );

        $country = Country::find( $company->address->country_id );

        $stateList = $country ? $country->states()->pluck('name', 'id')->toArray() : [] ;
    
        return View::make('companies.edit', compact('company', 'stateList'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /companies/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$this->validate($request, $this->company::$rules);
		$this->validate($request, $this->address::related_rules());

		$company = $this->company->findOrFail($id);
		$address = $company->address;

		// Handle the user upload of company logo
		if ( $request->has('company_logo_file') ) {
			$file = $request->file('company_logo_file');
//			$filename = 'company_logo'.'.'.$file->getClientOriginalExtension();
			$filename = time().'.'.$file->getClientOriginalExtension();
//			iImage::make($file)->resize(300,300)->save( public_path('/uploads/company/'.$filename) );
			iImage::make($file)->save( public_path( \App\Company::$company_path . $filename ) );

			// Delete old image
			$old_file = public_path() . \App\Company::$company_path . \App\Context::getContext()->company->company_logo;
	        if ( \App\Context::getContext()->company->company_logo && file_exists( $old_file ) ) {
	            unlink( $old_file );
      		}

      		$request->merge([ 'company_logo' => $filename ]);
		}

		$request->merge(['notes' => $request->input('address.notes'), 'name_commercial' => $request->input('address.name_commercial')]);
		
		$company->update(  $request->all()  );

		$data = $request->input('address');
//		$data['notes'] = '';
		$address->update( $data );

		return redirect('companies/'.$company->id.'/edit')
				->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $company->id], 'layouts') . $request->input('name_fiscal'));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /companies/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		// Bad idea to reach this point...
	}

}