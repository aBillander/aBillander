<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;

use App\Traits\ViewFormatterTrait;

class Payment extends Model {

    use ViewFormatterTrait;

    public static $types = array(
            'receivable', 
            'payable',
        );

    public static $statuses = array(
            'pending', 
            'bounced', 
            'paid',
            'uncollectible',     // irrecoverable
        );

 //   protected $guarded = array('id');

    protected $dates = [
    					'due_date', 
                        'due_date_next',
    					'payment_date'
    					];

    protected $fillable =  ['payment_type', 'reference', 'name', 'due_date', 'payment_date', 
                            'amount', 'currency_id', 'currency_conversion_rate', 'status', 
                            'notes', 'document_reference',

                            'auto_direct_debit', 'bank_order_id',

                            'down_payment', 'payment_document_id', 'payment_method_id',
                           ];

	// Add your validation rules here
	public static $rules = [
//            'due_date_next' => 'required_if:amount_next,true',
            'due_date' => 'required|date',
//            'payment_date' => 'date',
              'amount' => 'numeric|min:0|max:',
	];


    public static function getTypeList()
    {
            $list = [];
            foreach (self::$types as $type) {
                $list[$type] = l($type, [], 'appmultilang');
            }

            return $list;
    }

    public static function getStatusList()
    {
            $list = [];
            foreach (self::$statuses as $status) {
                $list[$status] = l($status, [], 'appmultilang');
            }

            return $list;
    }

    public static function getStatusName( $status )
    {
            return l($status, [], 'appmultilang');;
    }

    
    public function getIsOverdueAttribute()
    {
        if ($this->status == 'pending')
            return $this->due_date < \Carbon\Carbon::now();

        return false;
    }

    
    public function xgetDueDateAttribute($value)
    {
        // See: https://laracasts.com/discuss/channels/general-discussion/how-to-carbonparse-in-ddmmyyyy-format

        return abi_date_short( \Carbon\Carbon::parse($value), \App\Context::getContext()->language->date_format_lite );
    }

    public function xsetDueDateAttribute($value)
    {
        $this->attributes['due_date'] = \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $value );
    }
    
    public function xgetPaymentDateAttribute($value)
    {
        if ($value)
            return abi_date_short( \Carbon\Carbon::parse($value), \App\Context::getContext()->language->date_format_lite );
        else
            return NULL;
    }

    public function xsetPaymentDateAttribute($value)
    {
        if ($value)
            $this->attributes['payment_date'] = \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $value );
        else
            $this->attributes['payment_date'] = NULL;
    }

    public function getAmountAttribute($value)
    {
        // return abi_money_amount( $value, $currency = null);
        return $value;
    }

//    public function setAmountAttribute($value)
//    {
//        $this->attributes['amount'] = ;
//    }



    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function paymentable()
    {
        return $this->morphTo();
    }
    
    public function paymentorable()
    {
        return $this->morphTo();
    }

    public function xcustomerInvoice()
    {
        return $this->paymentable;
    }

    public function xgetCustomerinvoiceAttribute()
    {
        return $this->paymentable();        // Only if it is a Customer Invoice...
    }


    public function customer()
    {
        return $this->belongsTo('App\Customer', 'paymentorable_id');
    }

    public function customerinvoice()
    {
        return $this->belongsTo('App\CustomerInvoice', 'paymentable_id');     // ->where('paymentable_type', 'App\CustomerInvoice');        // Only if it is a Customer Invoice...
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function paymentdocument()
    {
        return $this->belongsTo('App\PaymentDocument');
    }

    public function paymentmethod()
    {
        return $this->belongsTo('App\PaymentMethod');
    }

    public function bankorder()
    {
        return $this->belongsTo('aBillander\SepaSpain\SepaDirectDebit', 'bank_order_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Data Factory :: Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfCustomer($query)
    {
//        abi_r(Auth::user()->customer_id, true);

        if ( isset(Auth::user()->customer_id) && ( Auth::user()->customer_id != NULL ) )
            return $query->where('paymentorable_id', Auth::user()->customer_id)->where('paymentorable_type', 'App\Customer');

        return $query;
    }


    public function scopeFilter($query, $params)
    {

        if ($params['date_from'])
            // if ( isset($params['date_to']) && trim($params['date_to']) != '' )
        {
            $query->where('due_date', '>=', $params['date_from'].' 00:00:00');
        }

        if ($params['date_to'])
        {
            $query->where('due_date', '<=', $params['date_to']  .' 23:59:59');
        }

        if (array_key_exists('auto_direct_debit', $params) && $params['auto_direct_debit'] > 0)
        {
            $query->where('auto_direct_debit', '>', 0);
        }

        if (array_key_exists('auto_direct_debit', $params) && $params['auto_direct_debit'] == 0)
        {
            $query->where('auto_direct_debit', 0);
        }

/*
        if ( isset($params['reference']) && trim($params['reference']) !== '' )
        {
            $query->where('reference', 'LIKE', '%' . trim($params['reference']) . '%');
            // $query->orWhere('combinations.reference', 'LIKE', '%' . trim($params['reference'] . '%'));
/ *
            // Moved from controller
            $reference = $params['reference'];
            $query->orWhereHas('combinations', function($q) use ($reference)
                                {
                                    // http://stackoverflow.com/questions/20801859/laravel-eloquent-filter-by-column-of-relationship
                                    $q->where('reference', 'LIKE', '%' . $reference . '%');
                                }
            );  // ToDo: if name is supplied, shows records that match reference but do not match name (due to orWhere condition)
* /
        }

        if ( isset($params['name']) && trim($params['name']) !== '' )
        {
            $query->where('name', 'LIKE', '%' . trim($params['name'] . '%'));
        }

        if ( isset($params['warehouse_id']) && $params['warehouse_id'] > 0 )
        {
            $query->where('warehouse_id', '=', $params['warehouse_id']);
        }
*/
        return $query;
    }

}
