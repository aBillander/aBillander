<?php

namespace App;

use \App\CustomerOrderLine;

class CustomerOrder extends Billable
{

    public static $badges = [
            'a_class' => 'alert-success',
            'i_class' => 'fa-shopping-bag',
        ];


    protected $document_dates = [
            'shipping_slip_at',
            'invoiced_at',
            'aggregated_at',
            'backordered_at',

            'export_date',
    ];

    /**
     * The fillable properties for this model.
     *
     * @var array
     *
     * https://gist.github.com/JordanDalton/f952b053ef188e8750177bf0260ce166
     */
    protected $document_fillable = [
            'production_sheet_id'
    ];


    public static $rules = [
                            'document_date' => 'required|date',
//                            'payment_date'  => 'date',
                            'delivery_date' => 'nullable|date|after_or_equal:document_date',
                            'customer_id' => 'exists:customers,id',
                            'invoicing_address_id' => '',
                            'shipping_address_id' => 'exists:addresses,id,addressable_id,{customer_id},addressable_type,App\Customer',
                            'sequence_id' => 'exists:sequences,id',
//                            'warehouse_id' => 'exists:warehouses,id',
//                            'carrier_id'   => 'exists:carriers,id',
                            'currency_id' => 'exists:currencies,id',
                            'payment_method_id' => 'nullable|exists:payment_methods,id',
               ];

    public static $rules_createshippingslip = [
                            'document_id' => 'exists:customer_orders,id',
                            'shippingslip_date' => 'required|date',
                            'shippingslip_delivery_date' => 'nullable|date',
                            
                            'shippingslip_sequence_id' => 'exists:sequences,id',
                            'shippingslip_template_id' => 'exists:templates,id',
               ];

    public static $rules_createorder = [
                            'order_id' => 'exists:orders,id',
                            'document_date' => 'required|date',
                            'sequence_id' => 'exists:sequences,id',
 //                           'template_id' => 'exists:templates,id',
               ];

    public static $rules_createaggregate = [
                            'order_document_date' => 'required|date',
                            'order_sequence_id' => 'exists:sequences,id',
 //                           'template_id' => 'exists:templates,id',
               ];


    public function getWebshopIdAttribute()
    {
        if ( Configuration::isTrue('ENABLE_WEBSHOP_CONNECTOR') && (strpos($this->reference, '#') !== FALSE) )
        {
            list($prifix, $ws_id) = explode('#', $this->reference);

            if ( ($ws_id = (int) $ws_id) > 0 )
                return $ws_id;
        }

        return null;
    }


    public function getDeletableAttribute()
    {
//        return $this->status != 'closed' && !$this->->status != 'canceled';
        return $this->status != 'closed';
    }

    public function getUncloseableAttribute()
    {
        if ( $this->status != 'closed' ) return false;

        if ( optional($this->rightAscriptions)->count() || optional($this->leftAscriptions)->count() ) return false;

        return true;
    }

    // Alias
    public function getShippingslipAttribute()
    {
        return $this->customerShippingSlip();
    }

    // Alias
    public function getBackorderAttribute()
    {
        return $this->customerbackorder();
    }

    // Alias
    public function getBackordereeAttribute()
    {
        return $this->customerorder();
    }

    // Alias
    public function getAggregateorderAttribute()
    {
        return $this->customeraggregateorder();
    }

    // Alias
    public function getAggregateorderbyAttribute()
    {
        return $this->customeraggregateorderby();
    }

