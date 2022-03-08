<?php

namespace App\Http\Controllers\CustomerCenter;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CustomerHomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $languages = \App\Language::orderBy('name')->get();

        // ToDo: remember language using cookie :: echo Request::cookie('user_language');

        return view('abcc.home')->with(compact('languages'));
    }

    /**
     * Update DEFAULT language (application wide, not logged-in usersS).
     *
     * @return Response
     */
    public function setLanguage($id)
    {
        $language = \App\Language::findOrFail( $id );

        Cookie::queue('user_language', $language->id, 30*24*60);
        
        return redirect('/abcc');
    }
}
