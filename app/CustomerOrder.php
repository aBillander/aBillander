<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;

use App\Traits\ViewFormatterTrait;

class CustomerOrder extends Model
{

    use ViewFormatterTrait;

    public static $statuses = array(
            'draft',
            'confirmed',
            'closed',       // with status Shipping/Delivered or Billed. El Pedido se cierra porque se pasa a Albarán o se pasa a Factura. El Albarán puede estar en Shipment (shipment in process) or Delivered. En ambos estados se puede hacer la factura, sin llegar a cerrarlo.
            'canceled',
        );

    protected $dates = [
                        'document_date',
                        'payment_date',
                        'validation_date',
                        'delivery_date',
                        'delivery_date_real',
                        'close_date',

                        'export_date',
                       ];

//                        'document_date', 
//                        'payment_date', 
//                      'valid_until_date', 
//                        'delivery_date', 
//                      'delivery_date_real', 
//                       'next_due_date', 
//                       'customer_viewed_at',
//                      'edocument_sent_at', 
//                      'posted_at'

//    protected $guarded = ['id', 'document_prefix', 'document_id', 'secure_key' ];
                       

    protected $fillable = [ 'sequence_id', 'customer_id', 'reference', 'reference_customer', 'reference_external', 
                            'created_via', 'document_prefix', 'document_id', 'document_reference',
                            'document_date', 'payment_date', 'validation_date', 'delivery_date',

                            'document_discount_percent', 'document_discount_amount', 'shipping_conditions',

                            'currency_conversion_rate', 'down_payment', 

/*
            $table->decimal('total_discounts_tax_incl', 20, 6)->default(0.0);   // Order/Document discount
            $table->decimal('total_discounts_tax_excl', 20, 6)->default(0.0);
            $table->decimal('total_products_tax_incl', 20, 6)->default(0.0);    // Product netto (product discount included!)
            $table->decimal('total_products_tax_excl', 20, 6)->default(0.0);
            $table->decimal('total_shipping_tax_incl', 20, 6)->default(0.0);
            $table->decimal('total_shipping_tax_excl', 20, 6)->default(0.0);
            $table->decimal('total_other_tax_incl', 20, 6)->default(0.0);
            $table->decimal('total_other_tax_excl', 20, 6)->default(0.0);
*           
            $table->decimal('total_tax_incl', 20, 6)->default(0.0);
            $table->decimal('total_tax_excl', 20, 6)->default(0.0);

            $table->decimal('commission_amount', 20, 6)->default(0.0);          // Sales Representative commission amount
*/
            
                            'total_lines_tax_incl', 'total_lines_tax_excl', 'total_tax_incl', 'total_tax_excl',
                            'customer_note', 'notes', 'notes_to_customer',
                            'status', 'locked',
                            'invoicing_address_id', 'shipping_address_id', 
                            'warehouse_id', 'shipping_method_id', 'sales_rep_id', 'currency_id', 'payment_method_id', 'template_id',

                            'production_sheet_id',
                          ];


    public static $rules = [
                            'document_date' => 'date',
//                            'payment_date'  => 'date',
                            'delivery_date' => 'nullable|date',
                            'customer_id' => 'exists:customers,id',
                            'invoicing_address_id' => '',
                            'shipping_address_id' => 'exists:addresses,id,addressable_id,{customer_id},addressable_type,App\Customer',
                            'sequence_id' => 'exists:sequences,id',
//                            'warehouse_id' => 'exists:warehouses,id',
//                            'carrier_id'   => 'exists:carriers,id',
                            'currency_id' => 'exists:currencies,id',
                            'payment_method_id' => 'exists:payment_methods,id',
               ];


    public static function boot()
    {
        parent::boot();

        static::creating(function($corder)
        {
            $corder->secure_key = md5(uniqid(rand(), true));
            
            if ( $corder->shippingmethod )
                $corder->carrier_id = $corder->shippingmethod->carrier_id;
        });

        static::saving(function($corder)
        {
            if ( $corder->shippingmethod )
                $corder->carrier_id = $corder->shippingmethod->carrier_id;
        });

        // https://laracasts.com/discuss/channels/general-discussion/deleting-related-models
        static::deleting(function ($corder)
        {
            // before delete() method call this
            foreach($corder->customerOrderLines as $line) {
                $line->delete();
            }
        });

    }
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public static function getStatusList()
    {
            $list = [];
            foreach (self::$statuses as $status) {
                $list[$status] = l('customerOrder.'.$status, [], 'appmultilang');
            }

            return $list;
    }

    public static function getStatusName( $status )
    {
            return l('customerOrder.'.$status, [], 'appmultilang');;
    }

    public function getTotalRevenueAttribute()
    {
        $lines = $this->customerorderlines;
        $filter = !(intval( \App\Configuration::get('INCLUDE_SHIPPING_COST_IN_PROFIT') ) > 0);

        $total_revenue = $lines->sum(function ($line) use ($filter) {

                if ( ($line->line_type == 'shipping') && $filter ) return 0.0;

                return $line->quantity * $line->unit_final_price;

            });

        return $total_revenue;
    }

