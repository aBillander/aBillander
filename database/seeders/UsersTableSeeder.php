<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
  
class UsersTableSeeder extends Seeder {
  
    public function run() {
        User::truncate();
  
        User::create( [
            'email'     => 'admin@abillander.com' ,
            'password'  => Hash::make( 'password' ) ,
            'name'      => 'wasp' ,
            'home_page' => '/' ,
            'firstname' => 'Lara' ,
            'lastname'  => 'Billander' ,
            'is_admin'  => '1' ,
            'active'    => '1' ,
            'language_id' => '1' ,
                    'created_at'  => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at'  => \Carbon\Carbon::now()->toDateTimeString(),
        ] );

/*
        User::create( [
            'email'     => 'user@abillander.com' ,
            'password'  => Hash::make( 'password' ) ,
            'name'      => 'hornet' ,
            'home_page' => '/' ,
            'firstname' => 'Markus' ,
            'lastname'  => 'Billander' ,
            'is_admin'  => '0' ,
            'active'    => '1' ,
            'language_id' => '1' ,
                    'created_at'  => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at'  => \Carbon\Carbon::now()->toDateTimeString(),
        ] );
*/
    }
}
