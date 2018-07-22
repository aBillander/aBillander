<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\ViewFormatterTrait;

class Customer extends Model {

    use ViewFormatterTrait;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

//    protected $guarded = ['id', 'outstanding_amount', 'unresolved_amount', 'secure_key'
//                ];

	
    protected $fillable = ['name_fiscal', 'name_commercial', 'identification', 'webshop_id', 'reference_external', 
                           'website', 'payment_days', 'no_payment_month', 
                           'outstanding_amount_allowed', 'unresolved_amount', 
                           'notes', 'sales_equalization', 'accept_einvoice', 'allow_login', 'blocked', 'active', 
                           'sales_rep_id', 'currency_id', 'language_id', 'customer_group_id', 'payment_method_id', 
//                           'sequence_id', 
                           'invoice_template_id', 'shipping_method_id', 'price_list_id', 'direct_debit_account_id', 
                           'invoicing_address_id', 'shipping_address_id', 
                ];


    public static $rules = array(
        'name_fiscal' => 'required',
        'no_payment_month' => 'numeric|min:0|max:12',
    	);

    public static function boot()
    {
        parent::boot();

        static::creating(function($client)
        {
            $client->secure_key = md5(uniqid(rand(), true));
        });

        // cause a delete of a Customer to cascade to children so they are also deleted
        static::deleted(function($client)
        {
            $client->addresses()->delete(); // Not calling the events on each child : http://laravel.io/forum/03-26-2014-delete-relationschild-relations-without-cascade

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

    // Get the full name of a User instance using Eloquent accessors
    
    public function getNameAttribute() 
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function currentpricelist()
    {
        // First: Customer has pricelist?
        if ($this->pricelist) {

            return $this->pricelist;
        } 

        // Second: Customer Group has pricelist?
        if ($this->customergroup AND $this->customergroup->pricelist) {

            return $this->customergroup->pricelist;
        }

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
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
    
    
    public function paymentDays() 
    {
        if ( !trim($this->payment_day) ) return [];

        $dstr = str_replace( [';', ':'], ',', $this->payment_day );
        $days = array_map( 'intval', explode(',', $dstr) );

        sort( $days, SORT_NUMERIC );

        return $days;
    }

    /**
     * Adjust date to Customer Payment Days
     */
    public function paymentDate( \Carbon\Carbon $date ) 
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

        $payday = \Carbon\Carbon::createFromDate($year, $month, $day);

        // Check Saturday & Sunday
        if ( $payday->dayOfWeek == 6 ) $payday->addDays(2); // Saturday
        if ( $payday->dayOfWeek == 0 ) $payday->addDays(1); // Sunday

        return $payday;
    }


    // NIF
    // Better use: https://github.com/amnesty/drupal-nif-nie-cif-validator/blob/master/includes/nif-nie-cif.php

    // http://da-software.blogspot.com/2011/12/comprobar-cif-nif-nie-con-php.html
    public static function check_spanish_nif_cif_nie( $value ) 
    {
         //Returns: 

         // 1 = NIF ok,

         // 2 = CIF ok,

         // 3 = NIE ok,

         //-1 = NIF bad,

         //-2 = CIF bad, 

         //-3 = NIE bad, 0 = ??? bad
        
        // Sanitize
        $nif = self::normalize_spanish_nif_cif_nie( $value );

        // Poor man check
        if ( strlen( $nif ) > 0 ) {
            if ( ctype_alnum($nif) && (strlen($nif)==9 || strlen($nif)==8) ) return 1;
            else return 0;
        } else return 0;


        if ( strlen( $nif ) == 9 ) {
//            $nif = strtoupper( $value );

            for ( $i = 0; $i < 9; $i ++ ) {
                $num[$i] = substr( $nif, $i, 1 );
            }

            // Check format :: Si no tiene un formato valido devuelve error
            if ( !preg_match( '/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/', $nif ) ) {

                return 0;
            }

            // comprobacion de NIFs estandar
            if ( preg_match( '/(^[0-9]{8}[A-Z]{1}$)/', $nif ) ) {
                if ( $num[8] == substr( 'TRWAGMYFPDXBNJZSQVHLCKE', substr( $nif, 0, 8 ) % 23, 1 ) ) {
                        return 1;
                  } else {
                        return -1;
                  }
            }

            // algoritmo para comprobacion de codigos tipo CIF
            $suma = $num[2] + $num[4] + $num[6];
            for ( $i = 1; $i < 8; $i += 2 ) {
                $suma += substr( ( 2 * $num[$i] ), 0, 1 ) + substr( ( 2 * $num[$i] ), 1, 1 );
            }
            $n = 10 - substr( $suma, strlen( $suma ) - 1, 1 );

            // comprobacion de NIFs especiales (se calculan como CIFs)
            if ( preg_match( '/^[KLM]{1}/', $nif ) ) { 
                if ( $num[8] == chr( 64 + $n ) ) {
                          return 1;
                  } else {
                          return -1;
                  }
            }

            // comprobacion de CIFs
            if ( preg_match( '/^[ABCDEFGHJNPQRSUVW]{1}/', $nif ) ) {
                
                if ( $num[8] == chr( 64 + $n ) || $num[8] == substr( $n, strlen( $n ) - 1, 1 ) ) { 
                        return 2;
                  } else {
                        return -2;
                  }
            }

            // comprobacion de NIEs
            // T
            if ( preg_match( '/^[T]{1}/', $nif ) ) {
                if ( $num[8] == preg_match( '/^[T]{1}[A-Z0-9]{8}$/', $nif ) ) {
                        return 3;
                  } else {
                        return -3;
                  }
            }

            // NIE válido (XYZ)
            if ( preg_match( '/^[XYZ]{1}/', $nif ) ) { 
                if ( $num[8] == substr( 'TRWAGMYFPDXBNJZSQVHLCKE', substr( str_replace( array( 'X','Y','Z' ), array( '0','1','2' ), $nif ), 0, 8 ) % 23, 1 ) ) {
                        return 3;
                  } else {
                        return -3;
                  }
            }
        }

        // si todavia no se ha verificado devuelve error
        return 0;
    }

    public static function normalize_spanish_nif_cif_nie( $value )
    {
        return strtoupper( str_replace([' ', '-'], '', $value) );
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get all of the customer's Bank Accounts.
     */
    public function bankaccounts()
    {
        return $this->morphMany('App\BankAccount', 'bank_accountable');
    }
    
    public function addresses()
    {
        return $this->morphMany('App\Address', 'addressable');
    }


    public function getAddressList()
    {
        return $this->morphMany('App\Address', 'addressable')->pluck( 'alias', 'id' )->toArray();
    }


    public function xgetAddressAttribute()
    {
        // https://laracasts.com/discuss/channels/eloquent/first-item-in-laravel-53-relationships
        return $this->invoicing_address();
    }


    public function address()
    {
        return $this->hasOne('App\Address', 'id', 'invoicing_address_id')
                   ->where('addressable_type', Customer::class);


        // return $this->invoicing_address();

        // Chainable relationship to use in customer index & more
//        return $this->morphMany('App\Address', 'addressable')
//                   ->where('addresses.id', $this->invoicing_address_id);
    }
    
    public function invoicing_address()
    {
        if ($this->invoicing_address_id>0)
            return $this->morphMany('App\Address', 'addressable')
                   ->where('addresses.id', $this->invoicing_address_id)->first();
        else
            return null;
    }
    
    public function shipping_address()
    {
        if ($this->shipping_address_id>0)
            return $this->morphMany('App\Address', 'addressable')
                   ->where('addresses.id', $this->shipping_address_id)->first();
        else
            return null;
        
//        return $this->belongsTo('App\Address', 'shipping_address_id')
//                   ->where('addresses.addressable_type', 'App\Customer');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function paymentmethod()
    {
        return $this->belongsTo('App\PaymentMethod', 'payment_method_id');
    }

    public function shippingmethod()
    {
        return $this->belongsTo('App\ShippingMethod', 'shipping_method_id');
    }

    public function pricelist()
    {
        return $this->belongsTo('App\PriceList', 'price_list_id');
    }

    public function salesrep()
    {
        return $this->belongsTo('App\SalesRep', 'sales_rep_id');
    }

    public function customergroup()
    {
        return $this->belongsTo('App\CustomerGroup', 'customer_group_id');
    }

    
    public function customerorders()
    {
        return $this->hasMany('App\CustomerInvoice');
    }

    public function customerinvoices()
    {
        return $this->hasMany('App\CustomerInvoice');
    }
    
    public function payments()
    {
        // return $this->hasMany('App\Payment', 'owner_id')->where('payment.owner_model_name', '=', 'Customer');

        return $this->morphMany('App\Payment', 'paymentorable');
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
        $clients = Customer::select('name_fiscal', 'name_commercial', 'id')->orderBy('name_fiscal')->where('name_fiscal', 'like', '%' . $query . '%');
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

    public function getPrice( \App\Product $product, \App\Currency $currency = null )
    {
        // Fall back price (use it if Price for $currency is not set)
        $fallback = null;

        if (!$currency && $this->currency_id)
            $currency = $this->currency;

        if (!$currency)
            $currency = \App\Context::getContext()->currency;

/*
        // ToDo: Set special prices priorities
        // First: Product has special price for this Customer?

        // Second: Product has special price for this Customer's Customer Group?

*/
        // Third: Customer has pricelist?
        if ( $this->pricelist && ($currency->id == $this->pricelist->currency_id) ) {

            $price = $this->pricelist->getPrice( $product );

            return $price;
        } 

        // Fourth: Customer Group has pricelist?
        if ( $this->customergroup && $this->customergroup->pricelist && ($currency->id == $this->customergroup->pricelist->currency_id) ) {

            $price = $this->customergroup->pricelist->getPrice( $product );

            return $price;
        }

        // Otherwise, use product price (initial or base price)
        $price = $product->getPrice();

//            abi_r('$product');abi_r($price);

        if ($currency->id == $price->currency->id) {
            return $price;
        }

        // Convert price
        $price = $price->convert( $currency );

        return $price;
    }

    public function getTaxRules( \App\Product $product, $address = null )
    {
        $rules = collect([]);

        // Sales Equalization
        if ( $this->sales_equalization && ($product->sales_equalization ?? 1) ) {

            $tax = $product->tax;


            if ( $address == null ) 
                $address = $this->invoicing_address();

            $country_id = $address->country_id;
            $state_id   = $address->state_id;

            $rules_re = $tax->taxrules()->where(function ($query) use ($country_id) {
                $query->where(  'country_id', '=', 0)
                      ->OrWhere('country_id', '=', $country_id);
            })
                                     ->where(function ($query) use ($state_id) {
                $query->where(  'state_id', '=', 0)
                      ->OrWhere('state_id', '=', $state_id);
            })
                                     ->where('rule_type', '=', 'sales_equalization')
                                     ->get();

            if ( $rules_re->isNotEmpty() ) $rules = $rules->merge( $rules_re );

        }

        return $rules;
    }
    
}
