<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;

use App\Configuration;

use \App\WarehouseShippingSlipLine;

use App\Traits\ViewFormatterTrait;

class WarehouseShippingSlip extends Model
{
    use ViewFormatterTrait;

    public static $statuses = array(
            'draft',
            'confirmed',
            'closed',       // with status Shipping/Delivered or Billed. El Pedido se cierra porque se pasa a Albarán o se pasa a Factura. El Albarán puede estar en Shipment (shipment in process) or Delivered. En ambos estados se puede hacer la factura, sin llegar a cerrarlo.
            'canceled',
        );

    public static $badges = [
            'a_class' => 'alert-warning',
            'i_class' => 'fa-truck',
        ];

    public static $shipment_statuses = array(
            'pending',
            'processing',              //  order is awaiting fulfillment.
//            'picking', 
//            'packing',              // fulfillment : the process of picking, packing, and shipping your order
              // Pending Carrier Pick up
            'transit',             // Shipment in process
            'exception',            // Delivery Exception appears if the shipment fails to reach its final destination due to issues such as rerouting back to the sender, an obstructed mailbox, the recipient refusing the package, or failed delivery attempts.
            'delivered',
        );

    public static $created_vias = array(
            'manual',
//            'abcc',
//            'absrc',
//            'aggregate_orders',
//            'aggregate_shipping_slips',
//            'production_sheet',
        );

    protected $dates = [
                        'document_date',        // Set to "confirmed" date (creation date is "created_at")
                        'validation_date',
                        'delivery_date',
                        'delivery_date_real',
                        'close_date',

                        'export_date',

                        'printed_at',
                        'edocument_sent_at',
                       ];
    
    protected $fillable = [ 'sequence_id', 'warehouse_id', 'warehouse_counterpart_id', 'reference', 
                            'created_via', 
//                            '_document_prefix', '_document_id', '_document_reference',
                            'document_date', 'validation_date', 'delivery_date',

                            'number_of_packages', 'volume', 'weight', 
                            'shipping_conditions', 'tracking_number',

                            'notes', 'notes_to_counterpart',
                            'status', 'onhold', 'locked',

                            'production_sheet_id',

//                            'invoicing_address_id', 'shipping_address_id', 
                            'shipping_method_id', 'carrier_id', 
                            'template_id',

                            'shipment_status', 'shipment_service_type_tag', 

                            'import_key',
                          ];

    public static $rules = [
                            'document_date' => 'required|date',
//                            'payment_date'  => 'date',
                            'delivery_date' => 'nullable|date|after_or_equal:document_date',
//                            'customer_id' => 'exists:customers,id',
//                            'shipping_address_id' => 'exists:addresses,id,addressable_id,{customer_id},addressable_type,App\Customer',
                            'sequence_id' => 'exists:sequences,id',
                            'warehouse_id' => 'exists:warehouses,id',
                            'warehouse_counterpart_id' => 'different:warehouse_id|exists:warehouses,id',
                            'shipping_method_id'   => 'nullable|exists:shipping_methods,id',
//                            'carrier_id'   => 'nullable|exists:carriers,id',
//                            'currency_id' => 'exists:currencies,id',
                            'number_of_packages' => 'required|min:1',
               ];


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
            // if ( $document->force_carrier_id === true )
            if ( $document->carrier_id > 0 )
            {
                // $document->carrier_id = $document->force_carrier_id;
                // unset($document->force_carrier_id);

                // abi_r($document->carrier_id);
            }
            else
            {

                if ( $document->shippingmethod )
                    $document->carrier_id = $document->shippingmethod->carrier_id;
                else
                    $document->carrier_id = null;
                
            }

