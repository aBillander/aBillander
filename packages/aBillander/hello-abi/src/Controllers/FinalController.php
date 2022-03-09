<?php

namespace aBillander\Installer\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class FinalController extends Controller
{
    /**
     * Display the installer finish page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('installer::done');
    }

}
