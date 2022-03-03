<?php

namespace App\Models;

use App\Scopes\ShowOnlyActiveScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Auth;
use Carbon\Carbon;

use App\Traits\ViewFormatterTrait;
use App\Traits\ModelAttachmentableTrait;

class Customer extends Model {

    use SoftDeletes;
    use ViewFormatterTrait;
    use ModelAttachmentableTrait;

    protected $dates = ['deleted_at'];

//    protected $guarded = ['id', 'outstanding_amount', 'unresolved_amount', 'secure_key'
//                ];


    protected $appends = ['name_regular'];
	
    protected $fillable = ['name_fiscal', 'name_commercial', 'identification', 'webshop_id', 'reference_external', 
                           'accounting_id',
                           'website', 'cc_addresses', 'payment_days', 'no_payment_month', 'discount_percent', 'discount_ppd_percent',
                           'outstanding_amount_allowed', 'unresolved_amount', 'notes', 
                           'is_invoiceable', 'invoice_by_shipping_address', 'automatic_invoice', 'vat_regime', 'sales_equalization', 'accept_einvoice', 'allow_login', 'blocked', 'active', 
                           'sales_rep_id', 'currency_id', 'language_id', 'customer_group_id', 'payment_method_id', 
                           'invoice_sequence_id', 
                           'invoice_template_id', 'shipping_method_id', 'price_list_id', 'direct_debit_account_id', 
                           'order_template_id', 'shipping_slip_template_id',
                           'invoicing_address_id', 'shipping_address_id', 
                ];


    public static $rules = array(
        'name_fiscal' => 'required',
        'currency_id' => 'exists:currencies,id',
        'no_payment_month' => 'numeric|min:0|max:12',
        
        'shipping_method_id' => 'sometimes|nullable|exists:shipping_methods,id',
        'price_list_id' => 'sometimes|nullable|exists:price_lists,id',

//        'cc_addresses' => ['nullable', new \App\Rules\CommaSeparatedEmails()],
        );


    public static function boot()
    {
        parent::boot();

        // Scope not useful. If a customer is deactivated, you cannot retrieve customer from invoice...
        // static::addGlobalScope(new ShowOnlyActiveScope( Configuration::isTrue('SHOW_CUSTOMERS_ACTIVE_ONLY') ));

        static::creating(function($client)
        {
            $client->secure_key = md5(uniqid(rand(), true));
        });


        static::deleting(function ($client)
        {
            // before delete() method call this
            $relations = [
                    'customerquotations',
                    'customerorders',
                    'customershippingslips',
                    'customerinvoices',
                    'payments',
                    'cheques',
            ];

            if ( Configuration::isTrue('ENABLE_MANUFACTURING') )
                $relations = $relations + ['deliverysheetlines'];

            // load relations
            $client->load( $relations );

            // Check Relations
            foreach ($relations as $relation) {
                # code...
                if ( $client->{$relation}->count() > 0 )
                    throw new \Exception( l('Customer has :relation', ['relation' => $relation], 'exceptions') );
            }

            // To do: manage models: Supplier, Party

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


            $client->users()->delete();
            $client->cart()->delete();
            $client->bankaccount()->delete();
            $client->customerordertemplates()->delete();
            $client->deliveryroutelines()->delete();
            $client->pricerules()->delete();
        });
    }


    /**
     * Clean up email list
     * 
     */
    public function setCcAddressesAttribute($value)
    {
        $this->attributes['cc_addresses'] = str_replace(' ', '', str_replace(';', ',', $value));
    }


    // Get the full name of a User instance using Eloquent accessors
    
    public function getNameAttribute() 
    {
        return $this->address->firstname . ' ' . $this->address->lastname;
    }
    
    public function getNameRegularAttribute() 
    {
        if ( Configuration::get('BUSINESS_NAME_TO_SHOW') == 'fiscal' ) return $this->name_fiscal;

        return $this->name_commercial ?: $this->name_fiscal;
    }
/*    
    public function getFirstnameAttribute() 
    {
        return $this->address->firstname;
    }
    
    public function getLastnameAttribute() 
    {
        return $this->address->lastname;
    }
*/    

    public function getReferenceAccountingAttribute()
    {
        // if ( $this->reference_external ) return $this->reference_external;

        if ( $this->accounting_id ) return $this->accounting_id;

        return $this->id;
    }

