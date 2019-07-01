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
        // $this->call(UsersTableSeeder::class);

        $this->call(ConfigurationsTableSeeder::class);

        $this->call(CountriesTableSeeder::class);

        $this->call(CurrenciesTableSeeder::class);

        $this->call(LanguagesTableSeeder::class);

        $this->call(TaxesTableSeeder::class);

        $this->call(SequencesTableSeeder::class);

        $this->call(MeasureUnitsTableSeeder::class);

        $this->call(PaymentMethodsTableSeeder::class);

        $this->call(TemplatesTableSeeder::class);

        $this->call(PaymentDocumentsTableSeeder::class);
        
    }
}