    public function getTotalRevenueWithDiscountAttribute()
    {
        return $this->total_revenue * ( 1.0 - $this->document_discount_percent / 100.0 );
    }

    public function getTotalCostPriceAttribute()
    {
        $lines = $this->customerorderlines;
        $filter = !(intval( \App\Configuration::get('INCLUDE_SHIPPING_COST_IN_PROFIT') ) > 0);

        $total_cost_price = $lines->sum(function ($line) use ($filter) {

                if ( ($line->line_type == 'shipping') && $filter ) return 0.0;

                return $line->quantity * $line->cost_price;

            });

        return $total_cost_price;
    }

    public function getEditableAttribute()
    {
        return !( $this->locked || $this->status == 'closed' || $this->status == 'canceled' );
    }

    public function getNumberAttribute()
    {
        // WTF???
        return    $this->document_id > 0
                ? $this->document_reference
                : l('draft', [], 'appmultilang') ;
    }
    
    
    public function customerCard()
    {
        $address = $this->customer->address;

        $card = $customer->name .'<br />'.
                $address->address_1 .'<br />'.
                $address->city . ' - ' . $address->state->name.' <a href="javascript:void(0)" class="btn btn-grey btn-xs disabled">'. $$address->phone .'</a>';

        return $card;
    }
    
    public function customerCardFull()
    {
        $address = $this->customer->address;

        $card = ($address->name_commercial ? $address->name_commercial .'<br />' : '').
                ($address->firstname  ? $address->firstname . ' '.$address->lastname .'<br />' : '').
                $address->address1 . ($address->address2 ? ' - ' : '') . $address->address2 .'<br />'.
                $address->city . ' - ' . $address->state->name.' <a href="javascript:void(0)" class="btn btn-grey btn-xs disabled">'. $address->phone .'</a>';

        return $card;
    }
    
    public function customerCardMini()
    {
        $customer = unserialize( $this->customer );

        $card = $customer["city"].' - '.($customer["state_name"] ?? '').' <a href="#" class="btn btn-grey btn-xs disabled">'. $customer["phone"] .'</a>';

        return $card;
    }
    
    public function customerInfo()
    {
        $customer = $this->customer;

        $name = $customer->name_fiscal ?: $customer->name_commercial;

        if ( !$name ) 
            $name = $name = $customer->name;

        return $name;
    }
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function confirm()
    {
        // Already confirmed?
        if ( $this->document_reference || ( $this->status != 'draft' ) ) return ;

        // Sequence
        $seq_id = $this->sequence_id > 0 ? $this->sequence_id : \App\Configuration::get('DEF_CUSTOMER_ORDER_SEQUENCE');
        $seq = \App\Sequence::find( $seq_id );
        $doc_id = $seq->getNextDocumentId();

        $this->document_prefix    = $seq->prefix;
        $this->document_id        = $doc_id;
        $this->document_reference = $seq->getDocumentReference($doc_id);

        $this->status = 'confirmed';

        $this->save();
    }

    public function close()
    {
        // Can I ...?
        if ( $this->status != 'canceled' ) return ;

        // Do stuf...

        $this->status = 'closed';

        $this->save();
    }

    public function cancel()
    {
        // Can I ...?
        if ( $this->status != 'closed' ) return ;

        // Do stuf...

        $this->status = 'canceled';

        $this->save();
    }

    
    public function makeTotals( $document_discount_percent = null )
    {
        if ( ($document_discount_percent !== null) && ($document_discount_percent >= 0.0) )
            $this->document_discount_percent = $document_discount_percent;

        $this->load('customerorderlines');

        $lines = $this->customerorderlines;
        
/*
        'total_discounts_tax_incl', 
        'total_discounts_tax_excl', 
        'total_products_tax_incl', 
        'total_products_tax_excl', 
        'total_shipping_tax_incl', 
        'total_shipping_tax_excl', 
        'total_other_tax_incl', 
        'total_other_tax_excl', 
*/

        // These are already rounded!
        $this->total_lines_tax_incl = $lines->sum('total_tax_incl');
        $this->total_lines_tax_excl = $lines->sum('total_tax_excl');

        if ($this->document_discount_percent>0) 
        {
            $total_tax_incl = $this->total_lines_tax_incl * (1.0 - $this->document_discount_percent/100.0) - $this->document_discount_amount_tax_incl;
            $total_tax_excl = $this->total_lines_tax_excl * (1.0 - $this->document_discount_percent/100.0) - $this->document_discount_amount_tax_excl;

            // Make a Price object for rounding
            $p = \App\Price::create([$total_tax_excl, $total_tax_incl], $this->currency, $this->currency_conversion_rate);

            // Improve this: Sum subtotals by tax type must match Order Totals
            $p->applyRoundingWithoutTax( );

            $this->total_tax_incl = $p->getPriceWithTax();
            $this->total_tax_excl = $p->getPrice();

        } else {

            $this->total_tax_incl = $this->total_lines_tax_incl;
            $this->total_tax_excl = $this->total_lines_tax_excl;
            
        }

        $this->save();

        return true;
    }
    
