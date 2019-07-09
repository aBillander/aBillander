<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;

use App\Traits\BillableIntrospectorTrait;
use App\Traits\BillableCustomTrait;
use App\Traits\BillableDocumentLinesTrait;
use App\Traits\BillableTotalsTrait;
use App\Traits\ViewFormatterTrait;

use App\Configuration;

// use ReflectionClass;

class Billable extends Model
{
    use BillableIntrospectorTrait;
    use BillableCustomTrait;
    use BillableDocumentLinesTrait;
    use BillableTotalsTrait;
    use ViewFormatterTrait;


    public static $billable_types = array(
            'customer_quotation',
            'customer_order',
            'customer_shipping_slip',
            'customer_invoice',
        );


    protected $totals = [];


    public static $badges = [];

    public static $types = [];

    public static $statuses = array(
            'draft',
            'confirmed',
            'closed',       // with status Shipping/Delivered or Billed. El Pedido se cierra porque se pasa a Albarán o se pasa a Factura. El Albarán puede estar en Shipment (shipment in process) or Delivered. En ambos estados se puede hacer la factura, sin llegar a cerrarlo.
            'canceled',
        );

    public static $created_vias = array(
            'manual',
            'abcc',
            'absrc',
            'aggregate_orders',
            'aggregate_shipping_slips',
        );

    protected $dates = [
                        'document_date',        // Set to "confirmed" date (creation date is "created_at")
                        'payment_date',
                        'validation_date',
                        'delivery_date',
                        'delivery_date_real',
                        'close_date',

                        'export_date',

                        'printed_at',
                        'edocument_sent_at',
                        'customer_viewed_at'
                       ];

    protected $document_dates = [
    ];


    // https://gist.github.com/JordanDalton/f952b053ef188e8750177bf0260ce166
    protected $fillable = [ 'sequence_id', 'customer_id', 'reference', 'reference_customer', 'reference_external', 
                            'created_via', 
//                            '_document_prefix', '_document_id', '_document_reference',
                            'document_date', 'payment_date', 'validation_date', 'delivery_date',

                            'document_discount_percent', 'document_discount_amount', 'document_ppd_percent',
                            'number_of_packages', 'volume', 'weight', 
                            'shipping_conditions', 'tracking_number',

                            'currency_conversion_rate', 'down_payment', 

            
                            'total_currency_tax_incl', 'total_currency_tax_excl', 'total_currency_paid',
                            'total_tax_incl', 'total_tax_excl',

                            'commission_amount', 

                            'notes_from_customer', 'notes', 'notes_to_customer',
                            'status', 'locked',

                            'invoicing_address_id', 'shipping_address_id', 
                            'warehouse_id', 'shipping_method_id', 'sales_rep_id', 'currency_id', 'payment_method_id', 
                            'template_id',

                            'import_key',
                          ];

    protected $document_fillable = [
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
        return array_merge(parent::getFillable(), $this->document_fillable);
    }

    public function getDates()
    {
        return array_merge(parent::getDates(), $this->document_dates);
    }


    public static function boot()
    {
        parent::boot();

        static::creating(function($document)
        {
//            $document->company_id = \App\Context::getContext()->company;

            $document->user_id = Auth::id();

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

            // Delete Ascriptions - Poor man :: ToDo: delete ascripted documents && nullyfy some dates (i.e.: backordered_at, etc.) too
            $document->rightAscriptions()->delete();
            $document->leftAscriptions()->delete();

        });

    }


    public function getDocumentCurrencyAttribute()
    {
        $currency = $this->currency;
        $currency->conversion_rate = $this->currency_conversion_rate;

        return $currency;
    }


/*
    Return values

     1 : quantity_onhand is enough
     0 : quantity_available is enough
    -1 : not enough quantity to fullfill the order

*/
    public function getStockFlagAttribute()
    {
        // Just to make sure...
        $this->load('lines.product');

        $flag = 1;

        foreach ($this->lines as $line) {
            # code...
            if (  $line->line_type != 'product' ) continue;
            if ( !$line->product                ) continue;     // Sometimes Product is deleted!!!

            if ( $line->product->quantity_available < 0 ) $flag = 0;    // Too many orders. Maybe not stock for all

            if ( $line->quantity > $line->product->quantity_onhand ) return $flag = -1;
        }

        return $flag;
    }

