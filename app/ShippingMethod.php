<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingMethod extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'webshop_id', 'carrier_id', 'active'];

    public static $rules = array(
        'name'         => 'required|min:2|max:128',
        'carrier_id'   => 'sometimes|nullable|exists:carriers,id',
    	);

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function carrier()
    {
        return $this->belongsTo('App\Carrier', 'carrier_id');
    }
    
    public function customerorders()
    {
        return $this->hasMany('App\CustomerOrder');
    }
}