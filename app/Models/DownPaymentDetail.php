<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// use Illuminate\Http\Request;
// use Illuminate\Validation\Rule;

use App\Traits\ViewFormatterTrait;

class DownPaymentDetail extends Model
{
    use ViewFormatterTrait;

    protected $fillable = [
    		'line_sort_order', 'name', 'amount', 'payment_id', 'document_invoice_id', 'document_invoice_reference',
    ];

    public static $rules = [
        'name'         => 'required|min:2|max:128',

    	'amount'   => 'numeric|min:0',

        'customer_invoice_id'   => 'nullable|exists:customer_invoices,id',

//        'drawee_bank_id'   => 'required|exists:banks,id',
    	];



    public function getDeletableAttribute()
    {
        // return !( $this->status == 'closed' || $this->status == 'canceled' );
        if ($this->customerpayment)
            return $this->customerpayment->status == 'pending';

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function downpayment()
    {
        return $this->belongsTo( 'App\DownPayment', 'down_payment_id' );
    }
    
    public function customerinvoice()
    {
        return $this->hasOne( 'App\CustomerInvoice', 'id', 'customer_invoice_id' );
    }
    
    public function customerpayment()
    {
        return $this->hasOne( 'App\Payment', 'id', 'payment_id' )->where('payment_type', 'receivable');
    }
    
    public function supplierpayment()
    {
        return $this->hasOne( 'App\Payment', 'id', 'payment_id' )->where('payment_type', 'payable');
    }
}
