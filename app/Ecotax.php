<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

use App\TaxRule as TaxRule;

class Ecotax extends Model {

    use ViewFormatterTrait;

//    protected $appends = ['fullName'];

    public static $types = array(
            'ecotax',
        );

    protected $dates = ['deleted_at'];
    
    protected $fillable = [ 'name', 'active' ];
//    protected $fillable = [ 'country_id', 'state_id', 'rule_type', 'name', 'percent', 'amount', 'position' ];

    public static $rules = array(
    	'name'     => array('required', 'min:2', 'max:64'),
//        'percent'  => array('nullable', 'numeric', 'between:0,100'), 
//        'amount'   => array('nullable', 'numeric'),
//        'position' => array('nullable', 'numeric'),      // , 'min:0')   Allow negative in case starts on 0
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
    
    public function getFirstRule()
    {
        return TaxRule::where('tax_id', '=', $this->id)->orderBy('position', 'asc')->first();
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function ecotaxrules()
    {
        return $this->hasMany('App\EcotaxRule')->orderBy('position', 'asc');
    }
    
    public function products()
    {
        return $this->hasMany('App\Product');
    }
}