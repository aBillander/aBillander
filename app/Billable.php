<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BillableIntrospectorTrait;
use App\Traits\BillableCustomTrait;
use App\Traits\BillableLinesTrait;
use App\Traits\BillableTotalsTrait;
use App\Traits\ViewFormatterTrait;

use App\Configuration;

// use ReflectionClass;

class Billable extends Model
{
    use BillableIntrospectorTrait;
    use BillableCustomTrait;
    use BillableLinesTrait;
    use BillableTotalsTrait;
    use ViewFormatterTrait;

    protected $totals = [];


    public static $statuses = array(
            'draft',
            'confirmed',
            'closed',       // with status Shipping/Delivered or Billed. El Pedido se cierra porque se pasa a Albarán o se pasa a Factura. El Albarán puede estar en Shipment (shipment in process) or Delivered. En ambos estados se puede hacer la factura, sin llegar a cerrarlo.
            'canceled',
        );

    protected $dates = [
                        'document_date',        // Set to "confirmed" date (creation date is "created_at")
                        'payment_date',
                        'validation_date',
                        'delivery_date',
                        'delivery_date_real',
                        'close_date',

                        'export_date',
                       ];
                       


    public static function boot()
    {
        parent::boot();

        static::creating(function($document)
        {
            $document->secure_key = md5(uniqid(rand(), true));
            
            if ( $document->shippingmethod )
                $document->carrier_id = $document->shippingmethod->carrier_id;
        });

        static::saving(function($document)
        {
            if ( $document->shippingmethod )
                $document->carrier_id = $document->shippingmethod->carrier_id;
        });

        // https://laracasts.com/discuss/channels/general-discussion/deleting-related-models
        static::deleting(function ($document)
        {
            // before delete() method call this
            // if ($document->has('customershippingsliplines'))
            foreach($document->lines as $line) {
                $line->delete();
            }
        });

    }


    public function getDocumentCurrencyAttribute()
    {
        $currency = $this->currency;
        $currency->conversion_rate = $this->currency_conversion_rate;

        return $currency;
    }  

    public function getTotalRevenueAttribute()
    {
        $lines = $this->lines;
        $filter = Configuration::isFalse('INCLUDE_SHIPPING_COST_IN_PROFIT');

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
        $lines = $this->lines;
        $filter = Configuration::isFalse('INCLUDE_SHIPPING_COST_IN_PROFIT');

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

    public function getDeletableAttribute()
    {
        return !( $this->status == 'closed' || $this->status == 'canceled' );
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
                $list[$status] = l($this->getClassName().'.'.$status, [], 'appmultilang');
            }

            return $list;
    }

    public static function getStatusName( $status )
    {
            return l($this->getClassName().'.'.$status, [], 'appmultilang');
    }

    public function confirm()
    {
        // Already confirmed?
        if ( $this->document_reference || ( $this->status != 'draft' ) ) return false;

        // Sequence
        $seq_id = $this->sequence_id > 0 ? $this->sequence_id : Configuration::get('DEF_'.strtoupper( $this->getClassSnakeCase() ).'_SEQUENCE');
        $seq = \App\Sequence::find( $seq_id );
        $doc_id = $seq->getNextDocumentId();

        $this->document_prefix    = $seq->prefix;
        $this->document_id        = $doc_id;
        $this->document_reference = $seq->getDocumentReference($doc_id);

        $this->status = 'confirmed';

        $this->save();

        return true;
    }

    public function close()
    {
        // Can I ...?
        if ( ($this->status == 'draft') || ($this->status == 'canceled') ) return false;

        // Do stuf...
        if ( $this->status == 'closed' ) return true;

        $this->status = 'closed';

        $this->save();

        return true;
    }

    public function cancel()
    {
        // Can I ...?
        if ( $this->status != 'closed' ) return false;

        // Do stuf...
        if ( $this->status == 'canceled' ) return true;

        $this->status = 'canceled';

        $this->save();
    }


    
    public static function sequences()
    {
        $class = get_called_class();    // $class contains namespace!!!

        return ( new $class() )->sequenceList();
    }

    public function sequenceList()
    {
        return Sequence::listFor( $this->getClassName() );
    }
    


    public function getMaxLineSortOrder()
    {
        if ( $this->lines->count() )
            return $this->lines->max('line_sort_order');

        return 0;           // Or: return intval( $this->customershippingsliplines->max('line_sort_order') );
    }
    
    public function hasShippingAddress()
    {
        return $this->shipping_address_id !== $this->invoicing_address_id;
    }

    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    

    public function documentlines()
    {

        // abi_r($this->getClassName().'Line'.' - '. $this->getClassSnakeCase().'_id');die();

        return $this->hasMany( $this->getClassName().'Line', $this->getClassSnakeCase().'_id' )
                    ->orderBy('line_sort_order', 'ASC');
    }
    
    // Alias
    public function lines()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->documentlines();
    }
    
    public function documentlinetaxes()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->hasManyThrough($this->getClassName().'LineTax', $this->getClassName().'Line');
    }
    
    // Alias
    public function taxes()
    {
        return $this->documentlinetaxes();
    }


    public function taxesGrouped()
    {
        $taxes = [];
        $tax_lines = $this->taxes;
        $tax_model = $this->getClassName().'Tax';


        foreach ($tax_lines as $line) {

            if ( isset($taxes[$line->tax_rule_id]) ) {
                $taxes[$line->tax_rule_id]->taxable_base   += $line->taxable_base;
                $taxes[$line->tax_rule_id]->total_line_tax += $line->total_line_tax;
            } else {
                $tax = new $tax_model();
                $tax->percent        = $line->percent;
                $tax->taxable_base   = $line->taxable_base; 
                $tax->total_line_tax = $line->total_line_tax;

                $taxes[$line->tax_rule_id] = $tax;
            }
        }

        return collect($taxes)->sortByDesc('percent')->values()->all();
    }



    public function taxesByTaxId( $tax_id )
    {
        $lines = $this->taxes->where('tax_id', $tax_id);

        return $lines->count() > 0 ? $lines : null;
    }

    public function taxesByTaxRuleId( $tax_rule_id )
    {
        $lines = $this->taxes->where('tax_rule_id', $tax_rule_id);

        return $lines->count() > 0 ? $lines : null;
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships :: Other
    |--------------------------------------------------------------------------
    */
    
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

    // Alias function
    public function billingaddress()
    {
        return $this->invoicingaddress();
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