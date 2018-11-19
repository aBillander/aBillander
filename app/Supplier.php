<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $fillable = [ 'alias', 'name_fiscal', 'name_commercial', 
                            'website', 'identification', 'reference_external', 'notes', 
                            'currency_id', 'language_id', 'payment_method_id', 'active' 
                        ];

    public static $rules = array(
    	'name_fiscal' => 'required|min:2|max:128',
        'currency_id' => 'exists:currencies,id',
        'language_id' => 'exists:languages,id',
//        'payment_method_id' => 'exists:payment_methods,id',
    	);

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function products()
    {
        return $this->hasMany('App\Product', 'main_supplier_id')->orderby('name', 'asc');
    }

}
