<?php

namespace App\Models;

use App\Helpers\DocumentAscription;

class SupplierOrder extends Billable
{

    public static $badges = [
            'a_class' => 'alert-success',
            'i_class' => 'fa-shopping-cart',
        ];


    protected $document_dates = [
            'shipping_slip_at',
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
                            'supplier_id', 'notes_to_supplier', 
                            'fulfillment_status'
    ];


    public static $rules = [
                            'document_date' => 'required|date',
//                            'payment_date'  => 'date',
                            'delivery_date' => 'nullable|date|after_or_equal:document_date',
                            'supplier_id' => 'exists:suppliers,id',
                            'invoicing_address_id' => '',
                            'shipping_address_id' => 'nullable|exists:addresses,id,addressable_id,{supplier_id},addressable_type,App\Supplier',
                            'sequence_id' => 'exists:sequences,id',
                            'warehouse_id' => 'nullable|exists:warehouses,id',
//                            'carrier_id'   => 'exists:carriers,id',
                            'currency_id' => 'exists:currencies,id',
                            'payment_method_id' => 'nullable|exists:payment_methods,id',
               ];

    public static $rules_createshippingslip = [
                            'document_id' => 'exists:supplier_orders,id',
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
        return $this->supplierShippingSlip();
    }

    // Alias
    public function getBackorderAttribute()
    {
        return $this->supplierbackorder();
    }

    // Alias
    public function getBackordereeAttribute()
    {
        return $this->supplierorder();
    }

    // Alias
    public function getAggregateorderAttribute()
    {
        return $this->supplieraggregateorder();
    }

    // Alias
    public function getAggregateorderbyAttribute()
    {
        return $this->supplieraggregateorderby();
    }

    // Alias
    public function getQuotationAttribute()
    {
        return $this->supplierquotation();
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function getAllNotesAttribute()
    {
        $notes = '';

        if ($this->notes_from_supplier && (strlen($this->notes_from_supplier) > 4)) $notes .= $this->notes_from_supplier."\n\n";
        if ($this->notes               ) $notes .= $this->notes."\n\n";
        if ($this->notes_to_supplier   ) $notes .= $this->notes_to_supplier."\n\n";


        return rtrim($notes);
    }

    
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

        // Gorrino way to detect which Order fails in listings!
        if ( !$address ) die();

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
        event( new \App\Events\SupplierOrderConfirmed($this) );

        return true;
    }

    public function close()
    {
        if ( ! parent::close() ) return false;

        // Dispatch event
        event( new \App\Events\SupplierOrderClosed($this) );

        return true;
    }

    public function unclose( $status = null )
    {
        if ( !$this->uncloseable ) return false;

        if ( ! parent::unclose() ) return false;

        // Dispatch event
        event( new \App\Events\SupplierOrderUnclosed($this) );

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

    
    // Alias
    public function supplierorderlines()
    {
        return $this->documentlines();
    }

    // Alias
    public function supplierorderlinetaxes()      // http://advancedlaravel.com/eloquent-relationships-examples
    {
        return $this->documentlinetaxes();
    }


    // Deprecated? I dunno
    public function supplierordertaxes()
    {
        $taxes = [];
        $tax_lines = $this->supplierorderlinetaxes;


        foreach ($tax_lines as $line) {

            if ( isset($taxes[$line->tax_rule_id]) ) {
                $taxes[$line->tax_rule_id]->taxable_base   += $line->taxable_base;
                $taxes[$line->tax_rule_id]->total_line_tax += $line->total_line_tax;
            } else {
                $tax = new SupplierOrderLineTax();
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
        return $this->supplierordertaxes();
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

    public function rightShippingSlipAscriptions( $model = '' )
    {
        return $this->rightAscriptions->where('type', 'traceability')->where('rightable_type', SupplierShippingSlip::class);
    }

    public function rightShippingSlips()
    {
        // $ascriptions = $this->rightInvoiceAscriptions();

        // abi_r($ascriptions->pluck('rightable_id')->all(), true);

        return SupplierShippingSlip::find( $this->rightShippingSlipAscriptions()->pluck('rightable_id') );
    }

    public function supplierShippingSlip()
    {
        return $this->rightShippingSlips()->first();
    }



    public function rightOrderAscriptions( $model = '' )
    {
        return $this->rightAscriptions->where('type', 'backorder')->where('rightable_type', SupplierOrder::class);
    }

    public function rightOrders()
    {
        // $ascriptions = $this->rightInvoiceAscriptions();

        // abi_r($ascriptions->pluck('rightable_id')->all(), true);

        return SupplierOrder::find( $this->rightOrderAscriptions()->pluck('rightable_id') );
    }

    public function supplierbackorder()
    {
        return $this->rightOrders()->first();
    }



    public function leftAscriptions()
    {
        return $this->morphMany(DocumentAscription::class, 'rightable')->orderBy('id', 'ASC');
    }

    public function leftOrderAscriptions( $model = '' )
    {
        return $this->leftAscriptions->where('type', 'backorder')->where('leftable_type', SupplierOrder::class);
    }

    public function leftOrders()
    {
        $ascriptions = $this->leftOrderAscriptions();

        return SupplierOrder::find( $ascriptions->pluck('leftable_id') );
    }

    public function supplierorder()
    {
        return $this->leftOrders()->first();
    }



    public function rightAggregateOrderAscriptions( $model = '' )
    {
        return $this->rightAscriptions->where('type', 'aggregate')->where('rightable_type', SupplierOrder::class);
    }

    public function rightAggregateOrders()
    {
        // $ascriptions = $this->rightInvoiceAscriptions();

        // abi_r($ascriptions->pluck('rightable_id')->all(), true);

        return SupplierOrder::find( $this->rightAggregateOrderAscriptions()->pluck('rightable_id') );
    }

    public function supplieraggregateorder()
    {
        return $this->rightAggregateOrders()->first();
    }



    public function leftAggregateOrderAscriptions( $model = '' )
    {
        return $this->leftAscriptions->where('type', 'aggregate')->where('leftable_type', SupplierOrder::class);
    }

    public function leftAggregateOrders()
    {
        $ascriptions = $this->leftAggregateOrderAscriptions();

        return SupplierOrder::find( $ascriptions->pluck('leftable_id') );
    }

    public function supplieraggregateorderby()
    {
        return $this->leftAggregateOrders()->first();
    }




    public function leftQuotationAscriptions( $model = '' )
    {
        return $this->leftAscriptions->where('type', 'traceability')->where('leftable_type', SupplierQuotation::class);
    }

    public function leftQuotations()
    {
        $ascriptions = $this->leftQuotationAscriptions();

        // return SupplierQuotation::find( $ascriptions->pluck('leftable_id') );
        return null;    // Do not consider Quotations for now
    }

    public function supplierquotation()
    {
        // return $this->leftQuotations()->first();
        return null;    // Do not consider Quotations for now
    }
    


    public function downpayments()
    {
        return $this->hasMany(DownPayment::class, 'supplier_order_id')->orderBy('due_date', 'ASC');
    }



    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */


    public function scopeFilter($query, $params)
    {
        $query = parent::scopeFilter($query, $params);

        return $query;
    }

}
