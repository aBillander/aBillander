<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ViewFormatterTrait;

class CustomerOrderTemplate extends Model
{
    use ViewFormatterTrait;

    protected $dates = [
            'last_used_at',
    ];

    protected $fillable = ['alias', 'name', 'document_discount_percent', 'document_ppd_percent', 
    						'notes', 'active', 
                            'last_used_at', 'last_customer_order_id', 'last_document_reference', 'total_tax_incl', 'total_tax_excl', 
                            'customer_id', 'shipping_address_id', 'template_id',
    					];

    public static $rules = [
        'name'         => 'required|min:2|max:128',

    	// 'document_discount_percent'   => 'numeric|min:0',
    	// 'document_ppd_percent'   => 'numeric|min:0',

        'customer_id'   => 'required|exists:customers,id',
        'shipping_address_id'   => 'required|exists:addresses,id',
        'template_id'   => 'nullable|sometimes|exists:templates,id',
    	];
    



    public static function boot()
    {
        parent::boot();

        // https://laracasts.com/discuss/channels/general-discussion/deleting-related-models
        static::deleting(function ($document)
        {
            // before delete() method call this
            foreach($document->lines as $line) {
                $line->delete();
            }

        });

    }

    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function customerordertemplatelines()
    {
        return $this->hasMany( 'App\CustomerOrderTemplateLine', 'customer_order_template_id' )
                    ->orderBy('line_sort_order', 'ASC');
    }
    
    // Alias
    public function lines()
    {
        return $this->customerordertemplatelines();
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
