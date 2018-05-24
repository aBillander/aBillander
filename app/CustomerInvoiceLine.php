<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerInvoiceLine extends Model {


    public static $types = array(
            'product',
            'service', 
            'shipping', 
            'discount', 
            'comment',
        );
    
//    protected $guarded = array('id');

	// Don't forget to fill this array
	protected $fillable = ['line_sort_order', 'line_type', 
                    'product_id', 'combination_id', 'reference', 'name', 'quantity', 
                    'cost_price', 'unit_price', 'unit_customer_price', 'unit_final_price', 'unit_final_price_tax_inc', 
                    'sales_equalization', 'discount_percent', 'discount_amount_tax_incl', 'discount_amount_tax_excl', 
                    'total_tax_incl', 'total_tax_excl', // Not fillable?
                    'tax_percent', 'commission_percent', 'notes', 'locked',
 //                 'customer_invoice_id',
                    'tax_id', 'sales_rep_id',
    ];

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];

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
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function customerinvoice()
    {
       return $this->belongsTo('App\CustomerInvoice', 'customer_invoice_id');
    }
    
    public function customerinvoicelinetaxes()
    {
        return $this->hasMany('App\CustomerInvoiceLineTax', 'customer_invoice_line_id');
    }

    public function tax()
    {
        return $this->belongsTo('App\Tax');
    }

}