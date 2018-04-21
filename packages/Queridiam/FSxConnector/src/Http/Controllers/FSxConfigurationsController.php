<?php 

namespace Queridiam\FSxConnector\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class FSxConfigurationsController extends Controller {


   public function __construct( )
   {
        //
   }

	/**
	 * Display a listing of the resource.
	 * GET /something
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		return view('fsx_connector::fsx_configurations.index');
	}
}


/* ********************************************************************************************* */   

