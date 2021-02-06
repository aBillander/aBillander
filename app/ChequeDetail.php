<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// use Illuminate\Http\Request;
// use Illuminate\Validation\Rule;

use App\Traits\ViewFormatterTrait;

class ChequeDetail extends Model
{
    use ViewFormatterTrait;

    protected $fillable = [
    		'line_sort_order', 'name', 'amount', 'payment_id', 'customer_invoice_id', 'customer_invoice_reference',
    ];

    public static $rules = [
        'name'         => 'required|min:2|max:128',

    	'amount'   => 'numeric|min:0',

        'customer_invoice_id'   => 'nullable|exists:customer_invoices,id',

//        'drawee_bank_id'   => 'required|exists:banks,id',
    	];



    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function cheque()
    {
        return $this->belongsTo( 'App\Cheque' );
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
