<?php

namespace aBillander\Installer\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class EnvironmentManager
{
    /**
     * @var string
     */
    private $envPath;

    /**
     * The equivalence between .env and config keys.
     *
     * @var array
     */
    public $envToConfig = [
        'APP_URL' => 'app.url',
        'DB_HOST' => 'database.connections.mysql.host',
        'DB_PORT' => 'database.connections.mysql.port',
        'DB_DATABASE' => 'database.connections.mysql.database',
        'DB_USERNAME' => 'database.connections.mysql.username',
        'DB_PASSWORD' => 'database.connections.mysql.password',

        'MAIL_DRIVER' => 'mail.driver',
        'MAIL_HOST'   => 'mail.host',
        'MAIL_PORT'   => 'mail.port',
        'MAIL_USERNAME'   => 'mail.username',
        'MAIL_PASSWORD'   => 'mail.password',
        'MAIL_ENCRYPTION' => 'mail.encryption',

        'MAIL_FROM_ADDRESS' => 'mail.from.address',
        'MAIL_FROM_NAME'    => 'mail.from.name',
    ];

    /**
     * Set the .env paths.
     */
    public function __construct()
    {
        $this->envPath = App::environmentFilePath();
    }

    /**
     * Save the edited configuration to the .env file.
     *
     * @param array $newValues
     * @return string
     */
    public function setValues($newValues)
    {
        $environment = file_get_contents($this->envPath);

        foreach ($newValues as $envKey => $newValue) {
            $configKey = $this->envToConfig[$envKey];

            $oldLine = $envKey.'='.Config::get($configKey);
            $newLine = $envKey.'='.$newValue;

            $environment = str_replace($oldLine, $newLine, $environment);

            Config::set($configKey, $newValue);
        }

        // Save .env file
        file_put_contents($this->envPath, $environment);

        // Clear the cached config
        Artisan::call("config:clear");
    }
}
