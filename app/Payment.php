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
        );

 //   protected $guarded = array('id');

    protected $dates = [
    					'due_date', 
    					'payment_date'
    					];

    protected $fillable =  ['reference', 'name', 'due_date', 'payment_date', 
                            'amount', 'currency_id', 'currency_conversion_rate', 'status', 
                            'notes'];

	// Add your validation rules here
	public static $rules = [
//            'due_date_next' => 'required_if:amount_next,true',
//            'due_date' => 'required|date',
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
    
    public function getDueDateAttribute($value)
    {
        // See: https://laracasts.com/discuss/channels/general-discussion/how-to-carbonparse-in-ddmmyyyy-format

        return abi_date_short( \Carbon\Carbon::parse($value), \App\Context::getContext()->language->date_format_lite );
    }

    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $value );
    }
    
    public function getPaymentDateAttribute($value)
    {
        if ($value)
            return abi_date_short( \Carbon\Carbon::parse($value), \App\Context::getContext()->language->date_format_lite );
        else
            return NULL;
    }

    public function setPaymentDateAttribute($value)
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
        return $this->paymentable();
    }

    public function getCustomerInvoiceAttribute()
    {
        return $this->paymentable;        // Only if it is a Customer Invoice...
    }


    public function customer()
    {
        return $this->belongsTo('App\Customer', 'owner_id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
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

}
