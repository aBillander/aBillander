<?php

namespace aBillander\Installer\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use aBillander\Installer\Helpers\LicenseReader;

class LicenseController extends Controller
{
    /**
     * Display the installer license page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $license = LicenseReader::getText();

        return view('installer::license', compact('license'));
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
