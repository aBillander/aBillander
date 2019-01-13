<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

use \App\Price;

use App\Traits\BillableLineTrait;
use App\Traits\ViewFormatterTrait;

use App\Configuration;

// use ReflectionClass;

class BillableLine extends Model
{
    use BillableLineTrait;
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
                    'product_id', 'combination_id', 'reference', 'name', 'quantity', 'measure_unit_id',
                    'cost_price', 'unit_price', 'unit_customer_price', 
                    'prices_entered_with_tax',
                    'unit_customer_final_price', 'unit_customer_final_price_tax_inc', 
                    'unit_final_price', 'unit_final_price_tax_inc', 
                    'sales_equalization', 'discount_percent', 'discount_amount_tax_incl', 'discount_amount_tax_excl', 
                    'total_tax_incl', 'total_tax_excl', // Not fillable? For sure: NOT. Totals are calculated after ALL taxes are set. BUT handy fillable when importing order!!!
                    'tax_percent', 'commission_percent', 'notes', 'locked',
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
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public static function getTypeList()
    {
            $list = [];
            foreach (self::$types as $type) {
                $list[$type] = l($this->getClassName().'.'.$type, [], 'appmultilang');;
            }

            return $list;
    }

    public static function getTypeName( $type )
    {
            return l($this->getClassName().'.'.$type, [], 'appmultilang');;
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

    public function combination()
    {
       return $this->belongsTo('App\Combination');
    }

    public function measureunit()
    {
        return $this->belongsTo('App\MeasureUnit', 'measure_unit_id');
    }

    public function tax()
    {
        return $this->belongsTo('App\Tax');
    }


    

    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Pump it up!
    |--------------------------------------------------------------------------
    */

    public function getTaxableAmount()
    {
        $amount = $this->quantity*$this->unit_final_price;

        if ( Configuration::isFalse('ENABLE_ECOTAXES') ) return $amount;

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

        $base_price = $this->getTaxableAmount();
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

        $priceObject = \App\Price::create( $price, $this->currency );

        return $priceObject;
    }

}