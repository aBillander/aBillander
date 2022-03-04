<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class Ecotax extends Model {

    use ViewFormatterTrait;

//    protected $appends = ['fullName'];

    public static $types = array(
            'ecotax',
        );

    protected $dates = ['deleted_at'];

    protected $appends = ['percent', 'amount'];
    
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
                $list[$type] = l($type, [], 'appmultilang');
            }

            return $list;
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getPercentAttribute()
    {
        // Address / Company models need fixing to retrieve country ISO code
        // $country = Context::getContext()->company->address()->country_ISO;
        
        $country_id = Configuration::get('DEF_COUNTRY');
        // $country_id = Context::getContext()->company->address()->country_id;

//        $value = $this->taxrules()->where('country_id', '=', '0')->orWhere('country_id', '=', $country_id)->orderBy('position', 'asc')->first()->percent;
        $value = $this->ecotaxrules()->where(function ($query) use ($country_id) {
            $query->where('country_id', '=', '0')
                  ->OrWhere('country_id', '=', $country_id);
        })->orderBy('position', 'asc')->first()->percent;

        return $value;
    }

    public function getAmountAttribute()
    {
        $country_id = Configuration::get('DEF_COUNTRY');
        
        $value = $this->ecotaxrules()->where(function ($query) use ($country_id) {
            $query->where('country_id', '=', '0')
                  ->OrWhere('country_id', '=', $country_id);
        })->orderBy('position', 'asc')->first()->amount;

        return $value;
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    
    public function getFirstRule()
    {
        return EcotaxRule::where('ecotax_id', '=', $this->id)->orderBy('position', 'asc')->first();
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
        return $this->hasMany(EcotaxRule::class)->orderBy('position', 'asc');
    }
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}