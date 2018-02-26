<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeasureUnit extends Model {

    use SoftDeletes;

    public static $types = array(
            'Quantity', 
            'Length',
            'Area',
            'Liquid Volume',
            'Dry Volume',
            'Mass',
            'Time',
        );

    protected $dates = ['deleted_at'];
	
    protected $fillable = [ 'type', 'sign', 'name', 
                            'decimalPlaces', 
                            'conversion_rate', 'active'
                          ];

    public static $rules = array(
        'sign'    => array('required', 'min:1', 'max:8'),
        'name'    => array('required', 'min:2', 'max:32'),
 //       'product_type' => 'required|in:simple,virtual,combinable,grouped',
        'type' => 'required',
        'decimalPlaces' => array('required', 'min:0'),
 //   	'conversion_rate' => array('required', 'min:0'),
    	);


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */


    public static function getTypeList()
    {
            $list = [];
            foreach (self::$types as $type) {
                $list[$type] = l($type, [], 'appmultilang');;
            }

            return $list;
    }
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    // 
}