<?php 

namespace App\Models;

use App\Helpers\DocumentAscription;

use App\Traits\CustomerInvoicePaymentsTrait;
use App\Traits\BillableStockMovementsTrait;

class CustomerInvoice extends Billable
{
    
    use CustomerInvoicePaymentsTrait;
    use BillableStockMovementsTrait;

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

    public static $stock_statuses = [
            'pending'  , // => Not performed
            'completed', // => Performed
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

                            'prices_entered_with_tax', 'round_prices_with_tax',
    ];

	// Add your validation rules here
	public static $rules = [
                            'type' => 'in:invoice,corrective,credit,deposit',
                            'document_date' => 'required|date',
                            'payment_date'  => 'nullable|date',
                            'delivery_date' => 'nullable|date|after_or_equal:document_date',
                            'customer_id' => 'exists:customers,id',
                            'shipping_address_id' => 'exists:addresses,id,addressable_id,{customer_id},addressable_type,App\\Models\\Customer',
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

        if ( $this->payment_status != 'pending' ) return false;

        // Are there any Payments in a Bankorder?
        $auto_direct_debit_payments = $this->payments->where('auto_direct_debit', '>', 0);

        if ( $auto_direct_debit_payments->count() > 0 )
        {
            foreach ($auto_direct_debit_payments as $payment) {
                // code...
                if ($payment->bankorder) return false;
            }
        }

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

/*
    // Not needed: it is a CustomerInvoice Model property
    public function getOpenBalanceAttribute()
    {
        // $payments = $this->payments;

        $open_balance = $this->payments()->where('status', 'pending')->sum('amount');
        // Remember: 'down_payment' is a payment with status=paid

        return $open_balance;

    }
*/

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

    public static function getStockStatusList()
    {
            $list = [];
            foreach (static::$stock_statuses as $stock_status) {
                $list[$stock_status] = l(get_called_class().'.'.$stock_status, [], 'appmultilang');
            }

            return $list;
    }

    public static function getStockStatusName( $stock_status )
    {
            return l(get_called_class().'.'.$stock_status, 'appmultilang');
    }

    public function getStockStatusNameAttribute()
    {
            return l(get_called_class().'.'.$this->stock_status, 'appmultilang');
    }


    public function confirm()
    {
        if ( ! parent::confirm() ) return false;

        // Dispatch event
        event( new \App\Events\CustomerInvoiceConfirmed($this) );

        return true;
    }

    public function close()
    {
//        if ( Configuration::isFalse('ENABLE_CRAZY_IVAN') )
        //    if ( $this->total_tax_incl == 0.0 ) return false;     <= Should allow zero value invoice for samples, etc.

        if ( ! parent::close() ) return false;

        // Dispatch event
        event( new \App\Events\CustomerInvoiceClosed($this) );

        return true;
    }

    public function unclose( $status = null )
    {
        if ( ! parent::unclose() ) return false;

        // Dispatch event
        event( new \App\Events\CustomerInvoiceUnclosed($this) );

        return true;
    }


    public function shouldPerformStockMovements()
    {
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
        if ($this->created_via == 'manual' && $this->stock_status == 'completed' ) return true;

        return false;
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
 /*       $relation = $this->morphMany(DocumentAscription::class, 'rightable'); //->where('type', 'traceability');

        if ($model != '' && 0) $relation = $relation->where('leftable_type', $model);

        abi_r($this->morphMany(DocumentAscription::class, 'rightable'));;die();

        // abi_toSQL($relation->orderBy('id', 'ASC'));die();
*/
        return $this->morphMany(DocumentAscription::class, 'rightable')->where('type', 'traceability')->orderBy('id', 'ASC');
    }

    public function leftShippingSlipAscriptions( $model = '' )
    {
/*        $relation = $this->morphMany(DocumentAscription::class, 'rightable'); //->where('type', 'traceability');

        if ($model != '' && 0) $relation = $relation->where('leftable_type', $model);

        abi_r($this->morphMany(DocumentAscription::class, 'rightable'));;die();

        // abi_toSQL($relation->orderBy('id', 'ASC'));die();CustomerShippingSlip::class
*/
        $ascriptions = $this->leftAscriptions;

        // abi_r($ascriptions);

        return $ascriptions->where('leftable_type', CustomerShippingSlip::class);
    }

    public function leftShippingSlips()
    {
        $ascriptions = $this->leftShippingSlipAscriptions();

        // abi_r($ascriptions->pluck('leftable_id')->all(), true);

        return CustomerShippingSlip::find( $ascriptions->pluck('leftable_id') );
    }


    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable')->orderBy('due_date', 'ASC');
    }


    public function commissionsettlementline()
    {
        return $this->hasOne(CommissionSettlementLine::class, 'commissionable_id', 'id')
                    ->where('commissionable_type', CustomerInvoice::class);
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
