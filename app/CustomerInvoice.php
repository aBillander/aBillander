<?php 

namespace App;

use Auth;

use App\Traits\CustomerInvoicePaymentsTrait;
use App\Traits\BillableStockMovementsTrait;

use \App\CustomerInvoiceLine;

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

    public static $payment_statuses = array(
            'pending',
            'halfpaid',
            'paid',
            'overdue',
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
                            'type' => 'in:invoice',
                            'document_date' => 'required|date',
                            'payment_date'  => 'nullable|date',
                            'delivery_date' => 'nullable|date|after_or_equal:document_date',
                            'customer_id' => 'exists:customers,id',
                            'shipping_address_id' => 'exists:addresses,id,addressable_id,{customer_id},addressable_type,App\Customer',
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


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function confirm()
    {
        if ( ! parent::confirm() ) return false;

        // Dispatch event
        event( new \App\Events\CustomerInvoiceConfirmed($this) );

        return true;
    }

    public function close()
    {
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



    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */



    public function payments()
    {
        return $this->morphMany('App\Payment', 'paymentable')->orderBy('due_date', 'ASC');
    }


   /**
     * Get all of the WC Orders that are assigned this Invoice.
     */
    public function wc_orders()
    {
        return $this->belongsToMany('aBillander\WooConnect\WooOrder', 'parent_child', 'childable_id', 'parentable_id')
                ->wherePivot('childable_type' , 'App\CustomerInvoice')
                ->wherePivot('parentable_type', 'aBillander\WooConnect\WooOrder')
                ->withTimestamps();
    }

    public function staple_wc_order( $document = null )
    {
        if (!$document) return;

        $this->wc_orders()->attach($document->id, ['parentable_type' => 'aBillander\WooConnect\WooOrder', 'childable_type' => 'App\CustomerInvoice']);
    }


    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

}