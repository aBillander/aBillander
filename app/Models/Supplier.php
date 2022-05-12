<?php

namespace App\Models;

use App\Helpers\Price;
use App\Traits\ModelAttachmentableTrait;
use App\Traits\ViewFormatterTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model {

    use SoftDeletes;
    
    use ViewFormatterTrait;
    use ModelAttachmentableTrait;

    protected $dates = ['deleted_at'];

    protected $appends = ['name_regular'];
    
    protected $fillable = [ 'alias', 'name_fiscal', 'name_commercial', 
                            'website', 'customer_center_url', 'customer_center_user', 'customer_center_password', 
                            'identification', 'approval_number', 'reference_external', 'accounting_id', 'discount_percent', 'discount_ppd_percent', 'payment_days', 'delivery_time', 'notes', 
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
        return Configuration::get('SUPPLIER_PRICES_ENTERED_WITH_TAX');
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

        $this->unresolved_amount = $amount;
        $this->save();

        return $amount;
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
        return $this->hasMany(Product::class, 'main_supplier_id')->orderby('reference', 'asc');
    }
    
    public function supplierpricelistlines()
    {
        return $this->hasMany(SupplierPriceListLine::class, 'supplier_id');
    }

    /**
     * Get all of the customer's Bank Accounts.
     */
    public function bankaccounts()
    {
        return $this->morphMany(BankAccount::class, 'bank_accountable');
    }

    public function bankaccount()
    {
        return $this->hasOne(BankAccount::class, 'id', 'bank_account_id')
                   ->where('bank_accountable_type', Supplier::class);
    }
    

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }


    public function getAddressList()
    {
        return $this->morphMany(Address::class, 'addressable')->pluck( 'alias', 'id' )->toArray();
    }


    public function address()
    {
        return $this->hasOne(Address::class, 'id', 'invoicing_address_id')
                   ->where('addressable_type', Supplier::class);
    }
    
    public function invoicing_address()
    {
        if ($this->invoicing_address_id>0)
            return $this->morphMany(Address::class, 'addressable')
                   ->where('addresses.id', $this->invoicing_address_id)->first();
        else
            return null;
    }
    
    // To be same as Customer:
    public function shipping_address()
    {
        return $this->invoicing_address();
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function paymentmethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    // To be same as Customer:
    public function shippingmethod()
    {
        // return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');

        // Return what?
        return ;
    }

    // To be same as Customer:
    public function suppliergroup()
    {
        // return $this->belongsTo(SupplierGroup::class, 'supplier_group_id');

        // Return what?
        return ;
    }

    
    public function supplierorders()
    {
        return $this->hasMany(SupplierOrder::class);
    }

    public function supplierordertemplates()
    {
        return $this->hasMany(SupplierOrderTemplate::class);
    }

    public function getSupplierordertemplateAttribute()
    {
        return $this->supplierordertemplates()->first();
    }

    public function suppliershippingslips()
    {
        return $this->hasMany(SupplierShippingSlip::class);
    }

    public function supplierinvoices()
    {
        return $this->hasMany(SupplierInvoice::class);
    }
    
    public function payments()
    {
        // return $this->hasMany(Payment::class, 'owner_id')->where('payment.owner_model_name', '=', 'Supplier');

        return $this->morphMany(Payment::class, 'paymentorable');
    }
    
    // Alias
    public function vouchers()
    {
        return $this->payments();
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

        // Measure unit ???
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

            $thePrice = $product->last_purchase_price * $currency->conversion_rate;   // last_purchase_price is stored in Company Currency
        }

        // One last try
        if (0)  // Hummm! Too restrictive! =>
        if ( $thePrice <= 0.0 ) 
        {
            # code...
            $thePrice = $product->price;    // Cannot be worse!
        }
        
        $price = new Price( $thePrice, 0, $this->currency);     // Price is tax excluded

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
