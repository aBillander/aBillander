<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $fillable = [ 'alias', 'webshop_id', 'name_commercial', 
                            'address1', 'address2', 'postcode', 'city', 'state_id', 'country_id', 
                            'state_name', 'country_name',
                            'firstname', 'lastname', 'email', 
                            'phone', 'phone_mobile', 'fax', 'notes', 'active', 
                            'latitude', 'longitude',
                            'shipping_method_id',
                          ];

    public static $rules = array(
        'alias'    => 'required|min:2|max:32',
        'firstname' => 'max:32',
        'lasttname' => 'max:32',
        'phone' => 'max:32',
        'phone_mobile' => 'max:32',
        'email' => 'max:128',
        'fax'   => 'max:32',
        'address1' => 'required|min:2|max:128',
        'state_id' => 'exists:states,id',           // If State exists, Country must do also!
        );

    public static function related_rules($rel = 'address')
    {
        // https://laracasts.com/discuss/channels/requests/laravel-5-validation-request-how-to-handle-validation-on-update/?page=1
        $rules = array();
        
        foreach ( self::$rules as $key => $rule) 
        {
            $rules[ $rel.'.'.$key ] = $rule;
        }
        return $rules;
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($address)
        {
            // before delete() method call this

            $documents = [
                    'CustomerQuotation',
                    'CustomerOrder',
                    'CustomerShippingSlip',
                    'CustomerInvoices',

                    'SupplierOrder',
                    'SupplierShippingSlip',
                    'SupplierInvoice',
            ];

            // Addressable
            $addressable = $address->addressable;
            
            if ( 
                ($address->id == $addressable->invoicing_address_id) ||
                ($address->id == $addressable->shipping_address_id )
            )
            {
                throw new \Exception( l('Address has :relation', ['relation' => $address->addressable_type.' (Shipping Address or Invoicing Address)'], 'exceptions') );
            }

            // Documents
            foreach ($documents as $document) {
                # code...
                $class = '\\App\\Models\\'.$document;
                $docs = $class::
                              where('invoicing_address_id', $address->id)
                            ->orWhere('shipping_address_id', $address->id)
                            ->get();

                if ( $docs->count() > 0 )
                    throw new \Exception( l('Address has :relation', ['relation' => $document], 'exceptions') );
            }

            // Delivery Routes Lines
            if ( Configuration::isTrue('ENABLE_MANUFACTURING') )
            {
                $relation = 'DeliverySheetLines';

                $class = '\\App\\Models\\'.$relation;
                $docs = $class::
                              where('address_id', $address->id)
                            ->get();

                if ( $docs->count() > 0 )
                    throw new \Exception( l('Address has :relation', ['relation' => $relation], 'exceptions') );
            }

        });
    }


    public function getContactNameAttribute()
    {
        return trim( $this->firstname . ' ' . $this->lastname );
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function customer()
    {
//        return $this->belongsTo(Customer::class, 'owner_id')->where('model_name', '=', 'Customer');
    }
    
    public function addressable()
    {
        return $this->morphTo();
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function shippingmethod()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }

    public function getShippingMethod()
    {
        if ( (int) $this->shipping_method_id > 0 ) return $this->shippingmethod;

        if ( (int) $this->addressable->shipping_method_id > 0 ) return $this->addressable->shippingmethod;

        return ShippingMethod::find( Configuration::getInt('DEF_SHIPPING_METHOD') );
    }
    

    /*
    |--------------------------------------------------------------------------
    | Tax calculations
    |--------------------------------------------------------------------------
    */

    public function getTaxRules( Tax $tax )
    {
        $country_id = $this->country_id;
        $state_id   = $this->state_id;

        $country_rules = $tax->taxrules()
                    ->where(function ($query) use ($country_id, $state_id) {
                        $query->where('country_id', '=', $country_id);
                        
                        $query->where(function ($query1) use ($state_id) {
                            $query1->where(  'state_id', '=', 0)
                                    ->OrWhere('state_id', '=', $state_id);
                        });
                    })
                    ->where('rule_type', '=', 'sales')
//                                ->orderBy('position', 'asc')     // $tax->taxrules() is already sorted by position asc
                    ->get();

        $state_rules = $country_rules->where('state_id', '=', $state_id);

        if ( $state_rules->count() > 0 )
            return $state_rules;

        return $country_rules;
    }

    public function getTaxes( )
    {
        $country_id = $this->country_id;
        $state_id   = $this->state_id;

        $taxes = Tax::with('taxrules')
                    ->whereHas('taxrules', function ($query) use ($country_id, $state_id) {
                        $query->where('country_id', '=', $country_id);
                        
                        $query->where(function ($query1) use ($state_id) {
                            $query1->where(  'state_id', '=', 0)
                                    ->orWhere('state_id', '=', $state_id);
// Notes to self:
//                                    ->orWhereNull('state_id');
// Alternative: COALESCE : Return the first non-null value in a list
//                                    ->where(DB::raw('COALESCE(state_id,0)'), '=', 0)
                        });
                    })
                    ->orderBy('id', 'asc')->get();
                    
/*
        $rules = $tax->taxrules()->where(function ($query) use ($country_id) {
            $query->where(  'country_id', '=', 0)
                  ->OrWhere('country_id', '=', $country_id);
        })
                                 ->where(function ($query) use ($state_id) {
            $query->where(  'state_id', '=', 0)
                  ->OrWhere('state_id', '=', $state_id);
        })
                                 ->where('rule_type', '=', 'sales')
 //                                ->orderBy('position', 'asc')     // $tax->taxrules() is already sorted by position asc
                                 ->get();
*/
        return $taxes;
    }

    public function getTaxList()
    {
        return $this->getTaxes()->pluck('name', 'id')->toArray();
    }

    public function getTaxPercentList()
    {
        $list = [];

        $taxes = $this->getTaxes();

        foreach ($taxes as $tax) {
            $list[$tax->id] = $this->getTaxRules( $tax )->where('rule_type', '=', 'sales')->sum('percent');
        }

        return $list;
    }

    public function getTaxWithREPercentList()
    {
        $list = [];

        $taxes = $this->getTaxes();

        foreach ($taxes as $tax) {
            $list[$tax->id] = $tax->taxrules()
                    ->where(function ($query) {
                            $query->where  ('rule_type', '=', 'sales')
                                  ->orWhere('rule_type', '=', 'sales_equalization');
                        })
                    ->sum('percent');
        }

        return $list;
    }

    public function getTaxREPercentList()
    {
        $list = [];

        $taxes = $this->getTaxes();

        foreach ($taxes as $tax) {
            $list[$tax->id] = $tax->taxrules()->where('rule_type', '=', 'sales_equalization')->sum('percent');
        }

        return $list;
    }
    
}