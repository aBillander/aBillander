<?php

namespace aBillander\Installer\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use App;

class WelcomeController extends Controller
{
    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome(Request $request)
    {
        $langs = config()->get('installer.supportedLocales');

        if ( $request->has('lang') )
        {
            if ( array_key_exists($request->input('lang'), $langs) )
            {
                $locale = $request->lang;
                App::setLocale($locale);
                $request->session()->put('installer.locale', $locale);
            }

        }

        $language = app()->getLocale();

        return view('installer::welcome', compact('language'));
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