    public function getEmailAttribute() 
    {
        return $this->address->email;
    }


    public function getIsActiveAttribute()
    {
        return $this->active > 0;
    }

    public function currentpricelist( Currency $currency = null )
    {
        if ( $currency == null )
        {
            if ($this->currency_id)
                $currency = $this->currency ?? Currency::findOrFail( Configuration::get('DEF_CURRENCY') );
        }

        // First: Customer has pricelist?
        if ( $this->pricelist && ($currency->id == $this->pricelist->currency_id) ) {

            return $this->pricelist;
        } 

        // Second: Customer Group has pricelist?
        if ( $this->customergroup && $this->customergroup->pricelist && ($currency->id == $this->customergroup->pricelist->currency_id) ) {

            return $this->customergroup->pricelist;
        }

        return null;
    }

    public function currentPricesEnteredWithTax( Currency $currency = null ) 
    {
        return $this->currentpricelist( $currency )->price_is_tax_inc ?? Configuration::get('PRICES_ENTERED_WITH_TAX');
    }


    public function getInvoiceSequenceId() 
    {
        if (   $this->invoice_sequence_id
            && Sequence::where('id', $this->invoice_sequence_id)->exists()
            )
            return $this->invoice_sequence_id;

        return Configuration::getInt('DEF_CUSTOMER_INVOICE_SEQUENCE');
    }

    public function getInvoiceTemplateId() 
    {
        if (   $this->invoice_template_id
            && Template::where('id', $this->invoice_template_id)->exists()
            )
            return $this->invoice_template_id;

        return Configuration::getInt('DEF_CUSTOMER_INVOICE_TEMPLATE');
    }

    public function getOrderTemplateId() 
    {
        if (   $this->order_template_id
            && Template::where('id', $this->order_template_id)->exists()
            )
            return $this->order_template_id;

        return Configuration::getInt('DEF_CUSTOMER_ORDER_TEMPLATE');
    }

    public function getShippingSlipTemplateId() 
    {
        if (   $this->shipping_slip_template_id
            && Template::where('id', $this->shipping_slip_template_id)->exists()
            )
            return $this->shipping_slip_template_id;

        return Configuration::getInt('DEF_CUSTOMER_SHIPPING_SLIP_TEMPLATE');
    }
    
    public function getPaymentMethodId() 
    {
        if (   $this->payment_method_id
            && PaymentMethod::where('id', $this->payment_method_id)->exists()
            )
            return $this->payment_method_id;

        return Configuration::getInt('DEF_CUSTOMER_PAYMENT_METHOD');
    }
    
    public function getShippingMethodId() 
    {
        if (   $this->shipping_method_id
            && ShippingMethod::where('id', $this->shipping_method_id)->exists()
            )
            return $this->shipping_method_id;

        return Configuration::getInt('DEF_SHIPPING_METHOD');
    }


