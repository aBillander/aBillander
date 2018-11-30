<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\ViewFormatterTrait;

class EcotaxRule extends Model {

    use ViewFormatterTrait;
    use SoftDeletes;

    protected $appends = ['fullName'];

    public static $types = array(
            'ecotax',
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
                $list[$type] = l($type, [], 'appmultilang');;
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
        $value = $this->ecotax->name . ' | ' . $this->name;

        return $value;
    }
    
    public function getTaxIdAttribute() 
    {
        return $this->ecotax_id;
    }

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function ecotax()
    {
        return $this->belongsTo('App\Ecotax');
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