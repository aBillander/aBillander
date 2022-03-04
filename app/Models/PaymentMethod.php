<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'deadlines_by', 'deadlines', 
                           'payment_is_cash', 'auto_direct_debit', 'active',
                           'payment_type_id'];

    public static $rules = array(
        'name'         => array('required', 'min:2', 'max:128'),
    	);


    public static function boot()
    {
        parent::boot();


        static::deleting(function ($method)
        {
            // before delete() method call this
            $relations = [
                    'customers',
                    'suppliers',
                    'customerquotations',
                    'customerorders',
                    'customershippingslips',
                    'customerinvoices',
                    'payments',
                    'carts',
            ];

            // load relations
            $method->load( $relations );

            // Check Relations
            foreach ($relations as $relation) {
                # code...
                if ( $method->{$relation}->count() > 0 )
                    throw new \Exception( l('Payment Method has :relation', ['relation' => $relation], 'exceptions') );
            }

        });
    }


    public function getDeadlinesAttribute($value)
    {
        return unserialize($value);
    }

    public function setDeadlinesAttribute($value)
    {
        $this->attributes['deadlines'] = serialize($value);
    }

    // Transient
    public function getPaymentDocumentIdAttribute($value)
    {
        return $value ?: 1;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function paymenttype()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }
    
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
    
    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    public function customerquotations()
    {
        return $this->hasMany(CustomerQuotation::class);
    }

    public function customerorders()
    {
        return $this->hasMany(CustomerOrder::class);
    }

    public function customershippingslips()
    {
        return $this->hasMany(CustomerShippingSlip::class);
    }
    
    public function customerinvoices()
    {
        return $this->hasMany(CustomerInvoice::class);
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}