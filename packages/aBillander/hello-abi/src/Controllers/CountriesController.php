<?php

namespace aBillander\Installer\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use App\Models\Country;

class CountriesController extends Controller {


   protected $country;

   public function __construct(Country $country)
   {
        $this->country = $country;
   }


    public function getStates($countryId)
    {
        // if ( request->ajax() )
        $country = $this->country->find($countryId);

        $states = $country ? $country->states()->getQuery()->get(['id', 'name']) : [] ;

        return Response::json($states);
    }

}
