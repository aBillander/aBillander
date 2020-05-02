<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerOrderTemplate extends Model
{
    protected $dates = [
            'last_used_at',
    ];

    protected $fillable = ['alias', 'name', 'document_discount_percent', 'document_ppd_percent', 
    						'notes', 'active', 'last_used_at', 'customer_id', 'shipping_address_id', 'template_id',
    					];

    public static $rules = [
        'name'         => 'required|min:2|max:128',

    	// 'document_discount_percent'   => 'numeric|min:0',
    	// 'document_ppd_percent'   => 'numeric|min:0',

        'customer_id'   => 'required|exists:customers,id',
        'shipping_address_id'   => 'required|exists:addresses,id',
        'template_id'   => 'nullable|sometimes|exists:templates,id',
    	];
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function customerordertemplatelines()
    {
    	return $this->hasMany( 'App\CustomerOrderTemplateLine', 'customer_order_template_id' );
    }
    
    public function customer()
    {
    	return $this->belongsTo( 'App\Customer' );
    }
    
    public function shippingaddress()
    {
        // return $this->morphMany('App\Address', 'addressable')
        //       ->where('addresses.id', $this->shipping_address_id)->first();
        
        return $this->hasOne('App\Address', 'id', 'shipping_address_id')
                   ->where('addressable_type', \App\Customer::class);
    }

    public function template()
    {
        return $this->belongsTo('App\Template');
	}
}
