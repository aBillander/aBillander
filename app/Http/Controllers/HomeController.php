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
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loggers = \App\ActivityLogger::filter( ['log_name' => 'aBillander_messenger'] )->orderBy('id', 'desc');


        $loggers = $loggers->paginate( 2 * \App\Configuration::get('DEF_ITEMS_PERPAGE') );

        // abi_r($loggers, true);

        $loggers->setPath('home');     // Customize the URI used by the paginator

        return view('home.home', compact('loggers'));
    }
}
