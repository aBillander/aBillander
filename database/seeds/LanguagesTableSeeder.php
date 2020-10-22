<?php

use Illuminate\Database\Seeder;
use App\Language as Language;
  
class LanguagesTableSeeder extends Seeder {
  
    public function run() {
        Language::truncate();
  
        Language::create( [
            'name'      => 'English' ,
            'iso_code'     => 'en' ,
            'language_code'  => 'en-en' ,
            'date_format_lite' => 'm/j/Y' ,
            'date_format_full' => 'm/j/Y H:i:s' ,
            'date_format_lite_view'  => 'm/j/Y' ,
            'date_format_full_view'  => 'm/j/Y H:i:s' ,
            'active'    => '1' ,
        ] );
  
        Language::create( [
            'name'      => 'Español' ,
            'iso_code'     => 'es' ,
            'language_code'  => 'es-es' ,
            'date_format_lite' => 'd/m/Y' ,
            'date_format_full' => 'd/m/Y H:i:s' ,
            'date_format_lite_view'  => 'd/m/yy' ,
            'date_format_full_view'  => 'd/m/yy H:i:s' ,
            'active'    => '1' ,
        ] );
    }
}
