<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\ViewFormatterTrait;

class TaxRule extends Model {

    use ViewFormatterTrait;
    use SoftDeletes;

    protected $appends = ['fullName'];

    public static $types = array(
            'sales', 
            'sales_equalization',
        );

    protected $dates = ['deleted_at'];
    
    protected $fillable = [ 'country_id', 'state_id', 'rule_type', 'name', 'percent', 'amount', 'position' ];

    public static $rules = array(
    	'name'     => array('required'),
        'percent'  => array('nullable', 'numeric', 'between:0,100'), 
        'amount'   => array('nullable', 'numeric'),
        'position' => array('nullable', 'numeric'),      // , 'min:0')   Allow negative in case starts on 0
    	);

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
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute()
    {
        $value = $this->tax->name . ' | ' . $this->name;

        return $value;
    }

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function tax()
    {
        return $this->belongsTo('App\Tax');
	}

    public function country()
    {
        return $this->belongsTo('App\Country')
                    ->withDefault(function ($country) {
                            $country->name = '';
                        });
    }

    public function state()
    {
        return $this->belongsTo('App\State')
                    ->withDefault(function ($state) {
                            $state->name = '';
                        });
    }
}