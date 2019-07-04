<?php

namespace App;

use App\Traits\BillableStockMovementsTrait;
// use App\Traits\BillableInvoiceableTrait;

use \App\CustomerShippingSlipLine;

class CustomerShippingSlip extends Billable
{
    
    use BillableStockMovementsTrait;
//    use BillableInvoiceableTrait;

    public static $badges = [
            'a_class' => 'alert-info',
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


    protected $document_dates = [
            'invoiced_at'
    ];


    /**
     * The fillable properties for this model.
     *
     * @var array
     *
     * https://gist.github.com/JordanDalton/f952b053ef188e8750177bf0260ce166
     */
    protected $document_fillable = [
                            'prices_entered_with_tax', 'round_prices_with_tax',
    ];

    public static $rules = [
                            'document_date' => 'required|date',
//                            'payment_date'  => 'date',
                            'delivery_date' => 'nullable|date|after_or_equal:document_date',
                            'customer_id' => 'exists:customers,id',
                            'shipping_address_id' => 'exists:addresses,id,addressable_id,{customer_id},addressable_type,App\Customer',
                            'sequence_id' => 'exists:sequences,id',
//                            'warehouse_id' => 'exists:warehouses,id',
//                            'carrier_id'   => 'exists:carriers,id',
                            'currency_id' => 'exists:currencies,id',
                            'payment_method_id' => 'nullable|exists:payment_methods,id',
               ];

    public static $rules_createinvoice = [
                            'document_date' => 'required|date',
                            'customer_id' => 'exists:customers,id',
                            'sequence_id' => 'exists:sequences,id',
                            'template_id' => 'exists:templates,id',
               ];


    public function getDeletableAttribute()
    {
        return $this->status != 'closed' && !$this->invoiced_at && $this->rightAscriptions->isEmpty();
    }

    public function getUncloseableAttribute()
    {
        if ( $this->status != 'closed' ) return false;

        if ( ! $this->rightAscriptions->isEmpty() ) return false;

        return true;
    }

    public function getUnconfirmableAttribute()
    {
        if ( $this->status != 'confirmed' ) return false;

        if ( optional($this->rightAscriptions)->count() || optional($this->leftAscriptions)->count() ) return false;

        return true;
    }

    // Alias
    public function getInvoiceAttribute()
    {
        return $this->customerinvoice();
    }

    // Alias
    public function getOrderAttribute()
    {
        return $this->customerorder();
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
        event( new \App\Events\CustomerShippingSlipConfirmed($this) );

        return true;
    }

    public function unConfirm()
    {
        if ( ! parent::unConfirmDocument() ) return false;

        // Dispatch event
        // event( new \App\Events\CustomerShippingSlipUnConfirmed($this) );

        return true;
    }

    public function close()
    {
        if ( ! parent::close() ) return false;

        // Dispatch event
        event( new \App\Events\CustomerShippingSlipClosed($this) );

        return true;
    }

    public function unclose( $status = null )
    {
        if ( ! parent::unclose() ) return false;

        // Dispatch event
        event( new \App\Events\CustomerShippingSlipUnclosed($this) );

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

        return true;
    }


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



    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */



    public function leftAscriptions()
    {
 /*       $relation = $this->morphMany('App\DocumentAscription', 'rightable'); //->where('type', 'traceability');

        if ($model != '' && 0) $relation = $relation->where('leftable_type', $model);

        abi_r($this->morphMany('App\DocumentAscription', 'rightable'));;die();

        // abi_toSQL($relation->orderBy('id', 'ASC'));die();
*/
        return $this->morphMany('App\DocumentAscription', 'rightable')->where('type', 'traceability')->orderBy('id', 'ASC');
    }

    public function leftOrderAscriptions( $model = '' )
    {
/*        $relation = $this->morphMany('App\DocumentAscription', 'rightable'); //->where('type', 'traceability');

        if ($model != '' && 0) $relation = $relation->where('leftable_type', $model);

        abi_r($this->morphMany('App\DocumentAscription', 'rightable'));;die();

        // abi_toSQL($relation->orderBy('id', 'ASC'));die();\App\CustomerShippingSlip::class
*/
        $ascriptions = $this->leftAscriptions;

        // abi_r($ascriptions);

        return $ascriptions->where('leftable_type', 'App\CustomerOrder');
    }

    public function leftOrders()
    {
        $ascriptions = $this->leftOrderAscriptions();

        // abi_r($ascriptions->pluck('leftable_id')->all(), true);

        return \App\CustomerOrder::find( $ascriptions->pluck('leftable_id') );
    }

    public function customerorder()
    {
        return $this->leftOrders()->first();
    }



    public function rightAscriptions()
    {
        return $this->morphMany('App\DocumentAscription', 'leftable')->where('type', 'traceability')->orderBy('id', 'ASC');
    }

    public function rightInvoiceAscriptions( $model = '' )
    {
        return $this->rightAscriptions->where('rightable_type', 'App\CustomerInvoice');
    }

    public function rightInvoices()
    {
        // $ascriptions = $this->rightInvoiceAscriptions();

        // abi_r($ascriptions->pluck('rightable_id')->all(), true);

        return \App\CustomerInvoice::find( $this->rightInvoiceAscriptions()->pluck('rightable_id') );
    }

    public function customerinvoice()
    {
        return $this->rightInvoices()->first();
    }




    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

}
