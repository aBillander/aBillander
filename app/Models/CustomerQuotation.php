<?php

namespace App\Models;

use App\Helpers\DocumentAscription;

class CustomerQuotation extends Billable
{

    public static $badges = [
            'a_class' => 'alert-warning',
            'i_class' => 'fa-file-text-o',
        ];


    protected $document_dates = [
            'valid_until_date',
            'order_at',
    ];

    /**
     * The fillable properties for this model.
     *
     * @var array
     *
     * https://gist.github.com/JordanDalton/f952b053ef188e8750177bf0260ce166
     */
    protected $document_fillable = [
            'valid_until_date'
    ];


    public static $rules = [
                            'document_date' => 'required|date',
//                            'payment_date'  => 'date',
                            'delivery_date' => 'nullable|date|after_or_equal:document_date',
                            'valid_until_date' => 'nullable|date|after_or_equal:document_date',
                            'customer_id' => 'exists:customers,id',
                            'invoicing_address_id' => '',
                            'shipping_address_id' => 'exists:addresses,id,addressable_id,{customer_id},addressable_type,App\Models\Customer',
                            'sequence_id' => 'exists:sequences,id',
//                            'warehouse_id' => 'exists:warehouses,id',
//                            'carrier_id'   => 'exists:carriers,id',
                            'currency_id' => 'exists:currencies,id',
                            'payment_method_id' => 'nullable|exists:payment_methods,id',
               ];

    public static $rules_createorder = [
                            'document_id' => 'exists:customer_quotations,id',
                            'order_date' => 'required|date',
                            'order_sequence_id' => 'exists:sequences,id',
                            'order_template_id' => 'exists:templates,id',
               ];


    public function getIsValidAttribute()
    {
        if ( $this->status == 'draft' ) return false;

        if ( $this->valid_until_date && ( $this->valid_until_date > \Carbon\Carbon::now()) ) return false;

        return true;
    }


    public function getDeletableAttribute()
    {
//        return $this->status != 'closed' && !$this->->status != 'canceled';
        return $this->status != 'closed';
    }

    public function getUncloseableAttribute()
    {
        if ( $this->status != 'closed' ) return false;

//        if ( optional($this->rightAscriptions)->count() || optional($this->leftAscriptions)->count() ) return false;

        return true;
    }

    // Alias
    public function getOrderAttribute()
    {
        return $this->customerOrder();
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
        if ( !$this->uncloseable ) return false;
        
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
        return $this->belongsTo(ProductionSheet::class, 'production_sheet_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Ascriptions
    |--------------------------------------------------------------------------
    */
    
    public function rightAscriptions()
    {
        return $this->morphMany(DocumentAscription::class, 'leftable')->orderBy('id', 'ASC');
    }

    public function rightOrderAscriptions( $model = '' )
    {
        return $this->rightAscriptions->where('type', 'traceability')->where('rightable_type', CustomerOrder::class);
    }

    public function rightOrders()
    {
        // $ascriptions = $this->rightInvoiceAscriptions();

        // abi_r($ascriptions->pluck('rightable_id')->all(), true);

        return CustomerOrder::find( $this->rightOrderAscriptions()->pluck('rightable_id') );
    }

    public function customerOrder()
    {
        return $this->rightOrders()->first();
    }

    



    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

}
