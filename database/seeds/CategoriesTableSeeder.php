<?php

use Illuminate\Database\Seeder;
use App\Category as Category;
  
class CategoriesTableSeeder extends Seeder {
  
    public function run() {
        Category::truncate();
  
        Category::create( [
            'name'      => 'Accesorios' ,
                    'created_at'  => \Carbon\Carbon::createFromDate(2015,04,01)->toDateTimeString(),
                    'updated_at'  => \Carbon\Carbon::now()->toDateTimeString(),
        ] );
  
        Category::create( [
            'name'      => 'Recambios' ,
                    'created_at'  => \Carbon\Carbon::createFromDate(2015,04,01)->toDateTimeString(),
                    'updated_at'  => \Carbon\Carbon::now()->toDateTimeString(),
        ] );
    }
}
