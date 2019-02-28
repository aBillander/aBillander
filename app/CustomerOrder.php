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
            'backordered_at'
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
                            'order_id' => 'exists:orders,id',
                            'document_date' => 'required|date',
                            'sequence_id' => 'exists:sequences,id',
                            'template_id' => 'exists:templates,id',
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


    public function getDeletableAttribute()
    {
//        return $this->status != 'closed' && !$this->->status != 'canceled';
        return $this->status != 'closed';
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


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    
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
    



    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

}
