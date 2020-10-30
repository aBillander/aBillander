<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $language = app()->getLocale();

        // $this->call(UsersTableSeeder::class);        

        $this->call(ConfigurationsTableSeeder::class);

        $this->call(CountriesTableSeeder::class);

        $this->call(CurrenciesTableSeeder::class);

        $this->call(LanguagesTableSeeder::class);

        $this->call(TemplatesTableSeeder::class);

        // $this->call(__NAMESPACE__ . '\\' . $language . 'TaxesTableSeeder');

        // abi_r(esTaxesTableSeeder::class); die();

        $this->call($language . 'TaxesTableSeeder');

        $this->call($language . 'SequencesTableSeeder');

        $this->call($language . 'MeasureUnitsTableSeeder');

        $this->call($language . 'PaymentMethodsTableSeeder');

        $this->call($language . 'PaymentTypesTableSeeder');

        $this->call($language . 'CarriersTableSeeder');

        $this->call($language . 'ShippingMethodsTableSeeder');
        
    }
}
