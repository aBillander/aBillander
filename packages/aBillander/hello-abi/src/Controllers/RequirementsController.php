<?php

namespace aBillander\Installer\Controllers;

// use Illuminate\Support\Facades\Artisan;
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
        // https://stackoverflow.com/questions/42560480/how-to-refresh-configuration-variables-from-package-in-laravel-5-3
        // Artisan::call("config:cache");

        if ( file_exists(storage_path('logs/laravel.log')) )
        {
            // Delete
            @unlink(storage_path('logs/laravel.log'));
        }

        $phpSupportInfo = $requirementsChecker->checkPHPversion(
            config('installer.core.minPhpVersion')
        );
        $requirements = $requirementsChecker->check(
            config('installer.requirements')
        );
        $permissions = $permissionsChecker->check(
            config('installer.permissions')
        );

        $noErrors = $phpSupportInfo['supported'] && !isset($requirements['errors']) && !isset($permissions['errors']);

        return view('installer::requirements', compact('phpSupportInfo', 'requirements', 'permissions', 'noErrors'));
    }
}
