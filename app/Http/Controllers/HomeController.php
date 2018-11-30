<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $logger = \App\ActivityLogger::setup( 'aBillander Project Updates', 'devMessenger-'.md5( config('app.url') ) );


        $loggers = $logger->activityloggerlines()->orderBy('id', 'desc')->paginate( 2 * \App\Configuration::get('DEF_ITEMS_PERPAGE') );

        // abi_r($loggers, true);

        $loggers->setPath('home');     // Customize the URI used by the paginator



        return view('home.home', compact('loggers'));
    }
}
