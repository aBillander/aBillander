<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\ViewFormatterTrait;

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
    

    public static function boot()
    {
        parent::boot();


        static::deleting(function ($tax)
        {
            // before delete() method call this
            $relations = [
                    'products',
                    'shippingmethods',
                    'cartlines',

                    'customerquotationlines',
                    'customerorderlines',
                    'customershippingsliplines',
                    'customerinvoicelines',
 //                   'warehouseshippingsliplines',

                    'supplierorderlines',
                    'suppliershippingsliplines',
                    'supplierinvoicelines',
            ];

            // load relations
            $tax->load( $relations );

            // Check Relations
            foreach ($relations as $relation) {
                # code...
                if ( $tax->{$relation}->count() > 0 )
                    throw new \Exception( l('Tax has :relation', ['relation' => $relation], 'exceptions') );
            }

        });

        static::deleted(function ($tax)
        {
            // After delete() method call this
            $tax->taxrules()->delete();
        });

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
        $value = optional($this->taxrules()->where(function ($query) use ($country_id) {
                    $query->where('country_id', '=', '0')
                          ->orWhere('country_id', '=', $country_id);
                })
                ->where('rule_type', '=', 'sales')
                ->orderBy('position', 'asc')->first())->percent ?? 0.0;

        return $value;
    }


    public function getEqualizationPercentAttribute()
    {
        $country_id = Configuration::get('DEF_COUNTRY');

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

    public function getTaxPercent( Address $address = null, $with_sales_equalization = 0)
    {
        if ( !$address ) $address = Context::getContext()->company->address;

        $rules = $address->getTaxRules( $this );    

        // Only rule_type = sales
        $rules_sales = $rules->where('rule_type', '=', 'sales');
        if ( $rules_sales->count() == 0 )       // No rules found for supplied address
            return null;

        $percent = $rules_sales->sum('percent');

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
        return $this->hasMany(TaxRule::class)->orderBy('position', 'asc');
    }
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
    public function shippingmethods()
    {
        return $this->hasMany(ShippingMethod::class);
    }
    
    public function cartlines()
    {
        return $this->hasMany(CartLine::class);
    }
	
    
    public function customerquotationlines()
    {
        return $this->hasMany(CustomerQuotationLine::class);
    }
    
    public function customerorderlines()
    {
        return $this->hasMany(CustomerOrderLine::class);
    }

    public function customershippingsliplines()
    {
        return $this->hasMany(CustomerShippingSlipLine::class);
    }

    public function customerinvoicelines()
    {
        return $this->hasMany(CustomerInvoiceLine::class);
    }


    public function supplierorderlines()
    {
        return $this->hasMany(SupplierOrderLine::class);
    }

    public function suppliershippingsliplines()
    {
        return $this->hasMany(SupplierShippingSlipLine::class);
    }

    public function supplierinvoicelines()
    {
        return $this->hasMany(SupplierInvoiceLine::class);
    }

}