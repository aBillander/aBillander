<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class ProductBOMLine extends Model
{

    use ViewFormatterTrait;

    public static $types = array(
            'product',
            'phantom',
        );
    
//    protected $guarded = array('id');

	// Don't forget to fill this array
	protected $fillable = ['line_sort_order', 'line_type', 
                    'product_id', 'quantity', 'measure_unit_id', 
                    'scrap', 'notes',
    ];

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];

    public static function getTypeList()
    {
            $list = [];
            foreach (self::$types as $type) {
                $list[$type] = l($type, [], 'appmultilang');
            }

            return $list;
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function measureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'measure_unit_id');
    }

    public function productBOM()
    {
       return $this->belongsTo('App\ProductBOM', 'product_bom_id');
    }
}