            // abi_r($document->carrier_id);die();
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
            // $document->rightAscriptions()->delete();
            // $document->leftAscriptions()->delete();

        });

    }
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */


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

        // if ($this->notes_from_customer && (strlen($this->notes_from_customer) > 4)) $notes .= $this->notes_from_customer."\n\n";        // Prevent accidental whitespaces
        if ($this->notes               ) $notes .= $this->notes."\n\n";
        if ($this->notes_to_counterpart   ) $notes .= $this->notes_to_counterpart;  // ."\n\n";


        return $notes;
    }

    public function getNbrLinesAttribute()
    {
        return $this->lines()->count();
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

    public static function isStatus( $status )
    {
            return in_array($status, self::$statuses);
    }

    public function getStatusNameAttribute()
    {
            return l(get_called_class().'.'.$this->status, 'appmultilang');
    }


    public static function getShipmentStatusList()
    {
            $list = [];
            foreach (static::$shipment_statuses as $status) {
                $list[$status] = l(get_called_class().'.'.$status, [], 'appmultilang');
            }

            return $list;
    }

    public static function getShipmentStatusName( $status )
    {
            return l(get_called_class().'.'.$status, [], 'appmultilang');
    }

    public static function isShipmentStatus( $status )
    {
            return in_array($status, self::$shipment_statuses);
    }


    public static function getBadge( $name )
    {
            return array_key_exists( $name, static::$badges) ? static::$badges[$name] : '';
    }





    
    public static function sequences()
    {
        $class = get_called_class();    // $class contains namespace!!!

        return ( new $class() )->sequenceList();
    }

    public function sequenceList()
    {
        return Sequence::listFor( 'App\WarehouseShippingSlip' );
    }

    public function templateList()
    {
        return Template::listFor( 'App\WarehouseShippingSlip' );
    }
    


    public function getMaxLineSortOrder()
    {
        // $this->load(['lines']);

        if ( $this->lines->count() )
            return $this->lines->max('line_sort_order');

        return 0;           // Or: return intval( $this->customershippingsliplines->max('line_sort_order') );
    }
    
    public function getNextLineSortOrder()
    {
        $inc = 10;

        return $this->getMaxLineSortOrder() + $inc;
    }
    







    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    

    public function documentlines()
    {

        // abi_r($this->getClassName().'Line'.' - '. $this->getClassSnakeCase().'_id');die();

        return $this->hasMany( 'App\WarehouseShippingSlipLine', 'warehouse_shipping_slip_id' )
                    ->orderBy('line_sort_order', 'ASC');
    }
    
    // Alias
    public function lines()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->documentlines();
    }

    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function sequence()
    {
        return $this->belongsTo('App\Sequence');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse');
    }

    public function warehousecounterpart()
    {
        return $this->belongsTo('App\Warehouse', 'warehouse_counterpart_id');
    }

    public function shippingmethod()
    {
        return $this->belongsTo('App\ShippingMethod', 'shipping_method_id');
    }

    public function carrier()
    {
        return $this->belongsTo('App\Carrier');
    }

    public function template()
    {
        return $this->belongsTo('App\Template');
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
        

        if (array_key_exists('is_invoiced', $params) && $params['is_invoiced'] > 0)
        {
            $query->where('invoiced_at', '<>', null);
        }

        if (array_key_exists('is_invoiced', $params) && $params['is_invoiced'] == 0)
        {
            $query->where('invoiced_at', null);
        }
        
        
        if ( array_key_exists('closed', $params) )
        {
            if ( $params['closed'] == '1' )
                $query->where('status', 'closed');
            else
            if ( $params['closed'] == '0' )
                $query->where('status', '<>', 'closed');
        }


        if (array_key_exists('date_from', $params) && $params['date_from'])
            // if ( isset($params['date_to']) && trim($params['date_to']) != '' )
        {
            $query->where('document_date', '>=', $params['date_from'].' 00:00:00');
        }

        if (array_key_exists('date_to', $params) && $params['date_to'])
        {
            $query->where('document_date', '<=', $params['date_to']  .' 23:59:59');
        }

        if (array_key_exists('status', $params) && $params['status'] && self::isStatus($params['status']))
        {
            $query->where('status', $params['status']);
        }

        if (array_key_exists('shipment_status', $params) && $params['shipment_status'])
        {
            $query->where('shipment_status', $params['shipment_status']);
        }

        if (array_key_exists('customer_id', $params) && $params['customer_id'])
        {
            $query->where('customer_id', $params['customer_id']);
        }

        if (array_key_exists('price_amount', $params) && is_numeric($params['price_amount']))
        {
            $amount = $params['price_amount'];

            $query->where( function ($query) use ($amount) {
                    $query->  where( 'total_tax_excl', $amount );
                    $query->orWhere( 'total_tax_incl', $amount );
            } );
        }

        if (array_key_exists('carrier_id', $params) && $params['carrier_id'] )
        {
            $query->where('carrier_id', $params['carrier_id']);
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





    /*
    |--------------------------------------------------------------------------
    | Document Lines Stuff
    |--------------------------------------------------------------------------
    */
    
    

    /**
     * Add Product to ShippingSlip
     *
     *     'line_sort_order', NO!!! => 'line_type', 'product_id', 'combination_id', 'reference', 'name', 'quantity', 'measure_unit_id', 'package_measure_unit_id', 'pmu_conversion_rate', 'notes', 'locked'
     *
     */
    public function addProductLine( $product_id, $combination_id = null, $quantity = 1.0, $params = [] )
    {
        // Do the Mambo!
        // $line_type = 'product';

        // Product
        if ($combination_id>0) {
            $combination = Combination::with('product')->findOrFail(intval($combination_id));
            $product = $combination->product;
            $product->reference = $combination->reference;
            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = Product::findOrFail(intval($product_id));
        }

        $reference  = $product->reference;
        $name = array_key_exists('name', $params) 
                            ? $params['name'] 
                            : $product->name;

        $measure_unit_id = $product->measure_unit_id;

        $package_measure_unit_id = array_key_exists('package_measure_unit_id', $params) 
                            ? $params['package_measure_unit_id'] 
                            : $product->measure_unit_id;

        $pmu_conversion_rate = 1.0; // Temporarily default

        // Measure unit stuff...
        if ( $package_measure_unit_id != $measure_unit_id )
        {
            $mu  = $product->measureunits->where('id', $measure_unit_id        )->first();
            $pmu = $product->measureunits->where('id', $package_measure_unit_id)->first();

            $pmu_conversion_rate = $pmu->conversion_rate;

            $quantity = $quantity * $pmu_conversion_rate;
            
        }



        // Misc
        $line_sort_order = array_key_exists('line_sort_order', $params) 
                            ? $params['line_sort_order'] 
                            : $this->getNextLineSortOrder();
/*
        $extra_quantity = array_key_exists('extra_quantity', $params) 
                            ? $params['extra_quantity'] 
                            : 0.0;

        $extra_quantity_label = array_key_exists('extra_quantity_label', $params) 
                            ? $params['extra_quantity_label'] 
                            : '';
*/
        $notes = array_key_exists('notes', $params) 
                            ? $params['notes'] 
                            : '';

        $locked = array_key_exists('locked', $params) 
                            ? $params['locked'] 
                            : 0;


        // Build OrderLine Object
        $data = [
            'line_sort_order' => $line_sort_order,
//            'line_type' => $line_type,
            'product_id' => $product_id,
            'combination_id' => $combination_id,
            'reference' => $reference,
            'name' => $name,
            'quantity' => $quantity,
            'measure_unit_id' => $measure_unit_id,

            'package_measure_unit_id' => $package_measure_unit_id,
            'pmu_conversion_rate'     => $pmu_conversion_rate,
            
            'notes' => $notes,
            'locked' => $locked,
        ];


        // Finishing touches
        $document_line = WarehouseShippingSlipLine::create( $data );

        $this->lines()->save($document_line);


        // Good boy, bye then
        return $document_line;

    }

}
