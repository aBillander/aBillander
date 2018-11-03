<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use \App\CustomerOrderLineTax;
use \App\Price;

use App\Traits\ViewFormatterTrait;
use App\Traits\BillableLineTrait;

class CustomerOrderLine extends Model
{

    use ViewFormatterTrait;
    use BillableLineTrait;

    public static $types = array(
            'product',
            'service', 
            'shipping', 
            'discount', 
            'comment',
            'account',      // Something to charge to an accounting account
        );
	
//    protected $fillable = [ 'product_id', 'woo_product_id', 'woo_variation_id', 
//    						'reference', 'name', 'quantity', 'customer_order_id'
//                          ];

    protected $fillable = ['line_sort_order', 'line_type', 
                    'product_id', 'combination_id', 'reference', 'name', 'quantity', 'measure_unit_id',
                    'cost_price', 'unit_price', 'unit_customer_price', 
                    'prices_entered_with_tax',
                    'unit_customer_final_price', 'unit_customer_final_price_tax_inc', 
                    'unit_final_price', 'unit_final_price_tax_inc', 
                    'sales_equalization', 'discount_percent', 'discount_amount_tax_incl', 'discount_amount_tax_excl', 
                    'total_tax_incl', 'total_tax_excl', // Not fillable? For sure: NOT. Totals are calculated after ALL taxes are set. BUT handy fillable when importing order!!!
                    'tax_percent', 'commission_percent', 'notes', 'locked',
 //                 'customer_order_id',
                    'tax_id', 'sales_rep_id',
    ];

    public static $rules = [
//        'product_id'    => 'required',
    ];


    public static function getTypeList()
    {
            $list = [];
            foreach (self::$types as $type) {
                $list[$type] = l('customerDocumentLine.'.$type, [], 'appmultilang');;
            }

            return $list;
    }

    public static function getTypeName( $type )
    {
            return l('customerDocumentLine.'.$type, [], 'appmultilang');;
    }


    protected static function boot()
    {
        parent::boot();

        // https://laracasts.com/discuss/channels/general-discussion/deleting-related-models
        // https://laracasts.com/discuss/channels/eloquent/laravel-delete-model-with-all-relations
        static::deleting(function ($line)
        {
            // before delete() method call this
            foreach($line->customerorderlinetaxes as $linetax) {
                $linetax->delete();
            }
        });
    }
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function customerorder()
    {
        return $this->belongsTo('App\CustomerOrder', 'customer_order_id');
    }

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
    
    public function customerorderlinetaxes()
    {
        return $this->hasMany('App\CustomerOrderLineTax', 'customer_order_line_id');
    }
    
    // Alias
    public function documentlinetaxes()
    {
        return $this->customerorderlinetaxes();
    }
    
    // Alias
    public function xlinetaxes()
    {
        return $this->customerorderlinetaxes();
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
     * Add Taxes to Order Line
     *
     *     ''
     */
    public function applyTaxRules( $rules )
    {
        // Do the Mambo!
        // $this->load('customerorder');

        // Reset
        $this->customerorderlinetaxes()->delete();

        $base_price = $this->quantity*$this->unit_final_price;
        // Rounded $base_price is the same, no matters the value of ROUND_PRICES_WITH_TAX

        // Initialize totals
        $this->total_tax_incl = $this->total_tax_excl = $base_price;

        foreach ( $rules as $rule ) {

            $line_tax = new CustomerOrderLineTax();

                $line_tax->name = $rule->fullName;
                $line_tax->tax_rule_type = $rule->rule_type;

                $p = Price::create([$base_price, $base_price*(1.0+$rule->percent/100.0)], $this->currency, $this->currency->conversion_rate);

                // $p->applyRounding( );

                $line_tax->taxable_base = $base_price;
                $line_tax->percent = $rule->percent;
                $line_tax->amount = $rule->amount;
                $line_tax->total_line_tax = $p->getPriceWithTax() - $p->getPrice() + $p->as_priceable($rule->amount);

                $line_tax->position = $rule->position;

                $line_tax->tax_id = $rule->tax_id;
                $line_tax->tax_rule_id = $rule->id;

                $line_tax->save();
                $this->total_tax_incl += $line_tax->total_line_tax;

                $this->CustomerOrderLineTaxes()->save($line_tax);
                $this->save();

        }

    }

}