    public function getTotalRevenueAttribute()
    {
        $lines = $this->lines;
        $filter = Configuration::isFalse('INCLUDE_SHIPPING_COST_IN_PROFIT');

        $total_revenue = $lines->sum(function ($line) use ($filter) {

                if ( ($line->line_type == 'shipping') && $filter ) return 0.0;

                $ecotax = 0.0;
                if ( $line->line_type == 'product' ) 
                {
                    $ecotax = optional( optional($line->product)->ecotax)->amount ?? 0.0;
                }

                return $line->quantity * ( $line->unit_final_price - $ecotax );

            });

        return $total_revenue;
    }

    public function getTotalRevenueWithDiscountAttribute()
    {
        return $this->total_revenue * ( 1.0 - $this->document_discount_percent / 100.0 ) * ( 1.0 - $this->document_ppd_percent / 100.0 );
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

    public function getNumberAttribute()
    {
        // WTF???  (ノಠ益ಠ)ノ彡┻━┻ 
        return    $this->document_id > 0
                ? $this->document_reference
                : l($this->getClassName().'.'.'draft', [], 'appmultilang') ;
    }

    public function getAllNotesAttribute()
    {
        $notes = '';

        if ($this->notes_from_customer && (strlen($this->notes_from_customer) > 4)) $notes .= $this->notes_from_customer."\n\n";        // Prevent accidental whitespaces
        if ($this->notes               ) $notes .= $this->notes."\n\n";
        if ($this->notes_to_customer   ) $notes .= $this->notes_to_customer."\n\n";


        return $notes;
    }


    /*
    |--------------------------------------------------------------------------
    | Ascriptions
    |--------------------------------------------------------------------------
    */
    
    public function rightAscriptions()
    {
        return $this->morphMany('App\DocumentAscription', 'leftable')->orderBy('id', 'ASC');
    }

    public function leftAscriptions()
    {
        return $this->morphMany('App\DocumentAscription', 'rightable')->orderBy('id', 'ASC');
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public static function getBillableTypeList()
    {
            $list = [];
            foreach (static::$billable_types as $billable_type) {
                $list[$billable_type] = l(get_called_class().'.'.$billable_type, [], 'appmultilang');;
            }

            return $list;
    }

    public static function getBillableTypeName( $billable_type )
    {
            return l('BillableType.'.$billable_type, [], 'appmultilang');;
    }

    public static function getTypeList()
    {
            $list = [];
            foreach (static::$types as $type) {
                $list[$type] = l(get_called_class().'.'.$type, [], 'appmultilang');;
            }

            return $list;
    }

    public static function getTypeName( $type )
    {
            return l(get_called_class().'.'.$type, [], 'appmultilang');;
    }


    public static function getStatusList()
    {
            $list = [];
            foreach (static::$statuses as $status) {
                $list[$status] = l(get_called_class().'.'.$status, [], 'appmultilang');
                // alternative => $list[$status] = l(static::class.'.'.$status, [], 'appmultilang');
            }

            return $list;
    }

    public static function getStatusName( $status )
    {
            return l(get_called_class().'.'.$status, [], 'appmultilang');
    }

    public function getStatusNameAttribute()
    {
            return l(get_called_class().'.'.$this->status, 'appmultilang');
    }

    public static function getBadge( $name )
    {
            return array_key_exists( $name, static::$badges) ? static::$badges[$name] : '';
    }


    public static function getCreatedViaList()
    {
            $list = [];
            foreach (static::$created_vias as $created_via) {
                $list[$created_via] = l(get_called_class().'.'.$created_via, [], 'appmultilang');
            }

            return $list;
    }

    public static function getCreatedViaName( $created_via )
    {
            return l(get_called_class().'.'.$created_via, [], 'appmultilang');
    }


/*
*   Convenient methods
*/
    
    public function getShippingMethodId() 
    {
        if (   $this->shipping_method_id
            && \App\ShippingMethod::where('id', $this->shipping_method_id)->exists()
            )
            return $this->shipping_method_id;

        return Configuration::getInt('DEF_CUSTOMER_SHIPPING_METHOD');
    }
    
    public function getShippingAddressId() 
    {
        if (   $this->shipping_address_id
            && $this->customer->addresses->contains('id', $this->shipping_address_id)
            )
            return $this->shipping_address_id;

        return $this->customer->shipping_address_id > 0 ? $this->customer->shipping_address_id : $this->customer->invoicing_address_id;
    }

    public function getWarehouseId() 
    {
        if (   $this->warehouse_id
            && \App\Warehouse::where('id', $this->warehouse_id)->exists()
            )
            return $this->warehouse_id;

        return Configuration::getInt('DEF_WAREHOUSE');
    }
    

/*
*
*/
    

    public function confirm()
    {
        // Already confirmed?
        if ( $this->document_reference || ( $this->status != 'draft' ) ) return false;

        // onhold?
        if ( $this->onhold ) return false;

        // Sequence
        $seq_id = $this->sequence_id > 0 ? $this->sequence_id : Configuration::get('DEF_'.strtoupper( $this->getClassSnakeCase() ).'_SEQUENCE');
        $seq = \App\Sequence::find( $seq_id );
        $doc_id = $seq->getNextDocumentId();

        $this->sequence_id = $seq_id;
        // Not fillable
        $this->document_prefix    = $seq->prefix;
        // Not fillable
        $this->document_id        = $doc_id;
        // Not fillable. May come from external system ???
        $this->document_reference = $seq->getDocumentReference($doc_id);

        $this->status = 'confirmed';
        $this->validation_date = \Carbon\Carbon::now();

        $this->save();

        return true;
    }

    public function unConfirm()
    {
        // Can I?
        if ( $this->status != 'confirmed' ) return false;

        // onhold? No problemo
        // if ( $this->onhold ) return false;

        // Can I "undo" last number in Sequence
        $seq_id = $this->sequence_id;
        $seq = \App\Sequence::find( $seq_id );
        $next_id = $seq->next_id;
        $doc_id = $this->document_id;

        if ( ($next_id - $doc_id) != 1 ) return false;

        // Update Sequence
        $seq->next_id = $doc_id;
// ???        $seq->last_date_used = \Carbon\Carbon::now();
        $seq->save();

        // Update Document
        // Not fillable
        $this->document_prefix    = NULL;
        // Not fillable
        $this->document_id        = 0;
        // Not fillable. May come from external system ???
        $this->document_reference = NULL;

        $this->status = 'draft';
        $this->validation_date = NULL;

        $this->save();

        return true;
    }

    public function unConfirmDocument()
    {
        // Can I?
        if ( $this->status != 'confirmed' ) return false;

        // onhold? No problemo
        // if ( $this->onhold ) return false;

        // Not taking care of Secuence numbering, but try to save current number
        // Can I "undo" last number in Sequence
        $seq_id = $this->sequence_id;
        $seq = \App\Sequence::find( $seq_id );
        $next_id = $seq->next_id;
        $doc_id = $this->document_id;

        if ( ($next_id - $doc_id) == 1 ) {
        
                // Update Sequence
                $seq->next_id = $doc_id;
        // ???        $seq->last_date_used = \Carbon\Carbon::now();
                $seq->save();
        }

        // Update Document
        // Not fillable
        $this->document_prefix    = NULL;
        // Not fillable
        $this->document_id        = 0;
        // Not fillable. May come from external system ???
        $this->document_reference = NULL;

        $this->status = 'draft';
        $this->validation_date = NULL;

        $this->save();

        return true;
    }

    public function close()
    {
        // Can I ...?
        if ( ($this->status == 'draft') || ($this->status == 'canceled') ) return false;
        
        if ( $this->status == 'closed' ) return false;

        // onhold?
        if ( $this->onhold ) return false;

        // Do stuf...
        $this->status = 'closed';
        $this->close_date = \Carbon\Carbon::now();

        $this->save();

        return true;
    }

    public function unclose( $status = null )
    {
        // Can I ...?
        if ( $this->status != 'closed' ) return false;

        // Do stuf...
        $this->status = $status ?: 'confirmed';
        $this->close_date =null;

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

    public function templateList()
    {
        return Template::listFor( $this->getClassName() );
    }
    


    public function getMaxLineSortOrder()
    {
        if ( $this->lines->count() )
            return $this->lines->max('line_sort_order');

        return 0;           // Or: return intval( $this->customershippingsliplines->max('line_sort_order') );
    }
    
    public function hasShippingAddress()
    {
        if ( !$this->shipping_address_id ) return false;

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

    public function sequence()
    {
        return $this->belongsTo('App\Sequence');
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


    public function scopeFilter($query, $params)
    {
        if ( array_key_exists('invoiced_not', $params) )
        {
            $query->where('invoiced_at', null);
        } else
        
        if ( array_key_exists('invoiced', $params) )
        {
            $query->where('invoiced_at', '<>', null);
        }


        if ( array_key_exists('closed_not', $params) )
        {
            $query->where('status', '<>', 'closed');
        } else
        
        if ( array_key_exists('closed', $params) )
        {
            $query->where('status', 'closed');
        }


/*
        if ( isset($params['reference']) && trim($params['reference']) !== '' )
        {
            $query->where('reference', 'LIKE', '%' . trim($params['reference']) . '%');
            $query->orWhere('ean13', 'LIKE', '%' . trim($params['reference']) . '%');
            // $query->orWhere('combinations.reference', 'LIKE', '%' . trim($params['reference'] . '%'));

            // Moved from controller
            $reference = $params['reference'];
            $query->orWhereHas('combinations', function($q) use ($reference)
                                {
                                    // http://stackoverflow.com/questions/20801859/laravel-eloquent-filter-by-column-of-relationship
                                    $q->where('reference', 'LIKE', '%' . $reference . '%');
                                }
            );  // ToDo: if name is supplied, shows records that match reference but do not match name (due to orWhere condition)
        }

        if ( isset($params['name']) && trim($params['name']) !== '' )
        {
            $query->where('name', 'LIKE', '%' . trim($params['name'] . '%'));

            if ( \Auth::user()->language->iso_code == 'en' )
            {
                $query->orWhere('name_en', 'LIKE', '%' . trim($params['name'] . '%'));
            }
        }

        if ( isset($params['stock']) )
        {
            if ( $params['stock'] == 0 )
                $query->where('quantity_onhand', '<=', 0);
            if ( $params['stock'] == 1 )
                $query->where('quantity_onhand', '>', 0);
        }

        if ( isset($params['category_id']) && $params['category_id'] > 0 )
        {
            $query->where('category_id', '=', $params['category_id']);
        }

        if ( isset($params['manufacturer_id']) && $params['manufacturer_id'] > 0 && 0)
        {
            $query->where('manufacturer_id', '=', $params['manufacturer_id']);
        }

        if ( isset($params['procurement_type']) && $params['procurement_type'] != '' )
        {
            $query->where('procurement_type', '=', $params['procurement_type']);
        }

        if ( isset($params['active']) )
        {
            if ( $params['active'] == 0 )
                $query->where('active', '=', 0);
            if ( $params['active'] == 1 )
                $query->where('active', '>', 0);
        }
*/

        return $query;
    }


    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfLoggedCustomer($query)
    {
//        return $query->where('customer_id', Auth::user()->customer_id);

        if ( Auth::guard('customer')->check() && ( Auth::guard('customer')->user()->customer_id != NULL ) )
        {
            $user = Auth::guard('customer')->user();

            $query->where('customer_id', $user->customer_id);

            if ( $user->address_id )
                $query->where('shipping_address_id', $user->address_id);

            return $query;
        }

        // Not allow to see resource
        return $query->where('customer_id', 0)->where('status', '');
    }

    public function scopeOfSalesRep($query)
    {
//        return $query->where('customer_id', Auth::user()->customer_id);

        if ( isset(Auth::user()->sales_rep_id) && ( Auth::user()->sales_rep_id != NULL ) )
            return $query->where('sales_rep_id', Auth::user()->sales_rep_id);

        // Not allow to see resource
        return $query->where('sales_rep_id', 0);
    }

    public function scopeFindByToken($query, $token)
    {
        return $query->where('secure_key', $token);
    }

    public function scopeIsOpen($query)
    {
        // WTF???     (ノಠ益ಠ)ノ彡┻━┻ 
        return $query->where( 'due_date', '>=', \Carbon\Carbon::now()->toDateString() );
    }

}