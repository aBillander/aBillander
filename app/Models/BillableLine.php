<?php 

namespace App\Models;

use App\Helpers\Price;
use App\Traits\BillableLineProfitabilityTrait;
use App\Traits\BillableLineTrait;
use App\Traits\ViewFormatterTrait;
use Illuminate\Database\Eloquent\Model;

// use ReflectionClass;

class BillableLine extends Model
{
    use BillableLineTrait;
    use BillableLineProfitabilityTrait;
    use ViewFormatterTrait;

    public static $types = array(
            'product',
            'service', 
            'shipping', 
            'discount', 
            'comment',
            'account',      // Something to charge to an accounting account
        );
    

    protected $fillable = ['line_sort_order', 'line_type', 
                    'product_id', 'combination_id', 'reference', 'name', 
                    'quantity', 'extra_quantity', 'extra_quantity_label', 'measure_unit_id',
                    'lot_references', 
                    'package_measure_unit_id', 'pmu_conversion_rate', 'pmu_label', 
                    'cost_price', 'cost_average', 'unit_price', 'unit_customer_price', 
                    'prices_entered_with_tax',
                    'unit_customer_final_price', 'unit_customer_final_price_tax_inc', 
                    'unit_final_price', 'unit_final_price_tax_inc', 
                    'sales_equalization', 'discount_percent', 'discount_amount_tax_incl', 'discount_amount_tax_excl', 
                    'total_tax_incl', 'total_tax_excl', // Not fillable? For sure: NOT. Totals are calculated after ALL taxes are set. BUT handy fillable when importing order!!!
                    'tax_percent', 'ecotax_amount', 'ecotax_total_amount', 'commission_percent', 'notes', 'locked',
 //                 'customer_shipping_slip_id',
                    'tax_id', 'sales_rep_id',
    ];

    protected $line_fillable = [
    ];
    

    /**
     * Get the fillable attributes for the model.
     *
     * https://gist.github.com/JordanDalton/f952b053ef188e8750177bf0260ce166
     *
     * @return array
     */
    public function getFillable()
    {
        return array_merge(parent::getFillable(), $this->line_fillable);
    }


