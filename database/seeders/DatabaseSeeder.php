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

        $this->call($language . 'ConfigurationsTableSeeder');

        $this->call($language . 'TaxesTableSeeder');

        $this->call($language . 'SequencesTableSeeder');

        $this->call($language . 'MeasureUnitsTableSeeder');

        $this->call($language . 'PaymentMethodsTableSeeder');

        $this->call($language . 'PaymentTypesTableSeeder');

        $this->call($language . 'CarriersTableSeeder');

        $this->call($language . 'ShippingMethodsTableSeeder');

    }
}
