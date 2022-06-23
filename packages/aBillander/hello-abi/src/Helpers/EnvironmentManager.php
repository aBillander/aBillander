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
        'ABI_DOMAIN'   => 'app.abi_domain',
        'ABCC_DOMAIN'  => 'app.abcc_domain',
        'ABSRC_DOMAIN' => 'app.absrc_domain',
        'DB_HOST' => 'database.connections.mysql.host',
        'DB_PORT' => 'database.connections.mysql.port',
        'DB_DATABASE' => 'database.connections.mysql.database',
        'DB_USERNAME' => 'database.connections.mysql.username',
        'DB_PASSWORD' => 'database.connections.mysql.password',

        'MAIL_MAILER' => 'mail.default',
        'MAIL_HOST'   => 'mail.mailers.smtp.host',
        'MAIL_PORT'   => 'mail.mailers.smtp.port',
        'MAIL_USERNAME'   => 'mail.mailers.smtp.username',
        'MAIL_PASSWORD'   => 'mail.mailers.smtp.password',
        'MAIL_ENCRYPTION' => 'mail.mailers.smtp.encryption',

        'MAIL_FROM_ADDRESS' => 'mail.from.address',
        'MAIL_FROM_NAME'    => 'mail.from.name',


        'WC_STORE_URL' => 'woocommerce.store_url',
        'WC_CONSUMER_KEY'    => 'woocommerce.consumer_key',
        'WC_CONSUMER_SECRET' => 'woocommerce.consumer_secret',
        'WC_VERIFY_SSL' => 'woocommerce.verify_ssl',
        'WC_VERSION' => 'woocommerce.api_version',
        'WC_WP_TIMEOUT' => 'woocommerce.timeout',
        'WC_WEBHOOK_SECRET_PRODUCT_UPDATED' => 'woocommerce.webhooks.product_updated',
        'WC_WEBHOOK_SECRET_ORDER_CREATED' => 'woocommerce.webhooks.order_created',
    ];

    /**
     * Set the .env paths.
     */
    public function __construct()
    {
        $this->envPath = App::environmentFilePath();
    }

    static public function envToConfigTable()
    {
        return (new EnvironmentManager())->envToConfig;
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
            $configValue = Config::get($configKey);

            // Sanitize
            if ( strpos($configValue, ' ') !== FALSE )
                $configValue = '"'.$configValue.'"';

            if ( $configValue === TRUE )
                $configValue = 'true';

            if ( $configValue === FALSE )
                $configValue = 'false';

            $oldLine = $envKey.'='.$configValue;
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
