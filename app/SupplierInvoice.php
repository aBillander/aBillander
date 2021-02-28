<?php

namespace App;

use App\Traits\SupplierInvoicePaymentsTrait;
// use App\Traits\BillableStockMovementsTrait;

use \App\SupplierInvoiceLine;

class SupplierInvoice extends Billable
{
    
    use SupplierInvoicePaymentsTrait;
//    use BillableStockMovementsTrait;

    public static $badges = [
            'a_class' => 'alert-danger',
            'i_class' => 'fa-money',
        ];

    public static $types = [
            'invoice',
            'corrective',
            'credit',
            'deposit',
        ];

    public static $payment_statuses = array(
            'pending',
            'halfpaid',
            'paid',
//            'overdue',
            'doubtful',
            'archived',
        );


    /**
     * The fillable properties for this model.
     *
     * @var array
     *
     * https://gist.github.com/JordanDalton/f952b053ef188e8750177bf0260ce166
     */
    protected $document_fillable = [
                            'type',
                            'valid_until_date',
                            'next_due_date',
                            'posted_at',
                            
                            'supplier_id', 'reference_supplier', 

//                            'prices_entered_with_tax', 'round_prices_with_tax',
    ];

	// Add your validation rules here
	public static $rules = [
//                            'type' => 'in:invoice,corrective,credit,deposit',
                            'document_date' => 'required|date',
                            'payment_date'  => 'nullable|date',
//                            'delivery_date' => 'nullable|date|after_or_equal:document_date',
                            'supplier_id' => 'exists:suppliers,id',
//                            'shipping_address_id' => 'exists:addresses,id,addressable_id,{supplier_id},addressable_type,App\Supplier',
                            'sequence_id' => 'exists:sequences,id',
//                            'warehouse_id' => 'exists:warehouses,id',
//                            'carrier_id'   => 'exists:carriers,id',
                            'currency_id' => 'exists:currencies,id',
                            'payment_method_id' => 'nullable|exists:payment_methods,id',
	];


    public function getDeletableAttribute()
    {
        return $this->status == 'draft';
    }

    public function getUncloseableAttribute()
    {
        if ( $this->status != 'closed' ) return false;
        
        // Payments other than Down Payments
        $paids = $this->payments()->where('is_down_payment', 0)->where('status', 'paid')->get();

        if ( $paids->count() > 0 ) return false;

//        if ( ! $this->rightAscriptions->isEmpty() ) return false;

        return true;
    }

    // Alias
    public function getShippingslipsAttribute()
    {
        return $this->leftShippingSlips();
    }


    public function getPaymentDateAttribute()
    {
        $payments = $this->payments;

        if ( $payments->where('status', 'uncollectible')->count() > 0 ) return null;

        if ( $payments->where('status', 'pending')->count() > 0 ) return null;

        return $payments->where('status', 'paid')->max('payment_date');
    }


    public function getNextPaymentAttribute()
    {
        $payments = $this->payments;

        return $payments->where('status', 'pending')->sortBy('due_date')->first();

    }


    // Gorrino Style. Based on the assumption that a Supplier has few (or none) down payments
    public function getDownpaymentsAttribute()
    {
        $downpayments = collect([]);

        // Customer Down Payments
        $cdps = DownPayment::where('customer_id', $this->customer_id)->get();

        if ( $cdps )
        foreach ($cdps as $cdp) {
            // Supplier order
            $so = $cdp->supplierorder;
            if ( ! $so ) continue;

            // Supplier Shipping Slip
            $sss = $so->shippingslip;
            if ( ! $sss ) continue;

            // Supplier invoice
            $si = $sss->invoice;
            if ( ! $si ) continue;

            // Qualify for this Supplier Invoice?
            if ( $si->id != $this->id ) continue;


            $downpayments->push($cdp);
        }

        return $downpayments;

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
        event( new \App\Events\SupplierInvoiceConfirmed($this) );

        return true;
    }

    public function close()
    {
//        if ( \App\Configuration::isFalse('ENABLE_CRAZY_IVAN') )
        //    if ( $this->total_tax_incl == 0.0 ) return false;     <= Should allow zero value invoice for samples, etc.

        if ( ! parent::close() ) return false;

        // Dispatch event
        event( new \App\Events\SupplierInvoiceClosed($this) );

        return true;
    }

    public function unclose( $status = null )
    {
        if ( ! parent::unclose() ) return false;

        // Dispatch event
        event( new \App\Events\SupplierInvoiceUnclosed($this) );

        return true;
    }



    public static function getPaymentStatusList()
    {
            $list = [];
            foreach (static::$payment_statuses as $status) {
                $list[$status] = l(get_called_class().'.'.$status, [], 'appmultilang');
            }

            return $list;
    }

    public static function getPaymentStatusName( $status )
    {
            return l(get_called_class().'.'.$status, [], 'appmultilang');
    }

    public static function isPaymentStatus( $status )
    {
            return in_array($status, self::$payment_statuses);
    }

    public function getPaymentStatusNameAttribute()
    {
            return l(get_called_class().'.'.$this->payment_status, 'appmultilang');
    }

    public function checkPaymentStatus()
    {
        
        $pendings = $this->payments()->where('status', 'pending')->get();

        $open_balance = $pendings->sum('amount');
        // Remember: 'down_payment' is a payment with status=paid

        if ( $pendings->count() == 0 )  // Check this or zero amount invoices will never set to "paid"
        {
            $this->open_balance = 0.0;
            $this->payment_status = 'paid';
        }
        else
        if ( $this->currency->round($this->total_tax_incl - $open_balance) == 0.0 )
        {
            $this->open_balance = $open_balance;
            $this->payment_status = 'pending';
        }
        else
        if ( $this->currency->round($open_balance) == 0.0 )
        {
            $this->open_balance = 0.0;
            $this->payment_status = 'paid';
        }
        else
        {
            $this->open_balance = $open_balance;
            $this->payment_status = 'halfpaid';
        }

        $this->save();

        return true;
    }

    
    public function getIsOverdueAttribute()
    {
        if ($this->status == 'closed')
            return optional($this->nextPayment())->is_overdue;

        return false;
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

    public function leftShippingSlipAscriptions( $model = '' )
    {
/*        $relation = $this->morphMany('App\DocumentAscription', 'rightable'); //->where('type', 'traceability');

        if ($model != '' && 0) $relation = $relation->where('leftable_type', $model);

        abi_r($this->morphMany('App\DocumentAscription', 'rightable'));;die();

        // abi_toSQL($relation->orderBy('id', 'ASC'));die();\App\SupplierShippingSlip::class
*/
        $ascriptions = $this->leftAscriptions;

        // abi_r($ascriptions);

        return $ascriptions->where('leftable_type', 'App\SupplierShippingSlip');
    }

    public function leftShippingSlips()
    {
        $ascriptions = $this->leftShippingSlipAscriptions();

        // abi_r($ascriptions->pluck('leftable_id')->all(), true);

        return \App\SupplierShippingSlip::find( $ascriptions->pluck('leftable_id') );
    }


    public function payments()
    {
        return $this->morphMany('App\Payment', 'paymentable')->orderBy('due_date', 'ASC');
    }
    



    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */


    public function scopeFilter($query, $params)
    {
        $query = parent::scopeFilter($query, $params);


        if (array_key_exists('payment_status', $params) && $params['payment_status'] && self::isPaymentStatus($params['payment_status']))
        {
            $query->where('payment_status', $params['payment_status']);
        }

        return $query;
    }

}

