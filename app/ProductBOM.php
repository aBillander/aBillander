<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class ProductBOM extends Model
{

    use ViewFormatterTrait;

    public static $statuses = array(
            'certified',
            'new',
            'development',
            'closed',
        );

    protected $fillable =  ['alias', 'name', 'quantity', 'measure_unit_id',
                            'status', 'notes',
                            ];

	// Add your validation rules here
	public static $rules = [
                            'alias'    => 'required|min:2|max:32',
                            'name'     => 'required|min:2|max:128',
                            'measure_unit_id' => 'exists:measure_units,id',
	];

    public static function getStatusList()
    {
            $list = [];
            foreach (self::$statuses as $status) {
                $list[$status] = l($status, [], 'appmultilang');
            }

            return $list;
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function measureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'measure_unit_id');
    }
    
    public function BOMlines()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->hasMany('App\ProductBOMLine', 'product_bom_id')->orderBy('line_sort_order', 'ASC');
    }
    
    public function bomitems()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->hasMany('App\BOMItem', 'product_bom_id');
    }
    
    public function products()
    {
        return $this->hasManyThrough('App\Product', 'App\BOMItem', 'product_bom_id', 'id', 'id', 'product_id')->with('measureunit');
    }
}
