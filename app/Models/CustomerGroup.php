<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerGroup extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
	
    protected $guarded = array('id');

	protected $fillable = [ 'name', 'webshop_id', 'active', 
                            'invoice_template_id', 'shipping_method_id', 'price_list_id',
                          ];

    // Add your validation rules here
    public static $rules = array(
        'name' => 'required',
        'shipping_method_id' => 'sometimes|nullable|exists:shipping_methods,id',
        'price_list_id' => 'sometimes|nullable|exists:price_lists,id',
    	);

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function paymentmethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class, 'invoice_template_id');
    }

    public function shippingmethod()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }

    public function pricelist()
    {
        return $this->belongsTo(PriceList::class, 'price_list_id');
    }

    public function sequence()
    {
        return $this->belongsTo(Sequence::class);
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

 // Cuenta remesas
 //
 //   public function directDebitAccount()
 //   {
 //       return $this->belongsTo(BankAccount::class);
 //   }
}