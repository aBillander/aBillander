<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();


        $language = app()->getLocale();

        // $this->call(UsersTableSeeder::class);        

        $this->call(ConfigurationsTableSeeder::class);

        $this->call(CountriesTableSeeder::class);

        $this->call(CurrenciesTableSeeder::class);

        $this->call(LanguagesTableSeeder::class);

        $this->call(TemplatesTableSeeder::class);

        // $this->call(__NAMESPACE__ . '\\' . $language . 'TaxesTableSeeder');

        // abi_r(esTaxesTableSeeder::class); die();

        $this->call('\\Database\\Seeders\\'.$language.'\\'.$language . 'ConfigurationsTableSeeder');

        $this->call('\\Database\\Seeders\\'.$language.'\\'.$language . 'TaxesTableSeeder');

        $this->call('\\Database\\Seeders\\'.$language.'\\'.$language . 'SequencesTableSeeder');

        $this->call('\\Database\\Seeders\\'.$language.'\\'.$language . 'MeasureUnitsTableSeeder');

        $this->call('\\Database\\Seeders\\'.$language.'\\'.$language . 'PaymentMethodsTableSeeder');

        $this->call('\\Database\\Seeders\\'.$language.'\\'.$language . 'PaymentTypesTableSeeder');

        $this->call('\\Database\\Seeders\\'.$language.'\\'.$language . 'CarriersTableSeeder');

        $this->call('\\Database\\Seeders\\'.$language.'\\'.$language . 'ShippingMethodsTableSeeder');

    }
}
