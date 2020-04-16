<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $appends = ['name_regular'];
    
    protected $fillable = [ 'alias', 'name_fiscal', 'name_commercial', 
                            'website', 'customer_center_url', 'customer_center_user', 'customer_center_password', 
                            'identification', 'reference_external', 'accounting_id', 'discount_percent', 'discount_ppd_percent', 'payment_days', 'delivery_time', 'notes', 
                            'supplier_logo', 'sales_equalization', 'creditor', 'approved', 'blocked', 'active', 'customer_id', 
                            'currency_id', 'language_id', 'payment_method_id', 
                            'bank_account_id', 'invoice_sequence_id', 'invoicing_address_id'
                        ];

    public static $rules = array(
//        'alias' => 'required',
    	'name_fiscal' => 'required|min:2|max:128',
        'currency_id' => 'exists:currencies,id',
        'language_id' => 'exists:languages,id',
//        'payment_method_id' => 'exists:payment_methods,id',
    	);


    public static function boot()
    {
        parent::boot();

        static::creating(function($vendor)
        {
            $vendor->secure_key = md5(uniqid(rand(), true));
        });

        // cause a delete of a Customer to cascade to children so they are also deleted
        static::deleted(function($vendor)
        {
            $vendor->addresses()->delete(); // Not calling the events on each child : http://laravel.io/forum/03-26-2014-delete-relationschild-relations-without-cascade

            // See:
            // http://laravel-tricks.com/tricks/cascading-deletes-with-model-events
            // http://laravel-tricks.com/tricks/using-model-events-to-delete-related-items
            /*
                    // Attach event handler, on deleting of the user
                    User::deleting(function($user)
                    {   
                        // Delete all tricks that belong to this user
                        foreach ($user->tricks as $trick) {
                            $trick->delete();
                        }
                    });
            */
        });
    }
    
    public function getNameRegularAttribute() 
    {
        if ( Configuration::get('BUSINESS_NAME_TO_SHOW') == 'fiscal' ) return $this->name_fiscal;

        return $this->name_commercial ?: $this->name_fiscal;
    }

    public function getEmailAttribute() 
    {
        return $this->address->email;
    }

    public function currentPricesEnteredWithTax( Currency $currency = null ) 
    {
        return Configuration::get('PRICES_ENTERED_WITH_TAX');
    }



    public function getInvoiceSequenceId() 
    {
        if (   $this->invoice_sequence_id
            && Sequence::where('id', $this->invoice_sequence_id)->exists()
            )
            return $this->invoice_sequence_id;

        return Configuration::getInt('DEF_SUPPLIER_INVOICE_SEQUENCE');
    }

    public function getInvoiceTemplateId() 
    {
        if (   $this->invoice_template_id
            && Template::where('id', $this->invoice_template_id)->exists()
            )
            return $this->invoice_template_id;

        return Configuration::getInt('DEF_SUPPLIER_INVOICE_TEMPLATE');
    }
    
    public function getPaymentMethodId() 
    {
        if (   $this->payment_method_id
            && PaymentMethod::where('id', $this->payment_method_id)->exists()
            )
            return $this->payment_method_id;

        return Configuration::getInt('DEF_SUPPLIER_PAYMENT_METHOD') ;
    }
    
    public function getShippingMethodId() 
    {
        if (   $this->shipping_method_id
            && ShippingMethod::where('id', $this->shipping_method_id)->exists()
            )
            return $this->shipping_method_id;

        return Configuration::getInt('DEF_SHIPPING_METHOD');
    }

    
    
    
    public function paymentDays() 
    {
        if ( !trim($this->payment_days) ) return [];

        $dstr = str_replace( [';', ':'], ',', $this->payment_days );
        $days = array_map( 'intval', explode(',', $dstr) );

        sort( $days, SORT_NUMERIC );

        return $days;
    }

    /**
     * Adjust date to Customer Payment Days
     */
    public function paymentDate( Carbon $date ) 
    {
        $pdays = $this->paymentDays();
        $n = count($pdays);
        if ( !$n ) return $date;

        $day   = $date->day;
        $month = $date->month;
        $year  = $date->year;

        if ( $day > $pdays[$n-1] ) {

            $day = $pdays[0];

            if ($month == 12) {

                $month = 1;
                $year += 1;

            } else {

                $month += 1;

            }

        } else {

            foreach ($pdays as $pday) {
                if ($day <= $pday) {
                    $day = $pday;
                    break;
                }
            }

        }

        // Check No-Payment Month
        if ($month==$this->no_payment_month) $month++;

        $payday = Carbon::createFromDate($year, $month, $day);

        // Check Saturday & Sunday
        if ( $payday->dayOfWeek == 6 ) $payday->addDays(2); // Saturday
        if ( $payday->dayOfWeek == 0 ) $payday->addDays(1); // Sunday

        return $payday;
    }



    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function products()
    {
        return $this->hasMany('App\Product', 'main_supplier_id')->orderby('name', 'asc');
    }

    /**
     * Get all of the customer's Bank Accounts.
     */
    public function bankaccounts()
    {
        return $this->morphMany('App\BankAccount', 'bank_accountable');
    }

    public function bankaccount()
    {
        return $this->hasOne('App\BankAccount', 'id', 'bank_account_id')
                   ->where('bank_accountable_type', Supplier::class);
    }
    

    public function addresses()
    {
        return $this->morphMany('App\Address', 'addressable');
    }


    public function getAddressList()
    {
        return $this->morphMany('App\Address', 'addressable')->pluck( 'alias', 'id' )->toArray();
    }


    public function address()
    {
        return $this->hasOne('App\Address', 'id', 'invoicing_address_id')
                   ->where('addressable_type', Supplier::class);
    }
    
    public function invoicing_address()
    {
        if ($this->invoicing_address_id>0)
            return $this->morphMany('App\Address', 'addressable')
                   ->where('addresses.id', $this->invoicing_address_id)->first();
        else
            return null;
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function language()
    {
        return $this->belongsTo('App\Language');
    }

    public function paymentmethod()
    {
        return $this->belongsTo('App\PaymentMethod', 'payment_method_id');
    }
    

    
    /*
    |--------------------------------------------------------------------------
    | Data Provider
    |--------------------------------------------------------------------------
    */

    /**
     * Provides a json encoded array of matching client names
     * @param  string $query
     * @return json
     */
    public static function searchByNameAutocomplete($query, $params)
    {
        $clients = Supplier::select('name_fiscal', 'name_commercial', 'id')->orderBy('name_fiscal')->where('name_fiscal', 'like', '%' . $query . '%');
        if ( isset($params['name_commercial']) AND ($params['name_commercial'] == 1) )
            $clients = $clients->orWhere('name_commercial', 'like', '%' . $query . '%');
        
        $clients = $clients->get();

        $return = array();

        if ( isset($params['name_commercial']) AND ($params['name_commercial'] == 1) ) {
            foreach ($clients as $client) {
                $return[] = array ('value' => $client->name_fiscal.' - '.$client->name_commercial, 'data' => $client->id);
            }

        } else {
            foreach ($clients as $client) {
                $return[] = array ('value' => $client->name_fiscal, 'data' => $client->id);
            }
        }

        return json_encode( array('query' => $query, 'suggestions' => $return) );
    }
    

    /*
    |--------------------------------------------------------------------------
    | Price calculations
    |--------------------------------------------------------------------------
    */

    public function getPrice( Product $product, $quantity = 1, Currency $currency = null )
    {

        if (!$currency && $this->currency_id)
            $currency = $this->currency;

        if (!$currency)
            $currency = Context::getContext()->currency;

        $line = SupplierPriceListLine::
                              where('supplier_id', $this->id)
                            ->where('product_id', $product->id)
                            ->where('currency_id', $this->currency_id)
                            ->where('from_quantity', '<=', $quantity)
                            ->orderBy('from_quantity', 'desc')
                            ->first();


        if ( $line )
        {
            $thePrice = $line->price * (1.0 - $line->discount_percent / 100.0);

        } else {

            $thePrice = $product->last_purchase_price;
        }
        
        $price = new Price( $thePrice, 0, $this->currency);

        // Bonus data:
        $price->discount_percent = 0.0;
        $price->discount_amount  = 0.0;

        if ( $line )
        {
            $price->discount_percent = $line->discount_percent;
            $price->discount_amount  = $line->discount_amount;
        }


        return $price;
    }

    

    public function getReference( Product $product )
    {
        $line = SupplierPriceListLine::
                              where('supplier_id', $this->id)
                            ->where('product_id', $product->id)
                            ->where('supplier_reference', '<>', '')
                            ->orderBy('from_quantity', 'asc')
                            ->first();


        if ( $line )
        {
            $reference = $line->supplier_reference;

        } else {

            $reference = null;
        }

        return $reference;
    }



    
    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */


    public function scopeFilter($query, $params)
    {
        if ( isset($params['name']) && $params['name'] != '' )
        {
            $name = $params['name'];

            $query->where( function ($query1) use ($name) {
                $query1->where(  'name_fiscal',     'LIKE', '%' . $name . '%')
                       ->OrWhere('name_commercial', 'LIKE', '%' . $name . '%');
            } );
        }

        if ( isset($params['reference_external']) && $params['reference_external'] != '' )
        {
            $query->where('reference_external', 'LIKE', '%' . $params['reference_external'] . '%');
        }

        if ( isset($params['identification']) && $params['identification'] != '' )
        {
            $query->where('identification', 'LIKE', '%' . $params['identification'] . '%');
        }

        if ( isset($params['email']) && $params['email'] != '' )
        {
            $email = $params['email'];

            $query->whereHas('addresses', function($q) use ($email) 
            {
                $q->where('email', 'LIKE', '%' . $email . '%');

            });
        }

        if ( isset($params['customer_group_id']) && $params['customer_group_id'] > 0 )
        {
            $query->where('customer_group_id', '=', $params['customer_group_id']);
        }

        if ( isset($params['active']) )
        {
            if ( $params['active'] == 0 )
                $query->where('active', '=', 0);
            if ( $params['active'] == 1 )
                $query->where('active', '>', 0);
        }

        return $query;
    }

    public function scopeIsActive($query)
    {
        return $query->where('active', '>', 0);
    }

    public function scopeIsBlocked($query)
    {
        return $query->where('blocked', '>', 0);
    }

    public function scopeIsNotBlocked($query)
    {
        return $query->where('blocked', 0);
    }


}
