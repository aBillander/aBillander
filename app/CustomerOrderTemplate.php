<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerOrderTemplate extends Model
{
    protected $document_dates = [
            'last_used_at',
    ];

    protected $fillable = ['alias', 'name', 'document_discount_percent', 'document_ppd_percent', 
    						'notes', 'active', 'last_used_at', 'customer_id', 'template_id',
    					];

    public static $rules = array(
        'name'         => 'required|min:2|max:128',

    	'document_discount_percent'   => 'numeric|min:0',
    	'document_ppd_percent'   => 'numeric|min:0',

        'template_id'   => 'nullable|sometimes|exists:templates,id',
    	);
    

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

    public function template()
    {
        return $this->belongsTo('App\Template');
	}
}
