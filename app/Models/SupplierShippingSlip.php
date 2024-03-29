<?php

namespace App\Models;

use App\Traits\BillableStockMovementsTrait;
use App\Traits\SupplierShippingSlipInvoiceableTrait;
// use App\Traits\BillableInvoiceableTrait;

use App\Helpers\DocumentAscription;

class SupplierShippingSlip extends Billable
{
    
    use BillableStockMovementsTrait;
    use SupplierShippingSlipInvoiceableTrait;
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
                            'supplier_id', 'reference_supplier', 'is_invoiceable', 'notes_to_supplier', 
                            'prices_entered_with_tax', 'round_prices_with_tax',
    ];

    public static $rules = [
                            'document_date' => 'required|date',
//                            'payment_date'  => 'date',
                            'delivery_date' => 'nullable|date|after_or_equal:document_date',
                            'supplier_id' => 'exists:suppliers,id',
                            'shipping_address_id' => 'exists:addresses,id,addressable_id,{supplier_id},addressable_type,App\\Models\\Supplier',
                            'sequence_id' => 'exists:sequences,id',
//                            'warehouse_id' => 'exists:warehouses,id',
//                            'carrier_id'   => 'exists:carriers,id',
                            'currency_id' => 'exists:currencies,id',
                            'payment_method_id' => 'nullable|exists:payment_methods,id',
               ];

    public static $rules_createinvoice = [
                            'document_date' => 'required|date',
                            'supplier_id' => 'exists:suppliers,id',
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

        // On delivery
        if (0)
            if ( $this->shipment_status != 'pending' ) return false;

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
        return $this->supplierinvoice();
    }

    // Alias
    public function getOrderAttribute()
    {
        return $this->supplierorder();
    }
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */


    
    
    public function supplierCard()
    {
        $address = $this->supplier->address;

        $card = $supplier->name .'<br />'.
                $address->address_1 .'<br />'.
                $address->city . ' - ' . $address->state->name.' <a href="javascript:void(0)" class="btn btn-grey btn-xs disabled">'. $$address->phone .'</a>';

        return $card;
    }
    
    public function supplierCardFull()
    {
        $address = $this->supplier->address;

        $card = ($address->name_commercial ? $address->name_commercial .'<br />' : '').
                ($address->firstname  ? $address->firstname . ' '.$address->lastname .'<br />' : '').
                $address->address1 . ($address->address2 ? ' - ' : '') . $address->address2 .'<br />'.
                $address->city . ' - ' . $address->state->name.' <a href="javascript:void(0)" class="btn btn-grey btn-xs disabled">'. $address->phone .'</a>';

        return $card;
    }
    
    public function supplierCardMini()
    {
        $supplier = unserialize( $this->supplier );

        $card = $supplier["city"].' - '.($supplier["state_name"] ?? '').' <a href="#" class="btn btn-grey btn-xs disabled">'. $supplier["phone"] .'</a>';

        return $card;
    }
    
    public function supplierInfo()
    {
        $supplier = $this->supplier;

        $name = $supplier->name_fiscal ?: $supplier->name_commercial;

        if ( !$name ) 
            $name = $supplier->name;

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
        event( new \App\Events\SupplierShippingSlipConfirmed($this) );

        return true;
    }

    public function unConfirm()
    {
        if ( ! parent::unConfirmDocument() ) return false;

        // Dispatch event
        // event( new \App\Events\SupplierShippingSlipUnConfirmed($this) );

        return true;
    }

    public function close()
    {
        if ( ! parent::close() ) return false;

        // Dispatch event
        event( new \App\Events\SupplierShippingSlipClosed($this) );

        return true;
    }

    public function unclose( $status = null )
    {
        if ( ! parent::unclose() ) return false;

        // Dispatch event
        event( new \App\Events\SupplierShippingSlipUnclosed($this) );

        return true;
    }

    public function deliver()
    {
        // if ( ! parent::close() ) return false;

        // Dispatch event
        // event( new \App\Events\SupplierShippingSlipDelivered($this) );

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
        // event( new \App\Events\SupplierShippingSlipDelivered($this) );

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

    public static function isShipmentStatus( $status )
    {
            return in_array($status, self::$shipment_statuses);
    }



    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */



    public function leftAscriptions()
    {
 /*       $relation = $this->morphMany(DocumentAscription::class, 'rightable'); //->where('type', 'traceability');

        if ($model != '' && 0) $relation = $relation->where('leftable_type', $model);

        abi_r($this->morphMany(DocumentAscription::class, 'rightable'));;die();

        // abi_toSQL($relation->orderBy('id', 'ASC'));die();
*/
        return $this->morphMany(DocumentAscription::class, 'rightable')->where('type', 'traceability')->orderBy('id', 'ASC');
    }

    public function leftOrderAscriptions( $model = '' )
    {
/*        $relation = $this->morphMany(DocumentAscription::class, 'rightable'); //->where('type', 'traceability');

        if ($model != '' && 0) $relation = $relation->where('leftable_type', $model);

        abi_r($this->morphMany(DocumentAscription::class, 'rightable'));;die();

        // abi_toSQL($relation->orderBy('id', 'ASC'));die();SupplierShippingSlip::class
*/
        $ascriptions = $this->leftAscriptions;

        // abi_r($ascriptions);

        return $ascriptions->where('leftable_type', SupplierOrder::class);
    }

    public function leftOrders()
    {
        $ascriptions = $this->leftOrderAscriptions();

        // abi_r($ascriptions->pluck('leftable_id')->all(), true);

        return SupplierOrder::find( $ascriptions->pluck('leftable_id') );
    }

    public function supplierorder()
    {
        return $this->leftOrders()->first();
    }



    public function rightAscriptions()
    {
        return $this->morphMany(DocumentAscription::class, 'leftable')->where('type', 'traceability')->orderBy('id', 'ASC');
    }

    public function rightInvoiceAscriptions( $model = '' )
    {
        return $this->rightAscriptions->where('rightable_type', SupplierInvoice::class);
    }

    public function rightInvoices()
    {
        // $ascriptions = $this->rightInvoiceAscriptions();

        // abi_r($ascriptions->pluck('rightable_id')->all(), true);

        return SupplierInvoice::find( $this->rightInvoiceAscriptions()->pluck('rightable_id') );
    }

    public function supplierinvoice()
    {
        return $this->rightInvoices()->first();
    }




    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

}
