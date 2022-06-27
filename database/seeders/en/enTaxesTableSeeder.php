<?php

namespace Database\Seeders\en;

use Illuminate\Database\Seeder;

use App\Models\Configuration;
use App\Models\Tax;
use App\Models\TaxRule;
use App\Models\Country;
  
class enTaxesTableSeeder extends Seeder {
  
    public function run() {
        TaxRule::truncate();
        Tax::truncate();

        $country = Country::where('iso_code', '=', 'ES')->first();
        $country_id = $country->id;
  
        // Taxe here is an example
        $tax = Tax::create( [
            'id'      => '1' ,
            'name'      => 'Regular Tax' ,
            'active'    => '1' ,
                    'created_at'  => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at'  => \Carbon\Carbon::now()->toDateTimeString(),
        ] );

/*
 * Default
 */
        Configuration::updateValue('DEF_TAX', $tax->id);
        // $conf = Configuration::where('name', 'DEF_TAX')->first()->update(['value' => $tax->id]);
  
        $taxRule = TaxRule::create( [
            'country_id' => '0' ,
            'state_id'   => '0' ,
            'rule_type' => 'sales' ,

            'name'      => 'Regular Tax (21%)' ,
            'percent' => '21.0' ,

            'position' => '10' ,
            // 'tax_id'    => '1' ,  // Won't work. Why? -> 'tax_id' not in Model $fillable array
                    'created_at'  => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at'  => \Carbon\Carbon::now()->toDateTimeString(),
        ] );

        $tax->taxrules()->save($taxRule);
    }
}