    protected static function boot()
    {
        parent::boot();

        // https://laracasts.com/discuss/channels/general-discussion/deleting-related-models
        // https://laracasts.com/discuss/channels/eloquent/laravel-delete-model-with-all-relations
        static::deleting(function ($line)
        {
            // before delete() method call this
            foreach($line->taxes as $linetax) {
                $linetax->delete();
            }

            // Unlink Stock Movements
            foreach( $line->stockmovements as $mvt ) {
                // $mvt->delete();

                $mvt->stockmovementable_id   = 0;
                $mvt->stockmovementable_type = '';
                $mvt->save();
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function getDocumentRoute()
    {
            static $segment;

            if ($segment) return $segment;

            $str = get_class($this);    // Otherwise $str = BillableLine

            // $segments = array_reverse(explode('\\', $str));
            $segments = explode('\\', $str);


            // Last segment
            $str = end($segments);
            $str = substr( $str, 0, strpos($str, "Line") );

            $segment = \Str::plural(strtolower($str));

            return $segment;
    }


    // Kind of deprecated function. Try not to use.
    public function getQuantityTotalAttribute()
    {
        return $this->quantity + $this->extra_quantity;
    }


    public static function getTypeList()
    {
            $list = [];
            foreach (self::$types as $type) {
                $list[$type] = l(get_called_class().'.'.$type, [], 'appmultilang');
            }

            return $list;
    }

    public static function getTypeName( $type )
    {
            return l(get_called_class().'.'.$type, [], 'appmultilang');
    }




    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function document()
    {
       return $this->belongsTo($this->getParentClassName(), $this->getParentClassSnakeCase().'_id');
    }
    
    


    public function product()
    {
       return $this->belongsTo(Product::class);
    }

    public function combination()
    {
       return $this->belongsTo(Combination::class);
    }

    public function measureunit()
    {
        return $this->belongsTo(MeasureUnit::class, 'measure_unit_id');
    }

    public function packagemeasureunit()
    {
        return $this->belongsTo(MeasureUnit::class, 'package_measure_unit_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function ecotax()
    {
        return $this->belongsTo(Ecotax::class);
    }

    /**
     * Get all of the billable's Stock Movements.
     */
    public function stockmovements()
    {
        return $this->morphMany( StockMovement::class, 'stockmovementable' );
    }


    public function lotitems()
    {
        return $this->morphMany(LotItem::class, 'lotable')->with('lot');
    }

    public function getLotsAttribute()
    {
        // Document line -> stock movements (one or more) -> lot (one per movement)
        // see: https://stackoverflow.com/questions/43285779/laravel-polymorphic-relations-has-many-through

        if (!$this->relationLoaded('lotitems.lot')) {
            $this->load('lotitems.lot');
        }

        return $this->lotitems->pluck('lot');
    }

    

    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Pump it up!
    |--------------------------------------------------------------------------
    */

    // Kind of useless???
    public function getTaxableAmount()
    {
        $amount = $this->quantity*$this->unit_final_price;

        if ( Configuration::isFalse('ENABLE_ECOTAXES') ) return $amount;

        if ( $this->line_type == 'service' )             return $amount;
        if ( $this->line_type == 'shipping' )            return $amount;

        if ( $this->product->ecotax_id <= 0 )            return $amount;

        if ( Configuration::isTrue('PRICES_ENTERED_WITH_ECOTAX') ) return $amount;
        
        // Apply Eco-Tax

        // Get rules
        // $rules = $product->getTaxRules( $this->taxingaddress,  $this->customer );
        // Gorrino style, instead:
        $ecotax = $this->product->ecotax;

        // Apply rules
        // $document_line->applyTaxRules( $rules );
        // Gorrino style again
        $amount = $amount*(1.0+$ecotax->percent/100.0) + $ecotax->amount * $this->quantity;

        return $amount;
    }

    /**
     * Add Taxes to ShippingSlip Line
     *
     *     ''
     */
    public function applyTaxRules( $rules )
    {
        // Do the Mambo!
        // $this->load('customershippingslip');

        // Reset
        $this->taxes()->delete();

        $base_price = $this->quantity*$this->unit_final_price;
        // $base_price = $this->getTaxableAmount();
        // Rounded $base_price is the same, no matters the value of ROUND_PRICES_WITH_TAX

        // Initialize totals
        $this->total_tax_incl = $this->total_tax_excl = $base_price;

        foreach ( $rules as $rule ) {

            $taxClass = $this->getClassName().'Tax';
            $line_tax = new $taxClass();
//            $line_tax = new CustomerShippingSlipLineTax();

                $line_tax->name = $rule->fullName;
                $line_tax->tax_rule_type = $rule->rule_type;

                $p = Price::create([$base_price, $base_price*(1.0+$rule->percent/100.0)], $this->currency, $this->currency->conversion_rate);

                // $p->applyRounding( );

                $line_tax->taxable_base = $base_price;
                $line_tax->percent = $rule->percent;
                $line_tax->amount = $rule->amount * $this->quantity;
                $line_tax->total_line_tax = $p->getPriceWithTax() - $p->getPrice() + $p->as_priceable($line_tax->amount);

                $line_tax->position = $rule->position;

                $line_tax->tax_id = $rule->tax_id;
                $line_tax->tax_rule_id = $rule->id;

                $line_tax->save();
                $this->total_tax_incl += $line_tax->total_line_tax;

                $this->taxes()->save($line_tax);
                $this->save();

        }

    }

    public function getPrice()
    {
        $price = [ $this->total_tax_excl/$this->quantity, $this->total_tax_incl/$this->quantity ];        // These prices are in Customer Order Currency

        $priceObject = Price::create( $price, $this->currency );

        return $priceObject;
    }


    public function getSalesRepCommission()
    {
        switch ( Configuration::get('SALESREP_COMMISSION_METHOD') ) {
            case 'TAXINC':
                # code...
                $amount = $this->total_tax_incl * $this->commission_percent / 100.0;
                break;
            
            case 'TAXEXC':
                # code...
                $amount = $this->total_tax_excl * $this->commission_percent / 100.0;
                break;
            
            default:
                # code..
                $amount = 0.0;
                break;
        }

        return $amount;
    }

}