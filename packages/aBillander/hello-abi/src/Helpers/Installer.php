<?php

namespace aBillander\Installer\Helpers;

class Installer
{
    /**
     * If application is already installed.
     *
     * @return bool
     */
    public static function alreadyInstalled()
    {
        return file_exists(storage_path('installed'));
    }

    /**
     * Create installed file.
     *
     * @return string
     */
    public static function registerInstallation()
    {
        $installedLogFile = storage_path('installed');

        $dateStamp  = date("Y/m/d h:i:sa");
        $dateStamp .= ' :: ' . abi_version();
        $dateStamp .= ' :: ' . abi_laravel_version();
        $dateStamp .= ' :: ' . abi_php_version();

        if (!file_exists($installedLogFile)) {
            $message = $dateStamp."\n";
            file_put_contents($installedLogFile, $message);
        } else {
            $message = $dateStamp;
            file_put_contents($installedLogFile, $message.PHP_EOL , FILE_APPEND | LOCK_EX);
        }

        return $message;
    }
}
