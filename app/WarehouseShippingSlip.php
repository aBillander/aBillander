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



    public function getUncloseableAttribute()
    {
        if ( $this->status != 'closed' ) return false;

        // On delivery
        if (0)
            if ( $this->shipment_status != 'pending' ) return false;

        // if ( ! $this->rightAscriptions->isEmpty() ) return false;

        return true;
    }

    public function getUnconfirmableAttribute()
    {
        // Convention: Cannot unconfirm warehouse shipping slip documents
        return false;

        if ( $this->status != 'confirmed' ) return false;

        // if ( optional($this->rightAscriptions)->count() || optional($this->leftAscriptions)->count() ) return false;

        return true;
    }


    public function getEditableAttribute()
    {
        return !( $this->locked || $this->status == 'closed' || $this->status == 'canceled' );
    }

    public function getDeletableAttribute()
    {
        // return !( $this->status == 'closed' || $this->status == 'canceled' );
        return $this->status != 'closed';
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

    /**
     * Update Product Line in Order
     *
     *     'line_sort_order', NO!!! => 'line_type', 'product_id', 'combination_id', 'reference', 'name', 'quantity', 'measure_unit_id', 'package_measure_unit_id', 'pmu_conversion_rate', 'notes', 'locked'
     *
     */
    public function updateProductLine( $line_id, $params = [] )
    {
        // Do the Mambo!
        $lineClass = $this->getClassName().'Line';

        $document_line = WarehouseShippingSlipLine::where('warehouse_shipping_slip_id', $this->id)
                        ->with('document')
                        ->with('product')
//                        ->with('combination')
                        ->with('measureunit')
//                        ->with('packagemeasureunit')
                        ->findOrFail($line_id);


        // Product
        if ($document_line->combination_id>0) {
//            $combination = \App\Combination::with('product')->with('product.tax')->findOrFail(intval($combination_id));
//            $product = $combination->product;
//            $product->reference = $combination->reference;
//            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = $document_line->product;
        }

        $pmu_conversion_rate = 1.0; // Temporarily default

        // Measure unit stuff...
        if ( $document_line->package_measure_unit_id && ($document_line->package_measure_unit_id != $document_line->measure_unit_id) )
        {
            $pmu_conversion_rate = $document_line->pmu_conversion_rate;
/*
            $quantity = $quantity * $pmu_conversion_rate;

            $pmu_label = array_key_exists('pmu_label', $params) && $params['pmu_label']
                            ? $params['pmu_label'] 
                            : $pmu->name.' : '.(int) $pmu_conversion_rate.'x'.$mu->name;
*/            
        }

        // Warning: stored $quantity is ALLWAYS in "stock keeping unit"
        if (array_key_exists('quantity', $params)) 
        {
            if (array_key_exists('use_measure_unit_id', $params) && ( $params['use_measure_unit_id'] == 'measure_unit_id' ))
            {
                // FORCE measure unit to default line measure unit
                // See CustomerOrdersController@createSingleShippingSlip, section relative to back order creation
                // No need of calculations
                $quantity = $params['quantity'];

            } else {
                // "Input quantity" (from form, etc.) is expected in "line measure unit", i.e., in package_measure_unit,
                // when $document_line->package_measure_unit_id != $document_line->measure_unit_id
                $quantity = $params['quantity'] * $pmu_conversion_rate;
            }
        }
        else
            $quantity = $document_line->quantity;       // Already in "stock keeping unit"

        // Product Price
//        $price = $product->getPrice();
//        $unit_price = $price->getPrice();


/*      Not needed:

        // Calculate price per $customer_id now!
        $customer_price = $product->getPriceByCustomer( $customer, $quantity, $currency );

        // Is there a Price for this Customer?
        if (!$customer_price) return null;      // Product not allowed for this Customer
        // What to do???

        $customer_price->applyTaxPercent( $tax_percent );
        $unit_customer_price = $customer_price->getPrice();
*/
        // Price Policy
        $pricetaxPolicy = array_key_exists('prices_entered_with_tax', $params) 
                            ? $params['prices_entered_with_tax'] 
                            : $document_line->price_is_tax_inc;

        // Customer Final Price
        if ( array_key_exists('prices_entered_with_tax', $params) && array_key_exists('unit_customer_final_price', $params) )
        {
            $unit_customer_final_price = new \App\Price( $params['unit_customer_final_price'] / $pmu_conversion_rate, $pricetaxPolicy, $currency );

            $unit_customer_final_price->applyTaxPercent( $tax_percent );

        } else {

            $unit_customer_final_price = \App\Price::create([$document_line->unit_customer_final_price, $document_line->unit_customer_final_price_tax_inc, $pricetaxPolicy], $currency);
        }

        // Discount
        $discount_percent = array_key_exists('discount_percent', $params) 
                            ? $params['discount_percent'] 
                            : $document_line->discount_percent;

        // Final Price
        $unit_final_price = clone $unit_customer_final_price;
        if ( $discount_percent ) 
            $unit_final_price->applyDiscountPercent( $discount_percent );

        // Sales Rep
        $sales_rep_id = array_key_exists('sales_rep_id', $params) 
                            ? $params['sales_rep_id'] 
                            : $document_line->sales_rep_id;
        
        $commission_percent = array_key_exists('sales_rep_id', $params) && array_key_exists('commission_percent', $params) 
                            ? $params['commission_percent'] 
                            : $document_line->commission_percent;

        // Misc
        $notes = array_key_exists('notes', $params) 
                            ? $params['notes'] 
                            : $document_line->notes;


        // Build OrderLine Object
        $data = [
 //           'line_sort_order' => $line_sort_order,
 //           'line_type' => $line_type,
 //           'product_id' => $product_id,
 //           'combination_id' => $combination_id,
 //           'reference' => $reference,
 //           'name' => $name,
            'quantity' => $quantity,
 //           'measure_unit_id' => $measure_unit_id,

            'prices_entered_with_tax' => $pricetaxPolicy,
    
//            'cost_price' => $cost_price,
//            'unit_price' => $unit_price,
//            'unit_customer_price' => $unit_customer_price,
            'unit_customer_final_price' => $unit_customer_final_price->getPrice(),
            'unit_customer_final_price_tax_inc' => $unit_customer_final_price->getPriceWithTax(),
            'unit_final_price' => $unit_final_price->getPrice(),
            'unit_final_price_tax_inc' => $unit_final_price->getPriceWithTax(), 
  //          'sales_equalization' => $sales_equalization,
            'discount_percent' => $discount_percent,
            'discount_amount_tax_incl' => 0.0,      // floatval( $request->input('discount_amount_tax_incl', 0.0) ),
            'discount_amount_tax_excl' => 0.0,      // floatval( $request->input('discount_amount_tax_excl', 0.0) ),

  //          'total_tax_incl' => $quantity * $unit_final_price->getPriceWithTax(),
  //          'total_tax_excl' => $quantity * $unit_final_price->getPrice(),

            'tax_percent' => $tax_percent,
            'commission_percent' => $commission_percent,
            'notes' => $notes,
    //        'locked' => 0,
    
    //        'customer_order_id',
    //        'tax_id' => $tax->id,
            'sales_rep_id' => $sales_rep_id,
        ];

        // More stuff

        if (array_key_exists('line_sort_order', $params)) 
            $data['line_sort_order'] = $params['line_sort_order'];
        
        if (array_key_exists('name', $params)) 
            $data['name'] = $params['name'];
        
        if (array_key_exists('measure_unit_id', $params)) 
            $data['measure_unit_id'] = $params['measure_unit_id'];


        // Finishing touches
        $document_line->update( $data );


        // Good boy, bye then
        return $document_line;

    }

    

    /*
    |--------------------------------------------------------------------------
    | Status Manager
    |--------------------------------------------------------------------------
    */

    public function confirm()
    {
        // if ( ! parent::confirm() ) return false;

        // Already confirmed?
        if ( $this->document_reference || ( $this->status != 'draft' ) ) return false;

        // onhold?
        if ( $this->onhold ) return false;

        // Sequence
        $seq_id = $this->sequence_id > 0 ? $this->sequence_id : Configuration::get('DEF_WAREHOUSE_SHIPPING_SLIP_SEQUENCE');
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
//        $this->document_date = $this->validation_date;

        $this->save();

        // So far, so good

        // Dispatch event
        event( new \App\Events\WarehouseShippingSlipConfirmed($this) );

        return true;
    }

    public function unConfirm()
    {
        // if ( ! parent::unConfirmDocument() ) return false;

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

        // So far, so good

        // Dispatch event
        // event( new \App\Events\WarehouseShippingSlipUnConfirmed($this) );

        return true;
    }

    public function close()
    {
        // if ( ! parent::close() ) return false;

        // Can I ...?
        if ( ($this->status == 'draft') || ($this->status == 'canceled') ) return false;
        
        if ( $this->status == 'closed' ) return false;

        // No lines?
        if ( $this->lines->count() == 0 ) return false;

        // onhold?
        if ( $this->onhold ) return false;

        // Do stuf...
        $this->status = 'closed';
        $this->close_date = \Carbon\Carbon::now();

        $this->save();

        // So far, so good

        // Dispatch event
        event( new \App\Events\WarehouseShippingSlipClosed($this) );

        return true;
    }

    public function unclose( $status = null )
    {
        // if ( ! parent::unclose() ) return false;

        // Can I ...?
        if ( $this->status != 'closed' ) return false;

        // Do stuf...
        $this->status = $status ?: 'confirmed';
        $this->close_date =null;

        $this->save();

        // So far, so good

        // Dispatch event
        event( new \App\Events\WarehouseShippingSlipUnclosed($this) );

        return true;
    }

    public function deliver()
    {
        // if ( ! parent::close() ) return false;

        // Dispatch event
        // event( new \App\Events\CustomerShippingSlipDelivered($this) );

        // Can I ...?
        if ( $this->status != 'closed' ) return false;

        // onhold?
        if ( $this->onhold ) return false;

        // Do stuf...
        $this->shipment_status = 'delivered';
        $this->delivery_date_real = \Carbon\Carbon::now();

        $this->save();

        return true;
    }

    public function undeliver()
    {
        // if ( ! parent::close() ) return false;

        // Dispatch event
        // event( new \App\Events\CustomerShippingSlipDelivered($this) );

        // Can I ...?
        if ( $this->status != 'closed' ) return false;

        // onhold?
        // if ( $this->onhold ) return false;

        // Do stuf...
        $this->shipment_status = 'pending';
        $this->delivery_date_real = null;

        $this->save();

        return true;
    }

    

    /*
    |--------------------------------------------------------------------------
    | Stock Movements
    |--------------------------------------------------------------------------
    */

    public function shouldPerformStockMovements()
    {
        return true;

        if ( $this->created_via == 'manual' && $this->stock_status == 'pending' ) return true;
/*
        if ($this->stock_status == 'pending') return true;

        if ($this->stock_status == 'completed') return false;

        if ($this->created_via == 'aggregate_shipping_slips') return false;
*/
        return false;
    }

    public function canRevertStockMovements()
    {
        if ( $this->status == 'closed' ) return true;

        return false;
/*
        if ($this->created_via == 'manual' && $this->stock_status == 'completed' ) return true;

        return false;
*/
    }

    
    public function makeStockMovements()
    {
        // Let's rock!
        foreach ($this->lines as $line) {
            //
            // Only products, please!!!
            // if ( ! ( $line->line_type == 'product' ) ) continue;
            if ( ! ( $line->product_id > 0 ) )         continue;

            // Prepare StockMovement::TRANSFER_OUT
            $data = [
                    'date' => \Carbon\Carbon::now(),

//                    'stockmovementable_id' => $line->,
//                    'stockmovementable_type' => $line->,

                    'document_reference' => $this->document_reference,

//                    'quantity_before_movement' => $line->,
                    'quantity' => $line->quantity,
                    'measure_unit_id' => $line->measure_unit_id,
//                    'quantity_after_movement' => $line->,

                    'price' => $line->product->cost_price,
                    'price_currency' => $line->product->cost_price,
//                    'currency_id' => $this->currency_id,
//                    'conversion_rate' => $this->currency_conversion_rate,

                    'notes' => '',

                    'product_id' => $line->product_id,
                    'combination_id' => $line->combination_id,
                    'reference' => $line->reference,
                    'name' => $line->name,

                    'warehouse_id' => $this->warehouse_id,
                    'warehouse_counterpart_id' => $this->warehouse_counterpart_id,

                    'movement_type_id' => StockMovement::TRANSFER_OUT,

//                    'user_id' => $line->,

//                    'inventorycode'
            ];

            $stockmovement = StockMovement::createAndProcess( $data );

            if ( $stockmovement )
            {
                //
                $line->stockmovements()->save( $stockmovement );
            }


            // The show **MUST** go on

            // Prepare StockMovement::TRANSFER_IN
            $data1 = [
                    'warehouse_id' => $this->warehouse_counterpart_id,
                    'warehouse_counterpart_id' => $this->warehouse_id,

                    'movement_type_id' => StockMovement::TRANSFER_IN,
            ];

            $stockmovement = StockMovement::createAndProcess( array_merge($data, $data1) );

            if ( $stockmovement )
            {
                //
                $line->stockmovements()->save( $stockmovement );
            }
        }

        // $this->stock_status = 'completed';
        $this->save();

        return true;
    }

    
    public function revertStockMovements()
    {
        // Let's rock!
        foreach ($this->lines as $line) {
            //
            // Only products, please!!!
            if ( ! ( $line->product_id > 0 ) ) continue;

            //
            foreach ( $line->stockmovements as $mvt ) {
                # code...
                $data = [
                        'date' => \Carbon\Carbon::now(),

    //                    'stockmovementable_id' => $line->,
    //                    'stockmovementable_type' => $line->,

                        'document_reference' => $mvt->document_reference,

    //                    'quantity_before_movement' => $line->,
                        'quantity' => -$mvt->quantity,
                        'measure_unit_id' => $mvt->measure_unit_id,
    //                    'quantity_after_movement' => $line->,

                        'price' => $mvt->price,
                        'price_currency' => $mvt->price_currency,
                        'currency_id' => $mvt->currency_id,
                        'conversion_rate' => $mvt->conversion_rate,

                        'notes' => '',

                        'product_id' => $mvt->product_id,
                        'combination_id' => $mvt->combination_id,
                        'reference' => $mvt->reference,
                        'name' => $mvt->name,

                        'warehouse_id' => $mvt->warehouse_id,
    //                    'warehouse_counterpart_id' => $line->,

                        'movement_type_id' => $mvt->movement_type_id,

    //                    'user_id' => $line->,

    //                    'inventorycode'
                ];

                $stockmovement = StockMovement::createAndProcess( $data );

                if ( $stockmovement )
                {
                    //
                    $line->stockmovements()->save( $stockmovement );
                }

            }   // Movements loop ENDS

        }   // Lines loop ENDS

        // $this->stock_status = 'pending';
        $this->save();

        return true;
    }


}
