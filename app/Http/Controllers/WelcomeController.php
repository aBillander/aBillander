<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Request, Cookie;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// $this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		if( \Auth::check() )
			return redirect('/home');
		else
			return redirect('/login');
	}

	/**
	 * Update DEFAULT language (application wide, not logged-in usersS).
	 *
	 * @return Response
	 */
	public function setLanguage($id)
	{
		$language = Language::findOrFail( $id );

		Cookie::queue('user_language', $language->id, 30*24*60);
		
		return redirect('/');
	}

}
