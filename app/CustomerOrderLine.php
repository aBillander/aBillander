<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class CustomerOrderLine extends Model
{

    use ViewFormatterTrait;

    public static $types = array(
            'product',
            'service', 
            'shipping', 
            'discount', 
            'comment',
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
        return $this->belongsTo('App\CustomerOrder');
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

    public function tax()
    {
        return $this->belongsTo('App\Tax');
    }
}
