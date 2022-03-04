<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class ShippingMethodTable extends Model {

    use ViewFormatterTrait;

    protected $dates = ['deleted_at'];

    protected $appends = ['percent'];
    
    protected $fillable = [ 'name', 'active' ];

    public static $rules = array(
    	'name'    => array('required', 'min:2', 'max:64'),
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
        
        $country_id = Configuration::get('DEF_COUNTRY');
        // $country_id = Context::getContext()->company->address()->country_id;

//        $value = $this->taxrules()->where('country_id', '=', '0')->orWhere('country_id', '=', $country_id)->orderBy('position', 'asc')->first()->percent;
        $value = optional($this->taxrules()->where(function ($query) use ($country_id) {
                    $query->where('country_id', '=', '0')
                          ->OrWhere('country_id', '=', $country_id);
                })->orderBy('position', 'asc')->first())->percent ?? 0.0;

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

    public function getTaxPercent( Address $address = null, $with_sales_equalization = 0)
    {
        if ( !$address ) $address = Context::getContext()->company->address;

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

    public function servicelines()
    {
        return $this->morphMany(ShippingMethodServiceLine::class, 'tabulable');
    }
    
    public function shippingmethod()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }
	
}