    // Alias
    public function getQuotationAttribute()
    {
        return $this->customerquotation();
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function getAllNotesAttribute()
    {
        $notes = '';

        if ($this->notes_from_customer && (strlen($this->notes_from_customer) > 4)) $notes .= $this->notes_from_customer."\n\n";
        if ($this->notes               ) $notes .= $this->notes."\n\n";
        if ($this->notes_to_customer   ) $notes .= $this->notes_to_customer."\n\n";


        return rtrim($notes);
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

        // Gorrino way to detect which Order fails in listings!
        if ( !$address ) die();

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
            $name = $customer->name;

        return $name;
    }
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function confirm()
    {
        if ( ! parent::confirm() ) return false;

        // Dispatch event
        event( new \App\Events\CustomerOrderConfirmed($this) );

        return true;
    }

    public function close()
    {
        if ( ! parent::close() ) return false;

        // Dispatch event
        event( new \App\Events\CustomerOrderClosed($this) );

        return true;
    }

    public function unclose( $status = null )
    {
        if ( !$this->uncloseable ) return false;

        if ( ! parent::unclose() ) return false;

        // Dispatch event
        event( new \App\Events\CustomerOrderUnclosed($this) );

        return true;
    }


    // Compatibility with sibling models
    public function shouldPerformStockMovements()
    {
        return false;
    }


    // Compatibility with sibling models
    public function canRevertStockMovements()
    {
        return false;
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

    public function customerorderManufacturableLines()
    {
        return $this->documentlines()
                    ->whereHas('product', function($query) {
                       $query->  where('procurement_type', 'manufacture');
                       $query->orWhere('procurement_type', 'assembly');
                    })
                    ->with('product');
    }

    
    // Alias
    public function customerorderlines()
    {
        return $this->documentlines();
    }

    // Alias
    public function customerorderlinetaxes()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->documentlinetaxes();
    }


    // Deprecated? I dunno
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
    
    // Alias
    public function documenttaxes()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->customerordertaxes();
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

    public function rightShippingSlipAscriptions( $model = '' )
    {
        return $this->rightAscriptions->where('type', 'traceability')->where('rightable_type', 'App\CustomerShippingSlip');
    }

    public function rightShippingSlips()
    {
        // $ascriptions = $this->rightInvoiceAscriptions();

        // abi_r($ascriptions->pluck('rightable_id')->all(), true);

        return \App\CustomerShippingSlip::find( $this->rightShippingSlipAscriptions()->pluck('rightable_id') );
    }

    public function customerShippingSlip()
    {
        return $this->rightShippingSlips()->first();
    }



    public function rightOrderAscriptions( $model = '' )
    {
        return $this->rightAscriptions->where('type', 'backorder')->where('rightable_type', 'App\CustomerOrder');
    }

    public function rightOrders()
    {
        // $ascriptions = $this->rightInvoiceAscriptions();

        // abi_r($ascriptions->pluck('rightable_id')->all(), true);

        return \App\CustomerOrder::find( $this->rightOrderAscriptions()->pluck('rightable_id') );
    }

    public function customerbackorder()
    {
        return $this->rightOrders()->first();
    }



    public function leftAscriptions()
    {
        return $this->morphMany('App\DocumentAscription', 'rightable')->orderBy('id', 'ASC');
    }

    public function leftOrderAscriptions( $model = '' )
    {
        return $this->leftAscriptions->where('type', 'backorder')->where('leftable_type', 'App\CustomerOrder');
    }

    public function leftOrders()
    {
        $ascriptions = $this->leftOrderAscriptions();

        return \App\CustomerOrder::find( $ascriptions->pluck('leftable_id') );
    }

    public function customerorder()
    {
        return $this->leftOrders()->first();
    }



    public function rightAggregateOrderAscriptions( $model = '' )
    {
        return $this->rightAscriptions->where('type', 'aggregate')->where('rightable_type', 'App\CustomerOrder');
    }

    public function rightAggregateOrders()
    {
        // $ascriptions = $this->rightInvoiceAscriptions();

        // abi_r($ascriptions->pluck('rightable_id')->all(), true);

        return \App\CustomerOrder::find( $this->rightAggregateOrderAscriptions()->pluck('rightable_id') );
    }

    public function customeraggregateorder()
    {
        return $this->rightAggregateOrders()->first();
    }



    public function leftAggregateOrderAscriptions( $model = '' )
    {
        return $this->leftAscriptions->where('type', 'aggregate')->where('leftable_type', 'App\CustomerOrder');
    }

    public function leftAggregateOrders()
    {
        $ascriptions = $this->leftAggregateOrderAscriptions();

        return \App\CustomerOrder::find( $ascriptions->pluck('leftable_id') );
    }

    public function customeraggregateorderby()
    {
        return $this->leftAggregateOrders()->first();
    }




    public function leftQuotationAscriptions( $model = '' )
    {
        return $this->leftAscriptions->where('type', 'traceability')->where('leftable_type', 'App\CustomerQuotation');
    }

    public function leftQuotations()
    {
        $ascriptions = $this->leftQuotationAscriptions();

        return \App\CustomerQuotation::find( $ascriptions->pluck('leftable_id') );
    }

    public function customerquotation()
    {
        return $this->leftQuotations()->first();
    }
    



    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

}
