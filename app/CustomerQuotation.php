<?php

namespace App;

use \App\CustomerQuotationLine;

class CustomerQuotation extends Billable
{

    public static $badges = [
            'a_class' => 'alert-warning',
            'i_class' => 'fa-file-text-o',
        ];


    protected $document_dates = [
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
                            'shippingslip_date' => 'required|date',
                            'sequence_id' => 'exists:sequences,id',
                            'template_id' => 'exists:templates,id',
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
        event( new \App\Events\CustomerQuotationConfirmed($this) );

        return true;
    }

    public function close()
    {
        if ( ! parent::close() ) return false;

        // Dispatch event
        event( new \App\Events\CustomerQuotationClosed($this) );

        return true;
    }

    public function unclose( $status = null )
    {
        if ( ! parent::unclose() ) return false;

        // Dispatch event
        event( new \App\Events\CustomerQuotationUnclosed($this) );

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



    public function rightQuotationAscriptions( $model = '' )
    {
        return $this->rightAscriptions->where('type', 'backorder')->where('rightable_type', 'App\CustomerQuotation');
    }

    public function rightQuotations()
    {
        // $ascriptions = $this->rightInvoiceAscriptions();

        // abi_r($ascriptions->pluck('rightable_id')->all(), true);

        return \App\CustomerQuotation::find( $this->rightQuotationAscriptions()->pluck('rightable_id') );
    }

    public function customerbackorder()
    {
        return $this->rightQuotations()->first();
    }



    public function leftAscriptions()
    {
        return $this->morphMany('App\DocumentAscription', 'rightable')->orderBy('id', 'ASC');
    }

    public function leftQuotationAscriptions( $model = '' )
    {
        return $this->leftAscriptions->where('type', 'backorder')->where('leftable_type', 'App\CustomerQuotation');
    }

    public function leftQuotations()
    {
        $ascriptions = $this->leftQuotationAscriptions();

        return \App\CustomerQuotation::find( $ascriptions->pluck('leftable_id') );
    }

    public function customerorder()
    {
        return $this->leftQuotations()->first();
    }
    



    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

}