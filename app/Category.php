<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{

    protected $fillable = ['name', 'position', 'publish_to_web', 'webshop_id', 'reference_external',
                           'is_root', 'active', 'parent_id',
    ];

    public static $rules = [
        'main_data' => [
            'name' => ['required', 'min:2', 'max:128'],
        ],
        'internet'  => [

        ],
    ];


    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeIsActive($query)
    {
        return $query->where('active', '>', 0);
    }

    public function scopeIsPublished($query)
    {
        return $query->where('publish_to_web', '>', 0);
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function parent()
    {

        return $this->belongsTo('App\Category', 'parent_id', 'id');

    }

    public function children()
    {

        return $this->hasMany('App\Category', 'parent_id', 'id');

    }

    public function activechildren()
    {
        return $this->hasMany('App\Category', 'parent_id', 'id')->where('active', '>', 0);    // ->IsActive() ;
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function customerproducts($customer_id = null, $currency_id = null)
    {
        $customer_user = Auth::user();

        return $this->hasMany('App\Product')
                    ->IsSaleable()
                    ->IsAvailable()
                    ->qualifyForCustomer($customer_user->customer_id, $customer_user->customer->currency->id)
                    ->get();
    }

}