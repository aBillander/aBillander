<?php

namespace aBillander\Installer\Controllers;

use Illuminate\Routing\Controller;

use aBillander\Installer\Helpers\RequirementsChecker;
use aBillander\Installer\Helpers\PermissionsChecker;

class RequirementsController extends Controller
{
    /**
     * Display the requirements page.
     *
     * @param  RequirementsChecker  $requirementsChecker
     * @param  PermissionsChecker  $permissionsChecker
     * @return \Illuminate\Http\Response
     */
    public function check(RequirementsChecker $requirementsChecker, PermissionsChecker $permissionsChecker)
    {
        $phpSupportInfo = $requirementsChecker->checkPHPversion(
            config('installer.core.minPhpVersion')
        );
        $requirements = $requirementsChecker->check(
            config('installer.requirements')
        );
        $permissions = $permissionsChecker->check(
            config('installer.permissions')
        );

        return view('installer::requirements', compact('phpSupportInfo', 'requirements', 'permissions'));
    }
}
