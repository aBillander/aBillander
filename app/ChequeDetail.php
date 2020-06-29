<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class ChequeDetail extends Model
{
    use ViewFormatterTrait;

    protected $fillable = [
    		'line_sort_order', 'name', 'amount', 'customer_invoice_id', 'customer_invoice_reference',
    ];

    public static $rules = [
        'name'         => 'required|min:2|max:128',

    	'amount'   => 'numeric|min:0',

//        'customer_invoice_id'   => 'nullable|exists:customer_invoices,id',
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
}
