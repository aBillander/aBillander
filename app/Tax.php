<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\ViewFormatterTrait;

use App\TaxRule as TaxRule;

class Tax extends Model {

    use ViewFormatterTrait;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $appends = ['percent'];
    
    protected $fillable = [ 'name', 'active' ];

    public static $rules = array(
    	'name'    => array('required', 'min:2', 'max:64'),
        'country_id' => 'exists:countries,id',
 //   	'percent' => array('required', 'numeric', 'between:0,100')
    	);
    
    
    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getPercentAttribute()
    {
        // Address / Company models need fixing to retrieve country ISO code
        // $country = Context::getContext()->company->address()->country_ISO;
        
        $country_id = \App\Configuration::get('DEF_COUNTRY');
        // $country_id = \App\Context::getContext()->company->address()->country_id;

//        $value = $this->taxrules()->where('country_id', '=', '0')->orWhere('country_id', '=', $country_id)->orderBy('position', 'asc')->first()->percent;
        $value = optional($this->taxrules()->where(function ($query) use ($country_id) {
                    $query->where('country_id', '=', '0')
                          ->orWhere('country_id', '=', $country_id);
                })->orderBy('position', 'asc')->first())->percent ?? 0.0;

        return $value;
    }


    public function getEqualizationPercentAttribute()
    {
        $country_id = \App\Configuration::get('DEF_COUNTRY');

        $value = optional($this->taxrules()->where(function ($query) use ($country_id) {
                    $query->where('country_id', '=', '0')
                          ->orWhere('country_id', '=', $country_id);
                })
                ->where('rule_type', '=', 'sales_equalization')
                ->orderBy('position', 'asc')->first())->percent ?? 0.0;

        return $value;
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    
    public function getFirstRule()
    {
        return TaxRule::where('tax_id', '=', $this->id)->orderBy('position', 'asc')->first();
    }

    public function getTaxPercent( \App\Address $address = null, $with_sales_equalization = 0)
    {
        if ( !$address ) $address = \App\Context::getContext()->company->address;

        $rules = $address->getTaxRules( $this );    // Only rule_type = sales

        $percent = $rules->where('rule_type', '=', 'sales')->sum('percent');

        // No effect: $rules are only rule_type = sales
        if ($with_sales_equalization)
            $percent += $rules->where('rule_type', '=', 'sales_equalization')->sum('percent');

        return $percent;
    }
	
    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function taxrules()
    {
        return $this->hasMany('App\TaxRule')->orderBy('position', 'asc');
    }
    
    public function products()
    {
        return $this->hasMany('App\Product');
    }
	
}