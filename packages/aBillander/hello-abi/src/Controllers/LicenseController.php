<?php

namespace aBillander\Installer\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    /**
     * Display the installer license page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('installer::license');
    }

    /**
     * Accept the license.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function accept(Request $request)
    {
        $request->validate([
            'terms' => 'accepted',
        ]);

        return redirect()->route('installer::requirements');
    }

}
