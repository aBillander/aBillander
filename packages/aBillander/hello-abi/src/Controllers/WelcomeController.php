<?php

namespace aBillander\Installer\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view('installer::welcome');
    }

    /**
     * Set the installer language.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function setLocale(Request $request)
    {
        $request->session()->put('installer.locale', $request->lang);

        return redirect()->route('installer::license');
    }

}
