<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;

use App\Traits\ViewFormatterTrait;

class CustomerInvoice extends Model {

    use ViewFormatterTrait;

    public static $statuses = array(
            'draft',
            'pending',
            'halfpaid',
            'paid',
            'overdue',
            'doubtful',
            'archived',
        );

    protected $guarded = array('id');

    protected $dates = [
    					'document_date', 
    					'valid_until_date', 
    					'delivery_date', 
    					'delivery_date_real', 
                        'next_due_date', 
                        'customer_viewed_at',
    					'edocument_sent_at', 
    					'posted_at'
    					];

    protected $fillable =  ['sequence_id', 'customer_id', 
                            'reference', 'document_discount', 'document_date', 'delivery_date',  
                            'document_prefix', 'document_id', 'document_reference', // These are calculated!!!
//                            'open_balance', 
                            'number_of_packages', 'shipping_conditions', 'tracking_number', 'currency_conversion_rate', 'down_payment', 
                            'prices_entered_with_tax', 'round_prices_with_tax',
//                            'total_discounts_tax_incl', 'total_discounts_tax_excl', 'total_products_tax_incl', 'total_products_tax_excl', 
//                            'total_shipping_tax_incl', 'total_shipping_tax_excl', 'total_other_tax_incl', 'total_other_tax_excl', 
                            'total_tax_incl', 'total_tax_excl',  // Not fillable?
 //                           'commission_amount', 
                            'notes', 'notes_to customer', 'status', 
//                            'einvoice', 'printed', 'customer_viewed', 'posted', 
                            'invoicing_address_id', 'shipping_address_id', 'warehouse_id', 'carrier_id', 
                            'sales_rep_id', 'currency_id', 'payment_method_id', 'template_id', 
//                            'parent_document_id'
                            ];

	// Add your validation rules here
	public static $rules = [
                            'document_date' => 'date',
                            'delivery_date' => 'date',
                            'customer_id' => 'exists:customers,id',
                            'shipping_address_id' => 'exists:addresses,id,addressable_id,{customer_id},addressable_type,App\Customer',
                            'sequence_id' => 'exists:sequences,id',
                            'warehouse_id' => 'exists:warehouses,id',
                            'currency_id' => 'exists:currencies,id',
                            'payment_method_id' => 'exists:payment_methods,id',
	];

    public static function boot()
    {
        parent::boot();

        static::creating(function($cinvoice)
        {
            $cinvoice->secure_key = md5(uniqid(rand(), true));
        });

        static::deleting(function ($cinvoice)    // https://laracasts.com/discuss/channels/general-discussion/deleting-related-models
        {
            // before delete() method call this
            foreach($cinvoice->customerInvoiceLines as $line) {
                $line->delete();
            }
        });
    }

    public static function getStatusList()
    {
            $list = [];
            foreach (self::$statuses as $status) {
                $list[$status] = l($status, [], 'appmultilang');
            }

            return $list;
    }

    public function getEditableAttribute()
    {
        return $this->status == 'draft';
    }

    public function getNumberAttribute()
    {
        return    $this->document_id > 0
                ? $this->document_reference
                : l('draft', [], 'appmultilang') ;
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function paymentmethod()
    {
        return $this->belongsTo('App\PaymentMethod', 'payment_method_id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function template()
    {
        return $this->belongsTo('App\Template');
    }

    public function invoicingaddress()
    {
        return $this->belongsTo('App\Address', 'invoicing_address_id')->withTrashed();
    }

    public function shippingaddress()
    {
        return $this->belongsTo('App\Address', 'shipping_address_id')->withTrashed();
    }

    
    public function customerinvoicelines()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->hasMany('App\CustomerInvoiceLine', 'customer_invoice_id');
    }
    
    public function customerinvoicelinetaxes()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->hasManyThrough('App\CustomerInvoiceLineTax', 'App\CustomerInvoiceLine');
    }

    public function customerinvoicetaxes()
    {
        $taxes = [];
        $tax_lines = $this->customerinvoicelinetaxes;


 //           abi_r($tax_lines, true);

        foreach ($tax_lines as $line) {
//            abi_r($line);
            if ( isset($taxes[$line->tax_rule_id]) ) {
                $taxes[$line->tax_rule_id]->taxable_base   += $line->taxable_base;
                $taxes[$line->tax_rule_id]->total_line_tax += $line->total_line_tax;
            } else {
                $tax = new \App\CustomerInvoiceLineTax();
                $tax->percent        = $line->percent;
                $tax->taxable_base   = $line->taxable_base; 
                $tax->total_line_tax = $line->total_line_tax;

                $taxes[$line->tax_rule_id] = $tax;
            }
        }

//        $taxes = $tax_lines;

        return collect($taxes)->sortByDesc('percent')->values()->all();
    }

    public function payments()
    {
        // return $this->hasMany('App\Payment', 'invoice_id')->where('model_name', '=', 'CustomerInvoice');

        return $this->morphMany('App\Payment', 'paymentable')->orderBy('due_date', 'ASC');
    }
/*    
    public function addresses()
    {
        return $this->hasMany('Address', 'owner_id')->where('model_name', '=', 'Customer');
    }
*/
   /**
     * Get all of the WC Orders that are assigned this Invoice.
     */
    public function wc_orders()
    {
        return $this->belongsToMany('aBillander\WooConnect\WooOrder', 'parent_child', 'childable_id', 'parentable_id')
                ->wherePivot('childable_type' , 'App\CustomerInvoice')
                ->wherePivot('parentable_type', 'aBillander\WooConnect\WooOrder')
                ->withTimestamps();
    }

    public function staple_wc_order( $document = null )
    {
        if (!$document) return;

        $this->wc_orders()->attach($document->id, ['parentable_type' => 'aBillander\WooConnect\WooOrder', 'childable_type' => 'App\CustomerInvoice']);
    }


    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfCustomer($query)
    {
//        return $query->where('customer_id', Auth::user()->customer_id);

        if ( isset(Auth::user()->customer_id) && ( Auth::user()->customer_id != NULL ) )
            return $query->where('customer_id', Auth::user()->customer_id)->where('status', '!=', 'draft');

        return $query;
    }

    public function scopeFindByToken($query, $token)
    {
        return $query->where('secure_key', $token);
    }

}