    public static function getVatRegimeList()
    {
            $list = [];
//            foreach (self::$types as $type) {
//                $list[$type] = l(get_called_class().'.'.$type, [], 'appmultilang');
//            }

            $list = [
                0 => l('General', 'customers'),
                1 => l('Intra-Community', 'customers'),
                2 => l('Export', 'customers'),
                3 => l('Exempt', 'customers'),
            ];

            return $list;
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */


    public function addRisk( $amount, Currency $currency = null ) 
    {
        return $this->calculateRisk( );

        // Old code
        if ($currency != null)
            $amount = $amount / $currency->conversion_rate;

        $this->outstanding_amount += $amount;
        $this->save();

        return true;
    }


    public function removeRisk( $amount, Currency $currency = null ) 
    {
        return $this->calculateRisk( );

        // Old code
        if ($currency != null)
            $amount = $amount / $currency->conversion_rate;

        $this->outstanding_amount -= $amount;
        $this->save();

        return true;
    }


    public function calculateRisk( ) 
    {
        // Get "open" vouchers;
        $vouchers = $this->vouchers()->whereIn('status', ['pending', 'uncollectible'])->get();

        // Sum Them up
        $amount = $vouchers->reduce(function ($carry, $item) {
            return $carry + $item->amount / $item->currency_conversion_rate;
        }, 0.0);

        $this->outstanding_amount = $amount;
        $this->save();

        return $amount;
    }


    public function addUnresolved( $amount, Currency $currency = null ) 
    {
        return $this->calculateUnresolved( );

        // Old code
        if ($currency != null)
            $amount = $amount / $currency->conversion_rate;

        $this->unresolved_amount += $amount;
        $this->save();

        return true;
    }


    public function removeUnresolved( $amount, Currency $currency = null ) 
    {
        return $this->calculateUnresolved( );

        // Old code
        if ($currency != null)
            $amount = $amount / $currency->conversion_rate;

        $this->unresolved_amount -= $amount;
        $this->save();

        return true;
    }


    public function calculateUnresolved( ) 
    {
        // Get "open" vouchers;
        $vouchers = $this->vouchers()->whereIn('status', ['uncollectible'])->get();

        // Sum Them up
        $amount = $vouchers->reduce(function ($carry, $item) {
            return $carry + $item->amount / $item->currency_conversion_rate;
        }, 0.0);

        $this->outstanding_amount = $amount;
        $this->save();

        return $amount;
    }


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

        if ( isset($params['accounting_id']) && $params['accounting_id'] != '' )
        {
            $query->where('accounting_id', 'LIKE', '%' . $params['accounting_id'] . '%');
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
            if ( Configuration::isTrue('SHOW_CUSTOMERS_ACTIVE_ONLY') )
            {
                if ( $params['active'] == 1 )
                {
                    // Show active customers, same as global scope ShowOnlyActiveScope
                    // Do nothing
                    ;

                } else {
                    // Show not active customers (0) or all (-1)
                    // Remove global scope
                    $query->withoutGlobalScope(ShowOnlyActiveScope::class);

                    // https://www.manifest.uk.com/blog/overriding-eloquent-global-scopes

                    // Show not active customers (0)
                    if ( $params['active'] == 0 )
                        $query->where('active', '=', 0);
                }

            } else {

                if ( $params['active'] == 0 )
                    $query->where('active', '=', 0);
                
                if ( $params['active'] == 1 )
                    $query->where('active', '>', 0);
            }
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

    public function scopeOfSalesRep($query)
    {
//        return $query->where('customer_id', Auth::user()->customer_id);

        if ( isset(Auth::user()->sales_rep_id) && ( Auth::user()->sales_rep_id != NULL ) )
            return $query->where('sales_rep_id', Auth::user()->sales_rep_id);

        // Not allow to see resource
        return $query->where('sales_rep_id', 0);
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

    public function bankaccount()
    {
        return $this->hasOne('App\BankAccount', 'id', 'bank_account_id')
                   ->where('bank_accountable_type', Customer::class);
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
            return $this->invoicing_address();
        
//        return $this->belongsTo('App\Address', 'shipping_address_id')
//                   ->where('addresses.addressable_type', 'App\Customer');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function language()
    {
        return $this->belongsTo('App\Language');
    }

    // Use CustomerUser->cart instead!!
    public function cart()
    {
        return $this->hasOne('App\Cart');
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

    
    public function customerquotations()
    {
        return $this->hasMany('App\CustomerQuotation');
    }

    public function customerorders()
    {
        return $this->hasMany('App\CustomerOrder');
    }

    public function customerordertemplates()
    {
        return $this->hasMany('App\CustomerOrderTemplate');
    }

    public function getCustomerordertemplateAttribute()
    {
        return $this->customerordertemplates()->first();
    }

    public function customershippingslips()
    {
        return $this->hasMany('App\CustomerShippingSlip');
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
    
    // Alias
    public function vouchers()
    {
        return $this->payments();
    }

    public function cheques()
    {
        return $this->hasMany('App\Cheque');
    }


    public function deliveryroutelines()
    {
        return $this->hasMany('App\DeliveryRouteLine');
    }

    public function deliverysheetlines()
    {
        return $this->hasMany('App\DeliverySheetLine');
    }


    public function pricerules()
    {
        return $this->hasMany('App\PriceRule');
    }


    /**
     * Get the user record associated with the user.
     */
    public function xgetUserAttribute()
    {
        return $this->users()->where('id', Auth::user()->id)->first();
        // return $this->hasOne('App\CustomerUser', 'customer_id');
    }

    public function user()
    {
        // return $this->users()->where()->first();
        return $this->hasOne('App\CustomerUser', 'customer_id')->where('is_principal', 1);
    }

    public function users()
    {
        return $this->hasMany('App\CustomerUser', 'customer_id');
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

    public function getPrice( Product $product, $quantity = 1, Currency $currency = null )
    {

        // Special prices have more priority
        $price = $this->getPriceByRules( $product, $quantity, $currency );

        if ( $price && $price->getPrice() < $product->price ) // A Rule has been applied
        {
            return $price;
        }


        // Customer has pricelist? (I hope so!)
        return $this->getPriceByPriceList( $product, $quantity, $currency );
    }

    public function getPriceByRules( Product $product, $quantity = 1, Currency $currency = null )
    {
        // Fall back price (use it if Price for $currency is not set)
        $fallback = null;

        if (!$currency && $this->currency_id)
            $currency = $this->currency;

        if (!$currency)
            $currency = Context::getContext()->currency;


        // Special prices have more priority
        $rules = $this->getPriceRules( $product, $currency )->whereIn('rule_type', ['price', 'discount']);

        if ( $rules->count() )
            return $product->getPrice()->applyPriceRules( $rules, $quantity );


        // No rules found:
        return null;
    }

    public function getPriceByPriceList( Product $product, $quantity = 1, Currency $currency = null )
    {
        // Fall back price (use it if Price for $currency is not set)
        $fallback = null;

        if (!$currency && $this->currency_id)
            $currency = $this->currency;

        if (!$currency)
            $currency = Context::getContext()->currency;


        // Skip this:
/*
        //Special prices have more priority
        $rules = $this->getPriceRules( $product, $currency )->where('rule_type', 'price');

        if ( $rules->count() )
            return $product->getPrice()->applyPriceRules( $rules, $quantity );
*/


        // Customer has pricelist?
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

    public function getExtraQuantityRule( Product $product, Currency $currency = null )
    {

        if (!$currency && $this->currency_id)
            $currency = $this->currency;

        if (!$currency)
            $currency = Context::getContext()->currency;


        $all_rules = $this->getPriceRules( $product, $currency );
        $rules = $all_rules->whereIn('rule_type', ['price', 'discount']);

        // Precedence takes place, and extra quantity rule won't apply
        if ( $rules->count() > 0 ) return null;


        $rule = $all_rules
                    ->where('rule_type', 'promo')
                    ->sortByDesc(function ($item, $key) {   // Best Rule for Customer
                        return $item->extra_quantity / $item->from_quantity;
                    })
                    ->first();

        return $rule;
    }

    public function getPackageRule( Product $product, $package_measure_unit_id = 0, Currency $currency = null )
    {

        if ( !$package_measure_unit_id ) 
            return null;

        if (!$currency && $this->currency_id)
            $currency = $this->currency;

        if (!$currency)
            $currency = Context::getContext()->currency;


        $rule = $this->getPriceRules( $product, $currency )
                    ->where('rule_type', 'pack')
                    ->where('measure_unit_id', $package_measure_unit_id)
//                    ->sortBy('from_quantity')
                    ->sortBy(function ($item, $key) {   // Best Rule for Customer
                        return $item->price / $item->conversion_rate;
                    })
                    ->first();

        return $rule;
    }


    public function getPriceRules( Product $product, Currency $currency = null )
    {

        if (!$currency && $this->currency_id)
            $currency = $this->currency;

        if (!$currency)
            $currency = Context::getContext()->currency;
        
        // $customer = Auth::user()->customer;
        $customer = $this;
        $id = $customer->id;

        $rules = PriceRule::
                      where('currency_id', $currency->id)
                    // Customer range
                    ->applyToCustomer($id)
                    /*
                    ->where( function($query) use ($customer){
                                $query->where('customer_id', $customer->id);
                                // $query->orWhere('customer_id', null);
                                $query->orWhere( function($query1) {
                                        $query1->whereDoesntHave('customer');
                                    } );
                                if ($customer->customer_group_id)
                                    $query->orWhere('customer_group_id', $customer->customer_group_id);
                        } )
                    */
                     // Product range
                    ->where( function($query) use ($product) {
                                $query->where('product_id', $product->id);
                                if ($product->category_id)
                                    $query->orWhere('category_id',  $product->category_id);
                        } )
                    // Date range
                    ->where( function($query){
                                $now = Carbon::now()->startOfDay(); 
                                $query->where( function($query) use ($now) {
                                    $query->where('date_from', null);
                                    $query->orWhere('date_from', '<=', $now);
                                } );
                                $query->where( function($query) use ($now) {
                                    $query->where('date_to', null);
                                    $query->orWhere('date_to', '>=', $now);
                                } );
                        } )
                    ->get();

        return $rules;
    }


    public function getQuantityPriceRules( Currency $currency = null )
    {

        if (!$currency && $this->currency_id)
            $currency = $this->currency;

        if (!$currency)
            $currency = Context::getContext()->currency;
        
        $rules = PriceRule::where('currency_id', $currency->id)
                    // Customer range
                    ->where( function($query) {
                                $query->where('customer_id', $this->id);
                                if ($this->customer_group_id)
                                    $query->orWhere('customer_group_id', $this->customer_group_id);
                        } )
                    // Product range
                    // All Products
//                    ->where( function($query) use ($product) {
//                                $query->where('product_id', $product->id);
//                                if ($product->category_id)
//                                    $query->orWhere('category_id',  $product->category_id);
//                        } )
                    // Quantity range
                    ->where( 'from_quantity', '>', 1 )
                    // Date range
                    ->where( function($query){
                                $now = Carbon::now()->startOfDay(); 
                                $query->where( function($query) use ($now) {
                                    $query->where('date_from', null);
                                    $query->orWhere('date_from', '<=', $now);
                                } );
                                $query->where( function($query) use ($now) {
                                    $query->where('date_to', null);
                                    $query->orWhere('date_to', '>=', $now);
                                } );
                        } )
                    ->get();

        return $rules;
    }


    public function getLastPrice( Product $product = null, Currency $currency = null )
    {
        // 
        if ( $product == null) return null;

        $customerId = $this->id;
        $productId = $product->id;


        // https://stackoverflow.com/questions/41679771/eager-load-relationships-in-laravel-with-conditions-on-the-relation?rq=1

        $line = CustomerOrderLine::whereHas(

                'customerorder', function ($order) use ($customerId) {
                    return $order->where('customer_id', $customerId);
                }


        )->whereHas(

                'product', function ($product) use ($productId) {
                    return $product->where('id', $productId);
                }

        )->with([
    //https://stackoverflow.com/questions/41679771/eager-load-relationships-in-laravel-with-conditions-on-the-relation?rq=1
    // https://stackoverflow.com/questions/25700529/laravel-eloquent-how-to-order-results-of-related-models
    // https://laracasts.com/discuss/channels/eloquent/order-by-on-relationship
    // https://laracasts.com/discuss/channels/eloquent/eloquent-order-by-related-table
    // https://stackoverflow.com/questions/40837690/laravel-eloquent-sort-by-relationship
    /*
                Won't work. Why?
                'customerorder' => function ($order) {
                    return $order->orderBy('document_date', 'desc');
                }
    */
                'customerorder',

                'product'

        ])->join('customer_orders', 'customer_order_lines.customer_order_id', '=', 'customer_orders.id')
          ->orderBy('customer_orders.document_date', 'desc')
          ->orderBy('customer_orders.id', 'desc')
          ->select('customer_order_lines.*')->first();

        if ($line) return $line->getPrice();

        else return null;
    }

    public function getTaxRules( Product $product, $address = null )
    {
        $rules = collect([]);

        // Sales Equalization
        if ( $this->sales_equalization && ($product->sales_equalization ?? 1) ) {

            $tax = $product->tax;

            $rules = $this->getTaxRulesByTax( $tax, $address );

        }

        return $rules;
    }

    public function getTaxRulesByTax( Tax $tax, $address = null )
    {
        $rules = collect([]);

        // Sales Equalization
        if ( $this->sales_equalization ) {

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

            if ( $rules_re->isNotEmpty() ) 
            {
                $state_rules = $rules_re->where('state_id', '=', $state_id);

                if ( $state_rules->count() > 0 )
                    $rules = $rules->merge( $state_rules );
                else
                    $rules = $rules->merge( $rules_re );
            }

        }

        return $rules;
    }
    
}
