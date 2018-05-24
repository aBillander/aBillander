<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\ViewFormatterTrait;

class PriceListLine extends Model {

    use ViewFormatterTrait;
//    use SoftDeletes;

//    protected $dates = ['deleted_at'];
    
    protected $fillable = [ 'price_list_id', 'product_id', 'price' ];

    public static $rules = array(
    	'product_id' => array('required|exists:products,id'),
        'price'      => array('numeric'), 
    	);

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function pricelist()
    {
        return $this->belongsTo('App\PriceList', 'price_list_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}