    public function getTotalTaxIncl()
    {
        $lines = $this->customerorderlines;
        
/*
        'total_discounts_tax_incl', 
        'total_discounts_tax_excl', 
        'total_products_tax_incl', 
        'total_products_tax_excl', 
        'total_shipping_tax_incl', 
        'total_shipping_tax_excl', 
        'total_other_tax_incl', 
        'total_other_tax_excl', 
*/

        // These are already rounded!
        $this->total_lines_tax_incl = $lines->sum('total_tax_incl');
        $this->total_lines_tax_excl = $lines->sum('total_tax_excl');

        $total_tax_incl = $this->total_lines_tax_incl * (1.0 - $this->document_discount_percent/100.0) - $this->document_discount_amount_tax_incl;
        $total_tax_excl = $this->total_lines_tax_excl * (1.0 - $this->document_discount_percent/100.0) - $this->document_discount_amount_tax_excl;

        // Make a Price object for rounding
        $p = \App\Price::create([$total_tax_excl, $total_tax_incl], $this->currency, $this->currency_conversion_rate);

        $p->applyRounding( );

        $this->total_tax_incl = $p->getPriceWithTax();
        $this->total_tax_excl = $p->getPrice();

        return $this->total_tax_incl;
    }
    
    public function getTotalTaxExcl()
    {
        $lines = $this->customerorderlines;
        
/*
        'total_discounts_tax_incl', 
        'total_discounts_tax_excl', 
        'total_products_tax_incl', 
        'total_products_tax_excl', 
        'total_shipping_tax_incl', 
        'total_shipping_tax_excl', 
        'total_other_tax_incl', 
        'total_other_tax_excl', 
*/

        // These are already rounded!
        $this->total_lines_tax_incl = $lines->sum('total_tax_incl');
        $this->total_lines_tax_excl = $lines->sum('total_tax_excl');

        $total_tax_incl = $this->total_lines_tax_incl * (1.0 - $this->document_discount_percent/100.0) - $this->document_discount_amount_tax_incl;
        $total_tax_excl = $this->total_lines_tax_excl * (1.0 - $this->document_discount_percent/100.0) - $this->document_discount_amount_tax_excl;

        // Make a Price object for rounding
        $p = \App\Price::create([$total_tax_excl, $total_tax_incl], $this->currency, $this->currency_conversion_rate);

        $p->applyRounding( );

        $this->total_tax_incl = $p->getPriceWithTax();
        $this->total_tax_excl = $p->getPrice();

        return $this->total_tax_excl;
    }
    
    public function getMaxLineSortOrder()
    {
        if ( $this->customerorderlines->count() )
            return $this->customerorderlines->max('line_sort_order');

        return 0;           // Or: return intval( $this->customerorderlines->max('line_sort_order') );
    }
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function productionsheet()
    {
        return $this->belongsTo('App\ProductionSheet', 'production_sheet_id');
    }
    

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function shippingmethod()
    {
        return $this->belongsTo('App\ShippingMethod', 'shipping_method_id');
    }

    public function carrier()
    {
        return $this->belongsTo('App\Carrier');
    }

    public function paymentmethod()
    {
        return $this->belongsTo('App\PaymentMethod', 'payment_method_id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse');
    }

    public function salesrep()
    {
        return $this->belongsTo('App\SalesRep', 'sales_rep_id');
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

    public function taxingaddress()
    {
        return \App\Configuration::get('TAX_BASED_ON_SHIPPING_ADDRESS') ? 
            $this->shippingaddress()  : 
            $this->invoicingaddress() ;
    }

    
    public function customerorderlines()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->hasMany('App\CustomerOrderLine', 'customer_order_id')->orderBy('line_sort_order', 'ASC');
    }
    
    public function customerorderlinetaxes()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->hasManyThrough('App\CustomerOrderLineTax', 'App\CustomerOrderLine');
    }

    public function customerordertaxes()
    {
        $taxes = [];
        $tax_lines = $this->customerorderlinetaxes;


        foreach ($tax_lines as $line) {

            if ( isset($taxes[$line->tax_rule_id]) ) {
                $taxes[$line->tax_rule_id]->taxable_base   += $line->taxable_base;
                $taxes[$line->tax_rule_id]->total_line_tax += $line->total_line_tax;
            } else {
                $tax = new \App\CustomerOrderLineTax();
                $tax->percent        = $line->percent;
                $tax->taxable_base   = $line->taxable_base; 
                $tax->total_line_tax = $line->total_line_tax;

                $taxes[$line->tax_rule_id] = $tax;
            }
        }

        return collect($taxes)->sortByDesc('percent')->values()->all();
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

    public function scopeIsOpen($query)
    {
        // WTF???
        return $query->where( 'due_date', '>=', \Carbon\Carbon::now()->toDateString() );
    }

}
