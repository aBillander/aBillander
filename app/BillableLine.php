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

    protected $totals = [];


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